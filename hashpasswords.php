<?php
include('databaseconnect.php');

// Fetch all users
$sql = "SELECT UserName, UserPassword FROM logincredentials";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) 
    {
    // Skip already hashed passwords 
    if (password_get_info($user['UserPassword'])['algo'] !== 0) 
    {
        continue;
    }

    // Hash the plain text password
    $hashedPassword = password_hash($user['UserPassword'], PASSWORD_DEFAULT);

    // Update the password in the database
    $update = $conn->prepare("UPDATE logincredentials SET UserPassword = :password WHERE UserName = :username");
    $update->execute
    ([
        'password' => $hashedPassword,
        'username' => $user['UserName']
    ]);

    echo "Password hashed for user: " . $user['UserName'] . "<br>";
}
?>