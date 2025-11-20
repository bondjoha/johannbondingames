<?php
session_start();
include('databaseconnect.php'); // PDO connection
// twig setup
include 'vendor/autoload.php'; 
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

// Redirect if not logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) 
{
    header("Location: LoginPage.php");
    exit();
}

// Get logged in customer
$useremail = $_SESSION['user_email'];
$stmtUser = $conn->prepare("SELECT user_id AS CredentialsID, first_name, last_name FROM logincredentials WHERE user_email = ?");
$stmtUser->execute([$useremail]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Fetch active hotels
$hotels = $conn->query("SELECT * FROM hotel_details WHERE Is_Active=1")->fetchAll(PDO::FETCH_ASSOC);

// store hotel id and room type
$selectedHotel = $_SESSION['pending_booking']['hotel_id'] ?? '';
$selectedType  = $_SESSION['pending_booking']['room_type'] ?? '';

// When user submits overwrite session values
if (!empty($_POST['Hotel_ID'])) 
{
    $selectedHotel = $_POST['Hotel_ID'];
}

if (!empty($_POST['Room_Type'])) 
{
    $selectedType = $_POST['Room_Type'];
}

// room population
$rooms = [];
if ($selectedHotel && $selectedType) 
{
    $stmt = $conn->prepare
    ("
        SELECT Room_Id, Room_Number 
        FROM hotels_rooms 
        WHERE Hotel_Id=:hotel AND Room_Type=:type AND Status='Available'
    ");
    $stmt->execute([':hotel' => $selectedHotel, ':type' => $selectedType]);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// booking submission
$message = $error = '';
if (isset($_POST['submitBookingRecord']) && !empty($_POST['Room_ID'])) 
{
    $roomId = $_POST['Room_ID'];
    $checkIn = $_POST['Check_in'];
    $checkOut = $_POST['Check_out'];
    $credentialsId = $user['CredentialsID'];

    // dates validation
    $today = date('Y-m-d');
    if ($checkIn < $today) 
    {
        $error = "Check-in cannot be in the past.";
    } 
    elseif ($checkIn >= $checkOut) 
    {
        $error = "Check-out must be after check-in.";
    } 
    else
    {
        $stmt = $conn->prepare
        ("
            SELECT COUNT(*) FROM booking 
            WHERE Room_Id=:room AND (Check_in <= :Check_out AND Check_out >= :Check_in)
        ");
        $stmt->execute
        ([
            ':room' => $roomId,
            ':Check_in' => $checkIn,
            ':Check_out' => $checkOut
        ]);

        if ($stmt->fetchColumn() > 0) 
        {
            $error = "Room is already booked for selected dates.";
        } 
        else 
        {
            $insert = $conn->prepare
            ("
                INSERT INTO booking (Room_Id, CredentialsID, Check_in, Check_out)
                VALUES (:room, :cred, :ci, :co)
            ");
            $insert->execute
            ([
                ':room' => $roomId,
                ':cred' => $credentialsId,
                ':ci' => $checkIn,
                ':co' => $checkOut
            ]);

            $conn->prepare("UPDATE hotels_rooms SET Status='Booked' WHERE Room_Id=:room")
                 ->execute([':room' => $roomId]);

            $message = "Booking successful!";

            // Clear pending booking once used
            if (isset($_SESSION['pending_booking'])) 
            {
                unset($_SESSION['pending_booking']);
            }
        }
    }
}

// get user bookings
$stmt = $conn->prepare
("
    SELECT 
        b.Booking_Id, b.Check_in, b.Check_out, 
        hr.Room_Number, hr.Room_Type, 
        hd.Hotel_Name, hd.Hotel_Image,
        CONCAT(hd.Hotel_Street_Name, ', ', hd.Hotel_City_Name, ', ', hd.Hotel_Country_Name) AS Hotel_Address
    FROM booking b
    JOIN hotels_rooms hr ON b.Room_Id = hr.Room_Id
    JOIN hotel_details hd ON hr.Hotel_Id = hd.Hotel_Id
    WHERE b.CredentialsID = :cred
");
$stmt->execute([':cred' => $user['CredentialsID']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// api
require_once 'weather.php';
foreach ($bookings as &$b) 
{
    $b['weather'] = getWeather($b['Hotel_Address']);
}
unset($b);

// close database connection
$conn = null;

// Render to CustomerBooking twig template
echo $twig->render
('CustomerBooking.html.twig', 
[
    'user' => $user,
    'hotels' => $hotels,
    'rooms' => $rooms,
    'selectedHotel' => $selectedHotel,
    'selectedType' => $selectedType,
    'bookings' => $bookings,
    'message' => $message,
    'error' => $error
]);
?>
