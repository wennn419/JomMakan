<?php
session_start();

$currentPage = "surprise";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Surprise Me | JomMakan</title>

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/surprise.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="dashboard">

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content">

    <div class="surprise-header">

        <div class="header-left">
            <h1>Surprise Me</h1>

<p class="hero-text">
    One click, one surprise. Find your next favourite meal.
</p>
        </div>

    </div>

    <div class="surprise-options">

    <a href="personalized.php" class="surprise-card personalized">

    <div class="card-content">

        <div class="card-title">

    <div class="card-icon gold">
        <i class="fa-solid fa-sliders"></i>
    </div>

    <h2>Personalized Surprise</h2>

</div>
        <p class="description">

            A meal picked just for you.

        </p>

        <ul class="feature-list">

            <li><i class="fa-solid fa-check"></i> Budget Preference</li>

            <li><i class="fa-solid fa-check"></i> Cuisine Selection</li>

            <li><i class="fa-solid fa-check"></i> Highly Rated</li>

        </ul>

        <div class="card-footer">

            <span class="start-btn">

                Get Started

                <i class="fa-solid fa-arrow-right"></i>

            </span>

        </div>

    </div>

</a>

<a href="loading.php?mode=total" class="surprise-card total">

    <div class="card-content">

        <div class="card-title">

    <div class="card-icon purple">
        <i class="fa-solid fa-dice"></i>
    </div>

    <h2>Total Surprise</h2>

</div>

        <p class="description">

            No choices. Just surprises.

        </p>

        <ul class="feature-list total-list">

            <li><i class="fa-solid fa-check"></i> Completely Random</li>

            <li><i class="fa-solid fa-check"></i> All Cuisines Included</li>

            <li><i class="fa-solid fa-check"></i> Discover Hidden Gems</li>

        </ul>

        <div class="card-footer">

            <span class="start-btn purple-btn">

                Try Now

                <i class="fa-solid fa-arrow-right"></i>

            </span>

        </div>

    </div>

</a>

    </main>

</div>

<script src="js/dashboard.js"></script>

</body>

</html>