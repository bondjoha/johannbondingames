<?php

include('databaseconnect.php'); // database connection PDO

// Twig 
require_once 'vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('templates'); // Twig looks in templates folder
$twig = new \Twig\Environment($loader);

// Get Hotels Records
$hotelsQuery = $conn->query("SELECT * FROM hotel_details WHERE Is_Active = 1");
$hotels = $hotelsQuery->fetchAll(PDO::FETCH_ASSOC);

// Get Rooms Records
$roomsQuery = $conn->query("SELECT * FROM hotels_rooms WHERE Is_Active = 1");
$rooms = $roomsQuery->fetchAll(PDO::FETCH_ASSOC);

// Get Customers Records
$usersQuery = $conn->query
("
    SELECT user_id AS CredentialsID, first_name AS FirstName, last_name AS Surname 
    FROM logincredentials 
    WHERE user_role = 'customer'
");
$users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

// Get Bookings of hotel`s room records
$bookingsQuery = $conn->query
("
    SELECT b.Booking_Id, b.Room_Id, b.CredentialsID, b.Check_in, b.Check_out,
           hr.Room_Number, hr.Room_Type, hd.Hotel_Name
    FROM booking b
    JOIN hotels_rooms hr ON b.Room_Id = hr.Room_Id
    JOIN hotel_details hd ON hr.Hotel_Id = hd.Hotel_Id
");
$bookings = $bookingsQuery->fetchAll(PDO::FETCH_ASSOC);

// render messages to Booking page twig template
$message = "";
$error = "";

// Add new booking 
if (isset($_POST['submitBookingRecord'])) 
{
    $hotelId = $_POST['Hotel_Id'];
    $roomType = $_POST['Room_Type'];
    $credentialsId = $_POST['CredentialsID'];
    $checkIn = $_POST['Check_in'];
    $checkOut = $_POST['Check_out'];

    // Finding available room type in hotel in selected dates
    // --- Find an available room of this type in the selected hotel for the given date range ---
$stmt = $conn->prepare
("
    SELECT hr.Room_Id, hr.Room_Number 
    FROM hotels_rooms hr
    WHERE hr.Hotel_Id = :hotelId
      AND hr.Room_Type = :roomType
      AND hr.Room_Id NOT IN (
          SELECT b.Room_Id 
          FROM booking b
          WHERE NOT (
              b.Check_out <= :checkIn OR b.Check_in >= :checkOut
          )
      )
    LIMIT 1
");

$stmt->execute
([
    ':hotelId' => $hotelId,
    ':roomType' => $roomType,
    ':checkIn' => $checkIn,
    ':checkOut' => $checkOut
]);

$room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) 
    {
        $roomId = $room['Room_Id'];

        // Add new booking 
        $insert = $conn->prepare
        ("
            INSERT INTO booking (Room_Id, CredentialsID, Check_in, Check_out)
            VALUES (:roomId, :credentialsId, :checkIn, :checkOut)
        ");
        $insert->execute
        ([
            ':roomId' => $roomId,
            ':credentialsId' => $credentialsId,
            ':checkIn' => $checkIn,
            ':checkOut' => $checkOut
        ]);

        // Mark room as booked
        $updateRoom = $conn->prepare("UPDATE hotels_rooms SET Status = 'Booked' WHERE Room_Id = :roomId");
        $updateRoom->execute([':roomId' => $roomId]);

        $message = "Room successfully booked by type ($roomType).";
    } 
    else 
    {
        $error = "No available rooms of type '$roomType' in the selected hotel.";
    }
}

// Update booking
if (isset($_POST['updatebooking'])) 
{
    $bookingId = $_POST['Booking_Id'];
    $roomId = $_POST['Room_Id'];
    $credentialsId = $_POST['CredentialsID'];
    $checkIn = $_POST['Check_in'];
    $checkOut = $_POST['Check_out'];

    $update = $conn->prepare
    ("
        UPDATE booking 
        SET Room_Id = :roomId, CredentialsID = :credentialsId, Check_in = :checkIn, Check_out = :checkOut
        WHERE Booking_Id = :bookingId
    ");
    $update->execute
    ([
        ':roomId' => $roomId,
        ':credentialsId' => $credentialsId,
        ':checkIn' => $checkIn,
        ':checkOut' => $checkOut,
        ':bookingId' => $bookingId
    ]);

    $message = "Booking ID #$bookingId updated successfully.";
}

// Delete booking
if (isset($_POST['deletebooking'])) 
{
    $bookingId = $_POST['Booking_Id'];

    //Get room ID for the booking 
    $stmt = $conn->prepare("SELECT Room_Id FROM booking WHERE Booking_Id = :bookingId");
    $stmt->execute([':bookingId' => $bookingId]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) 
    {
        $roomId = $room['Room_Id'];

        //Delete booking
        $delete = $conn->prepare("DELETE FROM booking WHERE Booking_Id = :bookingId");
        $delete->execute([':bookingId' => $bookingId]);

        //Mark room available
        $updateRoom = $conn->prepare("UPDATE hotels_rooms SET Status = 'Available' WHERE Room_Id = :roomId");
        $updateRoom->execute([':roomId' => $roomId]);

        $message = "Booking ID #$bookingId deleted successfully.";
    } 
    else 
    {
        $error = "Could not find booking to delete.";
    }
}

// Render Twig Template
echo $twig->render('TableBookingPage.html.twig', 
[
    'hotels' => $hotels,
    'rooms' => $rooms,
    'users' => $users,
    'bookings' => $bookings,
    'message' => $message,
    'error' => $error
]);
?>