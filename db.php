<?php
$host = "localhost";
$user = "root";   // XAMPP default
$pass = "";       // XAMPP default password is empty
$dbname = "nextintech";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
