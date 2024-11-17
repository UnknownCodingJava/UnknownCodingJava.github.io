<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}