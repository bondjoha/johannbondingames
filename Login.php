<?php
session_start();
include 'databaseconnect.php';
require_once 'vendor/autoload.php';

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); 
$twig = new \Twig\Environment($loader);

$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_email'], $_POST['user_password'])) 
{
    $email = trim($_POST['user_email']);
    $password = $_POST['user_password'];

    try 
    {
        $stmt = $conn->prepare("SELECT * FROM logincredentials WHERE user_email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['user_password'])) {
            // Save full user info in session
            $_SESSION['user'] = [
                'id' => $user['user_id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['user_email'],
                'role' => strtolower(trim($user['user_role'])),
                'phone' => $user['phone_number'],
                'cardNumber' => $user['cardNumber'],
                'expiry' => $user['expiry'],
                'cvv' => $user['cvv']
            ];

            // If admin, redirect to Administrator.php and forget pending booking
            if ($_SESSION['user']['role'] === 'admin') 
            {
                unset($_SESSION['pending_booking']);
                header('Location: Administrator.php');
                exit;
            }

            // Regular user with pending booking
            if (!empty($_POST['hotel_name']) && !empty($_POST['room_type'])) 
            {
                $_SESSION['pending_booking'] = 
                [
                    'hotel_name' => $_POST['hotel_name'],
                    'room_type' => $_POST['room_type'],
                    'check_in' => $_POST['check_in'] ?? date('Y-m-d'),
                    'check_out' => $_POST['check_out'] ?? date('Y-m-d', strtotime('+7 days'))
                ];
                header('Location: booking.php');
                exit;
            }

            // Regular user with no pending booking
            header('Location: index.php');
            exit;

        } else 
        {
            $error = true; // invalid credentials
        }

    } catch (PDOException $e) 
    {
        $error = true; // database error
    }
}

// Render login page
echo $twig->render('LoginPage.html.twig', ['error' => $error]);
$conn = null;
?>
