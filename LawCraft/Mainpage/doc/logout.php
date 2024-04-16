<?php
function logout() {
    session_start();

    // Clear all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to login.php
    header("Location: ../sign/signin.php");
    exit();
}

// Check if the 'action' parameter is set in the URL
if (isset($_GET['action'])) {
    // Perform the corresponding action
    if ($_GET['action'] === 'logout') {
        logout();
    }
}
?>
