<?php
require 'configure.php';

// Check login
if (!isset($_SESSION['user'])) 
{
    header('Location: Login.php');
    exit;
}

// Check admin role
if (!isset($_SESSION['user']['role']) || strtolower($_SESSION['user']['role']) !== 'admin') 
{
    header('Location: Login.php');
    exit;
}

$message = null;
$error   = null;

// setup Twig 
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig   = new Environment($loader, 
[
    'autoescape' => 'html' // Ensure all output is escaped by default
]);

// Role options
$roles = ['admin', 'staff', 'customer'];

// Password strength regex
$password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

// Add user
if (isset($_POST['submitUserRecord']))
{
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['user_email']);
    $password = trim($_POST['user_password']);
    $role = trim($_POST['user_role']);
    $phone = trim($_POST['phone_number']);
    $approved = isset($_POST['approved']) ? 1 : 0;
    
    // validating role input, password, email uniqness and that fields are not left empty
    if (!in_array($role, $roles)) 
    {
        $error = "Invalid role selected.";
    } 
    elseif ($first_name === '' || $last_name === '' || $email === '' || $password === '') 
    {
        $error = "All required fields must be filled.";
    } 
    elseif (!preg_match($password_pattern, $password))
    {
        $error = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
    }
    else 
    {
        // Check email uniqueness
        $check = $conn->prepare("SELECT COUNT(*) FROM logincredentials WHERE user_email=:email");
        $check->execute(['email' => $email]);
        if ($check->fetchColumn() > 0) 
        {
            $error = "Email $email is already registered.";
        } 
        else 
        {
            try 
            {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare
                ("
                    INSERT INTO logincredentials 
                    (first_name, last_name, user_email, user_password, user_role, phone_number, approved)
                    VALUES (:first_name, :last_name, :email, :password, :role, :phone, :approved)
                ");
                $stmt->execute
                ([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'password' => $hash,
                    'role' => $role,
                    'phone' => $phone,
                    'approved' => $approved
                ]);
                $message = "User added successfully!";
            } 
            catch (PDOException $e) 
            {
                $error = "Error adding user: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

// Update user
if (isset($_POST['updateuser'])) 
{
    $user_id = intval($_POST['user_id']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['user_email']);
    $password = trim($_POST['user_password']);
    $role = trim($_POST['user_role']);
    $phone = trim($_POST['phone_number']);
    $approved = isset($_POST['approved']) ? 1 : 0;

    if (!in_array($role, $roles)) 
    {
        $error = "Invalid role selected.";
    } 
    elseif ($first_name === '' || $last_name === '' || $email === '') 
    {
        $error = "First name, last name, and email are required.";
    } 
    else 
    {
        // Check email uniqueness for other users
        $check = $conn->prepare("SELECT COUNT(*) FROM logincredentials WHERE user_email=:email AND user_id!=:id");
        $check->execute(['email' => $email, 'id' => $user_id]);
        if ($check->fetchColumn() > 0) 
        {
            $error = "Email $email is already used by another user.";
        } 
        else 
        {
            try 
            {
                if ($password !== '') 
                {
                    if (!preg_match($password_pattern, $password)) 
                    {
                        $error = "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character.";
                    }
                    else
                    {
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        $passSql = ", user_password=:password";
                    }
                } 
                else 
                {
                    $passSql = "";
                }

                if (!$error) // Only update if no password error
                {
                    $stmt = $conn->prepare
                    ("
                        UPDATE logincredentials 
                        SET first_name=:first_name, last_name=:last_name, user_email=:email, user_role=:role, phone_number=:phone, approved=:approved
                        $passSql
                        WHERE user_id=:user_id
                    ");

                    $params = 
                    [
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'role' => $role,
                        'phone' => $phone,
                        'approved' => $approved,
                        'user_id' => $user_id
                    ];
                    if ($password !== '') $params['password'] = $hash;

                    $stmt->execute($params);
                    $message = "User updated successfully!";
                }
            }
            catch (PDOException $e) 
            {
                $error = "Error updating user: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

// Delete user
if (isset($_POST['deleteuser'])) 
{
    $user_id = intval($_POST['user_id']);
    try 
    {
        // Delete user from database
        $stmt = $conn->prepare("DELETE FROM logincredentials WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);

        $message = "User deleted successfully!";
    } 
    catch (PDOException $e) 
    {
        $error = "Error deleting user: " . htmlspecialchars($e->getMessage());
    }
}

// Fetch all users (with optional search)
$search = trim($_GET['search'] ?? '');

if ($search !== '') 
{
    // Use LIKE for partial matches in first name, last name, or email
    $stmt = $conn->prepare("
        SELECT * FROM logincredentials
        WHERE first_name LIKE :search OR last_name LIKE :search OR user_email LIKE :search
        ORDER BY user_id
    ");
    $stmt->execute(['search' => "%$search%"]);
}
else 
{
    // Fetch all users
    $stmt = $conn->query("SELECT * FROM logincredentials ORDER BY user_id");
}

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$conn = null;

// Render Twig
echo $twig->render('TableUserCredentials.html.twig', 
[
    'users' => $users,
    'roles' => $roles,
    'message' => $message,
    'error' => $error
]);
?>
