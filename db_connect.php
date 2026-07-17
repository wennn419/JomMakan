<?php

$host = "db";
$dbname = "guestbook";
$username = "appuser";
$password = "apppass";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>