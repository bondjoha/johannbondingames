<?php

require 'configure.php';

//twig setup
$loader = new \Twig\Loader\FilesystemLoader('templates'); // Twig looks in templates folder
$twig = new \Twig\Environment($loader);

// Get search term from GET request
$search = trim($_GET['search'] ?? '');

// Fetch staff users from database with optional search
if ($search !== '') 
{
    // Use LIKE for partial matches in first_name, last_name, or email
    $stmt = $conn->prepare("
        SELECT first_name, last_name, user_email, approved 
        FROM logincredentials 
        WHERE user_role = 'staff' 
          AND (first_name LIKE :search OR last_name LIKE :search OR user_email LIKE :search)
    ");
    $stmt->execute([':search' => "%$search%"]);
} 
else 
{
    // Fetch all staff users
    $stmt = $conn->prepare("
        SELECT first_name, last_name, user_email, approved 
        FROM logincredentials 
        WHERE user_role = 'staff'
    ");
    $stmt->execute();
}

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update approval status 
if (isset($_POST['approve'])) 
{
    $userEmail = $_POST['approve'];

    // Get current approval status
    $stmt = $conn->prepare("SELECT approved FROM logincredentials WHERE user_role = 'staff' AND user_email = :email");
    $stmt->execute([':email' => $userEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) 
    {
        $currentApproval = (bool)$user['approved']; // true or false
        $newApproval = !$currentApproval;           // toggle

        // Update approval
        $update = $conn->prepare("UPDATE logincredentials SET approved = :approved WHERE user_role = 'staff' AND user_email = :email");
        $update->execute
        ([
            ':approved' => $newApproval,
            ':email'    => $userEmail
        ]);
        $message = "User $userEmail updated successfully.";
    } 
    else 
    {
        $error = "User not found.";
    }

    // Redirect immediately to avoid resubmission
    header("Location: Add_Read_Update_Delete_Approvals.php?search=" . urlencode($search));
    exit;
}

// Render Twig Template
echo $twig->render('TableApprovalsPage.html.twig', 
[
    'users'  => $users,
    'search' => $search,
    'message' => $message ?? null,
    'error'   => $error ?? null
]);
?>