<?php

// Security
ini_set('session.cookie_httponly', 1);//Prevent JavaScript from accessing session cookies
ini_set('session.use_strict_mode', 1);//Reject uninitialized session IDs to prevent fixation

if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) 
{
    ini_set('session.cookie_secure', 1);
}

session_start();  // Start or resume the user session
session_regenerate_id(true); // Generate a new session ID to prevent fixation

// twig and database set up
include __DIR__ . '/vendor/autoload.php';
include 'databaseconnect.php';
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// Check login
if (!isset($_SESSION['user'])) 
{
    header("Location: Login.php");
    exit();
}

$user = $_SESSION['user'];

//Check the role if it is admin otherwise redirect back to login
if (!isset($user['role']) && (strtolower($user['role']) !== 'admin' || strtolower($user['role']) !== 'staff')) 
{
    header("Location: Login.php");
    exit();
}

// store name
$adminName = $user['first_name'] ?? 'Admin';

// Twig Initialization 
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);


// Render to twig
echo $twig->render('AdministratorPage.html.twig', 
[
    'adminName' => htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8'),
    'isApproved' => htmlspecialchars($user['approved'], ENT_QUOTES, 'UTF-8'), // to approve staff role 
    'role' => htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') 
]);
?>
