<?php
// Start session at the beginning of every page
session_start();

// Database connection parameters
$host = "localhost";
$username = "root";
$password = "";
$database = "assignment1";

// Create connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>