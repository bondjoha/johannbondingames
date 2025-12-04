<?php
require 'configure.php';

// twig setup
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

// check that user logged in
if (!isset($_SESSION['user'])) 
{
    header('Location: Login.php');
    exit;
}

// get user details
$user = $_SESSION['user'];
// check if user is admin or staff
$isAdmin = strtolower($user['role']) === 'admin';
$isStaff = strtolower($user['role']) === 'staff';
if (!$isAdmin && !$isStaff) 
{
    header('Location: Login.php');
    exit;
}

// Initializing variables
$message = "";
$error = "";

// search box filter
$filterBy = $_GET['filterBy'] ?? '';   // "hotel", "room", or "user"
$searchTerm = $_GET['search'] ?? '';   // search text

// Initializing variables as empty arrays 
$hotels = [];
$rooms = [];
$users = [];
$bookings = [];

// do if user is admin
if ($isAdmin) 
{
    if (isset($_POST['submitBookingRecord'])) 
    {
    $hotelId = $_POST['Hotel_Id'];
    $roomType = $_POST['Room_Type'];
    $credentialsId = $_POST['CredentialsID'];
    $checkIn = $_POST['Check_in'];
    $checkOut = $_POST['Check_out'];

    // Finding the room available according to selected type
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

    if ($room) {
        $roomId = $room['Room_Id'];

        // Adding new booking 
        $insert = $conn->prepare
        ("
            INSERT INTO booking (Hotel_Id, Room_Id, User_Id, Check_in, Check_out)
            VALUES (:hotelId, :roomId, :userId, :checkIn, :checkOut)
        ");
        $insert->execute([
            ':hotelId' => $hotelId,   
            ':roomId' => $roomId,
            ':userId' => $credentialsId,
            ':checkIn' => $checkIn,
            ':checkOut' => $checkOut
        ]);


        $message = "Room successfully booked: {$room['Room_Number']} (Type: $roomType)";
    } else {
        $error = "No available rooms of type '$roomType' in the selected hotel for the selected dates.";
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
        SET Room_Id = :roomId, user_id = :credentialsId, Check_in = :checkIn, Check_out = :checkOut
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
    // retrieve hotels details
    $hotelsQuery = $conn->query("SELECT * FROM hotel_details WHERE Is_Active = 1");
    $hotels = $hotelsQuery->fetchAll(PDO::FETCH_ASSOC);

    // retrieve rooms
    $roomsQuery = $conn->query("SELECT * FROM hotels_rooms WHERE Is_Active = 1");
    $rooms = $roomsQuery->fetchAll(PDO::FETCH_ASSOC);


    // retrieve users
    $usersQuery = $conn->query
    ("
        SELECT user_id AS CredentialsID, first_name AS FirstName, last_name AS Surname 
        FROM logincredentials 
        WHERE user_role = 'customer'
    ");
    $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

   // retrieve bookings according to filtering (hotel, room, user)
    $sql = 
    "
        SELECT b.Booking_Id, b.Room_Id, b.user_id, b.Check_in, b.Check_out,
               hr.Room_Number, hr.Room_Type, hd.Hotel_Name,
               u.first_name AS FirstName, u.last_name AS Surname
        FROM booking b
        JOIN hotels_rooms hr ON b.Room_Id = hr.Room_Id
        JOIN hotel_details hd ON hr.Hotel_Id = hd.Hotel_Id
        JOIN logincredentials u ON b.user_id = u.user_id
        WHERE 1
    ";

    $params = [];

    if (!empty($filterBy) && !empty($searchTerm)) 
    {
        if ($filterBy === 'hotel') 
        {
            $sql .= " AND hd.Hotel_Name LIKE :search";
            $params[':search'] = "%$searchTerm%";
        } 
        elseif ($filterBy === 'room') 
        {
            $sql .= " AND hr.Room_Number LIKE :search";
            $params[':search'] = "%$searchTerm%";
        } 
        elseif ($filterBy === 'user') 
        {
            $sql .= " AND (u.first_name LIKE :search OR u.last_name LIKE :search)";
            $params[':search'] = "%$searchTerm%";
        }
    }

    $bookingsStmt = $conn->prepare($sql);
    $bookingsStmt->execute($params);
    $bookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);
}

// if user role is staff retrive bookings done in his hotel
else if ($isStaff) 
{
    if (isset($_POST['submitBookingRecord'])) 
    {
        $hotelId = $_POST['Hotel_Id'];
        $roomType = $_POST['Room_Type'];
        $credentialsId = $_POST['CredentialsID'];
        $checkIn = $_POST['Check_in'];
        $checkOut = $_POST['Check_out'];

        //Find available room of the selected type
        $stmt = $conn->prepare("
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

        $stmt->execute([
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
                INSERT INTO booking (Hotel_Id, Room_Id, User_Id, Check_in, Check_out)
                VALUES (:hotelId, :roomId, :userId, :checkIn, :checkOut)
            ");
            $insert->execute
            ([
                ':hotelId' => $hotelId,   // include the hotel id
                ':roomId' => $roomId,
                ':userId' => $credentialsId,
                ':checkIn' => $checkIn,
                ':checkOut' => $checkOut
            ]);
            $message = "Room successfully booked: {$room['Room_Number']} (Type: $roomType)";
        } 
        else 
        {
            $error = "No available rooms of type '$roomType' in the selected hotel for the selected dates.";
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
        SET Room_Id = :roomId, user_id = :credentialsId, Check_in = :checkIn, Check_out = :checkOut
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

    // Get assigned hotel IDs
    $hotelStmt = $conn->prepare("SELECT hotel_id FROM usershotels WHERE user_id = :staff_id");
    $hotelStmt->execute([':staff_id' => $user['id']]);
    $assignedHotels = $hotelStmt->fetchAll(PDO::FETCH_COLUMN, 0);

    if (!empty($assignedHotels)) 
    {
        // creation of a variable placeholders according to the number of assigned hotels
        $placeholders = implode(',', array_fill(0, count($assignedHotels), '?'));

        // retrive the hotel to which user staff is assigned to
        $hotelsStmt = $conn->prepare("SELECT * FROM hotel_details WHERE Hotel_Id IN ($placeholders) AND Is_Active = 1");
        $hotelsStmt->execute($assignedHotels);
        $hotels = $hotelsStmt->fetchAll(PDO::FETCH_ASSOC);

        // retrive the rooms assigned to the hotel
        $roomsStmt = $conn->prepare("SELECT * FROM hotels_rooms WHERE Hotel_Id IN ($placeholders) AND Is_Active = 1");
        $roomsStmt->execute($assignedHotels);
        $rooms = $roomsStmt->fetchAll(PDO::FETCH_ASSOC);

        // sselect bookings assigned to the hotel to whom staff is assigned
        $sql = "
            SELECT b.Booking_Id, b.Room_Id, b.user_id, b.Check_in, b.Check_out,
                   hr.Room_Number, hr.Room_Type, hd.Hotel_Name,
                   u.first_name AS FirstName, u.last_name AS Surname
            FROM booking b
            JOIN hotels_rooms hr ON b.Room_Id = hr.Room_Id
            JOIN hotel_details hd ON hr.Hotel_Id = hd.Hotel_Id
            JOIN logincredentials u ON b.user_id = u.user_id
            WHERE hr.Hotel_Id IN ($placeholders)
        ";

        $params = $assignedHotels;

        //filter for staff
        if (!empty($filterBy) && !empty($searchTerm)) 
        {
            if ($filterBy === 'hotel') 
            {
                $sql .= " AND hd.Hotel_Name LIKE ?";
                $params[] = "%$searchTerm%";
            } 
            elseif ($filterBy === 'room') 
            {
                $sql .= " AND hr.Room_Number LIKE ?";
                $params[] = "%$searchTerm%";
            } 
            elseif ($filterBy === 'user') 
            {
                $sql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ?)";
                $params[] = "%$searchTerm%";
                $params[] = "%$searchTerm%";
            }
        }

        $bookingsStmt = $conn->prepare($sql);
        $bookingsStmt->execute($params);
        $bookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);

        $usersQuery = $conn->query
        ("
            SELECT user_id AS CredentialsID, first_name AS FirstName, last_name AS Surname 
            FROM logincredentials 
            WHERE user_role = 'customer'
        ");
        $users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);
    } 
    else 
    {
        $hotels = $rooms = $bookings = $users = [];
        $error = "No hotels assigned to your account.";
    }    
}

// Render Twig template 
echo $twig->render('TableBookingPage.html.twig', 
[
    'hotels' => $hotels,
    'rooms' => $rooms,
    'users' => $users,
    'bookings' => $bookings,
    'message' => $message,
    'error' => $error,
    'filterBy' => $filterBy,
    'searchTerm' => $searchTerm
]);
?>
