<?php
// Start session that identifies admin
session_start();

$message = null; // Initialize message
$error = null;   // Initialize error

// Checks that user are logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) 
{
    header('Location: Login.php');
    exit;
}

if (!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') 
{
    header('Location: Login.php');
    exit;
}

// Include Twig Composer autoloader  and database connection
require_once __DIR__ . '/vendor/autoload.php';
require_once 'databaseconnect.php';
require_once 'weather.php';

// Import Twig classes
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// Initialize Twig
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, []);

// Add new hotel record when submit button is pressed
if (isset($_POST['submitHotelRecord'])) 
{
    // Store and clean input values
    $Hotel_Name = trim($_POST['Hotel_Name']);
    $Hotel_Street_Name = trim($_POST['Hotel_Street_Name']);
    $Hotel_City_Name = trim($_POST['Hotel_City_Name']);
    $Hotel_Country_Name = trim($_POST['Hotel_Country_Name']);
    $Phone_Number = trim($_POST['Phone_Number']);
    $Email = trim($_POST['Email']);
    $Star_Rating = intval($_POST['Star_Rating']);
    $Number_of_Rooms = intval($_POST['Number_of_Rooms']);
    $Hotel_Image = trim($_POST['Hotel_Image']);

    // Check that required fields are not empty
    if ($Hotel_Name !== "" && $Hotel_Country_Name !== "") 
    {
        // Check for duplicate hotel name by counting
        $check = $conn->prepare("SELECT COUNT(*) FROM hotel_details WHERE Hotel_Name = :Hotel_Name AND Is_Active = 1");
        $check->execute([':Hotel_Name' => $Hotel_Name]);
        if ($check->fetchColumn() > 0) 
        {
            $error = "A hotel with the name '$Hotel_Name' already exists!";
        }
        else
        {
            try 
            {
                // Insert a new record into the hotel_details table
                $stmt = $conn->prepare
                ("
                    INSERT INTO hotel_details 
                    (Hotel_Name, Hotel_Street_Name, Hotel_City_Name, Hotel_Country_Name, Phone_Number, Email, Star_Rating, Number_of_Rooms, Hotel_Image)
                    VALUES 
                    (:Hotel_Name, :Hotel_Street_Name, :Hotel_City_Name, :Hotel_Country_Name, :Phone_Number, :Email, :Star_Rating, :Number_of_Rooms, :Hotel_Image)
                ");
                $stmt->execute
                ([
                    ':Hotel_Name' => $Hotel_Name,
                    ':Hotel_Street_Name' => $Hotel_Street_Name,
                    ':Hotel_City_Name' => $Hotel_City_Name,
                    ':Hotel_Country_Name' => $Hotel_Country_Name,
                    ':Phone_Number' => $Phone_Number,
                    ':Email' => $Email,
                    ':Star_Rating' => $Star_Rating,
                    ':Number_of_Rooms' => $Number_of_Rooms,
                    ':Hotel_Image' => $Hotel_Image
                ]);

                // Set success message to display in HotelDetails Twig template
                $message = "New hotel record added successfully!";
            } 
            catch (PDOException $e) 
            {
                // Set error message to display in HotelDetails Twig template
                $error = "Error adding new hotel record: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

// Update hotel record when update button is pressed
if (isset($_POST['updatehotel'])) 
{
    $Hotel_Id = intval($_POST['Hotel_Id']);
    $Hotel_Name = trim($_POST['Hotel_Name']);
    $Hotel_Street_Name = trim($_POST['Hotel_Street_Name']);
    $Hotel_City_Name = trim($_POST['Hotel_City_Name']);
    $Hotel_Country_Name = trim($_POST['Hotel_Country_Name']);
    $Phone_Number = trim($_POST['Phone_Number']);
    $Email = trim($_POST['Email']);
    $Star_Rating = intval($_POST['Star_Rating']);
    $Number_of_Rooms = intval($_POST['Number_of_Rooms']);
    $Hotel_Image = trim($_POST['Hotel_Image']);

    // Check for duplicate hotel name 
    $check = $conn->prepare("SELECT COUNT(*) FROM hotel_details WHERE Hotel_Name = :Hotel_Name AND Hotel_Id != :Hotel_Id AND Is_Active = 1");
    $check->execute([':Hotel_Name' => $Hotel_Name, ':Hotel_Id' => $Hotel_Id]);
    if ($check->fetchColumn() > 0) 
    {
        $error = "Another hotel with the name '$Hotel_Name' already exists!";
    }
    else
    {
        try 
        {
            $stmt = $conn->prepare
            ("
                UPDATE hotel_details SET 
                    Hotel_Name = :Hotel_Name,
                    Hotel_Street_Name = :Hotel_Street_Name,
                    Hotel_City_Name = :Hotel_City_Name,
                    Hotel_Country_Name = :Hotel_Country_Name,
                    Phone_Number = :Phone_Number,
                    Email = :Email,
                    Star_Rating = :Star_Rating,
                    Number_of_Rooms = :Number_of_Rooms,
                    Hotel_Image = :Hotel_Image
                WHERE Hotel_Id = :Hotel_Id
            ");
            $stmt->execute
            ([
                ':Hotel_Id' => $Hotel_Id,
                ':Hotel_Name' => $Hotel_Name,
                ':Hotel_Street_Name' => $Hotel_Street_Name,
                ':Hotel_City_Name' => $Hotel_City_Name,
                ':Hotel_Country_Name' => $Hotel_Country_Name,
                ':Phone_Number' => $Phone_Number,
                ':Email' => $Email,
                ':Star_Rating' => $Star_Rating,
                ':Number_of_Rooms' => $Number_of_Rooms,
                ':Hotel_Image' => $Hotel_Image
            ]);
            //Set success message to display in HotelDetails Twig template
            $message = "Hotel record updated successfully!";
        } 
        catch (PDOException $e) 
        {
            // Set error message to display in HotelDetails Twig template
            $error = "Error updating hotel record: " . htmlspecialchars($e->getMessage());
        }
    }
}

// Delete/hide hotel record when delete button is pressed
if (isset($_POST['delethotel'])) 
{
    $Hotel_Id = intval($_POST['Hotel_Id']);
    try 
    {
        $stmt = $conn->prepare("UPDATE hotel_details SET Is_Active = 0 WHERE Hotel_Id = :Hotel_Id");
        $stmt->execute([':Hotel_Id' => $Hotel_Id]);        
        $message = "Hotel record hidden successfully!"; //Set success message to display in HotelDetails Twig template
    } 
    catch (PDOException $e) 
    {
        //Set error message to display in HotelDetails Twig template
        $error = "Error hiding hotel record: " . htmlspecialchars($e->getMessage());
    }
}

/// Filtering by Country and City only when form is submitted
$selectedCountry = $_POST['country'] ?? '';
$selectedCity = $_POST['city'] ?? '';

// Country dropdown
$countriesQuery = $conn->query("SELECT DISTINCT Hotel_Country_Name FROM hotel_details WHERE Is_Active = 1 ORDER BY Hotel_Country_Name");
$countries = $countriesQuery->fetchAll(PDO::FETCH_COLUMN);

// City dropdown based on selected country
$cities = [];
if ($selectedCountry) 
{
    $cityStmt = $conn->prepare("SELECT DISTINCT Hotel_City_Name FROM hotel_details WHERE Hotel_Country_Name = :country AND Is_Active = 1 ORDER BY Hotel_City_Name");
    $cityStmt->execute([':country' => $selectedCountry]);
    $cities = $cityStmt->fetchAll(PDO::FETCH_COLUMN);
}

// Only fetch hotels if the filter form was submitted
$hotels = [];
if (isset($_POST['filterHotels'])) 
{
    $sql = "SELECT * FROM hotel_details WHERE Is_Active = 1";
    $params = [];
    if ($selectedCountry) 
    {
        $sql .= " AND Hotel_Country_Name = :country";
        $params[':country'] = $selectedCountry;
    }
    if ($selectedCity) 
    {
        $sql .= " AND Hotel_City_Name = :city";
        $params[':city'] = $selectedCity;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else
{
    // Optionally, show all hotels when the page first loads
    $stmt = $conn->query("SELECT * FROM hotel_details WHERE Is_Active = 1");
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$conn = null;

// Render Twig
echo $twig->render('HotelDetails.html.twig', 
[
    'hotels' => $hotels,
    'message' => $message,
    'error' => $error,
    'countries' => $countries,
    'cities' => $cities,
    'selectedCountry' => $selectedCountry,
    'selectedCity' => $selectedCity
]);
?>
