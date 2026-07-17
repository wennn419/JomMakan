<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ..是往上一层的意思
require_once __DIR__ . "/../../includes/auth.php";
require_once "../../db_connect.php";

if (!isset($_SESSION["user_id"])) {

    header("Location: ../login.php");
    exit;
}

// Check whether the logged-in user is an admin
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user_id"]);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user["role"] !== "admin") {

    header("Location: ../home.php");
    exit;
}