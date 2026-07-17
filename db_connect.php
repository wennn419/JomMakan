<?php

$host = getenv('DB_HOST') ?: 'db';
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: 'guestbook';
$username = getenv('DB_USER') ?: 'appuser';
$password = getenv('DB_PASSWORD') ?: 'apppass';

$conn = new mysqli($host, $username, $password, $dbname, (int)$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
