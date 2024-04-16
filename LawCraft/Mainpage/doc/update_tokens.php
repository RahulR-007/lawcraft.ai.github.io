<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['fullname'])) {
    exit('User not logged in');
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lawcraft";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current user's fullname and update the tokens column
$fullname = $_SESSION['fullname'];
$sql = "UPDATE signdata SET tokens = tokens - 1 WHERE fullname = '$fullname'";
if ($conn->query($sql) === TRUE) {
    echo "Tokens updated successfully";
} else {
    echo "Error updating tokens: " . $conn->error;
}

$conn->close();
?>
