<?php
// ratingsAjax.php
header('Content-Type: application/json');

include 'databaseconnect.php';

$country = $_GET['country'] ?? '';
$city    = $_GET['city'] ?? '';

// Base query
$sql = "SELECT DISTINCT Star_Rating FROM hotel_details WHERE Is_Active = 1";
$params = [];

// Add filters if provided
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

$sql .= " ORDER BY Star_Rating";

$stmt = $conn->prepare($sql);
$stmt->execute($params);

$ratings = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($ratings);

$conn = null;
