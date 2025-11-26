<?php
ini_set('display_errors', 1);
session_start();

// including twig and pdo connection
include 'vendor/autoload.php'; 
include 'databaseconnect.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Twig setup
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader);

// Login user session
$user    = $_SESSION['user'] ?? null;
$isAdmin = $user && isset($user['role']) && strtolower($user['role']) === 'admin';

//storing the header search filters and the hotel id
$hotel_id   = $_POST['hotel_id'] ?? $_GET['hotel_id'] ?? null;
$check_in   = $_POST['check_in'] ?? $_GET['check_in'] ?? date('Y-m-d');
$check_out  = $_POST['check_out'] ?? $_GET['check_out'] ?? date('Y-m-d', strtotime('+1 day'));
$country    = $_POST['country'] ?? $_GET['country'] ?? '';
$city       = $_POST['city'] ?? $_GET['city'] ?? '';
$star_rating= $_POST['star_rating'] ?? $_GET['star_rating'] ?? '';

if (!$hotel_id) 
{
    die("Hotel not specified.");
}

// Saving the search filters in the header in session
$_SESSION['selected_country'] = $country;
$_SESSION['selected_city']    = $city;
$_SESSION['selected_star']    = $star_rating;
$_SESSION['check_in']         = $check_in;
$_SESSION['check_out']        = $check_out;

// Fetching all information from database table hotel details
$stmt = $conn->prepare("SELECT * FROM hotel_details WHERE Hotel_Id = ?");
$stmt->execute([$hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$hotel) die("Hotel not found.");

// Fetching all information from database table hotel rooms
$stmt = $conn->prepare("SELECT * FROM hotels_rooms WHERE Hotel_Id = ? AND Is_Active = 1");
$stmt->execute([$hotel_id]);
$roomTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// getting the countries to populate the header country dropdown
$countries = $conn->query
("
    SELECT DISTINCT Hotel_Country_Name 
    FROM hotel_details 
    WHERE Is_Active = 1 
    ORDER BY Hotel_Country_Name
")->fetchAll(PDO::FETCH_ASSOC);

// getting the cities to populate the header city dropdown according to the country
// in case country is not selected the dropdown will not be populated
$cities = [];
if (!empty($hotel['Hotel_Country_Name'])) 
{
    $stmt = $conn->prepare("
        SELECT DISTINCT Hotel_City_Name 
        FROM hotel_details 
        WHERE Is_Active = 1 AND Hotel_Country_Name = ?
        ORDER BY Hotel_City_Name
    ");
    $stmt->execute([$hotel['Hotel_Country_Name']]);
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// selecting first avaliable room number for each room type
foreach ($roomTypes as &$room) 
{
    $stmt = $conn->prepare
    ("
        SELECT hr.Room_Number, hr.Room_Id
        FROM hotels_rooms hr
        WHERE hr.Hotel_Id = ? 
          AND hr.Room_Type = ?
          AND hr.Is_Active = 1
          AND hr.Room_Id NOT IN 
          (
              SELECT b.Room_Id
              FROM booking b
              WHERE NOT 
              (
                  b.Check_Out <= ? OR b.Check_In >= ?
              )
          )
        LIMIT 1
    ");
    $stmt->execute([$hotel_id, $room['Room_Type'], $check_in, $check_out]);
    $FreeRoom = $stmt->fetch(PDO::FETCH_ASSOC); // fetch single room

    if ($FreeRoom) 
    {
        // assign only the first available room in an array for consistency
        $room['available_rooms'] = [$FreeRoom];
    } 
    else 
    {
        $room['available_rooms'] = [];
        $room['no_rooms_message'] = "No rooms available for this type during selected dates.";
    }
}
unset($room);

$hotels = include 'weatherApiOneHotel.php';

// Render to Twig
echo $twig->render('ShowRoomTypes.html.twig', 
[
    'hotel'       => $hotel,
    'roomTypes'   => $roomTypes,
    'user'        => $user,
    'isAdmin'     => $isAdmin,
    'check_in'    => $check_in,
    'check_out'   => $check_out,
    'country'     => $country,
    'city'        => $city,
    'star_rating' => $star_rating,
    'countries'   => $countries,
    'cities'      => $cities
]);

$conn = null;
