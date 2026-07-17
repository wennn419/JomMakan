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

$stmt = $conn->prepare("DELETE FROM foods WHERE id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    die("Error deleting food: " . $stmt->error);
}

$stmt->close();

header("Location: ../../search.php?success=food_deleted");
exit;
?>