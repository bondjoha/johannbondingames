<?php
session_start();

// include twig and database connections
require_once 'vendor/autoload.php';
include 'databaseconnect.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Twig setup
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$error = false;

// Storing data from POST or GET methods
$hotel_id    = $_POST['hotel_id']    ?? $_GET['hotel_id'] ?? null;
$room_id     = $_POST['room_id']     ?? $_GET['room_id'] ?? null;
$check_in    = $_POST['check_in']    ?? $_GET['check_in'] ?? '';
$check_out   = $_POST['check_out']   ?? $_GET['check_out'] ?? '';
$country     = $_POST['country']     ?? $_GET['country'] ?? '';
$city        = $_POST['city']        ?? $_GET['city'] ?? '';
$star_rating = $_POST['star_rating'] ?? $_GET['star_rating'] ?? '';

// Process the login form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_email']) && !empty($_POST['user_password'])) {

    $email = trim($_POST['user_email']);
    $password = $_POST['user_password'];

    try 
    {
        $stmt = $conn->prepare("SELECT * FROM logincredentials WHERE user_email = :email");
        $stmt->execute(['email' => $email]);
        $userDB = $stmt->fetch(PDO::FETCH_ASSOC);         

        if ($userDB && password_verify($password, $userDB['user_password'])) {

            // Save data about the guest in the session
            $_SESSION['user'] = 
            [
                'id'         => $userDB['user_id'],
                'first_name' => $userDB['first_name'],
                'last_name'  => $userDB['last_name'],
                'email'      => $userDB['user_email'],
                'role'       => strtolower(trim($userDB['user_role'])),
                'phone'      => $userDB['phone_number'],
                'cardNumber' => $userDB['cardNumber'],
                'expiry'     => $userDB['expiry'],
                'cvv'        => $userDB['cvv'],
                'approved'   => $userDB['approved']
            ];

            // if the super admin log in redirect to Administrator.php
            if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'staff')
            {
                
                unset($_SESSION['pending_booking']);
                header("Location: Administrator.php");
                exit;
            }

            // pending booking session 
            if ($hotel_id) {
                $_SESSION['pending_booking'] = 
                [
                    'hotel_id'    => $hotel_id,
                    'room_id'     => $room_id,
                    'check_in'    => $check_in,
                    'check_out'   => $check_out,
                    'country'     => $country,
                    'city'        => $city,
                    'star_rating' => $star_rating
                ];

                // Incase the room not selected by guest it will redirect to hotel page
                if (empty($room_id)) 
                {
                    header("Location: ShowRoomTypes.php?hotel_id={$hotel_id}&check_in={$check_in}&check_out={$check_out}");
                    exit;
                }
                else
                {
                    // But id the room has been selected it will redirect the user booking page
                    header("Location: booking.php");
                    exit;
                }
            }

            // Incase of no pending booking redirect to home page
            header("Location: index.php");
            exit;

        } else 
        {
            $error = true;
        }

    } catch (PDOException $e) 
    {
        $error = true;
    }
}

// Render to template login page
echo $twig->render('LoginPage.html.twig',
[
    'error'      => $error,
    'hotel_id'   => $hotel_id,
    'room_id'    => $room_id,
    'check_in'   => $check_in,
    'check_out'  => $check_out,
    'country'    => $country,
    'city'       => $city,
    'star_rating'=> $star_rating
]);

$conn = null;
?>
