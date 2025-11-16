<?php
include('databaseconnect.php');

// Get the country 
$country = $_POST['country'] ?? '';
if (!$country) 
{
    echo json_encode([]); // Return empty array if user do not select a country
    exit;
}

try 
{
    // Obtain cities for the choosen country
    $stmt = $conn->prepare("
        SELECT DISTINCT Hotel_City_Name 
        FROM hotel_details 
        WHERE Is_Active = 1 AND Hotel_Country_Name = ?
        ORDER BY Hotel_City_Name
    ");
    $stmt->execute([$country]);
    $cities = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Send cities as JSON
    echo json_encode($cities);
} 
catch (PDOException $e) 
{
    // return empty array incase of an error
    echo json_encode([]);
}

// Close database connection
$conn = null;
?>
