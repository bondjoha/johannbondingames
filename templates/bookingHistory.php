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

// check that user is logged in
if (!isset($_SESSION['user'])) 
{
    header("Location: login.php");
    exit;
}

// retrieve user details and id
$user = $_SESSION['user'];
// Validate user ID as integer
$user_id = filter_var($user['id'], FILTER_VALIDATE_INT);
if (!$user_id) 
{
    exit("Invalid user ID.");
}
// var_dump($user);
// var_dump($user_id);
// exit;

try 
{
    // retrieve all the bookings of user
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

    // calculating number of nights
    foreach ($bookings as &$b) 
    {
        try 
        {
            $checkIn  = new DateTime($b['Check_in']);
            $checkOut = new DateTime($b['Check_out']);
            $b['Nights'] = $checkIn->diff($checkOut)->days;
        } catch (Exception $e) 
        {
            $b['Nights'] = 0;
        }
    }

} 
catch (Exception $e) 
{
    die("Failed to fetch bookings: " . $e->getMessage());
}

// Rendering to twig template
echo $twig->render('HistoryBookings.html.twig', 
[
    'user'     => $user,
    'bookings' => $bookings
]);

$conn = null;
?>
