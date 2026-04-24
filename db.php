<?php
$host = "localhost";
$username = "root"; // Or your MySQL username
$password = "";     // Your MySQL password
$database = "final_milktea";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
