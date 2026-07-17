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

<div class="question-header">

    <a href="surprise.php" class="back-btn">

        <i class="fa-solid fa-arrow-left"></i>

    </a>

    <div>

        <h1>Personalized Surprise</h1>

        <p>
            Tell us a little about your preferences and
            we'll recommend something you'll love.
        </p>

    </div>

</div>

<form action="loading.php" method="POST">

<div class="question-container">

<div class="question-card">

<div class="question-title">

    <i class="fa-solid fa-wallet"></i>

    <div>

        <h2>What's your budget?</h2>

        <p>Select the budget that fits you best.</p>

    </div>

</div>

    <div class="option-grid budget-grid">

        <button type="button" 
        class="option-card" 
        data-value="10">
            <strong>RM10 or less</strong>
            <span>Budget Friendly</span>
        </button>

        <button type="button"
        class="option-card"
        data-value="20">
            <strong>RM11 – RM20</strong>
            <span>Great Value</span>
        </button>

        <button type="button"
        class="option-card"
        data-value="35">
            <strong>RM21 – RM35</strong>
            <span>Treat Yourself</span>
        </button>

        <button type="button"
        class="option-card"
        data-value="999">
            <strong>No Limit</strong>
            <span>I'm Feeling Lucky</span>
        </button>

    </div>

</div>

</div>

<!-- Restaurant quality -->
<div class="question-container">

<div class="question-card">

<div class="question-title">

    <i class="fa-solid fa-star"></i>

    <div>

        <h2>Restaurant Quality</h2>

        <p>Choose the type of restaurant you'd like us to prioritise.</p>

    </div>

</div>

    <div class="rating-grid">

    <button type="button"
        class="option-card"
        data-value="high">

        <strong>Highly Rated</strong>

        <span>Excellent Reviews</span>

    </button>

    <button type="button"
        class="option-card"
        data-value="medium">

        <strong>Well Rated</strong>

        <span>Great Value</span>

    </button>

    <button type="button"
        class="option-card"
        data-value="any">

        <strong>No Preference</strong>

        <span>Anything Goes</span>

    </button>

</div>

</div>

</div>

<div class="question-container">

    <div class="question-card">

        <div class="question-title">

            <i class="fa-solid fa-utensils"></i>

            <div>

                <h2>Preferred Cuisine</h2>

                <p>Select the cuisine you're craving today.</p>

            </div>

        </div>

        <div class="cuisine-grid">

            <button type="button" class="cuisine-chip" data-value="Chinese">Chinese</button>
            <button type="button" class="cuisine-chip" data-value="Malay">Malay</button>
            <button type="button" class="cuisine-chip" data-value="Western">Western</button>
            <button type="button" class="cuisine-chip" data-value="Japanese">Japanese</button>
            <button type="button" class="cuisine-chip" data-value="Any">Any Cuisine</button>

        </div>

    </div>

</div>

<div class="continue-section">

<input type="hidden" name="budget" id="budgetInput">

<input type="hidden" name="quality" id="qualityInput">

<input type="hidden" name="cuisine" id="cuisineInput">

    <div class="continue-section">
    <button type="submit" class="continue-btn">
        Continue
    </button>
</div>

</div>

</form>

    </main>

</div>

<script src="js/dashboard.js"></script>
<script src="js/surprise.js"></script>


</body>

</html>