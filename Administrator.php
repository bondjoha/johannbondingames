<?php

ini_set('session.cookie_httponly', 1); // prevent JavaScript access

// checking that connection is secure
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) 
{
    ini_set('session.cookie_secure', 1); // only send cookies over HTTPS
}
ini_set('session.use_strict_mode', 1); // prevent uninitialized session IDs

session_start();
session_regenerate_id(true); // regenerate session ID

require_once __DIR__ . '/vendor/autoload.php'; //Loading Composer autoloader
require_once 'databaseconnect.php'; // Include the database connection file

// Import Twig classes to load templates and to render them
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// check if user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) 
{
    header("Location: Login.php");
    exit();
}

// check if user is admin
if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') 
{
    header("Location: Login.php");
    exit();
}

// include database connection (already required above)
// secure PDO settings
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// Fetch admin first name safely using prepared statement
$adminName = 'Admin';
if (isset($_SESSION['user_email']) && filter_var($_SESSION['user_email'], FILTER_VALIDATE_EMAIL)) 
{
    try
    {
        $sql = "SELECT first_name FROM logincredentials WHERE LOWER(user_email) = LOWER(?) LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION['user_email']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['first_name'])) 
        {
            $adminName = htmlspecialchars($row['first_name'], ENT_QUOTES, 'UTF-8');
        }
    } 
    catch (PDOException $e) 
    {
        error_log("Admin lookup error: " . $e->getMessage());
    }
}

// close DB connection
$conn = null;

// Initialize Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, []);

// Render the admin dashboard
echo $twig->render('AdministratorPage.html.twig', ['adminName' => $adminName]);
?>
