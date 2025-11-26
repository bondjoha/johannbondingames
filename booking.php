<?php
ini_set('display_errors', 1);
session_start();

include 'vendor/autoload.php';
include 'databaseconnect.php'; // $conn as PDO

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Twig setup
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader);

// Logged-in user
$user    = $_SESSION['user'] ?? null;
$isAdmin = $user && isset($user['role']) && strtolower($user['role']) === 'admin';

// Redirect to login if not logged in
if (!$user) {
    $_SESSION['pending_booking'] = $_POST + $_GET; // save all params
    header("Location: login.php");
    exit;
}

// --------------------------
// Get booking info from POST or pending_booking session
// --------------------------
$hotel_id  = $_POST['hotel_id']  ?? $_SESSION['pending_booking']['hotel_id'] ?? null;
$room_id   = $_POST['room_id']   ?? $_SESSION['pending_booking']['room_id'] ?? null;
$check_in  = $_POST['check_in']  ?? $_SESSION['pending_booking']['check_in'] ?? date('Y-m-d');
$check_out = $_POST['check_out'] ?? $_SESSION['pending_booking']['check_out'] ?? date('Y-m-d', strtotime('+1 day'));
$country   = $_POST['country']   ?? $_SESSION['pending_booking']['country'] ?? '';
$city      = $_POST['city']      ?? $_SESSION['pending_booking']['city'] ?? '';
$star_rating = $_POST['star_rating'] ?? $_SESSION['pending_booking']['star_rating'] ?? '';

// Validate
if (!$hotel_id || !$room_id) {
    die("Incomplete booking information.");
}

// --------------------------
// Fetch hotel info
// --------------------------
$stmt = $conn->prepare("SELECT * FROM hotel_details WHERE Hotel_Id = ?");
$stmt->execute([$hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$hotel) die("Hotel not found.");

// --------------------------
// Fetch selected room
// --------------------------
$stmt = $conn->prepare("SELECT * FROM hotels_rooms WHERE Room_Id = ? AND Is_Active = 1");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$room) die("Room not found.");

// --------------------------
// Calculate total nights and price
// --------------------------
$checkInDate  = new DateTime($check_in);
$checkOutDate = new DateTime($check_out);
$interval     = $checkInDate->diff($checkOutDate);
$nights       = $interval->days;
$totalPrice   = $room['Price'] * $nights;

// --------------------------
// Render Twig
// --------------------------
echo $twig->render('BookingPage.html.twig', [
    'hotel'       => $hotel,
    'room'        => $room,
    'user'        => $user,
    'isAdmin'     => $isAdmin,
    'check_in'    => $check_in,
    'check_out'   => $check_out,
    'nights'      => $nights,
    'totalPrice'  => $totalPrice,
    'country'     => $country,
    'city'        => $city,
    'star_rating' => $star_rating
]);

$conn = null;
?>
