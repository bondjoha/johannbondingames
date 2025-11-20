<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include 'vendor/autoload.php'; // Twig
include 'databaseconnect.php'; // $conn as PDO

// Get logged-in user from session
$user = $_SESSION['user'] ?? null;
$isAdmin = $user && isset($user['role']) && strtolower($user['role']) === 'admin';

// Setting up the Twig 
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); 
$twig = new \Twig\Environment($loader);

// POST variables in the search form
$check_in  = $_POST['check_in'] ?? date('Y-m-d');
$check_out = $_POST['check_out'] ?? date('Y-m-d', strtotime('+7 days'));
$country   = $_POST['country'] ?? '';
$city      = $_POST['city'] ?? '';

// Dates Validation in variable today store today day and time
$today = new DateTime();
$today->setTime(0, 0, 0); // time is set midnight for better comparision

$check_in_date  = DateTime::createFromFormat('Y-m-d', $check_in);
$check_out_date = DateTime::createFromFormat('Y-m-d', $check_out);
$check_in  = $check_in_date ? $check_in_date->format('Y-m-d') : date('Y-m-d');
$check_out = $check_out_date ? $check_out_date->format('Y-m-d') : date('Y-m-d', strtotime('+7 days'));

// check that check-in is not in the past
// incase is in the past change it to today
if ($check_in_date < $today) 
{
    $check_in_date = clone $today;
    $check_in = $check_in_date->format('Y-m-d');
}

// check that check-out is not is after check-in
// incase it is before change it to on day after
if ($check_out_date <= $check_in_date) 
{
    $check_out_date = (clone $check_in_date)->modify('+1 day');
    $check_out = $check_out_date->format('Y-m-d');
}

// Countries query from database table 
$countries = $conn->query
("
    SELECT DISTINCT Hotel_Country_Name 
    FROM hotel_details 
    WHERE Is_Active = 1 
    ORDER BY Hotel_Country_Name
")->fetchAll(PDO::FETCH_ASSOC);

// Cities list based on country 
if ($country) 
{
    $stmt = $conn->prepare
    ("SELECT DISTINCT Hotel_City_Name 
      FROM hotel_details 
      WHERE Is_Active = 1 AND Hotel_Country_Name=? 
      ORDER BY Hotel_City_Name");
    $stmt->execute([$country]);
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
else 
{
    $cities = $conn->query
    ("SELECT DISTINCT Hotel_City_Name 
      FROM hotel_details 
      WHERE Is_Active = 1 
      ORDER BY Hotel_City_Name")
    ->fetchAll(PDO::FETCH_ASSOC);
}

// Active Hotels in Table
$sql = "SELECT * FROM hotel_details WHERE Is_Active = 1";
$params = [];
if ($country !== '') { $sql .= " AND Hotel_Country_Name = ?"; $params[] = $country; }
if ($city !== '') { $sql .= " AND Hotel_City_Name = ?"; $params[] = $city; }
$sql .= " ORDER BY Hotel_Name";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cities list based on country 
if ($country) 
{
    $stmt = $conn->prepare(
        "SELECT DISTINCT Hotel_City_Name 
         FROM hotel_details 
         WHERE Is_Active = 1 AND Hotel_Country_Name = ? 
         ORDER BY Hotel_City_Name"
    );
    $stmt->execute([$country]);
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
else 
{
    // No country selected => no cities
    $cities = [];
}

$conn = null;

// Render variable to template indexPage
echo $twig->render('indexPage.html.twig', 
[
    'check_in'  => $check_in,
    'check_out' => $check_out,
    'country'   => $country,
    'city'      => $city,
    'countries' => $countries,
    'cities'    => $cities,
    'hotels'    => $hotels,
    'user'      => $user,
    'isAdmin'       => $isAdmin
]);
?>
