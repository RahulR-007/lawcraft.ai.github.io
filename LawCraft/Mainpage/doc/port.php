<?php
// Start session
session_start();

// Check if the fullname session variable exists
if (!isset($_SESSION['fullname'])) {
    header("Location:../sign/signin.php"); // Redirect to login page if the user session doesn't exist
    exit();
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

// Query to fetch the plan name and tokens for the logged-in user using their full name
$fullname = $_SESSION['fullname']; // Assuming you have a 'fullname' session variable
$sql = "SELECT plan_name, tokens FROM signdata WHERE fullname = '$fullname'";
$result = $conn->query($sql);

// Check if the query executed successfully
if ($result->num_rows > 0) {
    // Fetch the plan name and tokens
    $row = $result->fetch_assoc();
    $plan_name = $row['plan_name'];
    $tokens = $row['tokens'];

    // Check if tokens are greater than 0
    if ($tokens > 0) {
        // Tokens are greater than 0, continue rendering the page
    } else {
        // Tokens are 0, redirect to the price page
        header("Location: http://localhost/dashboard/LawCraft/Mainpage/price/index.php");
        exit();
    }
} else {
    // Handle the case where the plan name and tokens are not found
    $plan_name = "No Plan Assigned"; // Default value if plan name is not found
    $tokens = "No Tokens Assigned"; // Default value if tokens are not found
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IFrame Example</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
    <style>
body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url("img/bg.png") no-repeat; /* Set background image without repeating */
            background-size: cover; /* Ensure the background image covers the entire viewport */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .glass-container {
            position: relative;
            width: 80%;
            max-width: 500px;
            text-align: center;
            transition: box-shadow 0.3s ease;
            z-index: 2; /* Ensure the glass container is above the image */
        }

        .glass-background {
            background: #39393906;
            border: 1px solid #ffffff33;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px 0 rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .glass-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1; /* Ensure the image is below the glass container */
        }

        h1 {
            color: #fff;
            margin-bottom: 20px; /* Add some space below the heading */
        }

        form {
            display: flex;
            flex-direction: column; /* Stack form elements vertically */
            align-items: center; /* Center align form elements */
        }

        label {
            color: #fff;
            margin-bottom: 3 px; /* Add space below each label */
        }

        input[type="text"], textarea {
            width: 95%; /* Full width */
            padding: 10px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
            background: white;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            color: black;
        }

        input[type="submit"] {
            width: 95%;
            padding: 10px;
            margin-top: 1px;
            border: none;
            border-radius: 5px;
            background: #970fff;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
            background-image: linear-gradient(45deg, #6f0fff, #d900ff,#970fff);
            background-size: 200% 200%;
        }

        input[type="submit"]:hover {
            animation: gradientTransition 0.5s ease infinite alternate;
        }

        @keyframes gradientTransition {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 50% 100%;
            }
            100% {
                background-position: 100% 50%;
            }
        }

        /* Add styles for suggestion boxes */
        .suggestion-boxes {
            position: absolute;
            top: 0;
            right: 50px; /* Push suggestion boxes outside the container */
            top: 30%;
            width: 20%; /* Set the width of suggestion boxes */
            z-index: 2;
            padding: 10px;
            transition: right 0.3s ease; /* Smooth transition */
        }

        .idea-box {
            margin-bottom: 10px; /* Add some space between idea boxes */
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px; /* Smooth border radius */
            background: transparent; /* Background color */
            box-shadow: 0 0px 10px #970fff; /* Box shadow */
            transition: background-color 0.3s ease; /* Smooth transition */
            color: white;
            top:10px
        }

        .idea-box:hover {
            background: #000000; /* Background color on hover */
            box-shadow: 0 0px 10px #ffffff; /* Box shadow */
            transition: background-color 0.3s ease; /* Smooth transition */
        }

        /* Loading screen styles */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgb(0, 0, 0);
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 3; /* Ensure loading screen is above other content */
        }

        .loading-message {
            color: #fff;
            font-size: 24px;
            margin-top: 20px; /* Add some space between the GIF and message */
        }

        .loading-gif {
            margin-bottom: 20px; /* Add some space between the message and GIF */
        }
    </style>
</head>
<body>
<div id="myPopup" class="popup">
    <!-- Popup content -->
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <h2>Welcome,</h2>
        <?php
            echo '<span style="font-size: 25px;">' . htmlspecialchars($_SESSION['fullname']) . '</span>';
        ?>
        <hr>
        <h2 style="font-size: 25px;">Plan:
        <?php
            echo '<span style="color: #39FF14";>' . htmlspecialchars($plan_name) . '</span>';
        ?>
        Tokens: 
        <?php
            echo '<span style="color: #39FFFF";>' . htmlspecialchars($tokens) . '</span>';
        ?></h2>
        <hr> <!-- Horizontal line -->
        <button onclick="logout()">Logout</button>  
    </div>
</div>

<nav class="horizontal-navbar">
    <ul>
        <li><a href="../../Coverpage/index.php"><i class="material-icons">home</i></a></li>
        <li><a href="#" onclick="openPopup()"><i class="material-icons">account_box</i></a></li>
        <li><a href="../doc/help.php"><i class="material-icons">help</i></a></li>
        <li><a style="color: #1c1c1c;"><i class="material-icons">file_copy</i></a></li>     
    </ul>
</nav>
<div class="loading-screen" id="loadingScreen">
        <div class="loading-message">Generating Document...</div>
        <img style="width: 200px;height: 80px;" src="img/lc_spin.gif" alt="Loading GIF">
    </div>

    <div class="glass-image">
        <img src="img/lc_nobg.png" alt="Background Image" style="height: 400px;width: 400px;">
    </div>

    <div class="glass-container">
        <div class="glass-background">
            <h1>Legal Documentation by LawCraft</h1>
            <form action="http://localhost:5000/" method="post" enctype="multipart/form-data">
                <label for="prompt">Use "Create" with your Document name and "agreement"</label><br>
                <textarea id="prompt" name="prompt" rows="4" cols="50" placeholder="Type your legal document here..."></textarea><br>
                <label for="keyword">Document Name:</label><br>
                <input type="text" id="keyword" name="keyword" placeholder="Enter document name"><br><br>
                <input type="submit" value="Generate" class="btn btn-primary">
            </form>
        </div>
    </div>

    <!-- Suggestion boxes outside the container -->
    <div class="suggestion-boxes">
        <div style="color: #970fff;">Suggestions:</div>
        <br>
        <div class="idea-box" onclick="pasteText('Create Loan Agreement for 3 Parties')">Create Loan Agreement for 3 Parties</div>
        <div class="idea-box" onclick="pasteText('Create Car Rental Agreement for 8 months')">Create Car Rental Agreement for 8 months</div>
        <div class="idea-box" onclick="pasteText('Create NDA for Company LawCraft and TPT College')">Create NDA for Company LawCraft and TPT College</div>
        <!-- Add more idea boxes as needed -->
    </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script>
    // Function to open the popup
    function openPopup() {
        document.getElementById('myPopup').style.display = 'block';
    }

    // Function to close the popup
    function closePopup() {
        document.getElementById('myPopup').style.display = 'none';
    }
    function logout() {
        window.location.href = "logout.php?action=logout";
            alert('Logout successful!');
        }
</script>
<script>
        function pasteText(text) {
            document.getElementById('prompt').value = text; // Set the value instead of appending
        }

        function showLoadingScreen() {
            document.getElementById('loadingScreen').style.display = 'flex';
        }

        function hideLoadingScreen() {
            document.getElementById('loadingScreen').style.display = 'none';
        }

        document.querySelector('form').addEventListener('submit', function() {
        showLoadingScreen();
        // Simulate file download process
        setTimeout(function() {
            // AJAX request to update tokens
            $.ajax({
                url: 'update_tokens.php',
                type: 'POST',
                success: function(response) {
                    console.log(response); // Log the response from PHP script
                    hideLoadingScreen(); // Hide loading screen after updating tokens
                },
                error: function(xhr, status, error) {
                    console.error('Error updating tokens:', error);
                    hideLoadingScreen(); // Hide loading screen even if there's an error
                }
            });
        }, 10000); 
    });
    </script>

    <!-- Bootstrap JavaScript (optional, if needed for certain Bootstrap components) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
