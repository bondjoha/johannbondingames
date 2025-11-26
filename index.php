<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'vendor/autoload.php';
include 'databaseconnect.php';

// Get logged-in user
$user = $_SESSION['user'] ?? null;
$isAdmin = ($user && isset($user['role']) && strtolower($user['role']) === 'admin');

// Twig setup
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig   = new \Twig\Environment($loader);

// POST variables ONLY
$check_in     = $_POST['check_in']     ?? date('Y-m-d');
$check_out    = $_POST['check_out']    ?? date('Y-m-d', strtotime('+1 day'));
$country      = $_POST['country']      ?? '';
$city         = $_POST['city']         ?? '';
$star_rating  = $_POST['star_rating']  ?? '';

// Countries Query
$countries = $conn->query
("
    SELECT DISTINCT Hotel_Country_Name 
    FROM hotel_details 
    WHERE Is_Active = 1 
    ORDER BY Hotel_Country_Name
")->fetchAll(PDO::FETCH_ASSOC);

// Cities list based on POSTed country
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

// Load hotels with filtering
$sql = "SELECT * FROM hotel_details WHERE Is_Active = 1";
$params = [];

if ($country !== '') 
{
    $sql .= " AND Hotel_Country_Name = ?";
    $params[] = $country;
}

if ($city !== '') 
{
    $sql .= " AND Hotel_City_Name = ?";
    $params[] = $city;
}

if ($star_rating !== '') 
{
    $sql .= " AND Star_Rating = ?";
    $params[] = $star_rating;
}

$sql .= " ORDER BY Hotel_Name";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    'isAdmin'      => $isAdmin
]);
?>
