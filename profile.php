<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "db_connect.php";

$currentPage = "profile";

$username = $_SESSION["username"];
$role = ucfirst($_SESSION["role"]);

$userId = $_SESSION["user_id"];

$favouriteQuery = $conn->prepare("
SELECT COUNT(*) AS total
FROM favourites
WHERE user_id = ?
");

$favouriteQuery->bind_param("i", $userId);
$favouriteQuery->execute();

$favouriteCount = $favouriteQuery
    ->get_result()
    ->fetch_assoc()["total"];

$recentQuery = $conn->prepare("
SELECT COUNT(*) AS total
FROM recently_viewed
WHERE user_id = ?
");

$recentQuery->bind_param("i", $userId);
$recentQuery->execute();

$recentCount = $recentQuery
    ->get_result()
    ->fetch_assoc()["total"];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Profile | JomMakan</title>

    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/dashboard.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="dashboard">

    <?php include "includes/sidebar.php"; ?>

<main class="main-content">

<div class="profile-header">

    <div class="profile-avatar">

        <?= strtoupper(substr($username, 0, 1)) ?>

    </div>

    <h1><?= htmlspecialchars($username) ?></h1>

</div>

<div class="account-card">

    <h2>Account Overview</h2>

    <div class="account-item">

        <span>Username</span>

        <strong><?= htmlspecialchars($username) ?></strong>

    </div>

    <div class="account-item">

        <span>Role</span>

        <strong><?= htmlspecialchars($role) ?></strong>

    </div>

    <div class="account-item">

        <span>Favourite Foods</span>

        <strong><?= $favouriteCount ?></strong>

    </div>

    <div class="account-item">

        <span>Recently Viewed</span>

        <strong><?= $recentCount ?></strong>

    </div>

</div>

</main>

</div>

</body>

<script src="js/dashboard.js"></script>

</html>