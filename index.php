<?php
require_once 'vendor/autoload.php'; // Twig
require_once 'databaseconnect.php'; // $conn as PDO

//Twig setup 
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); 
$twig = new \Twig\Environment($loader);

//POST variables data
$check_in  = $_POST['check_in'] ?? date('Y-m-d');
$check_out = $_POST['check_out'] ?? date('Y-m-d', strtotime('+7 days'));
$country   = $_POST['country'] ?? '';
$city      = $_POST['city'] ?? '';

//Dates Validation
$check_in  = DateTime::createFromFormat('Y-m-d', $check_in) ? $check_in : date('Y-m-d');
$check_out = DateTime::createFromFormat('Y-m-d', $check_out) ? $check_out : date('Y-m-d', strtotime('+7 days'));

//countries query from database table 
$countries = $conn->query
("
    SELECT DISTINCT Hotel_Country_Name 
    FROM hotel_details 
    WHERE Is_Active = 1 
    ORDER BY Hotel_Country_Name
")->fetchAll(PDO::FETCH_ASSOC);

//cities list based on country 
if ($country) 
{
    $stmt = $conn->prepare
    ("
        SELECT DISTINCT Hotel_City_Name 
        FROM hotel_details 
        WHERE Is_Active = 1 AND Hotel_Country_Name=? 
        ORDER BY Hotel_City_Name
    ");
    $stmt->execute([$country]);
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} 
else 
{
    $cities = $conn->query
    ("
        SELECT DISTINCT Hotel_City_Name 
        FROM hotel_details 
        WHERE Is_Active = 1 
        ORDER BY Hotel_City_Name
    ")
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

$conn = null;

//Render variable to template indexPage
echo $twig->render('indexPage.html.twig', 
[
    'check_in'  => $check_in,
    'check_out' => $check_out,
    'country'   => $country,
    'city'      => $city,
    'countries' => $countries,
    'cities'    => $cities,
    'hotels'    => $hotels
]);
