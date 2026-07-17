<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../../search.php");
    exit;
}

require_once "../../db_connect.php";

if (!isset($_GET["id"])) {
    header("Location: ../../search.php");
    exit;
}

$id = (int) $_GET["id"];

$stmt = $conn->prepare("DELETE FROM restaurants WHERE id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    die("Error deleting restaurant: " . $stmt->error);
}

$stmt->close();

header("Location: ../../search.php?success=restaurant_deleted");
exit;
?>