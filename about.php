<?php
session_start();

require_once "db_connect.php";

$currentPage = "about";

$isLoggedIn = isset($_SESSION["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About | JomMakan</title>

    <link rel="stylesheet" href="css/about.css">
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

    <div class="about-header">

        <h1>About JomMakan</h1>

        <p>
            Helping users discover delicious meals quickly,
            easily and within budget.
        </p>

    </div>

    <div class="about-card">

        <div class="about-logo">

            <i class="fa-solid fa-bowl-food"></i>

        </div>

        <div class="about-info">

            <h2>JomMakan</h2>

            <p>
                JomMakan is a food recommendation web application
                that helps users discover delicious meals quickly,
                compare prices, and find restaurants that match
                their preferences.
            </p>

        </div>

    </div>

    <div class="info-grid">

        <div class="info-card">

            <span>Version</span>

            <h3>1.0</h3>

        </div>

        <div class="info-card">

            <span>Developed For</span>

            <h3>Web Programming</h3>

        </div>

        <div class="info-card">

            <span>Database</span>

            <h3>MySQL</h3>

        </div>

        <div class="info-card">

            <span>Backend</span>

            <h3>PHP</h3>

        </div>

    </div>

    <section class="tech-section">

        <h2>Technology Stack</h2>

        <div class="tech-tags">

            <span>PHP</span>
            <span>MySQL</span>
            <span>HTML5</span>
            <span>CSS3</span>
            <span>JavaScript</span>
            <span>Docker</span>

        </div>

    </section>

    </main>

</div>

</body>

<script src="js/dashboard.js"></script>

</html>