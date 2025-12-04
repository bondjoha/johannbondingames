<?php
require 'configure.php'; // Includes session setup, database, and Twig

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Set up Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, 
[
    'autoescape' => 'html' // Ensure all output is escaped by default
]);

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) 
{
    // Sanitize and validate input
    $first_name   = htmlspecialchars(trim($_POST['first_name'] ?? ''), ENT_QUOTES, 'UTF-8');// remove extra spaces and make it safe for being output
    $last_name    = htmlspecialchars(trim($_POST['last_name'] ?? ''), ENT_QUOTES, 'UTF-8'); // remove extra spaces and make it safe for being output
    $user_email   = filter_var(trim($_POST['user_email'] ?? ''), FILTER_VALIDATE_EMAIL); // remove extra spaces and validate  email
    $phone_number = preg_replace('/[^0-9+]/', '', $_POST['phone_number'] ?? ''); //remove non digits characters 
    $user_password = $_POST['user_password'] ?? ''; // stores passwoord if availiable otherwise leave it empty
    $confirm_password = $_POST['confirm_password'] ?? ''; // confirm password storing
    $user_role    = in_array($_POST['user_role'] ?? '', ['admin','staff','guest']) ? $_POST['user_role'] : 'guest'; // allow only admin, staff or customer

    // Validate required fields
    if (!$first_name || !$last_name || !$user_email || !$phone_number || !$user_password || !$confirm_password) 
    {
        $message = "All fields are required.";
    } 
    elseif ($user_password !== $confirm_password) 
    {
        $message = "Passwords do not match.";
    } 
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $user_password)) 
    {
        $message = "Password must include uppercase, lowercase, number, and special character.";
    }
    else 
    {
        try 
        {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM logincredentials WHERE user_email = ?");
            $stmt->execute([$user_email]);
            if ($stmt->fetchColumn() > 0) 
            {
                $message = "This email is already registered.";
            } 
            else 
            {
                // Hash password securely
                $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

                // Insert user into database
                $stmt = $conn->prepare("
                    INSERT INTO logincredentials 
                    (first_name, last_name, user_email, user_password, user_role, phone_number) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$first_name, $last_name, $user_email, $hashed_password, $user_role, $phone_number]);

                $message = "Registration successful! You can now <a href='LoginPage.php'>sign in</a>.";

                //Reset form fields
                $first_name = '';
                $last_name = '';
                $user_email = '';
                $phone_number = '';
                $user_role = '';
            }
        } 
        catch (PDOException $e) 
        {
            // Log error and show generic message
            error_log("Registration error: " . $e->getMessage());
            $message = "Registration failed. Please try again later.";
        }
    }
}

// Close database connection
$conn = null;

// Render Twig template
echo $twig->render('registerpage.html.twig', 
[
    'message'        => $message,
    'first_name'     => $first_name ?? '',
    'last_name'      => $last_name ?? '',
    'user_email'     => $user_email ?? '',
    'phone_number'   => $phone_number ?? '',
    'user_role'      => $user_role ?? ''
]);
?>
