<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    // Empty session variables
    $_SESSION = [];

    // Delete the session cookie
    if (ini_get("session.use_cookies")) 
    {
        $params = session_get_cookie_params();
        setcookie
        (
            session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Remove session
    session_destroy();

    // Go back to login page
    header("Location: Login.php");
    exit();
}

// In case that it is accessed without POST method go back to login page
header("Location: LoginPage.php");
exit();
?>
