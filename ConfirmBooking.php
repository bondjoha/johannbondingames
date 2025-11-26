<?php
session_start();
ini_set('display_errors', 1);

include 'vendor/autoload.php';
include 'databaseconnect.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Twig setup
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader);

// User must be logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id']; // consistent key

// Get POST data
$hotel_id    = $_POST['hotel_id'] ?? null;
$room_id     = $_POST['room_id'] ?? null;
$check_in    = $_POST['check_in'] ?? null;
$check_out   = $_POST['check_out'] ?? null;
$total_price = $_POST['total_price'] ?? null;
$room_type   = $_POST['room_type'] ?? null;

// Validate input
if (!$hotel_id || !$room_id || !$check_in || !$check_out || !$room_type) {
    die("Missing booking information.");
}

try {
    // Start transaction
    $conn->beginTransaction();

    // Insert new booking
    $stmt = $conn->prepare("
        INSERT INTO booking 
        (User_Id, Hotel_Id, Room_Id, Room_Type, Check_In, Check_Out, Total_Price, Booking_Date)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $user_id,
        $hotel_id,
        $room_id,
        $room_type,
        $check_in,
        $check_out,
        $total_price
    ]);

    $conn->commit();

} catch (Exception $e) {
    $conn->rollBack();
    die("Booking failed: " . $e->getMessage());
}

// ---------------------------------------------------------
// FETCH CUSTOMER BOOKING HISTORY
// ---------------------------------------------------------
$stmt = $conn->prepare("
    SELECT b.*, h.Hotel_Name, h.Hotel_City_Name, h.Hotel_Country_Name, r.Room_Number
    FROM booking b
    INNER JOIN hotel_details h ON h.Hotel_Id = b.Hotel_Id
    INNER JOIN hotels_rooms r ON r.Room_Id = b.Room_Id
    WHERE b.User_Id = ?
    ORDER BY b.Check_In DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Bookings number of nights 
$checkInDate  = new DateTime($check_in);
$checkOutDate = new DateTime($check_out);
$number_of_nights = $checkInDate->diff($checkOutDate)->days;

// ---------------------------------------------------------
// FETCH HOTEL INFO FOR THE CURRENT BOOKING CONFIRMATION
// ---------------------------------------------------------
$stmt = $conn->prepare("SELECT * FROM hotel_details WHERE Hotel_Id = ?");
$stmt->execute([$hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

// ---------------------------------------------------------
// LOAD TWIG TEMPLATE
// ---------------------------------------------------------
echo $twig->render('BookingSuccess.html.twig', [
    'hotel'       => $hotel,
    'room_id'     => $room_id,
    'room_type'   => $room_type,
    'check_in'    => $check_in,
    'check_out'   => $check_out,
    'total_price' => $total_price,
    'user'        => $user,
    'bookings'    => $bookings,
    'number_of_nights' => $number_of_nights
]);

$conn = null;
?>
