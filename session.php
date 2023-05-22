<?php
session_start();

if (!isset($_SESSION['fname'])) {
    // Redirect to login page if not logged in
    header('Location: index.php');
    exit();
}

$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];

function fetchProfilePictureFromDatabase()
{
    // Establish a database connection
    // Your database connection code goes here

    // Replace the following lines with your code to fetch the profile picture URL from the database
    $database_host = 'localhost';
    $database_name = 'hardware';
    $database_user = 'root';
    $database_password = '';

    $conn = new PDO("mysql:host=$database_host;dbname=$database_name", $database_user, $database_password);

    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $profile_picture = $row['profile_picture'];

    // Close the database connection if needed
    // Your database connection closing code goes here

    return $profile_picture;
}

$profile_picture = fetchProfilePictureFromDatabase();
?>