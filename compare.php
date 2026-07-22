<?php

session_start();

include "db_connect.php";

$currentPage = "search";

if (!isset($_GET['group'])) {
    die("No compare group selected.");
}

$compareGroup = $_GET['group'];
$foodId = (int)($_GET['id'] ?? 0);
$from = $_GET['from'] ?? 'search';

$sql = "
SELECT
    foods.*,
    restaurants.restaurant_name,
    restaurants.rating,
    restaurants.cuisine
FROM foods
JOIN restaurants
ON foods.restaurant_id = restaurants.id
WHERE foods.compare_group = ?
";

// prepare() is used to prepare the SQL statement before execution. 
// It allows values to be added later using placeholders (?), 
// making the query safer and helping prevent SQL Injection.
$stmt = $conn->prepare($sql);

// bind_param() is used to bind actual values to the placeholder (?) 
// in the prepared SQL statement before execution.
$stmt->bind_param("s", $compareGroup);

$stmt->execute();

// get_result() retrieves the query result from the database and 
// stores it in the $result variable for further processing.
$result = $stmt->get_result();

// Create an empty array to store all food records
$foods = [];

// Retrieve each row from the query result
while($row = $result->fetch_assoc()){

    // Add each food record into the array
    $foods[] = $row;

}

// Initialize the lowest price (no value yet)
$lowestPrice = null;

// Loop through every food record
foreach ($foods as $food) {

    // Update the lowest price if the current food is cheaper
    if ($lowestPrice === null || $food['price'] < $lowestPrice) {
        $lowestPrice = $food['price'];
    }

}

// Initialize the highest rating (no value yet)
$highestRating = null;

// Loop through every food record
foreach ($foods as $food) {

    // Update the highest rating if the current food has a higher rating
    if ($highestRating === null || $food['rating'] > $highestRating) {
        $highestRating = $food['rating'];
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JomMakan | Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/compare.css">
    
</head>
<body>
<div class="dashboard">

<?php include "includes/sidebar.php"; ?>

<main class="main-content">

<!-- Food Title -->
<section class="compare-header">

    <!-- Back button to return to the selected food page -->
    <a
    href="food.php?id=<?= $foodId ?>&from=<?= urlencode($from) ?>"
    class="back-btn"
>
    ← Back
    </a>

    <!-- Safely display the food name -->
    <h1><?= htmlspecialchars($foods[0]['food_name']) ?></h1>

    <p>
        Found <?= count($foods) ?> restaurants offering similar dishes.
    </p>

</section>

 <!-- Only display the comparison section if more than one restaurant offers this food. -->
<?php if (count($foods) > 1): ?>
<section class="compare-summary">

    <div class="summary-card">

        <h4>Lowest Price</h4>
        <h2>RM <?= number_format($lowestPrice, 2) ?></h2>

    </div>

    <div class="summary-card">

        <h4>Highest Rating</h4>
        <h2>⭐ <?= number_format($highestRating, 1) ?></h2>

    </div>

    <div class="summary-card">

        <h4>Restaurants</h4>

    <h2><?= count($foods) ?></h2>

    </div>

</section>

    <div class="sort-buttons">

    <button id="sort-price" class="active">
        Lowest Price
    </button>

    <button id="sort-rating">
        Highest Rating
    </button>

    <button id="sort-name">
        A - Z
    </button>

    </div>

    <!-- Restaurant List -->

    <section class="compare-list">

    <!-- Loop through each restaurant offering the selected food -->
    <?php foreach($foods as $food): ?>

    <!-- Store price for JavaScript -->
    <!-- Store lowercase restaurant name for alphabetical sorting -->
    <div class="compare-card" 
    data-price="<?= $food['price'] ?>"
    data-rating="<?= $food['rating'] ?>"
    data-name="<?= strtolower($food['restaurant_name']) ?>"
    >

    <div class="restaurant-info">

    <!-- Display a badge if the restaurant has both the lowest price and highest rating -->
    <?php if ($food['price'] == $lowestPrice && $food['rating'] == $highestRating): ?>

    <div class="badge">
        🏆 Best Budget • Highest Rated
    </div>

    <?php elseif ($food['price'] == $lowestPrice): ?>

    <div class="badge">
        🥇 Best Budget
    </div>

    <?php elseif ($food['rating'] == $highestRating): ?>

    <div class="badge">
        ⭐ Highest Rated
    </div>

    <?php endif; ?>

        <h2><?= htmlspecialchars($food['restaurant_name']) ?></h2>

        <p class="food-name">
            <?= htmlspecialchars($food['food_name']) ?>
        </p>

        <p class="rating">
            ⭐ <?= htmlspecialchars($food['rating']) ?>
        </p>

        <p class="location">
            📍 Mount Austin
        </p>

    </div>

    <div class="price-info">

        <h3>RM <?= number_format($food['price'],2) ?></h3>

        <a href="food.php?id=<?= $food['id'] ?>&from=compare&group=<?= urlencode($compareGroup) ?>" class="view-btn">
            View Details
        </a>

    </div>

</div>

<?php endforeach; ?>

</section>

<?php endif; ?>

<?php if (count($foods) <= 1): ?>

<div class="no-comparison">

    <h2>No Comparison Available</h2>

    <p>
        Only one restaurant currently offers this food.
        Comparison will be available when more restaurants offer it.
    </p>

</div>

<?php endif; ?>

</main>

</div>

<script src="js/compare.js"></script>
<script src="js/dashboard.js"></script>

</body>
</html>