<?php

include 'dbconn.php';
$message = "";

?>

<!DOCTYPE html>
<html lang = "en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Database CRUD Functions</title>
  <!--<link rel="stylesheet" href="style.css"> -->
</head>

<body>

<h1>Database Tables</h1>

<!-- Add game Form  -->
<form method="POST">
<label for="name">Name:</label>
<input type="text" id="game_name" name="game_name" placeholder="Name" required>

<label for="gametype">game type:</label>
<input type="text" id="game_type" name="game_type" placeholder="type" required>

<label for="game_year">Game year:</label>
<input type="date" id="game_year" name="game_year" required>

<label for="gameprice">game price:</label>
<input type="double" id="game_price" name="game_price" placeholder="type" required>

<button type="submit" name="submitgame" class="btn">Add Game</button>
</form>

<br><br>


<!--- find records ---->
<form method = "POST">
    <button type="submit" name="showtable" class="btn">Show table</button>
</form>
<br> <br>


<?Php
if (isset($_POST['showtable']))
    {
        $sql = "SELECT * FROM gamelist";
        $result = $conn -> query($sql);

        if ($result -> num_rows > 0) // to ensure that rows have found during database query
        {
        // creating table to print records in it from the database users table
        echo "<table border = '1'>";
        echo "<tr>
                <th>Game ID</th>
                <th>Game Name</th>
                <th>Game Type</th>
                <th>Game year</th>
                <th>Game Price</th>
              </tr>";
        while ($row = $result -> fetch_assoc()) // stores the data of the user table row in the variable and write them in the table text fields
        {
            echo "<tr>
                    <form method = 'POST'>
                    <td> {$row['game_id']}</td> 
                    <td> <input type='text' name = 'game_name' value ='{$row['game_name']}'></td>
                    <td> <input type='text' name = 'game_type' value ='{$row['game_type']}'></td>
                    <td> <input type='date' name = 'game_year' value ='{$row['game_year']}'></td>
                    <td> <input type='double' name = 'game_price' value ='{$row['game_price']}'></td>
                    </form>         
                    </tr>";
        }
    }
    }

    // ADD USER
if (isset($_POST['submitgame'])) 
{
    //store new  user text fields records in the variables
    $game_name = $_POST['game_name'];
    $game_type = $_POST['game_type'];
    $game_year = $_POST['game_year'];
    $game_price = $_POST['game_price'];


    
    $sql = "INSERT INTO gamelist (game_name, game_type, game_year,game_price) VALUES ('$game_name', '$game_type', '$game_year', '$game_price )";
    
    if ($conn->query($sql) === TRUE) 
    {
        echo "User added successfully!</p>";
    } 
    else 
    {
        echo "Error adding user: " . $conn->error . "</p>";
    }
} 

$conn = null; // close connection
?>
</body>
</html>