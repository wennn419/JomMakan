<?php
session_start();

require_once "db_connect.php";

// Remove remember token from database
if (isset($_SESSION["user_id"])) {

    $stmt = $conn->prepare("
        UPDATE users
        SET
            remember_token = NULL,
            remember_expires = NULL
        WHERE id = ?
    ");

    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
}

// Clear remember cookie
setcookie("remember_me", "", time() - 3600, "/");

// Clear all session data
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to Home Page
header("Location: home.php");
exit;