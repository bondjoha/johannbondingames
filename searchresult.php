<?php

ini_set('display_errors', 1);
session_start();

// include twig and database connection
include 'vendor/autoload.php'; // Twig
include 'databaseconnect.php'; // $conn as PDO

// Twig Setup
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig   = new \Twig\Environment($loader);

// Logged in guest credentials
$user    = $_SESSION['user'] ?? null;
$isAdmin = $user && isset($user['role']) && strtolower($user['role']) === 'admin';

// search data from POST when search button is clicked
$country     = $_POST['country']     ?? '';
$city        = $_POST['city']        ?? '';
$star_rating = $_POST['star_rating'] ?? '';
$check_in    = $_POST['check_in']    ?? '';
$check_out   = $_POST['check_out']   ?? '';

// Save the data of the search in the session
$_SESSION['selected_country'] = $country;
$_SESSION['selected_city']    = $city;
$_SESSION['selected_star']    = $star_rating;
$_SESSION['check_in']         = $check_in;
$_SESSION['check_out']        = $check_out;

// get the country list from the database table hotel details
$countries = $conn->query
("
    SELECT DISTINCT Hotel_Country_Name
    FROM hotel_details
    WHERE Is_Active = 1
    ORDER BY Hotel_Country_Name
")
->fetchAll(PDO::FETCH_ASSOC);

// get the city list from the database table hotel details
if (!empty($country))
{
    $stmt = $conn->prepare
    (
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
    $cities = [];
}

// find the hotel that matches the search inputs 

$sql    = "SELECT * FROM hotel_details WHERE Is_Active = 1";
$searchResult = [];

if ($country !== '')
{
    $sql .= " AND Hotel_Country_Name = ?";
    $searchResult[] = $country;
}

if ($city !== '')
{
    $sql .= " AND Hotel_City_Name = ?";
    $searchResult[] = $city;
}

if ($star_rating !== '')
{
    $sql .= " AND Star_Rating = ?";
    $searchResult[] = $star_rating;
}

$sql .= " ORDER BY Hotel_Name ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($searchResult);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;

// Render Template
echo $twig->render('IndexSearchResultsPage.html.twig',
[
    'hotels'      => $hotels,
    'country'     => $country,
    'city'        => $city,
    'star_rating' => $star_rating,
    'check_in'    => $check_in,
    'check_out'   => $check_out,
    'countries'   => $countries,
    'cities'      => $cities,
    'user'        => $user,
    'isAdmin'     => $isAdmin
]);
