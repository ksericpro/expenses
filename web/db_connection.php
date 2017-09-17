<?php
$servername = "172.30.133.236";
$username = "personaluser";
$password = "personaluser321";
$dbname = 'personal';

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
$conn->close();
?>