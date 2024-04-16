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
    <link rel="stylesheet" href="style.css">
    <title>LawCraft-Pricing</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
        <li><a href="../doc/redirect.php"><i class="material-icons">file_copy</i></a></li>
    </ul>
</nav>
<main class="main flow">

    <h1 class="main__heading" data-text="LawCraft">LawCraft</h1>

    <h2 class="main_h2">P R I C I N G</h2>
    <h3 class="main_h3">We Provide You the Best Prices, Choose To your Need!</h3>

    <div class="main__cards cards">
        <div class="cards__inner">
            <!-- 1st -->
            <div class="cards__card card">
                <h2 class="card__heading">Free</h2>
                <p class="card__price">₹0.00</p>
                <ul role="list" class="card__bullets flow">
                    <li>Unlimited Queries with ALICE (ChatBot)</li>
                    <li>Two Document Generation</li>
                </ul>
                <a href="#" class="card__cta cta" id="free-link">
                    <?php echo ($plan_name == 'Free') ? 'Current Plan' : '- - -'; ?>
                </a>
            </div>

            <!-- 2nd -->
            <div class="cards__card card">
                <h2 class="card__heading">Advanced</h2>
                <p class="card__price">₹29.00</p>
                <ul role="list" class="card__bullets flow">
                    <li>Unlimited Queries with ALICE (ChatBot)</li>
                    <li>Can Generate Document Upto 10</li>
                    <li>Can request Addition 5 Tokens (*<span style="color: red";>Extra</span> Charges Included)</li>
                    <li>Support 24/5</li>
                </ul>
                <a href="#" class="card__cta cta buy_now" data-amount="29" data-name="Advanced" id="advanced-link">
                    <?php echo ($plan_name == 'Advanced') ? 'Current Plan' : 'Get Started'; ?>
                </a>
            </div>

            <!-- 3rd -->
            <div class="cards__card card">
                <h2 class="card__heading">Premium</h2>
                <p class="card__price">₹49.00</p>
                <ul role="list" class="card__bullets flow">
                    <li>Unlimited Queries with ALICE (ChatBot)</li>
                    <li>Can Generate Document Upto 20</li>
                    <li>Can request Addition 7 Tokens (*<span style="color: red";>No Extra</span> Charges Fined)</li>
                    <li>Support 24/7</li>
                </ul>
                <a href="#" class="card__cta cta buy_now" data-amount="49" data-name="Premium" id="premium-link">
                    <?php echo ($plan_name == 'Premium') ? 'Current Plan' : 'Get Started'; ?>
                </a>
            </div>

        </div>

        <div class="overlay cards__inner"></div>
    </div>
</main>

<script src="main.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
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
        window.location.href = "../doc/logout.php?action=logout";
        alert('Logout successful!');
    }

    // Function to disable the link if the user is already on the respective plan
    function disableLink(linkId, isCurrentPlan) {
        document.getElementById(linkId).addEventListener('click', function(event) {
            if (isCurrentPlan) {
                event.preventDefault(); // Prevent default action
                alert('You are already on this plan.'); // Optionally, display a message
                window.location.href = "index.php"
            }
        });
    }

    // Call the function for each link
    disableLink('free-link', <?php echo ($plan_name == 'Free') ? 'true' : 'false'; ?>);
    disableLink('advanced-link', <?php echo ($plan_name == 'Advanced') ? 'true' : 'false'; ?>);
    disableLink('premium-link', <?php echo ($plan_name == 'Premium') ? 'true' : 'false'; ?>);

    // Razorpay Integration
    $('.buy_now').on('click', function(e) {
        var totalAmount = $(this).attr("data-amount");
        var plan_name = $(this).attr("data-name"); // Get the plan_name attribute
        var options = {
            "key": "rzp_test_Deqs4AJQSSVZn0",
            "amount": (totalAmount * 100), // 2000 paise = INR 20
            "name": "LawCraft",
            "description": "Payment",
            "image": "img/lc_nobg.png",
            "handler": function(response) {
                $.ajax({
                    url: 'http://localhost/dashboard/LawCraft/Mainpage/price/payment-proccess.php',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        razorpay_payment_id: response.razorpay_payment_id,
                        totalAmount: totalAmount,
                        plan_name: plan_name, // Pass the plan_name instead of product_id
                    },
                    success: function(msg) {
                        window.location.href = 'http://localhost/dashboard/LawCraft/Mainpage/price/payment-success.php';
                    }
                });

            },
            "theme": {
                "color": "#970fff"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
        e.preventDefault();
    });
</script>
</body>
</html>
