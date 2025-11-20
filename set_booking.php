<?php
session_start();
require_once 'databaseconnect.php';

// Get POST data
$hotelId   = $_POST['hotel_id'] ?? null;
$roomType  = $_POST['room_type'] ?? null;
$checkIn   = $_POST['check_in'] ?? null;
$checkOut  = $_POST['check_out'] ?? null;

if (!$hotelId || !$roomType) 
{
    echo "<p class='text-danger'>Missing hotel or room information.</p>";
    exit;
}

// Fetch hotel details
$stmt = $conn->prepare("SELECT * FROM hotel_details WHERE Hotel_Id = ? AND Is_Active = 1");
$stmt->execute([$hotelId]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hotel) 
{
    echo "<p class='text-danger'>Hotel not found.</p>";
    exit;
}

// Store pending booking in session including dates
$_SESSION['pending_booking'] = 
[
    'hotel_id'   => $hotelId,
    'hotel_name' => $hotel['Hotel_Name'],
    'room_type'  => $roomType,
    'check_in'   => $checkIn,
    'check_out'  => $checkOut
];

// Redirect depending on login
if (!isset($_SESSION['user'])) 
{
    header('Location: login.php');
    exit;
} 
else 
{
    header('Location: booking.php');
    exit;
}
?>
