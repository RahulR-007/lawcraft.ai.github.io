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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LawCraft</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(to bottom, #000000, #222222), center center/cover;
      background-attachment: fixed;
    }

    .container {
      max-width: 100%;
      margin: 50px auto;
      text-align: center;
    }

    h4 {
      font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif, sans-serif;
      color: #ffffff;
      font-size: 60px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    b {
      font-size: 60px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
      background: linear-gradient(to right, #00a6ff, #970fff, #00ff91,#e600ff,#970fff, #00a6ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-size: 400% 100%;
      animation: gradientText 10s linear infinite;
    }

    @keyframes gradientText {
      0% {
        background-position: 0% 50%;
      }
      100% {
        background-position: 100% 50%;
      }
    }

    h2 {
      color: #fff;
      font-size: 40px;
    }

    p {
      color: #fff;
      line-height: 1.6;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 50px;
      background-color: rgba(30, 25, 25, 0.31);
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .text {
      flex: 0 0 60%;
      text-align: left;
      padding: 0 20px;
    }

    .image {
      flex: 0 0 40%;
      position: relative;
      animation: floatImage 4s ease-in-out infinite alternate;
    }

    img {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
    }

    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      margin-bottom: 10px;
    }

    @keyframes floatImage {
      0% {
        transform: translateY(0);
      }
      100% {
        transform: translateY(-20px);
      }
    }

    @media screen and (max-width: 768px) {
      .container {
        width: 90%;
      }
      .content {
        flex-direction: column;
      }
      .text {
        flex: 1;
        margin-bottom: 20px;
        padding: 0 10px;
      }
      .image {
        flex: 1;
        margin-left: 0; /* Reset margin for mobile view */
      }
    }

    .horizontal-navbar {
      display: flex;
      justify-content: space-around;
      align-items: bottom;
      height: 50px;
      width: 180px;
      position: fixed;
      top: 20px;
      left: 10px;
      background-color: rgba(0, 0, 0, 0.5);
      overflow-x: auto;
      overflow-y: hidden;
      border-radius: 10px;
      backdrop-filter: blur(10px);
      box-shadow: 0 0 10px #970fff;
      z-index: 10;
    }

    .horizontal-navbar ul {
      display: flex;
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    .horizontal-navbar li {
      padding: 10px;
    }

    .horizontal-navbar a {
      text-decoration: none;
      color: #ffffff;
      transition: background-color 0.3s ease;
    }

    .horizontal-navbar a:hover {
      color: #970fff;
    }

    .horizontal-navbar li.disabled a {
      pointer-events: none;
      color: #666666; /* Change color to indicate it's disabled */
    }

    /* End Card Section Styles */
    .end-card {
      background-color: #222222;
      padding: 50px;
      border-radius: 10px;
      margin-top: 50px;
      color: #fff;
    }

    .end-card .text {
      text-align: center;
    }

    .end-card img {
      max-width: 100px;
      margin-bottom: 20px;
    }

    .end-card p {
      margin-bottom: 20px;
    }

    /* Button Styles */
    .subscribe-btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #970fff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .subscribe-btn:hover {
      background-color: #970fff9d;
      color: #fff;
    }

.popup {
   display: none;
   position: fixed;
   z-index: 1;
   left: 10px; /* Adjust this value as needed */
   top: 80px; /* Adjust this value as needed */
   width: 200px; /* Adjust the width as needed */
   background-color: rgba(80 32 208 / 34%); /* Adjust the background color and opacity as needed */
   border: 1px solid #970fff; /* Adjust the border as needed */
   padding: 10px;
   border-radius: 5px;
}

.popup-content {
   color: #ffffff; /* Text color */
}

.close {
   color: #ffffff; /* Close button color */
   float: right;
   font-size: 20px;
   font-weight: bold;
   cursor: pointer;
}

.close:hover {
   color: #970fff; /* Close button color on hover */
}

.popup-content h2 {
   margin-top: 0;
}

.popup-content hr {
   border: none;
   border-top: 1px solid #ffffff; /* Horizontal line color */
   margin: 10px 0;
}

.popup-content button {
   padding: 5px 10px; /* Button padding */
   font-size: 16px; /* Button font size */
   background-color: #ffffff; /* Button background color */
   color: #000000; /* Button text color */
   border: none;
   border-radius: 3px;
   cursor: pointer;
}

.popup-content button:hover {
   background-color: #970fff; /* Button background color on hover */
   color: #ffffff; /* Button text color on hover */
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
        <li><a><i class="material-icons">help</i></a></li>
        <li><a href="../doc/redirect.php"><i class="material-icons">file_copy</i></a></li>        
    </ul>
</nav>
<div class="container">
  <h4>LawCraft</h4>
</div>
<div class="content">
  <div class="text">
    <b style="color: #970fff;">We are ready to assist you anytime and anywhere!</b>
    <h2>Here are the Guidelance to Chat with our AI and Create a Legal Documentation through AI</h2>
    <ul style="color: white;font-size: 20px;">
      <li>> Click Try Alice in the Home Page</li>      
      <li>> The page will be redirected to the Chatbot as shown in the IMAGE</li>
      <li>> Now ,you can chat with our chatbot,Our AI is learned about laws from 2010 to upto Date </li>
    </ul>
  </div>
  <div class="image">
    <img src="img/helpimg1.png" alt="Your Image">
  </div>
</div>
<div class="container">
  <div class="content">
    <div class="image">
      <img src="img/helpimg2.png" alt="Image on Left">
    </div>
    <div class="text">
      <h2>Next Step</h2>
      <ul style="color: white;font-size: 20px;">
      <li>> Click "Try AI Document Generation"</li>      
      <li>------------------OR-----------------</li>
      <li>> You click on the File Icon on the navigation bar</li>
    </ul>
    </div>
  </div>
</div>
<div class="container">

</div>
<div class="content">
  <div class="text">
    <h2>Last Step</h2>
    <p>Now Enter you Prompt for the AI to Generate the Document.</p>
      <p>Its Better to add ["Create"- DOCUMENT NAME - "AGREEMENT" - OTHER NEEDED DETAILS] so that it generates the Document Accurately!</p>
    <p>Once Finishing entering your details,click GENERATE Button to create a Legal Document of you need and you can retive it in the Downloads</p>
</div>
  <div class="image">
    <img src="img/helpimg3.png" alt="Your Image">
  </div>
</div>

<!-- End Card Section -->
<div class="container end-card">
  <div class="content">
    <div class="text">
      <h2>HelpLine</h2>
      <p>Connect with us on social media:</p>
      <ul>
        <i class="material-icons">email</i><li><button>rahul.ramsraji@gmail.com</button></li>
        <i class="material-icons">phone</i><li><button>Contact: +1 123 456 7890</button></li>
      </ul>
    </div>
    <div>
      <a href="https://lawcraft007.blogspot.com/2024/01/blog01.html" class="subscribe-btn">Subscribe to our blog post on Google</a>
      </div>
    </div>
  </div>
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
</body>
</html>
