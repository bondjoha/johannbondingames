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

try {
    // Fetch all bookings for this user
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
    foreach ($bookings as &$b) 
    {
        $checkIn  = new DateTime($b['Check_in']);
        $checkOut = new DateTime($b['Check_out']);
        $b['Nights'] = $checkIn->diff($checkOut)->days;
    }


} catch (Exception $e) {
    die("Failed to fetch bookings: " . $e->getMessage());
}

// Render Twig template
echo $twig->render('HistoryBookings.html.twig', [
    'user'     => $user,
    'bookings' => $bookings
]);

$conn = null;
?>
