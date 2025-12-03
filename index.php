<?php

require 'configure.php';

// Twig setup
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig   = new \Twig\Environment($loader, 
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

// get data from template search input via POST 
$check_in     = $_POST['check_in']     ?? date('Y-m-d');
$check_out    = $_POST['check_out']    ?? date('Y-m-d', strtotime('+1 day'));
$country      = $_POST['country']      ?? '';
$city         = $_POST['city']         ?? '';
$star_rating  = $_POST['star_rating']  ?? '';

// Validate check-in and check-out dates
$checkInDate  = DateTime::createFromFormat('Y-m-d', $check_in);
$checkOutDate = DateTime::createFromFormat('Y-m-d', $check_out);

if (!$checkInDate || !$checkOutDate || $checkInDate > $checkOutDate) 
{
    $check_in  = date('Y-m-d');
    $check_out = date('Y-m-d', strtotime('+1 day'));
}

// Validate star rating as integer
if ($star_rating !== '') 
{
    $star_rating = filter_var($star_rating, FILTER_VALIDATE_INT);
    if ($star_rating === false) 
    {
        $star_rating = '';
    }
}

// Loading countries from database table hotel details
try 
{
    $countries = $conn->query
    ("
        SELECT DISTINCT Hotel_Country_Name 
        FROM hotel_details 
        WHERE Is_Active = 1 
        ORDER BY Hotel_Country_Name
    ")->fetchAll(PDO::FETCH_ASSOC);
} 
catch (Exception $e) 
{
    $countries = [];
}

// Checks if country is selected and display the cities accordingly
if ($country) 
{
    $stmt = $conn->prepare
    ("
        SELECT DISTINCT Hotel_City_Name 
        FROM hotel_details 
        WHERE Is_Active = 1 AND Hotel_Country_Name = ?
        ORDER BY Hotel_City_Name
    ");
    $stmt->execute([$country]);
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else
{
    $cities = [];
}

// Filtering available hotels by country , city and star rating
$sql = "SELECT * FROM hotel_details WHERE Is_Active = 1";
$filteringValues = [];

if ($country !== '') 
{
    $sql .= " AND Hotel_Country_Name = ?";
    $filteringValues[] = $country;
}

if ($city !== '') 
{
    $sql .= " AND Hotel_City_Name = ?";
    $filteringValues[] = $city;
}

if ($star_rating !== '') 
{
    $sql .= " AND Star_Rating = ?";
    $filteringValues[] = $star_rating;
}

$sql .= " ORDER BY Hotel_Name";

$stmt = $conn->prepare($sql);
$stmt->execute($filteringValues);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Populating the search Star ratings dropdown according to the selected country/city
$ratingSql = 
"
    SELECT DISTINCT Star_Rating 
    FROM hotel_details 
    WHERE Is_Active = 1
";
$ratingValue = [];

// check if country is selected and store star ratings values according to the hotel
if ($country !== '') 
{
    $ratingSql .= " AND Hotel_Country_Name = ?";
    $ratingValue[] = $country;
}

// check if city is selected and get availiable star ratings values
if ($city !== '') 
{
    $ratingSql .= " AND Hotel_City_Name = ?";
    $ratingValue[] = $city;
}

// order in ascending the ratings values
$ratingSql .= " ORDER BY Star_Rating";

$stmt = $conn->prepare($ratingSql);
$stmt->execute($ratingValue);
$available_ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// close database connection
$conn = null;

// Render template
echo $twig->render('indexPage.html.twig',
[
    'check_in'     => $check_in,
    'check_out'    => $check_out,
    'country'      => $country,
    'city'         => $city,
    'star_rating'  => $star_rating,
    'countries'    => $countries,
    'cities'       => $cities,
    'hotels'       => $hotels,
    'user'         => $user,
    'isAdmin'      => $isAdmin,
    'available_ratings' => $available_ratings
]);
?>