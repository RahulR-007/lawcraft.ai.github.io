<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Change if your username is different
$password = ""; // Change if your password is different
$dbname = "lawcraft";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Signin form handling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Escape the values to prevent SQL injection (better to use prepared statements)
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $sql = "SELECT * FROM signdata WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch user data from the database
        $row = $result->fetch_assoc();
        $fullname = $row['fullname']; // Adjust this according to your database column name
        $tokens = $row['tokens']; // Assuming the column name is 'tokens'

        // Check if the user has advanced or premium plan and tokens are less than 3
        if (($row['plan_name'] == 'Advanced' || $row['plan_name'] == 'Premium') && $tokens < 3) {
            // Switch the user to the free plan by updating the database
            $sql_update = "UPDATE signdata SET plan_name='Free' WHERE email='$email'";
            if ($conn->query($sql_update) === TRUE) {
                // Update successful
                echo "Switched to Free plan due to insufficient tokens.";
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }

        // Set the fullname in the session
        $_SESSION['fullname'] = $fullname;

        // Redirect to the desired page after successful login
        header("Location: ../doc/index.php");
        exit();
    } else {
        echo "Invalid email or password";
    }
} else {
    echo "Please provide email and password";
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Signin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    
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
            Signin Form
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="field">                
                <label>Sign In with google</label>
            </div>
            <div class="google-icon">
                <a href="#">
                    <i class="fab fa-google"></i> <!-- Font Awesome Google icon -->
                </a>
            </div>
            <hr>
            <div class="field">
                <input type="text" name="email" required>
                <label>Email Address</label>
            </div>
            <div class="field">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <div class="field">
                <input type="submit" value="Login">
            </div>
            <div class="signup-link">
                Not a member? <a href="signup.php">Signup now</a>
            </div>
        </form>
    </div>
</body>
</html>
