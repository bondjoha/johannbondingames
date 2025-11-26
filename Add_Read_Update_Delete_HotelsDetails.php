<?php
session_start();

$message = null;
$error = null;

if (!isset($_SESSION['user']) && strtolower($_SESSION['user']['role']) !== 'admin' && strtolower($_SESSION['user']['role']) !== 'staff')
{
    header('Location: Login.php');
    exit;
}

// Setting of twig and database
include __DIR__ . '/vendor/autoload.php';
include 'databaseconnect.php';
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, []);


// Add new hotel
if (isset($_POST['submitHotelRecord'])) 
{
    $Hotel_Name = trim($_POST['Hotel_Name']);
    $Hotel_Street_Name = trim($_POST['Hotel_Street_Name']);
    $Hotel_City_Name = trim($_POST['Hotel_City_Name']);
    $Hotel_Country_Name = trim($_POST['Hotel_Country_Name']);
    $Phone_Number = trim($_POST['Phone_Number']);
    $Email = trim($_POST['Email']);
    $Star_Rating = intval($_POST['Star_Rating']);
    $Number_of_Rooms = intval($_POST['Number_of_Rooms']);
    $Hotel_Image = trim($_POST['Hotel_Image']);
    $Hotel_Image2 = trim($_POST['Hotel_Image2'] ?? '');
    $Hotel_Image3 = trim($_POST['Hotel_Image3'] ?? '');
    $staff_ids = $_POST['staff_ids'] ?? '';

    if ($Hotel_Name !== "" && $Hotel_Country_Name !== "") 
    {
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
                // Insert hotel
                $stmt = $conn->prepare("
                    INSERT INTO hotel_details 
                    (Hotel_Name, Hotel_Street_Name, Hotel_City_Name, Hotel_Country_Name, Phone_Number, Email, Star_Rating, Number_of_Rooms, Hotel_Image, Hotel_Image2, Hotel_Image3)
                    VALUES 
                    (:Hotel_Name, :Hotel_Street_Name, :Hotel_City_Name, :Hotel_Country_Name, :Phone_Number, :Email, :Star_Rating, :Number_of_Rooms, :Hotel_Image, :Hotel_Image2, :Hotel_Image3)
                ");
                $stmt->execute([
                    ':Hotel_Name' => $Hotel_Name,
                    ':Hotel_Street_Name' => $Hotel_Street_Name,
                    ':Hotel_City_Name' => $Hotel_City_Name,
                    ':Hotel_Country_Name' => $Hotel_Country_Name,
                    ':Phone_Number' => $Phone_Number,
                    ':Email' => $Email,
                    ':Star_Rating' => $Star_Rating,
                    ':Number_of_Rooms' => $Number_of_Rooms,
                    ':Hotel_Image' => $Hotel_Image,
                    ':Hotel_Image2' => $Hotel_Image2,
                    ':Hotel_Image3' => $Hotel_Image3
                ]);

                // Get the ID of the newly inserted hotel
                $hotelId = $conn->lastInsertId();

                // Assign staff to this hotel
                if (!empty($staff_ids)) 
                {
                    $assignStmt = $conn->prepare("
                        INSERT INTO usershotels (user_id, hotel_id, role)
                        VALUES (:user_id, :hotel_id, 'staff')
                        ON DUPLICATE KEY UPDATE assigned_at = CURRENT_TIMESTAMP()
                    ");

                    $assignStmt->execute([
                        ':user_id' => $staff_ids,
                        ':hotel_id' => $hotelId
                    ]);
                }

                $message = "New hotel record added successfully, and staff assigned!";
            } 
            catch (PDOException $e) 
            {
                $error = "Error adding new hotel record: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

// Update hotel
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
    $Hotel_Image2 = trim($_POST['Hotel_Image2'] ?? '');
    $Hotel_Image3 = trim($_POST['Hotel_Image3'] ?? '');
    $staff_ids = $_POST['staff_ids'] ?? '';

    $check = $conn->prepare("SELECT COUNT(*) FROM hotel_details WHERE Hotel_Name = :Hotel_Name AND Hotel_Id != :Hotel_Id AND Is_Active = 1");
    $check->execute([':Hotel_Name' => $Hotel_Name, ':Hotel_Id' => $Hotel_Id]);
    if ($check->fetchColumn() > 0) 
    {
        $error = "Another hotel with the name '$Hotel_Name' already exists!";
    } 
    else 
    {
        try {
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
                    Hotel_Image = :Hotel_Image,
                    Hotel_Image2 = :Hotel_Image2,
                    Hotel_Image3 = :Hotel_Image3
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
                ':Hotel_Image' => $Hotel_Image,
                ':Hotel_Image2' => $Hotel_Image2,
                ':Hotel_Image3' => $Hotel_Image3
            ]);

            // --- Update staff assignments ---
            // Remove all previous staff assignments for this hotel
            $conn->prepare("DELETE FROM usershotels WHERE hotel_id = :hotel_id AND role='staff'")->execute([
                ':hotel_id' => $Hotel_Id
            ]);

            // Assign new staff
            if (!empty($staff_ids)) 
            {
                $assignStmt = $conn->prepare("
                    INSERT INTO usershotels (user_id, hotel_id, role)
                    VALUES (:user_id, :hotel_id, 'staff')
                    ON DUPLICATE KEY UPDATE assigned_at = CURRENT_TIMESTAMP()
                ");
               
                    $assignStmt->execute
                    ([
                        ':user_id' => $staff_ids,
                        ':hotel_id' => $Hotel_Id
                    ]);
            }

            $message = "Hotel record updated successfully!";
        } 
        catch (PDOException $e) 
        {
            $error = "Error updating hotel record: " . htmlspecialchars($e->getMessage());
        }
    }
}

// Delete hotel
if (isset($_POST['delethotel'])) 
    {
    $Hotel_Id = intval($_POST['Hotel_Id']);
    try 
    {
        $stmt = $conn->prepare("UPDATE hotel_details SET Is_Active = 0 WHERE Hotel_Id = :Hotel_Id");
        $stmt->execute([':Hotel_Id' => $Hotel_Id]);
        $message = "Hotel record hidden successfully!";
    } 
    catch (PDOException $e) 
    {
        $error = "Error hiding hotel record: " . htmlspecialchars($e->getMessage());
    }
}

// Filters
$selectedCountry = $_POST['country'] ?? '';
$selectedCity = $_POST['city'] ?? '';

// Country list
$countriesQuery = $conn->query("SELECT DISTINCT Hotel_Country_Name FROM hotel_details WHERE Is_Active = 1 ORDER BY Hotel_Country_Name");
$countries = $countriesQuery->fetchAll(PDO::FETCH_COLUMN);

// City list based on country
$cities = [];
if ($selectedCountry) 
{
    $cityStmt = $conn->prepare("SELECT DISTINCT Hotel_City_Name FROM hotel_details WHERE Hotel_Country_Name = :country AND Is_Active = 1 ORDER BY Hotel_City_Name");
    $cityStmt->execute([':country' => $selectedCountry]);
    $cities = $cityStmt->fetchAll(PDO::FETCH_COLUMN);
}

// Fetch hotels
$hotels = [];
if (isset($_POST['filterHotels'])) 
{
    $sql = "SELECT * FROM hotel_details WHERE Is_Active = 1";
    $params = [];
    if ($selectedCountry) { $sql .= " AND Hotel_Country_Name = :country"; $params[':country'] = $selectedCountry; }
    if ($selectedCity) { $sql .= " AND Hotel_City_Name = :city"; $params[':city'] = $selectedCity; }
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
else 
{
    $stmt = $conn->query("SELECT * FROM hotel_details WHERE Is_Active = 1");
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all staff users for dropdown
try 
{
    $stmtUsers = $conn->query("SELECT user_id, first_name, last_name, user_email 
                               FROM logincredentials 
                               WHERE user_role = 'staff' AND approved = 1
                               ORDER BY first_name, last_name");
    $users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
} 
catch (PDOException $e) 
{
    $users = [];
    $error = "Error fetching staff users: " . htmlspecialchars($e->getMessage());
}


$conn = null;

// Render Twig
echo $twig->render('TableHotelDetails.html.twig', 
[
    'hotels' => $hotels,
    'message' => $message,
    'error' => $error,
    'countries' => $countries,
    'cities' => $cities,
    'selectedCountry' => $selectedCountry,
    'selectedCity' => $selectedCity,
    'users' => $users,          // <-- add this
]);

