<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirection Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS file -->
</head>
<body>
<div class="container">
    <div class="text-center">
        <img src="img/lc_spin.gif" alt="Loading" width="300px" height="100px"> <!-- GIF image -->
    </div>
    <div class="text-center">
        <div class="message">
            P R O C E S S I N G
        </div>
    </div>
</div>
<!-- Bootstrap JavaScript (optional, only if you need it) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "lawcraft";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the user is logged in and has tokens
    session_start();

    if (!isset($_SESSION['fullname'])) {
        // Redirect to login page if the user is not logged in
        header("Location: ../sign/signin.php");
        exit();
    }

    $fullname = $_SESSION['fullname'];

    // Query to fetch the tokens for the logged-in user
    $sql = "SELECT tokens FROM signdata WHERE fullname = '$fullname'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the tokens
        $row = $result->fetch_assoc();
        $tokens = $row['tokens'];

        // Check if tokens are greater than 1
        if ($tokens > 0) {          
            echo "<script>window.setTimeout(function() { window.location.href = 'http://localhost/dashboard/LawCraft/Mainpage/doc/port.php'; }, 3000);</script>";
        } else {
            echo "<script>window.setTimeout(function() { window.location.href = 'http://localhost/dashboard/LawCraft/Mainpage/price/index.php'; }, 3000);</script>";
        }
    } else {
        // Redirect to login page if tokens are not found
        echo "<script>window.setTimeout(function() { window.location.href = '../sign/signin.php'; }, 3000);</script>";
    }

    $conn->close();
?>
</body>
</html>
