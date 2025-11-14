<?php
require_once 'vendor/autoload.php'; // Make sure Twig is installed via Composer
include('databaseconnect.php');

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Set up Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

$message = "";

// Handle form submission
if (isset($_POST['submit'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $user_email = trim($_POST['user_email']);
    $phone_number = trim($_POST['phone_number']);
    $user_password = $_POST['user_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_role = $_POST['user_role'];

    if ($user_password === $confirm_password) 
    {
        $user_password = password_hash($user_password, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO logincredentials 
                (first_name, last_name, user_email, user_password, user_role, phone_number) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$first_name, $last_name, $user_email, $user_password, $user_role, $phone_number]);

            $message = "Registration successful! You can now <a href='LoginPage.php'>sign in</a>.";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "Passwords do not match.";
    }
}

$conn = null;

// Render Twig template
echo $twig->render('registerpage.html.twig', [
    'message' => $message
]);
