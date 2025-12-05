<?php

// security configure
ini_set('session.cookie_httponly', 1); // HttpOnly for security
ini_set('session.use_strict_mode', 1); // it allow only session ID generated through php

// if the connection uses the HTTPS session cookies will be used for secure connections
if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
     $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) 
{
    ini_set('session.cookie_secure', 1);
}

// start session and regeneration
session_start();
session_regenerate_id(true);

// twig and database connection
require __DIR__ . '/vendor/autoload.php';
require 'databaseconnect.php';

// setup twig 
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// check log in is done
if (!isset($_SESSION['user'])) 
{
    header("Location: Login.php");
    exit();
}

$user = $_SESSION['user']; // getting user details
$role = strtolower($user['role'] ?? ''); // convert to lower case

// checking that user role is either admin or staff
if ($role !== 'admin' && $role !== 'staff') 
{
    header("Location: Login.php");
    exit();
}

// storing admin name
$adminName = $user['first_name'] ?? 'Admin';
$hotelName = null;

// retrieve hotel name to which user with role staff is assigned
if ($role === 'staff') 
    {
    $stmt = $conn->prepare("
        SELECT hd.Hotel_Name
        FROM usershotels uh
        JOIN hotel_details hd ON uh.hotel_id = hd.Hotel_Id
        WHERE uh.user_id = :staff_id
        LIMIT 1
    ");

    $stmt->execute(['staff_id' => $user['id']]);
    $hotel = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($hotel) 
    {
        $hotelName = $hotel['Hotel_Name'];
    } else 
    {
        $hotelName = "Unassigned Staff";
    }
}

// hotel name (page header title)  incase of admin role 
if ($role === 'admin') 
{
    $hotelName = "Administration Panel";
}

// render to twig template 
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

echo $twig->render('AdministratorPage.html.twig', [
    'adminName'  => htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8'),
    'hotelName'  => htmlspecialchars($hotelName, ENT_QUOTES, 'UTF-8'),
    'role'       => htmlspecialchars($role, ENT_QUOTES, 'UTF-8'),
    'isApproved' => htmlspecialchars($user['approved'], ENT_QUOTES, 'UTF-8')
]);

?>
