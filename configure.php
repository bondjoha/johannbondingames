<?php
// report and display all errors on page
if (getenv('APP_ENV') === 'development') 
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} 
else 
{
    error_reporting(E_ALL);
    ini_set('display_errors', 0); 
}

// Session cookie settings and connection is HTTPS
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
          (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

session_set_cookie_params
([
    'lifetime' => 0,       // Session expires on browser close
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'] ?? '', 
    'secure' => $secure,   // Only over HTTPS
    'httponly' => true,    // Prevent Java Script access
    'samesite' => 'Lax'    // Helps mitigate Cross-Site Request Forgery attacks
]);

//rejects uninitialized session ID
ini_set('session.use_strict_mode', 1);

// Start session safely
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
    session_regenerate_id(true); // Prevent session fixation
    // prevent caching
    header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    
}

// Include twig and database connection
require 'databaseconnect.php';
require 'vendor/autoload.php';
?>
