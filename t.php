<?php
// $servername = "localhost";
// $username = "emelda_ma";
// $password = "UgWZjbrg72MTDvzS7WUq";
// $dbname = "emeldama_bot";

// Test connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connection successful";
$conn->close();
?>