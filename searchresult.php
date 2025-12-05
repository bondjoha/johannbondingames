<?php
require 'configure.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}

// Twig setup
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig   = new \Twig\Environment($loader, ['autoescape' => 'html']);

// Identify logged-in user
$user = $_SESSION['user'] ?? null;
$isAdmin = ($user && isset($user['role']) && strtolower($user['role']) === 'admin');

// Retrieve search parameters. First try with  POST then with GET then with SESSION -> default
$country     = trim($_POST['country'] ?? $_GET['country'] ?? $_SESSION['selected_country'] ?? '');
$city        = trim($_POST['city'] ?? $_GET['city'] ?? $_SESSION['selected_city'] ?? '');
$star_rating = trim($_POST['star_rating'] ?? $_GET['star_rating'] ?? $_SESSION['selected_star'] ?? '');
$check_in    = trim($_POST['check_in'] ?? $_GET['check_in'] ?? $_SESSION['check_in'] ?? date('Y-m-d'));
$check_out   = trim($_POST['check_out'] ?? $_GET['check_out'] ?? $_SESSION['check_out'] ?? date('Y-m-d', strtotime('+1 day')));

// Prevent form resubmission
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $query = http_build_query
    ([
        'country'     => $country,
        'city'        => $city,
        'star_rating' => $star_rating,
        'check_in'    => $check_in,
        'check_out'   => $check_out
    ]);
    header("Location: " . $_SERVER['PHP_SELF'] . "?$query");
    exit;
}

// Store search parameters in session
$_SESSION['selected_country'] = $country;
$_SESSION['selected_city']    = $city;
$_SESSION['selected_star']    = $star_rating;
$_SESSION['check_in']         = $check_in;
$_SESSION['check_out']        = $check_out;

// Validate dates
$checkInDate  = DateTime::createFromFormat('Y-m-d', $check_in);
$checkOutDate = DateTime::createFromFormat('Y-m-d', $check_out);

if (!$checkInDate || !$checkOutDate || $checkInDate > $checkOutDate) 
{
    $checkInDate  = new DateTime();
    $checkOutDate = (new DateTime())->modify('+1 day');
    $check_in  = $checkInDate->format('Y-m-d');
    $check_out = $checkOutDate->format('Y-m-d');
}

// Validate star rating (only integers 1-5)
if ($star_rating !== '') 
{
    $star_rating = filter_var($star_rating, FILTER_VALIDATE_INT, 
    [
        'options' => ['min_range' => 1, 'max_range' => 5]
    ]) ?: '';
}

// Fetch countries for dropdown
try 
{
    $countries = $conn->query
    ("
        SELECT DISTINCT Hotel_Country_Name 
        FROM hotel_details 
        WHERE Is_Active = 1 
        ORDER BY Hotel_Country_Name
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Validate selected country exists
    $countryNames = array_column($countries, 'Hotel_Country_Name');
    if ($country !== '' && !in_array($country, $countryNames, true)) 
    {
        $country = '';
    }

} 
catch (Exception $e) 
{
    $countries = [];
    $country = '';
}

// Fetch cities based on selected country
$cities = [];
if ($country !== '')
{
    $stmt = $conn->prepare
    ("
        SELECT DISTINCT Hotel_City_Name 
        FROM hotel_details 
        WHERE Is_Active = 1 AND Hotel_Country_Name = :country
        ORDER BY Hotel_City_Name
    ");
    $stmt->execute(['country' => $country]);
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Validate selected city exists
    $cityNames = array_column($cities, 'Hotel_City_Name');
    if ($city !== '' && !in_array($city, $cityNames, true))
    {
        $city = '';
    }
}
// Fetch hotels matching criteria
$whereHotels = ["Is_Active = 1"];
$paramsHotels = [];
if ($country !== '') 
{
    $whereHotels[] = "Hotel_Country_Name = :country";
    $paramsHotels['country'] = $country;
}
if ($city !== '') 
{
    $whereHotels[] = "Hotel_City_Name = :city";
    $paramsHotels['city'] = $city;
}
if ($star_rating !== '') 
{
    $whereHotels[] = "Star_Rating = :rating";
    $paramsHotels['rating'] = $star_rating;
}

$sqlHotels = "SELECT * FROM hotel_details WHERE " . implode(" AND ", $whereHotels) . " ORDER BY Hotel_Name ASC";
$stmt = $conn->prepare($sqlHotels);
$stmt->execute($paramsHotels);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close database connection
$conn = null;

// Render Twig template
echo $twig->render('IndexSearchResultsPage.html.twig', [
    'hotels'           => $hotels,
    'country'          => $country,
    'city'             => $city,
    'star_rating'      => $star_rating,
    'check_in'         => $check_in,
    'check_out'        => $check_out,
    'countries'        => $countries,
    'cities'           => $cities,
    'user'             => $user,
    'isAdmin'          => $isAdmin,
    'available_ratings'=> $available_ratings
]);
?>
