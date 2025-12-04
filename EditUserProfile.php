<?php
require 'configure.php';

// Twig setup 
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
$loader = new FilesystemLoader('templates');
$twig   = new Environment($loader, 
[
    'autoescape' => 'html' // Ensure all output is escaped by default
]);

// Redirect if not logged in
if (!isset($_SESSION['user']['id'])) 
{
    header("Location: Login.php");
    exit();
}

$user_id = filter_var($_SESSION['user']['id'], FILTER_VALIDATE_INT);
if (!$user_id) 
{
    die("Invalid user session.");
}

$message = "";

// Fetch user details
$stmt = $conn->prepare("
    SELECT first_name, last_name, user_email, phone_number, user_password 
    FROM logincredentials 
    WHERE user_id = :id
");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Trim and sanitize input
    $first_name = htmlspecialchars(trim($_POST['first_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $last_name  = htmlspecialchars(trim($_POST['last_name'] ?? ''), ENT_QUOTES, 'UTF-8');
    $email      = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone      = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
    $old_pass   = trim($_POST['old_password'] ?? '');
    $new_pass   = trim($_POST['new_password'] ?? '');
    $confirm_pass = trim($_POST['confirm_password'] ?? '');

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $message = "Invalid email address.";
    } 
    elseif (empty($first_name) || empty($last_name)) 
    {
        $message = "First and last name cannot be empty.";
    } 
    else 
    {
        // Check if password update is requested
        $update_password = !empty($new_pass);

        if ($update_password) 
        {
            // Verify old password
            if (!password_verify($old_pass, $user['user_password'])) 
            {
                $message = "Old password is incorrect.";
            } 
            elseif ($new_pass !== $confirm_pass) 
            {
                $message = "New passwords do not match.";
            } 
            // Password strength validation: Minimum 8 characters, uppercase, lowercase, number, special character
            elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_pass)) 
            {
                $message = "New password must include uppercase, lowercase, number, and special character.";
            }
            else 
            {
                // Hash new password
                $hashed_password = password_hash($new_pass, PASSWORD_BCRYPT);

                $stmt = $conn->prepare("
                    UPDATE logincredentials
                    SET first_name = :first, last_name = :last, user_email = :email, phone_number = :phone, user_password = :password
                    WHERE user_id = :id
                ");
                $stmt->execute([
                    'first'    => $first_name,
                    'last'     => $last_name,
                    'email'    => $email,
                    'phone'    => $phone,
                    'password' => $hashed_password,
                    'id'       => $user_id
                ]);

                $message = "Profile and password updated successfully!";
            }
        } 
        else 
        {
            // Update without password
            $stmt = $conn->prepare("
                UPDATE logincredentials
                SET first_name = :first, last_name = :last, user_email = :email, phone_number = :phone
                WHERE user_id = :id
            ");
            $stmt->execute([
                'first' => $first_name,
                'last'  => $last_name,
                'email' => $email,
                'phone' => $phone,
                'id'    => $user_id
            ]);

            $message = "Profile updated successfully!";
        }

        // Refresh user data
        $stmt = $conn->prepare("
            SELECT first_name, last_name, user_email, phone_number, user_password 
            FROM logincredentials 
            WHERE user_id = :id
        ");
        $stmt->execute(['id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Update session safely
        $_SESSION['user']['first_name'] = $user['first_name'];
        $_SESSION['user']['last_name']  = $user['last_name'];
        $_SESSION['user']['email']      = $user['user_email'];
        $_SESSION['user']['phone']      = $user['phone_number'];
    }
}

// Render Twig template
echo $twig->render('EditUserProfile.html.twig', 
[
    'user'        => $user,
    'message'     => $message,
    'first_name'  => $first_name ?? $user['first_name'],
    'last_name'   => $last_name ?? $user['last_name'],
    'email'       => $email ?? $user['user_email'],
    'phone'       => $phone ?? $user['phone_number']
]);

$conn = null;
?>