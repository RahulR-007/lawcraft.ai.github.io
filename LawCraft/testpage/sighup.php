<?php

session_start();

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lawcraft1";

// Connect to database
$conn = new mysqli($servername, $username, $password, 'lawcraft1');


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process registration form submission
if (isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $city = $_POST['city'];
    $state = $_POST['state'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    // Check if email is already taken
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already taken";
    } else {
        // Insert new user into database
        $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, email, password, city, state) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $firstname, $lastname, $email, $password, $city, $state);
        $stmt->execute();
        $_SESSION['email'] = $email;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        header("Location: login.php");
    }
} $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
<style>
        .form-control{
          border: 2px solname black;
          padding: 10px;
          width: 100%;
          height: 50%
        }
        form{
          border: 2px green solid; 
          width: 300px; 
          margin: auto;
          margin-top: 150px;
        }
        .radio-buttons {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}


        </style>
    <title>new user Registration form</title>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>

<h2 style="text-align: center;margin-top: 80px;">Register Now</h2> 
 <script>
        $(document).ready(function(){
            $("#email").on("blur", function(){
                var email = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "signup.php", // Update this with your PHP file to check email
                    data: {email: email},
                    success: function(response){
                        $("#emailError").text(response); // Update element to show error message
                    }
                });
            });
        });
    </script> 
  
    <form style="margin-top: 10px;" method="post" action="">
      <div style="margin: 20px;">
        <div class="form-group">
          <label for="firstName">First Name</label>
          <input type="text" id="firstName" class="form-control" name="firstname" placeholder="Enter First Name" required>
        </div>
        <div class="form-group">
          <label for="lastName">Last Name</label>
          <input type="text" id="lastName" class="form-control" name="lastname"  placeholder="Enter Last Name" required>
        </div>
         <div class="field">
            <input type="text" id="email" name="email" required>
            <label>Email Address</label>
            <span id="emailError" style="color: red;"></span>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id ="password" class="form-control" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
          <label for="confirmPassword">Confirm Password</label>
          <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm Password"r equired>
        </div>
        <div class="form-group">
          <label for="phoneNo">City</label>
          <input type="text" class="form-control" name="city" placeholder="Enter City" required>
        </div>
	<div class="form-group">
          <label for="phoneNo">State</label>
          <input type="text" class="form-control" placeholder="Enter state" name="state" required>
        </div>
        <div style="margin-top: 10px;">
          <input type="submit" name="submit" value="Register">
        </div>
      </div>
    </form>


</body>
</html>