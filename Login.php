<?php
require 'configure.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Twig setup
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, 
[
    'autoescape' => 'html' // Ensure all output is escaped by default
]);

// checking logging status to redirect to the correct webpage
if (!empty($_SESSION['user'])) 
{
    // If user is admin or staff, go to admin page
    if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'staff') 
    {
        header("Location: Administrator.php");
        exit;
    }

    // Otherwise redirect to homepage
    header("Location: index.php");
    exit;
}

$error = false; // initializing variable as boolean false

// store booking details entered by user in the previous page from session
if (!empty($_SESSION['pending_booking']) && empty($_POST['user_email'])) 
{
    $_POST = $_SESSION['pending_booking'];
}

// transfering booking details into allocated variables
$hotel_id    = $_POST['hotel_id']    ?? $_GET['hotel_id'] ?? null;
$room_id     = $_POST['room_id']     ?? $_GET['room_id'] ?? null;
$room_type   = $_POST['room_type']   ?? $_GET['room_type'] ?? null;
$check_in    = $_POST['check_in']    ?? $_GET['check_in'] ?? '';
$check_out   = $_POST['check_out']   ?? $_GET['check_out'] ?? '';
$country     = $_POST['country']     ?? $_GET['country'] ?? '';
$city        = $_POST['city']        ?? $_GET['city'] ?? '';
$star_rating = $_POST['star_rating'] ?? $_GET['star_rating'] ?? '';

// Validate and filtering booking inputs
$hotel_id    = filter_var($hotel_id, FILTER_VALIDATE_INT);
$room_id     = filter_var($room_id, FILTER_VALIDATE_INT);
$star_rating = filter_var($star_rating, FILTER_VALIDATE_INT);

// RECAPTCHA verification
$captcha_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Check CAPTCHA
    if (empty($_POST['g-recaptcha-response'])) 
    {
        $captcha_error = 'Please verify that you are not a robot.';
    } 
    else 
    {
        $captcha = $_POST['g-recaptcha-response'];
        $secretKey = "6LejTR8sAAAAADIhUv37BG9o4DBZKI2rYyrGbu9b";
        $verifyURL = "https://www.google.com/recaptcha/api/siteverify";

        $response = file_get_contents($verifyURL . "?secret=" . $secretKey . "&response=" . $captcha);
        $responseKeys = json_decode($response, true);

        if (!$responseKeys["success"]) 
        {
            $captcha_error = 'Captcha validation failed';
        }
    }

    // Stop login if CAPTCHA failed
    if ($captcha_error) 
    {
        echo $twig->render('LoginPage.html.twig', 
        [
            'error'         => $error,
            'captcha_error' => $captcha_error,
            'hotel_id'      => $hotel_id,
            'room_id'       => $room_id,
            'room_type'     => $room_type,
            'check_in'      => $check_in,
            'check_out'     => $check_out,
            'country'       => $country,
            'city'          => $city,
            'star_rating'   => $star_rating
        ]);
        exit;
    }
}


// checking that useremail and password are not left blank in the twig
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_email']) && !empty($_POST['user_password'])) 
{
    // sanitize email
    $email = filter_var(trim($_POST['user_email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['user_password']);

    // Server-side password pattern (min 8 chars, uppercase, lowercase, number, special char)
    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

    if (!$email) 
    {
        $error = 'Please enter a valid email.';
    } 
    elseif (!preg_match($passwordPattern, $password)) 
    {
        $error = 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character.';
    } 
    else 
    {
        try 
        {
            // load users credentials from database table
            $stmt = $conn->prepare("SELECT * FROM logincredentials WHERE user_email = :email");
            $stmt->execute(['email' => $email]);
            $userDB = $stmt->fetch(PDO::FETCH_ASSOC);

            // check that username and password are correct
            if ($userDB && isset($userDB['user_password']) && password_verify($password, $userDB['user_password'])) 
            {
                // regenerate session ID to prevent session fixation
                session_regenerate_id(true);

                // Save user information in the session (remove sensitive card info)
                $_SESSION['user'] = [
                    'id'         => $userDB['user_id'],
                    'first_name' => $userDB['first_name'],
                    'last_name'  => $userDB['last_name'],
                    'email'      => $userDB['user_email'],
                    'role'       => strtolower(trim($userDB['user_role'])),
                    'phone'      => $userDB['phone_number'],
                    'approved'   => $userDB['approved']
                ];

                // redirect to administration if user is admin or staff
                if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'staff') 
                {
                    unset($_SESSION['pending_booking']);
                    header("Location: Administrator.php");
                    exit;
                }

                // if user was making a booking and log in the process redirect to booking page
                if (!empty($_SESSION['pending_booking'])) 
                {
                    header("Location: booking.php");
                    exit;
                }

                // this is used for when user log in before trying to do a booking
                header("Location: index.php");
                exit;
            } 
            else 
            {
                $error = 'Invalid email or password.';
            }

        } 
        catch (PDOException $e) 
        {
            // Log the error message securely (do not expose to user)
            error_log("Login error: " . $e->getMessage());
            $error = 'An error occurred. Please try again later.';
        }
    }
}

// render to login twig template
echo $twig->render('LoginPage.html.twig', 
[
    'error'         => $error,
    'captcha_error' => $captcha_error,
    'hotel_id'      => $hotel_id,
    'room_id'       => $room_id,
    'room_type'     => $room_type,
    'check_in'      => $check_in,
    'check_out'     => $check_out,
    'country'       => $country,
    'city'          => $city,
    'star_rating'   => $star_rating
]);


$conn = null;
?>