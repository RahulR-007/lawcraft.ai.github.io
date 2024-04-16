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

// Query to fetch the plan name for the logged-in user using their full name
$fullname = $_SESSION['fullname']; // Assuming you have a 'fullname' session variable
$sql = "SELECT plan_name, tokens FROM signdata WHERE fullname = '$fullname'";
$result = $conn->query($sql);

// Check if the query executed successfully
if ($result->num_rows > 0) {
    // Fetch the plan name and tokens
    $row = $result->fetch_assoc();
    $plan_name = $row['plan_name'];
    $tokens = $row['tokens'];
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LawCraft AI: Alice</title>
    <!-- Updated Bootstrap CDN links -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    
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
        <li><a onclick="openPopup()"><i class="material-icons">account_box</i></a></li>
        <li><a href="../doc/help.php"><i class="material-icons">help</i></a></li>
        <li><a href="../doc/redirect.php"><i class="material-icons">file_copy</i></a></li>        
    </ul>
</nav>

<div class="chat-container">
    <iframe
    src="https://widget.writesonic.com/CDN/index.html?service-base-url=https://api.botsonic.ai&token=7d72217e-db45-4ac2-a9e3-dfeccbb0541e&base-origin=https://bot.writesonic.com&instance-name=Botsonic&standalone=true&page-url=https://bot.writesonic.com/c2d076e5-a32f-4dce-9045-351a4bfe6bf7?t=connect&workspace_id=c495a9de-5aaa-4995-a6c3-96157a01c173" 
    title="Chatbot"
    style="width: 100%; height: 300px; min-width: 200px; min-height: 100px;"
    class="glass-frame"
    frameborder="0"
></iframe>
    </div>
    <div class="box-container">
    <div class="box" onclick="redirectToRedirectPage()">TRY AI DOCUMENT GENERATION</div>
   </div>


   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
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
    async function sendMessage() {
        var userInput = document.getElementById('user-input').value;
        document.getElementById('user-input').value = ''; // Clear input field

        try {
            const response = await fetch('http://localhost:5000/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ "message": userInput })
            });

            const data = await response.json();
            displayMessage(data.response);
        } catch (error) {
            console.error('Error:', error);
        }
    }
    
    function redirectToWebsite(url) {
            window.location.href = url;
        }


    function displayMessage(message) {
        var chatMessages = document.getElementById('chat-messages');
        var messageElement = document.createElement('div');
        messageElement.textContent = message;
        chatMessages.appendChild(messageElement);
    }
    function redirectToRedirectPage() {
            window.location.href = "redirect.php";
        }

</script>

</body>
</html>
