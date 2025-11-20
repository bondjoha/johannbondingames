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

// Render Twig Template
echo $twig->render('customerBookingViewPage.html.twig', 
[
    'hotels' => $hotels,
    'rooms' => $rooms,
    'users' => $users,
    'bookings' => $bookings,
    'message' => $message,
    'error' => $error
]);
?>