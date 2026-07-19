<?php

include "db_connect.php";

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

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $compareGroup);

$stmt->execute();

$result = $stmt->get_result();

$foods = [];

while($row = $result->fetch_assoc()){

    $foods[] = $row;

}

$lowestPrice = null;

foreach ($foods as $food) {

    if ($lowestPrice === null || $food['price'] < $lowestPrice) {
        $lowestPrice = $food['price'];
    }

}

$highestRating = null;

foreach ($foods as $food) {

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

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-header">
            <div class="logo">
                <span class="logo-text">JomMakan</span>
            </div>
            <button id="toggle-btn" title="Toggle sidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <div class="sidebar-menu">
            <!-- home gold -->
            <a href="home.php" class="menu-item">
            <img class="menu-icon" src="image/dashboard/home.png" alt="Home">
            <span class="label">Home</span>
            </a>
            <!-- original -->
            <a href="search.php" class="menu-item active">
                <img src="image/dashboard/search-gold.png" alt="search">
                <span class="label">Search</span>
            </a>
            <a href="favourites.php" class="menu-item">
                <img src="image/icons/heart.png" alt="favourites">
                <span class="label">Favourites</span>
            </a>
            <a href="recently.php" class="menu-item">
                <img src="image/dashboard/recently.png" alt="recently">
                <span class="label">Recently Viewed</span>
            </a>
            <a href="surprise.php" class="menu-item">
                <img src="image/dashboard/surprise.png" alt="surprise">
                <span class="label">Surprise Me</span>
            </a>
        </div>

        <div class="sidebar-divider"></div>

        <div class="sidebar-bottom">
            <a href="profile.php" class="menu-item">
                <i class="fa-solid fa-user"></i>
                <span class="label">Profile</span>
            </a>
            <a href="help.php" class="menu-item">
                <i class="fa-solid fa-circle-question"></i>
                <span class="label">Help</span>
            </a>
            
        </div>

    </aside>

   <main class="main-content">

    <!-- Food Title -->

  <section class="compare-header">

<!-- Back Button -->
<?php if ($from === 'compare_home'): ?>
    <a href="compare_home.php" class="back-btn">← Back</a>
<?php else: ?>
    <a href="food.php?id=<?= $foodId ?>&from=<?= urlencode($from) ?>" class="back-btn">← Back</a>
<?php endif; ?>

    <h1><?= htmlspecialchars($foods[0]['food_name']) ?></h1>

<p>
    Found <?= count($foods) ?> restaurants offering similar dishes.
</p>

</section>

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

        <!-- Card 1 -->

<?php foreach($foods as $food): ?>

<div
    class="compare-card"
    data-price="<?= $food['price'] ?>"
    data-rating="<?= $food['rating'] ?>"
    data-name="<?= strtolower($food['restaurant_name']) ?>"
>

<?php if ($food['price'] == $lowestPrice): ?>

    <div class="badge">
    🥇 Best Budget
    </div>

<?php endif; ?>

<?php if ($food['rating'] == $highestRating): ?>

    <div class="badge">
    ⭐ Highest Rated
    </div>

<?php endif; ?>

    <div class="restaurant-info">

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

    <h3>
        RM <?= number_format($food['price'],2) ?>
    </h3>

    <a href="food.php?id=<?= $food['id'] ?>&from=compare&group=<?= urlencode($compareGroup) ?>&origin=<?= urlencode($from) ?>&food=<?= $foodId ?>" class="view-btn">
    View Details
    </a>

</div>

</div>
<?php endforeach; ?>


    </section>

</main>

</div>

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

<script src="js/compare.js"></script>

</body>
</html>