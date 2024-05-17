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

// Insert user into the table
$sql = "INSERT INTO users (username) VALUES ('$username')";
if ($conn->query($sql) === TRUE) {
    // Registration successful, redirect to login page
    header("Location: login.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
