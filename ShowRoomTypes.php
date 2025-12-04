<?php

require 'configure.php';

// Twig setup
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, 
[
    'autoescape' => 'html' // Ensure all output is escaped by default
]);

// Identify logged in user
if (isset($_SESSION['user'])) 
{
    $user = $_SESSION['user'];
} 
else 
{
    $user = null;
}

// checks if user is Administrator (isAdmin gives either true or false)
$isAdmin = ($user && isset($user['role']) && strtolower($user['role']) === 'admin');

// storing the header search filters and the hotel id
$hotel_id   = filter_input(INPUT_POST, 'hotel_id', FILTER_VALIDATE_INT) 
           ?? filter_input(INPUT_GET, 'hotel_id', FILTER_VALIDATE_INT);

$check_in   = $_POST['check_in'] ?? $_GET['check_in'] ?? date('Y-m-d');
$check_out  = $_POST['check_out'] ?? $_GET['check_out'] ?? date('Y-m-d', strtotime('+1 day'));
$country    = $_POST['country'] ?? $_GET['country'] ?? '';
$city       = $_POST['city'] ?? $_GET['city'] ?? '';
$star_rating= $_POST['star_rating'] ?? $_GET['star_rating'] ?? '';

// Redirect POST to GET inorder to prevent cache and resubmission dialogs problems
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $query = http_build_query([
        'hotel_id'    => $hotel_id,
        'country'     => $country,
        'city'        => $city,
        'star_rating' => $star_rating,
        'check_in'    => $check_in,
        'check_out'   => $check_out
    ]);

    header("Location: " . $_SERVER['PHP_SELF'] . "?$query");
    exit;
}

// if there is no valid hotel id exit the code to prevent invalid SQL queries or any other misusage
if (empty($hotel_id)) 
{
    exit("Invalid or missing hotel ID.");
}

// Validate that check-in is before check-out
if (strtotime($check_in) >= strtotime($check_out)) 
{
    exit("Check-out date must be after check-in date.");
}

// Saving the search filters in the header in session
$_SESSION['selected_country'] = $country;
$_SESSION['selected_city']    = $city;
//$_SESSION['selected_star']    = $star_rating;
$_SESSION['check_in']         = $check_in;
$_SESSION['check_out']        = $check_out;

// Finding and storing in $hotel the hotel record which matches the searched hotel ID 
$stmt = $conn->prepare("SELECT * FROM hotel_details WHERE Hotel_Id = ?");
$stmt->execute([$hotel_id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);

// check that hotel exists if not exit the program
if (!$hotel) 
{
    exit("Hotel not found.");
}

// Retrieve all active room types associated with the selected hotel
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

// For each room type retrieve available rooms during the selected dates
foreach ($roomTypes as &$room) 
{
    $stmt = $conn->prepare("
        SELECT hr.Room_Number, hr.Room_Id
        FROM hotels_rooms hr
        LEFT JOIN booking b
            ON hr.Room_Id = b.Room_Id
            AND NOT (b.Check_Out <= ? OR b.Check_In >= ?)
        WHERE hr.Hotel_Id = ? 
          AND hr.Room_Type = ?
          AND hr.Is_Active = 1
          AND b.Room_Id IS NULL
        ORDER BY hr.Room_Number
    ");

    $stmt->execute([$check_in, $check_out, $hotel_id, $room['Room_Type']]);
    $availableRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($availableRooms) 
    {
        $room['available_rooms'] = $availableRooms;
    } 
    else 
    {
        $room['available_rooms'] = [];
        $room['no_rooms_message'] = "No rooms available for this type during selected dates.";
    }
}
unset($room);

// save pending bookings if not logged in
if (!$user && !empty($roomTypes)) 
{
    $firstRoomType = $roomTypes[0]['Room_Type'] ?? null;
    $firstRoom     = $roomTypes[0]['available_rooms'][0]['Room_Id'] ?? null;

    $_SESSION['pending_booking'] = [
        'hotel_id'    => $hotel_id,
        'room_id'     => $firstRoom,
        'room_type'   => $firstRoomType,
        'check_in'    => $check_in,
        'check_out'   => $check_out,
        'country'     => $country,
        'city'        => $city,
        'star_rating' => $star_rating,
        
    ];
}
// Include weather API data
include 'weatherApiOneHotel.php';
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
?>