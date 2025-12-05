<?php
require 'configure.php';
// Twig setup
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, 
[
    'autoescape' => 'html' // Ensure all output is escaped by default
]);

// resuming pending booking to get the guest booking data
if (empty($_POST) && empty($_GET) && isset($_SESSION['pending_booking'])) 
{
    $_POST = $_SESSION['pending_booking'];
    unset($_SESSION['pending_booking']);
}

// checking that user is logged in and if not redirect him back to login
if (!isset($_SESSION['user'])) 
{
    $_SESSION['pending_booking'] = $_POST + $_GET; // store booking details to not lose them
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = filter_var($user['id'], FILTER_VALIDATE_INT);
if (!$user_id) 
{
    die("Invalid user ID.");
}

// retrieving booking details with validation
$hotel_id    = filter_var($_POST['hotel_id'] ?? null, FILTER_VALIDATE_INT);
$room_id     = filter_var($_POST['room_id'] ?? null, FILTER_VALIDATE_INT);
$total_price = filter_var($_POST['total_price'] ?? null, FILTER_VALIDATE_FLOAT);
$check_in    = DateTime::createFromFormat('Y-m-d', $_POST['check_in'] ?? '');
$check_out   = DateTime::createFromFormat('Y-m-d', $_POST['check_out'] ?? '');
$room_type   = htmlspecialchars($_POST['room_type'] ?? '');



if (!$hotel_id || !$room_id || !$total_price || !$check_in || !$check_out || !$room_type) 
{
    die("Missing or invalid booking information.");
}

// Convert Date to string because database
$check_in_str  = $check_in->format('Y-m-d');
$check_out_str = $check_out->format('Y-m-d');


$stmt = $conn->prepare("SELECT Price FROM hotels_rooms WHERE Room_Id = ?");
$stmt->execute([$room_id]);
$roomPriceData = $stmt->fetch(PDO::FETCH_ASSOC);
$pricePerNight = $roomPriceData['Price'] ?? 0;

try
{
    $conn->beginTransaction();
    // Insert user booking in database
    $stmt = $conn->prepare
    ("
        INSERT INTO booking 
        (User_Id, Hotel_Id, Room_Id, Room_Type, Price_Per_Night, Check_In, Check_Out, Total_Price, Booking_Date)
        VALUES (?, ?, ?, ?, ?, ?, ?,?, NOW())
    ");
    $stmt->execute([
        $user_id,
        $hotel_id,
        $room_id,
        $room_type,
        $pricePerNight,
        $check_in_str,
        $check_out_str,
        $total_price
    ]);
    $conn->commit();
} 
catch (Exception $e) 
{
    $conn->rollBack(); // prevent inconsistent database state
    die("Booking failed."); // do not expose DB errors in production
}

// retrieving booking records from table booking
$stmt = $conn->prepare
("
    SELECT b.*, h.Hotel_Name, h.Hotel_City_Name, h.Hotel_Country_Name, r.Room_Number
    FROM booking b
    INNER JOIN hotel_details h ON h.Hotel_Id = b.Hotel_Id
    INNER JOIN hotels_rooms r ON r.Room_Id = b.Room_Id
    WHERE b.User_Id = ?
    ORDER BY b.Check_In DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// retrieving booked room info
$stmt = $conn->prepare("SELECT Room_Number FROM hotels_rooms WHERE Room_Id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

$room_number = $room['Room_Number'] ?? 'N/A';


// Calculate number of nights safely
$checkInDate  = $check_in;
$checkOutDate = $check_out;
try 
{
    $number_of_nights = $checkInDate->diff($checkOutDate)->days;
} 
catch (Exception $e) 
{
    $number_of_nights = 0;
}

// retrieving current booking record to display it
$stmt = $conn->prepare("SELECT * FROM hotel_details WHERE Hotel_Id = ?");
$stmt->execute([$hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

// rendering to twig template
echo $twig->render('BookingSuccess.html.twig', 
[
    'hotel'            => $hotel,
    'room_id'          => $room_id,
    'room_type'        => $room_type,
    'room_number'      => $room_number,
    'check_in'         => $check_in_str,
    'check_out'        => $check_out_str,
    'pricePerNight'    => $pricePerNight,
    'total_price'      => $total_price,
    'user'             => $user,
    'bookings'         => $bookings,
    'number_of_nights' => $number_of_nights
]);

$conn = null;
?>