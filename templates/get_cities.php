<?php
include('databaseconnect.php');

header('Content-Type: application/json'); // JSON response

// Get and sanitize the country 
$country = trim($_POST['country'] ?? '');
$country = htmlspecialchars($country, ENT_QUOTES, 'UTF-8');

if (!$country) 
{
    echo json_encode([]); // Return empty array if no country selected
    exit;
}

try 
{
    // Obtain cities for the chosen country
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
    error_log($e->getMessage()); // optional logging
    echo json_encode([]);
}

// Close database connection
$conn = null;
?>
