<?php
// Retrieve form data
$username = $_POST['username'];

// Connect to MySQL
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "login_system";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user from the table
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Login successful, save the username to session
    session_start();
    $_SESSION['username'] = $username;

    // Redirect to the specific page
    header("Location: index.html");
    exit();
} else {
    echo "Invalid username.";
}

$conn->close();
?>
