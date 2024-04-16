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

// Signup form handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Check if passwords match
    if ($password != $confirm_password) {
        echo "Passwords do not match";
        exit();
    }

    // Checking if email already exists
    $stmt = $conn->prepare("SELECT * FROM signdata WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email ID already taken";
    } else {
        // Insert into database if email doesn't exist
// Assign user to free plan and assign 2 tokens
$plan_name = "Free";
$tokens = 2; // Value to assign to the tokens column

$stmt = $conn->prepare("INSERT INTO signdata (fullname, email, password, plan_name, tokens) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $fullname, $email, $password, $plan_name, $tokens);

if ($stmt->execute()) {
    // Redirect to signin page after successful signup
    header("Location: signin.php");
    exit(); // Ensure no further code is executed after redirection
} else {
    echo "Error: " . $stmt->error;
}

    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Signup</title>
      <link rel="stylesheet" href="styles.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        .google-icon {
            text-align: center;
            margin-bottom: 20px;
        }
        .google-icon a {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 20px;
            background-color: #f1f1f1;
            transition: background-color 0.3s ease;
        }
        .google-icon a:hover {
            background-color: #ddd;
        }
    </style>
   </head>
   <body>
      <div class="wrapper">
         <div class="title">
            Signup Form
         </div>
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
         <div class="field">                
                <label>Sign In with google</label>
            </div>
            <div class="google-icon">
                <a href="#">
                    <i class="fab fa-google"></i>  </a>
            </div>
            <hr>
            <div class="field">
                <input type="text" name="fullname" required>
                <label>Full Name</label>
            </div>
            <div class="field">
                <input type="text" name="email" required>
                <label>Email Address</label>
            </div>
            <div class="field">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <div class="field">
                <input type="password" name="confirm_password" required>
                <label>Confirm Password</label>
            </div>
            <div class="content">
                <div class="checkbox">
                    <input type="checkbox" id="agree">
                    <label for="agree">I agree to the terms and conditions</label>
                </div>
            </div>
            <div class="field">
                <input type="submit" value="Signup">
            </div>
            <div class="signup-link">
                Already a member? <a href="signin.php">Login</a>
            </div>
        </form>
      </div>
   </body>
</html>
