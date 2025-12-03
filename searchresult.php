<?php
require 'configure.php'; 

// Twig Setup
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig   = new \Twig\Environment($loader, ['autoescape' => 'html']);

// Identify logged in user
$user = $_SESSION['user'] ?? null;
$isAdmin = ($user && isset($user['role']) && strtolower($user['role']) === 'admin');

// Get search parameters from POST/GET with defaults
$country     = $_POST['country'] ?? $_GET['country'] ?? '';
$city        = $_POST['city'] ?? $_GET['city'] ?? '';
$star_rating = $_POST['star_rating'] ?? $_GET['star_rating'] ?? '';
$check_in    = $_POST['check_in'] ?? $_GET['check_in'] ?? date('Y-m-d');
$check_out   = $_POST['check_out'] ?? $_GET['check_out'] ?? date('Y-m-d', strtotime('+1 day'));

// Store search parameters in session
$_SESSION['selected_country'] = $country;
$_SESSION['selected_city']    = $city;
$_SESSION['selected_star']    = $star_rating;
$_SESSION['check_in']         = $check_in;
$_SESSION['check_out']        = $check_out;

// Validate check-in and check-out dates
$checkInDate  = DateTime::createFromFormat('Y-m-d', $check_in);
$checkOutDate = DateTime::createFromFormat('Y-m-d', $check_out);

if (!$checkInDate || !$checkOutDate || $checkInDate > $checkOutDate) {
    $checkInDate  = new DateTime();
    $checkOutDate = (new DateTime())->modify('+1 day');
    $check_in  = $checkInDate->format('Y-m-d');
    $check_out = $checkOutDate->format('Y-m-d');
}

// Validate star rating (integer 1–5)
if ($star_rating !== '') {
    $star_rating = filter_var($star_rating, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 5]
    ]) ?: '';
}

// Fetch countries
$countries = $conn->query("
    SELECT DISTINCT Hotel_Country_Name
    FROM hotel_details
    WHERE Is_Active = 1
    ORDER BY Hotel_Country_Name
")->fetchAll(PDO::FETCH_ASSOC);

// Fetch cities if country selected
if (!empty($country)) {
    $stmt = $conn->prepare("
        SELECT DISTINCT Hotel_City_Name
        FROM hotel_details
        WHERE Is_Active = 1 AND Hotel_Country_Name = ?
        ORDER BY Hotel_City_Name
    ");
    $stmt->execute([$country]);
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $cities = [];
}

// Fetch available star ratings
$ratingSql = "SELECT DISTINCT Star_Rating FROM hotel_details WHERE Is_Active = 1";
$ratingParams = [];
if ($country !== '') {
    $ratingSql .= " AND Hotel_Country_Name = ?";
    $ratingParams[] = $country;
}
if ($city !== '') {
    $ratingSql .= " AND Hotel_City_Name = ?";
    $ratingParams[] = $city;
}
$ratingSql .= " ORDER BY Star_Rating";

$stmt = $conn->prepare($ratingSql);
$stmt->execute($ratingParams);
$available_ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch hotels matching search criteria
$sql = "SELECT * FROM hotel_details WHERE Is_Active = 1";
$searchParams = [];
if ($country !== '') {
    $sql .= " AND Hotel_Country_Name = ?";
    $searchParams[] = $country;
}
if ($city !== '') {
    $sql .= " AND Hotel_City_Name = ?";
    $searchParams[] = $city;
}
if ($star_rating !== '') {
    $sql .= " AND Star_Rating = ?";
    $searchParams[] = $star_rating;
}
$sql .= " ORDER BY Hotel_Name ASC";

$stmt = $conn->prepare($sql);
$stmt->execute($searchParams);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close DB connection
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