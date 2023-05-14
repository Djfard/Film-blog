<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "sql205.epizy.com";
$username = "epiz_34189545";
$password = "gyr05mk4";
$dbname = "epiz_34189545_users";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>