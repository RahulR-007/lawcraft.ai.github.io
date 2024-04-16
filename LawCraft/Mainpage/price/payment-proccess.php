<?php

// Establishing database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lawcraft";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Getting data from POST request
$payment_id = $_POST['razorpay_payment_id'];
$amount = $_POST['totalAmount'];
$plan_name = $_POST['plan_name'];

// Assuming 'fullname' is available in the session
session_start();
$fullname = $_SESSION['fullname'];

// Fetching email and existing tokens from the signdata table based on fullname
$sql_user_data = "SELECT email, tokens FROM signdata WHERE fullname = '$fullname'";
$result_user_data = $conn->query($sql_user_data);

if ($result_user_data->num_rows > 0) {
    $row_user_data = $result_user_data->fetch_assoc();
    $email = $row_user_data['email'];
    $existing_tokens = $row_user_data['tokens'];
} else {
    // Handle the case where user data is not found
    $email = ""; // You can set a default value or handle the error accordingly
    $existing_tokens = 0; // Default value for existing tokens
}

// Inserting data into the payments table
$sql = "INSERT INTO payments (payment_id, email, amount, plan_name) VALUES ('$payment_id', '$email', '$amount', '$plan_name')";

if ($conn->query($sql) === TRUE) {
    // Update the user's plan and tokens in the signdata table based on the selected plan
    $update_sql = "";

    if ($plan_name == 'Advanced') {
        $new_tokens = $existing_tokens + 10; // Adding 10 tokens for Advanced plan
        $update_sql = "UPDATE signdata SET plan_name = 'Advanced', tokens = $new_tokens WHERE email = '$email'";
    } elseif ($plan_name == 'Premium') {
        $new_tokens = $existing_tokens + 20; // Adding 20 tokens for Premium plan
        $update_sql = "UPDATE signdata SET plan_name = 'Premium', tokens = $new_tokens WHERE email = '$email'";
    } else {
        $update_sql = "UPDATE signdata SET plan_name = '$plan_name' WHERE email = '$email'";
    }

    if ($conn->query($update_sql) === TRUE) {
        // Plan and tokens successfully updated
        $response = array('msg' => 'Payment successfully credited', 'status' => true);
    } else {
        // Handle the error if the plan and tokens update fails
        $response = array('msg' => 'Error updating plan and tokens: ' . $conn->error, 'status' => false);
    }
} else {
    $response = array('msg' => 'Error: ' . $sql . '<br>' . $conn->error, 'status' => false);
}

// Closing database connection
$conn->close();

// Sending response
echo json_encode($response);

?>
