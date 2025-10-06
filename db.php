<?php
$host = "localhost";
$db = "user_management";
$user = "root"; // MySQL username
$pass = "";     // MySQL password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
