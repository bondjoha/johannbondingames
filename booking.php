<?php
session_start();

// twig and database connection 
include 'vendor/autoload.php';
include 'databaseconnect.php';

// Setting up Twig 
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);


// Redirect if not logged in
if (!isset($_SESSION['user'])) 
{
    header('Location: login.php');
    exit;
}

// Redirect in case there is no pending booking
if (!isset($_SESSION['pending_booking'])) 
{
    header('Location: index.php');
    exit;
}

$pending = $_SESSION['pending_booking'];
$user = $_SESSION['user'];

// Get hotel details
if (!isset($pending['hotel_id'])) 
{
    die("Pending booking missing hotel ID.");
}

$stmt = $conn->prepare("SELECT * FROM hotel_details WHERE Hotel_Id = ? AND Is_Active = 1");
$stmt->execute([$pending['hotel_id']]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$hotel) die("Hotel not found.");

// Get room details
if (!isset($pending['room_type'])) 
{
    die("Pending booking missing room type.");
}

$stmt = $conn->prepare("SELECT * FROM hotels_rooms WHERE Hotel_Id = ? AND Room_Type = ? AND Is_Active = 1");
$stmt->execute([$pending['hotel_id'], $pending['room_type']]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$room) die("Room not found.");

// Full user name
$fullName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));

// Calculate nights & total price
$checkInDate = isset($pending['check_in']) ? new DateTime($pending['check_in']) : null;
$checkOutDate = isset($pending['check_out']) ? new DateTime($pending['check_out']) : null;
$nights = 0;
$totalPrice = 0.0;
if ($checkInDate && $checkOutDate && $room) 
{
    $nights = $checkInDate->diff($checkOutDate)->days; // number of  nights
    $totalPrice = $nights * floatval($room['Price']); // total cost
}

// Booking form handling

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    if (isset($_POST['confirmBooking'])) 
    {
        $checkIn  = $_POST['check_in'] ?? '';
        $checkOut = $_POST['check_out'] ?? '';
        $userId   = $user['id'];
        $roomId   = $room['Room_Id'];
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
            // Check that bookings do not overlap
            $stmt = $conn->prepare
            (
                "SELECT COUNT(*) FROM booking 
                 WHERE Room_Id = :room AND (Check_in <= :checkOut AND Check_out >= :checkIn)"
            );
            $stmt->execute
            ([
                ':room' => $roomId,
                ':checkIn' => $checkIn,
                ':checkOut' => $checkOut
            ]);

            if ($stmt->fetchColumn() > 0) 
            {
                $error = "Room is already booked for selected dates.";
            } 
            else 
            {
                // make booking
                $stmtInsert = $conn->prepare
                (
                    "INSERT INTO booking (Room_Id, CredentialsID, Check_in, Check_out)
                     VALUES (:room, :person, :checkin, :checkout)"
                );
                $stmtInsert->execute
                ([
                    ':room' => $roomId,
                    ':person' => $userId,
                    ':checkin'   => $checkIn,
                    ':checkout'   => $checkOut
                ]);

                // Update room status
                $conn->prepare("UPDATE hotels_rooms SET Status='Booked' WHERE Room_Id=:room")
                     ->execute([':room' => $roomId]);

                unset($_SESSION['pending_booking']);

                header("Location: customerBookingView.php");
                exit;
            }
        }
    }

    // Cancel booking
    if (isset($_POST['cancelBooking'])) 
    {
        unset($_SESSION['pending_booking']);
        header("Location: index.php");
        exit;
    }
}

// Render booking page twig
echo $twig->render('booking.html.twig', 
[
    'user'             => $user,
    'hotel'            => $hotel,
    'room'             => $room,
    'fullName'         => $fullName,
    'pending_checkin'  => $pending['check_in'] ?? '',
    'pending_checkout' => $pending['check_out'] ?? '',
    'error'            => $error,
    'nights'           => $nights,
    'totalPrice'       => $totalPrice
]);

$conn = null;
?>
