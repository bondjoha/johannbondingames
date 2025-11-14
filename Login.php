<?php

ini_set('session.cookie_httponly', 1); // only http so that JavaScript cannot manipulate it in order to prevent XSS (Cross-Site Scripting)
ini_set('session.cookie_secure', 1);   // cookies are only sent through HTTPS connections
ini_set('session.use_strict_mode', 1); // use only initialized session IDs to protect against fake ones
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once 'databaseconnect.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// Check if the user is already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) 
{
    header("Location: Login.php");
    exit();
}

// Initialization of Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, []);
$error = false;

// login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    session_regenerate_id(true); // Creates a new session ID so that the old ones become invalid

    // PDO security configuration
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // generate exception in case of database error 
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);          // use only native prepared statements for security protection and correct data type

    // clean email data input and password
    $inputuseremail = filter_input(INPUT_POST, 'user_email', FILTER_SANITIZE_EMAIL);
    $inputpassword  = isset($_POST['user_password']) ? trim($_POST['user_password']) : '';

    // in case the email or password is not entered or invalid go back to the login page
    if (!$inputuseremail || !filter_var($inputuseremail, FILTER_VALIDATE_EMAIL) || empty($inputpassword)) 
    {
        $error = true;
    } 
    else 
    {
        try 
        {
            // SQL query to store the email (convert it to lowercase letters), hashed password and role
            // returns only one record
            $sql = "SELECT user_email, user_password, user_role
                    FROM logincredentials
                    WHERE LOWER(user_email) = LOWER(?)
                    LIMIT 1";

            // statement to prevent SQL injection and execute query
            $stmt = $conn->prepare($sql);
            $stmt->execute([$inputuseremail]);

            // fetch user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // close database connection
            $conn = null;

            // check that email address and password matches
            // it was used password_verify for safely comparison of plain-text password with the data base table hashed password
            if ($user && password_verify($inputpassword, $user['user_password'])) 
            {
                // make sessions to keep user logged in across pages with his accessibility
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_email'] = $user['user_email'];
                $_SESSION['user_role'] = $user['user_role'];

                // check if the user is administrator or a guest for website authorization
                if (strtolower($user['user_role']) === 'admin') 
                {
                    header("Location: Administrator.php");
                } 
                else 
                {
                    header("Location: HotelBooking.php");
                }
                exit();
            } 
            else 
            {
                $error = true;
            }

        } 
        catch (PDOException $e) 
        {
            // Log errors 
            error_log("Login error: " . $e->getMessage());
            $error = true;
        }
    }
}

// Render the login page template and pass any error message to it
echo $twig->render('LoginPage.html.twig', ['error' => $error]);

?>
