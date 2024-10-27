<?php 
$hostname = "localhost";
$username = "root";
$password = "";
$database = "dotchat";

$conn = new mysqli($hostname, $username, $password, $database);

if($conn->connect_error) {
    die("Mysqli Connection Failed: " . $conn->connect_error);
}



