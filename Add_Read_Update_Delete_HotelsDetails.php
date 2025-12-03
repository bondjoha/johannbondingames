<?php
require 'configure.php';

// initialiaze variables to null
$message = null;
$error = null;

// check that user has logged if not redirect to login 
if (!isset($_SESSION['user']) || !in_array(strtolower($_SESSION['user']['role']), ['admin','staff']))
{
    header('Location: Login.php');
    exit;
}

// Setting of twig 
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, 
[
    'autoescape' => 'html' // this line of code ensures that by default all output is escaped 
]);

// Function for the images of the hotels uploads
function uploadImage($file, $uploadDir = 'uploads/')
{
    // checks if file has been uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return null;

    // checks that the folder for the upload exist
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // creating a unique and safe file name 
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($file['name']));
    $targetPath = $uploadDir . $filename;

    // transfer uploaded file from the temporarily storage to the designated location
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $targetPath; // Return path to store in DB
    }
    return null;
}

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
    $Latitude = isset($_POST['Latitude']) ? floatval($_POST['Latitude']) : null;
    $Longitude = isset($_POST['Longitude']) ? floatval($_POST['Longitude']) : null;
    $staff_ids = $_POST['staff_ids'] ?? '';

    // Upload images
    $Hotel_Image  = uploadImage($_FILES['Hotel_Image']);
    $Hotel_Image2 = uploadImage($_FILES['Hotel_Image2']);
    $Hotel_Image3 = uploadImage($_FILES['Hotel_Image3']);

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
                    (Hotel_Name, Hotel_Street_Name, Hotel_City_Name, Hotel_Country_Name, Phone_Number, Email, Star_Rating, Number_of_Rooms, Hotel_Image, Hotel_Image2, Hotel_Image3, Latitude, Longitude)
                    VALUES 
                    (:Hotel_Name, :Hotel_Street_Name, :Hotel_City_Name, :Hotel_Country_Name, :Phone_Number, :Email, :Star_Rating, :Number_of_Rooms, :Hotel_Image, :Hotel_Image2, :Hotel_Image3, :Latitude, :Longitude)
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
                    ':Hotel_Image3' => $Hotel_Image3,
                    ':Latitude' => $Latitude,
                    ':Longitude' => $Longitude
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
    $Latitude = isset($_POST['Latitude']) ? floatval($_POST['Latitude']) : null;
    $Longitude = isset($_POST['Longitude']) ? floatval($_POST['Longitude']) : null;
    $staff_ids = $_POST['staff_ids'] ?? '';

    // Handle image replacement
    $Hotel_Image  = uploadImage($_FILES['Hotel_Image']) ?: $_POST['Hotel_Image_existing'];
    $Hotel_Image2 = uploadImage($_FILES['Hotel_Image2']) ?: $_POST['Hotel_Image2_existing'];
    $Hotel_Image3 = uploadImage($_FILES['Hotel_Image3']) ?: $_POST['Hotel_Image3_existing'];


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
            $stmt = $conn->prepare("
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
                    Hotel_Image3 = :Hotel_Image3,
                    Latitude = :Latitude,
                    Longitude = :Longitude
                WHERE Hotel_Id = :Hotel_Id
            ");
            $stmt->execute([
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
                ':Hotel_Image3' => $Hotel_Image3,
                ':Latitude' => $Latitude,
                ':Longitude' => $Longitude
            ]);
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
               
                $assignStmt->execute([
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

// Hiding hotel instead of deleting it
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
$selectedName = trim($_POST['hotel_name'] ?? '');

// Country list
$countriesQuery = $conn->query("SELECT DISTINCT Hotel_Country_Name FROM hotel_details WHERE Is_Active = 1 ORDER BY Hotel_Country_Name");
$countries = $countriesQuery->fetchAll(PDO::FETCH_COLUMN);

// City list based on country
$citiesQuery = "SELECT DISTINCT Hotel_City_Name FROM hotel_details WHERE Is_Active = 1";
$params = [];

if ($selectedCountry) 
{
    $citiesQuery .= " AND Hotel_Country_Name = :country";
    $params[':country'] = $selectedCountry;
}

$citiesQuery .= " ORDER BY Hotel_City_Name";
$cityStmt = $conn->prepare($citiesQuery);
$cityStmt->execute($params);
$cities = $cityStmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch hotels with filters
$hotels = [];
if (isset($_POST['filterHotels'])) 
{
    $sql = "SELECT * FROM hotel_details WHERE Is_Active = 1";
    $params = [];
    if ($selectedCountry) { $sql .= " AND Hotel_Country_Name = :country"; $params[':country'] = $selectedCountry; }
    if ($selectedCity) { $sql .= " AND Hotel_City_Name = :city"; $params[':city'] = $selectedCity; }
    if ($selectedName) { $sql .= " AND Hotel_Name LIKE :hotel_name"; $params[':hotel_name'] = "%$selectedName%"; }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
else 
{
    $stmt = $conn->query("SELECT * FROM hotel_details WHERE Is_Active = 1");
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch assigned staff for each hotel
foreach ($hotels as &$hotel) 
{
    $stmtAssigned = $conn->prepare
    ("
        SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS full_name, u.user_email
        FROM usershotels uh
        JOIN logincredentials u ON uh.user_id = u.user_id
        WHERE uh.hotel_id = :hotel_id AND uh.role = 'staff'
        LIMIT 1
    ");
    $stmtAssigned->execute([':hotel_id' => $hotel['Hotel_Id']]);
    $assignedStaff = $stmtAssigned->fetch(PDO::FETCH_ASSOC);

    if ($assignedStaff) 
    {
        $hotel['assigned_staff_id'] = $assignedStaff['user_id'];
        $hotel['assigned_staff_name'] = $assignedStaff['full_name'] . " (" . $assignedStaff['user_email'] . ")";
    } 
    else 
    {
        $hotel['assigned_staff_id'] = null;
        $hotel['assigned_staff_name'] = 'No staff assigned';
    }
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
    'users' => $users,  
    'selectedName' => $selectedName
]);