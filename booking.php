<?php
require 'configure.php';
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
// Twig setup
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, 
[
    'autoescape' => 'html' // Ensure all output is escaped by default
]);

// Identify logged in user
$user = $_SESSION['user'] ?? null;
// If no one is logged redirect to login and store booking data into the session
if (!$user) 
{
    $_SESSION['pending_booking'] = $_POST + $_GET; 
    $_SESSION['redirect_after_login'] = 'booking.php';
    header("Location: login.php");
    exit;
}

// checks if user is Administrator (isAdmin gives either true or false)
$isAdmin = ($user && isset($user['role']) && strtolower($user['role']) === 'admin');

// retrieval of booking data
$hotel_id  = $_POST['hotel_id']  ?? $_SESSION['pending_booking']['hotel_id'] ?? null;
$room_id   = $_POST['room_id']   ?? $_SESSION['pending_booking']['room_id'] ?? null;
$check_in  = $_POST['check_in']  ?? $_SESSION['pending_booking']['check_in'] ?? date('Y-m-d');
$check_out = $_POST['check_out'] ?? $_SESSION['pending_booking']['check_out'] ?? date('Y-m-d', strtotime('+1 day'));
$country   = $_POST['country']   ?? $_SESSION['pending_booking']['country'] ?? '';
$city      = $_POST['city']      ?? $_SESSION['pending_booking']['city'] ?? '';
$star_rating = $_POST['star_rating'] ?? $_SESSION['pending_booking']['star_rating'] ?? '';

// Validate hotel_id and room_id as integers
$hotel_id = filter_var($hotel_id, FILTER_VALIDATE_INT);
$room_id  = filter_var($room_id, FILTER_VALIDATE_INT);

// Incase there is no hotel or room ID exit the program redirect to index.php
if (!$hotel_id || !$room_id) 
{
    header("Location: index.php");
    exit; // Stop further execution
}

// Validate star rating if provided
if ($star_rating !== '') 
{
    $star_rating = filter_var($star_rating, FILTER_VALIDATE_INT, 
    [
        'options' => ['min_range' => 1, 'max_range' => 5]
    ]);

    if ($star_rating === false) 
    {
        $star_rating = '';
    }
}

// Creation of a unique key for the current booking to prevent using  duplicate POST data
$bookingKey = md5(json_encode
([
    'hotel' => $hotel_id,
    'room'  => $room_id,
    'in'    => $check_in,
    'out'   => $check_out
]));

// Prevention of duplicate or stale POST submissions with the use of a unique booking key and redirecting as GET
if (!isset($_SESSION['booking_load']) || $_SESSION['booking_load'] !== $bookingKey) 
{

    $_SESSION['booking_load'] = $bookingKey;
    header("Location: " . $_SERVER['PHP_SELF'] . "?ref=" . urlencode($bookingKey));
    exit;
}

// retrieve selected hotel information
$stmt = $conn->prepare("SELECT * FROM hotel_details WHERE Hotel_Id = ?");
$stmt->execute([$hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

// If no hotel is found stop execution and display error message
if (!$hotel) 
{
    exit("Hotel not found.");
}

// retrieving selected room 
$stmt = $conn->prepare("SELECT * FROM hotels_rooms WHERE Room_Id = ? AND Is_Active = 1");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

// If no room is found stop execution and display error message
if (!$room) 
{
    exit("Room not found.");
}

// Calculate number of nights and the total price
$checkInDate  = new DateTime($check_in);
$checkOutDate = new DateTime($check_out);
$interval     = $checkInDate->diff($checkOutDate);
$nights       = $interval->days;
$totalPrice   = $room['Price'] * $nights;

// Rendering to Twig BookingPage.html.twig
echo $twig->render('BookingPage.html.twig', 
[
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