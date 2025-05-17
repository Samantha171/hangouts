<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost"; // Change if using a remote database
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$database = "package"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>