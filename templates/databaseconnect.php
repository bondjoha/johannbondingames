<?php
// databaseconnect.php
$servername = "localhost";
$db_username = "root";
$password = "";
$databasename = "hotel_database";

try 
{
    $conn = new PDO("mysql:host=$servername;dbname=$databasename;charset=utf8", $db_username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $errormessa) 
{
    die("Connection failed: " . $errormessa->getMessage());
}
?>
