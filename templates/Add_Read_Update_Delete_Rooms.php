<?php
require 'configure.php';

// Check login
if (!isset($_SESSION['user'])) 
{
    header('Location: Login.php');
    exit;
}

$user = $_SESSION['user'];
$isAdmin = strtolower($user['role']) === 'admin';
$isStaff = strtolower($user['role']) === 'staff';

// Staff or admin only
if (!$isAdmin && !$isStaff) 
{
    header('Location: Login.php');
    exit;
}

$message = null;
$error = null;

//setup of twig
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, 
[
    'autoescape' => 'html' // Ensure all output is escaped by default
]);

// --- Retrieve all hotels records if user is admin ---
if ($isAdmin) 
{
    $hotelStmt = $conn->prepare("SELECT Hotel_Id, Hotel_Name FROM hotel_details WHERE Is_Active=1 ORDER BY Hotel_Name ASC");
    $hotelStmt->execute();
    $hotels = $hotelStmt->fetchAll(PDO::FETCH_ASSOC);
} 
else 
{
    $hotelStmt = $conn->prepare
    ("
        SELECT h.Hotel_Id, h.Hotel_Name 
        FROM hotel_details h
        JOIN usershotels uh ON h.Hotel_Id = uh.hotel_id
        WHERE uh.user_id=:staff_id AND h.Is_Active=1
        ORDER BY h.Hotel_Name ASC
    ");
    $hotelStmt->execute(['staff_id'=>$user['id']]);
    $hotels = $hotelStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Only admins can select hotel from dropdown
$selectedHotel = '';
if ($isAdmin) 
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hotel_filter'])) 
    {
        $selectedHotel = intval($_POST['hotel_filter']);
    }
} 
// if the user role is not admin in this case staff select the hotel_id assigned to the staff member
else 
{
    $selectedHotel = $hotels[0]['Hotel_Id'] ?? null;
}

//Valid room types
$validRoomTypes = ['Standard', 'Deluxe', 'Suite'];

//Search
$searchRoomNumber = trim($_GET['searchRoomNumber'] ?? '');

//Add room record
if (isset($_POST['submitRoomRecord'])) 
{
    $Hotel_Id = intval($_POST['Hotel_Id']);
    $Room_Number = trim($_POST['Room_Number']);
    $Room_Type = trim($_POST['Room_Type']);
    $Room_Price = floatval($_POST['Room_Price']);
    $Room_Status = trim($_POST['Room_Status']);
    $Room_Square_Meter = floatval($_POST['Room_Square_Meter']); 
    $Bed_Type = trim($_POST['Bed_Type']);
    $Is_Active = isset($_POST['Is_Active']) ? intval($_POST['Is_Active']) : 1;

    // --- Handle Images ---
    $uploadDir = 'images/rooms/';
    $Image1 = $Image2 = $Image3 = null;

    if (isset($_FILES['Image1']) && $_FILES['Image1']['tmp_name'] !== '') 
    {
        $Image1 = $uploadDir . basename($_FILES['Image1']['name']);
        move_uploaded_file($_FILES['Image1']['tmp_name'], $Image1);
    }
    if (isset($_FILES['Image2']) && $_FILES['Image2']['tmp_name'] !== '') 
    {
        $Image2 = $uploadDir . basename($_FILES['Image2']['name']);
        move_uploaded_file($_FILES['Image2']['tmp_name'], $Image2);
    }
    if (isset($_FILES['Image3']) && $_FILES['Image3']['tmp_name'] !== '') 
    {
        $Image3 = $uploadDir . basename($_FILES['Image3']['name']);
        move_uploaded_file($_FILES['Image3']['tmp_name'], $Image3);
    }

    // if user role is staff room cannot be added
    if ($isStaff && !in_array($Hotel_Id, array_column($hotels, 'Hotel_Id'))) 
    {
        $error = "You cannot add rooms to this hotel.";
    } 
    // validate room type
    elseif (!in_array($Room_Type, $validRoomTypes)) 
    {
        $error = "Invalid room type.";
    }
    // add room 
    else 
    {
        $check = $conn->prepare
        ("
            SELECT COUNT(*) 
            FROM hotels_rooms 
            WHERE Hotel_Id=:Hotel_Id AND Room_Number=:Room_Number AND Is_Active=1
        ");
        $check->execute(['Hotel_Id'=>$Hotel_Id, 'Room_Number'=>$Room_Number]);

        if ($check->fetchColumn() > 0) 
        {        
            $error = "Room number $Room_Number already exists for this hotel.";
        } 
        else 
        {
            try 
            {
                $stmt = $conn->prepare
                ("
                    INSERT INTO hotels_rooms 
                    (Hotel_Id, Room_Number, Room_Type, Price, Status, Room_Square_Meter, Bed_Type, Is_Active, Image1, Image2, Image3)
                    VALUES (:Hotel_Id, :Room_Number, :Room_Type, :Room_Price, :Room_Status, :Room_Square_Meter, :Bed_Type, :Is_Active, :Image1, :Image2, :Image3)
                ");
                // prepared SQL INSERT statement is executed so to add the new room record to the database table
                // Execution is also done a protection against SQL injection 
                $stmt->execute
                ([
                    'Hotel_Id'=>$Hotel_Id,
                    'Room_Number'=>$Room_Number,
                    'Room_Type'=>$Room_Type,
                    'Room_Price'=>$Room_Price,
                    'Room_Status'=>$Room_Status,
                    'Room_Square_Meter'=>$Room_Square_Meter,
                    'Bed_Type'=>$Bed_Type,
                    'Is_Active'=>$Is_Active,
                    'Image1'=>$Image1,
                    'Image2'=>$Image2,
                    'Image3'=>$Image3
                ]);
                $message = "Room added successfully!";
            } 
            catch (PDOException $e) 
            {
                $error = "Error adding room: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

//Updating Room Record
if (isset($_POST['updateroom'])) 
{
    $Room_Id = intval($_POST['Room_Id']);
    $Hotel_Id = intval($_POST['Hotel_Id']);
    $Room_Number = trim($_POST['Room_Number']);
    $Room_Type = trim($_POST['Room_Type']);
    $Room_Price = floatval($_POST['Room_Price']);
    $Room_Status = trim($_POST['Room_Status']);
    $Room_Square_Meter = floatval($_POST['Room_Square_Meter']); 
    $Bed_Type = trim($_POST['Bed_Type']);
    $Is_Active = isset($_POST['Is_Active']) ? intval($_POST['Is_Active']) : 1;

    // Uploading and displaying images
    $uploadDir = 'images/rooms/'; // Directory address

    // Retrieve images of the room from the database table
    $stmtImages = $conn->prepare("SELECT Image1, Image2, Image3 FROM hotels_rooms WHERE Room_Id=:Room_Id");
    $stmtImages->execute(['Room_Id'=>$Room_Id]);
    $existingImages = $stmtImages->fetch(PDO::FETCH_ASSOC);

    // transfer paths from table to php variables
    $Image1 = $existingImages['Image1'];
    $Image2 = $existingImages['Image2'];
    $Image3 = $existingImages['Image3'];

    // checking if images are uploaded
    if (isset($_FILES['Image1']) && $_FILES['Image1']['tmp_name'] !== '') 
    {
        $Image1 = $uploadDir . basename($_FILES['Image1']['name']);
        move_uploaded_file($_FILES['Image1']['tmp_name'], $Image1);
    }
    if (isset($_FILES['Image2']) && $_FILES['Image2']['tmp_name'] !== '') 
    {
        $Image2 = $uploadDir . basename($_FILES['Image2']['name']);
        move_uploaded_file($_FILES['Image2']['tmp_name'], $Image2);
    }
    if (isset($_FILES['Image3']) && $_FILES['Image3']['tmp_name'] !== '') 
    {
        $Image3 = $uploadDir . basename($_FILES['Image3']['name']);
        move_uploaded_file($_FILES['Image3']['tmp_name'], $Image3);
    }

    // check that ensures login user role is staff can only add room to the assigned hotel
    if ($isStaff && !in_array($Hotel_Id, array_column($hotels, 'Hotel_Id'))) 
    {
        $error = "You cannot update rooms in this hotel.";
    }
    // validate room type 
    elseif (!in_array($Room_Type, $validRoomTypes)) 
    {
        $error = "Invalid room type.";
    }
    // start the process to update room number 
    else 
    {
        // check that room number does not already exist
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
            $error = "Another room with this number already exists";
        } 
        // update room records
        else 
        {
            try 
            {
                $stmt = $conn->prepare
                ("
                    UPDATE hotels_rooms 
                    SET Hotel_Id=:Hotel_Id, Room_Number=:Room_Number, Room_Type=:Room_Type, 
                        Price=:Room_Price, Status=:Room_Status, Room_Square_Meter=:Room_Square_Meter,
                        Bed_Type=:Bed_Type, Is_Active=:Is_Active,
                        Image1=:Image1, Image2=:Image2, Image3=:Image3
                    WHERE Room_Id=:Room_Id
                ");
                $stmt->execute
                ([
                    'Hotel_Id'=>$Hotel_Id,
                    'Room_Number'=>$Room_Number,
                    'Room_Type'=>$Room_Type,
                    'Room_Price'=>$Room_Price,
                    'Room_Status'=>$Room_Status,
                    'Room_Square_Meter'=>$Room_Square_Meter,
                    'Bed_Type'=>$Bed_Type,
                    'Is_Active'=>$Is_Active,
                    'Image1'=>$Image1,
                    'Image2'=>$Image2,
                    'Image3'=>$Image3,
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

// Delete a room  record
if (isset($_POST['deleteroom'])) {
    // convert Room ID into an integer
    $Room_Id = intval($_POST['Room_Id']);
    try 
    {
        // Staff are only allowed to delete rooms in the hotel assigned to them
        if ($isStaff) 
        {
            $stmtCheck = $conn->prepare("SELECT Hotel_Id FROM hotels_rooms WHERE Room_Id = :Room_Id");
            $stmtCheck->execute(['Room_Id' => $Room_Id]);
            $row = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // Check if the room belongs to one of the logged in staff 
            if (!in_array($row['Hotel_Id'], array_column($hotels, 'Hotel_Id'))) 
            {
                $error = "You cannot delete rooms in this hotel.";
            }
        }

        // If everything is okay delete the room from the database base
        if (!$error) 
        {
            $stmt = $conn->prepare("DELETE FROM hotels_rooms WHERE Room_Id = :Room_Id");
            $stmt->execute(['Room_Id' => $Room_Id]);
            $message = "Room deleted ";
        }
    } 
    catch (PDOException $e) 
    {
        // Display any error safely
        $error = "Error deleting room: " . htmlspecialchars($e->getMessage());
    }
}


// Retrieve rooms with search and hotel filtering
$params = [];
$sql = "
    SELECT r.*, h.Hotel_Name 
    FROM hotels_rooms r
    JOIN hotel_details h ON r.Hotel_Id = h.Hotel_Id
    WHERE 1
";

// Staff can only see assigned hotels
if ($isStaff) 
{
    $sql .= " AND h.Hotel_Id IN (" . implode(',', array_column($hotels, 'Hotel_Id')) . ")";
}

// Admin hotel filter
if ($selectedHotel && $isAdmin) 
{
    $sql .= " AND h.Hotel_Id = :selectedHotel";
    $params['selectedHotel'] = $selectedHotel;
}

// Search by room number
if ($searchRoomNumber) 
{
    $sql .= " AND r.Room_Number LIKE :searchRoom";
    $params['searchRoom'] = "%$searchRoomNumber%";
}

$sql .= " ORDER BY r.Room_Number ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
$conn = null;

//Get selected hotel name
$selectedHotelName = null;
foreach ($hotels as $hotel) 
{
    if ($hotel['Hotel_Id'] == $selectedHotel) 
    {
        $selectedHotelName = $hotel['Hotel_Name'];
        break;
    }
}

//Render Twig template
echo $twig->render('TableRoomDetails.html.twig', 
[
    'hotels' => $hotels,
    'rooms' => $rooms,
    'selectedHotel' => $selectedHotel,
    'selectedHotelName' => $selectedHotelName,
    'searchRoomNumber' => htmlspecialchars($searchRoomNumber, ENT_QUOTES, 'UTF-8'),
    'message' => $message,
    'error' => $error,
    'isAdmin' => $isAdmin,
    'isStaff' => $isStaff,
    'user' => $user
]);
?>
