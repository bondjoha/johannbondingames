<?php

session_start(); 

// Check login and that the role is admin
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true ||!isset($_SESSION['user_role']) || strtolower($_SESSION['user_role']) !== 'admin') 
{
    header('Location: LoginPage.php');
    exit;
}

$message = null;
$error   = null;

// Load Twig and database connection
require_once __DIR__ . '/vendor/autoload.php';
require_once 'databaseconnect.php';
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader, []);

// Hotels  names for dropdown
$hotelStmt = $conn->prepare("SELECT Hotel_Id, Hotel_Name FROM hotel_details WHERE Is_Active=1 ORDER BY Hotel_Name ASC");
$hotelStmt->execute();
$hotels = $hotelStmt->fetchAll(PDO::FETCH_ASSOC);

// Room types selection
$validRoomTypes = ['Standard', 'Deluxe', 'Suite'];

// Adding room
if (isset($_POST['submitRoomRecord'])) 
{
    $Hotel_Id = intval($_POST['Hotel_Id']);
    $Room_Number = trim($_POST['Room_Number']);
    $Room_Type = trim($_POST['Room_Type']);
    $Room_Price = floatval($_POST['Room_Price']);
    $Room_Status = trim($_POST['Room_Status']);
    $Room_Square_Meter = floatval($_POST['Room_Square_Meter']); // NEW FIELD

    // Check that Room Type can be only standard, deluxe or suite
    if (!in_array($Room_Type, $validRoomTypes)) {
        $error = "Invalid room type.";
    } 
    else 
    {
        // Check that room number does not exist before adding room record
        $check = $conn->prepare("SELECT COUNT(*) FROM hotels_rooms WHERE Hotel_Id=:Hotel_Id AND Room_Number=:Room_Number AND Is_Active=1");
        $check->execute(['Hotel_Id'=>$Hotel_Id, 'Room_Number'=>$Room_Number]);

        if ($check->fetchColumn() > 0) 
        {
            $error = "Room number $Room_Number already exists for this hotel.";
        } 
        // Adding record
        else 
        {
            try 
            {
                $stmt = $conn->prepare
                ("
                    INSERT INTO hotels_rooms (Hotel_Id, Room_Number, Room_Type, Price, Status, Room_Square_Meter, Is_Active)
                    VALUES (:Hotel_Id, :Room_Number, :Room_Type, :Room_Price, :Room_Status, :Room_Square_Meter, 1)
                ");

                $stmt->execute([
                    'Hotel_Id'=>$Hotel_Id,
                    'Room_Number'=>$Room_Number,
                    'Room_Type'=>$Room_Type,
                    'Room_Price'=>$Room_Price,
                    'Room_Status'=>$Room_Status,
                    'Room_Square_Meter'=>$Room_Square_Meter
                ]);

                $message = "Room added successfully!";
            } 
            catch (PDOException $e) 
            {
                $error = "Error adding new room: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

if (isset($_POST['updateroom'])) 
{
    $Room_Id = intval($_POST['Room_Id']);
    $Hotel_Id = intval($_POST['Hotel_Id']);
    $Room_Number = trim($_POST['Room_Number']);
    $Room_Type = trim($_POST['Room_Type']);
    $Room_Price = floatval($_POST['Room_Price']);
    $Room_Status = trim($_POST['Room_Status']);
    $Room_Square_Meter = floatval($_POST['Room_Square_Meter']); 

    // Check Room Type input is correct
    if (!in_array($Room_Type, $validRoomTypes)) 
    {
        $error = "Invalid room type.";
    } 
    else 
    {
        // Check room number does not exist
        $check = $conn->prepare
        ("
            SELECT COUNT(*) 
            FROM hotels_rooms 
            WHERE Hotel_Id=:Hotel_Id AND Room_Number=:Room_Number AND Room_Id!=:Room_Id AND Is_Active=1
        ");
        $check->execute
        ([
            'Hotel_Id'=>$Hotel_Id,
            'Room_Number'=>$Room_Number,
            'Room_Id'=>$Room_Id
        ]);

        if ($check->fetchColumn() > 0) 
        {
            $error = "Another room with this number already exists in this hotel.";
        } 
        else 
        {
            try 
            {
                $stmt = $conn->prepare
                ("
                    UPDATE hotels_rooms 
                    SET Hotel_Id=:Hotel_Id, Room_Number=:Room_Number, Room_Type=:Room_Type, Price=:Room_Price, Status=:Room_Status, Room_Square_Meter=:Room_Square_Meter
                    WHERE Room_Id=:Room_Id
                ");
                $stmt->execute([
                    'Hotel_Id'=>$Hotel_Id,
                    'Room_Number'=>$Room_Number,
                    'Room_Type'=>$Room_Type,
                    'Room_Price'=>$Room_Price,
                    'Room_Status'=>$Room_Status,
                    'Room_Square_Meter'=>$Room_Square_Meter,
                    'Room_Id'=>$Room_Id
                ]);

                $message = "Room updated successfully!";
            } 
            catch (PDOException $e) 
            {
                $error = "Error updating room: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

// Hiding Room Record
if (isset($_POST['deleteroom'])) 
{
    $Room_Id = intval($_POST['Room_Id']);
    try 
    {
        $stmt = $conn->prepare("UPDATE hotels_rooms SET Is_Active=0 WHERE Room_Id=:Room_Id");
        $stmt->execute(['Room_Id'=>$Room_Id]);

        $message = "Room hidden successfully!";
    } 
    catch (PDOException $e) 
    {
        $error = "Error hiding room: " . htmlspecialchars($e->getMessage());
    }
}


// Showing rooms according to hotel name selection
$selectedHotel = $_POST['hotel_filter'] ?? '';

if ($selectedHotel) 
{
    $stmt = $conn->prepare
    ("
        SELECT r.*, h.Hotel_Name 
        FROM hotels_rooms r
        JOIN hotel_details h ON r.Hotel_Id = h.Hotel_Id
        WHERE r.Is_Active=1 AND r.Hotel_Id=:Hotel_Id
        ORDER BY r.Room_Number ASC
    ");
    $stmt->execute(['Hotel_Id'=>$selectedHotel]);
} 
else 
{
    $stmt = $conn->prepare
    ("
        SELECT r.*, h.Hotel_Name 
        FROM hotels_rooms r
        JOIN hotel_details h ON r.Hotel_Id = h.Hotel_Id
        WHERE r.Is_Active=1
        ORDER BY h.Hotel_Name ASC, r.Room_Number ASC
    ");
    $stmt->execute();
}

$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
$conn = null; // Close DB connection

// Render page to twig template
echo $twig->render('RoomDetails.html.twig', [
    'hotels'=>$hotels,
    'rooms'=>$rooms,
    'selectedHotel'=>$selectedHotel,
    'message'=>$message,
    'error'=>$error
]);

?>
