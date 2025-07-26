<?php
$conn = new mysqli("your_host", "your_username", "your_password", "your_database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$conn->close();
?>
