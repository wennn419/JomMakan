<?php
session_start();
$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";

require 'db_connect.php';

$keyword = "";

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
}

$budget = "";

if (isset($_GET['budget'])) {
    $budget = $_GET['budget'];
}

$cuisine = "";

if (isset($_GET['cuisine'])) {
    $cuisine = $_GET['cuisine'];
}

$rating = "";

if (isset($_GET['rating'])) {
    $rating = $_GET['rating'];
}

$category = "";

if (isset($_GET['category'])) {
    $category = $_GET['category'];
}

$sql = "
SELECT
    foods.*,
    restaurants.restaurant_name,
    restaurants.rating
FROM foods
JOIN restaurants
ON foods.restaurant_id = restaurants.id
WHERE 1=1
";

if ($keyword != "") {
    $sql .= " AND foods.food_name LIKE '%$keyword%'";
}

if ($budget != "") {
    $sql .= " AND foods.price <= $budget";
}

if ($cuisine != "") {

    $cuisineList = explode(",", $cuisine);

    $quotedCuisine = [];

    foreach ($cuisineList as $item) {
        $quotedCuisine[] = "'" . $conn->real_escape_string(trim($item)) . "'";
    }

    $sql .= " AND restaurants.cuisine IN (" . implode(",", $quotedCuisine) . ")";
}

if ($rating != "") {
    $sql .= " AND restaurants.rating >= " . (float)$rating;
}

if ($category != "") {
    $sql .= " AND foods.category = '$category'";
}

$sql .= " ORDER BY foods.price ASC";

$result = $conn->query($sql);
?>

<?php
$currentPage = 'search';
?>

<?php

$successMessage = "";

if (isset($_GET["success"])) {

    switch ($_GET["success"]) {

        case "food_added":
            $successMessage = "✓ Food added successfully.";
            break;

        case "food_updated":
            $successMessage = "✓ Food updated successfully.";
            break;

        case "food_deleted":
            $successMessage = "✓ Food deleted successfully.";
            break;

        case "restaurant_added":
            $successMessage = "✓ Restaurant added successfully.";
            break;

        case "restaurant_updated":
            $successMessage = "✓ Restaurant updated successfully.";
            break;

        case "restaurant_deleted":
            $successMessage = "✓ Restaurant deleted successfully.";
            break;

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
    
    
</head>
<body>

<div class="dashboard">

    <?php include "includes/sidebar.php"; ?>

    <!-- 原本右边的内容继续 -->

    <main class="main-content">

    <?php if ($successMessage): ?>

    <div class="success-alert">

        <?= htmlspecialchars($successMessage) ?>

    </div>

    <?php endif; ?>

    <!-- Search Header + Results Title: kept together so both stay fixed on scroll -->

    <div class="sticky-toolbar">

    <section class="search-header">

<form method="GET" action="">

<div class="search-box">

    <img class="search-icon"
         src="image/dashboard/search.png">

    <input
        type="text"
        name="keyword"
        placeholder="Search food..."
        value="<?= htmlspecialchars($keyword) ?>">

    <button type="submit" class="search-btn">

        Search

    </button>

    <button
        type="button"
        class="filter-btn"
        id="filter-btn">

        <img
            src="image/dashboard/filter.png"
            alt="Filter">

    </button>

</div>

</section>

<div class="section-title">

        <div>

            <h2>Search Results</h2>

            <p><?= $result->num_rows ?> Results Found</p>

        </div>

        <?php if ($isAdmin): ?>

        <div class="admin-actions">

            <a href="admin/foods/add_food.php" class="admin-btn">
                + Add Food
            </a>

            <a href="admin/restaurants/add_restaurant.php" class="admin-btn">
                + Add Restaurant
            </a>

        </div>

        <?php endif; ?>

</div>

    </div>

<!-- Search Result -->

<section class="result-section">

            <!-- Card 1 -->

            <div class="result-list">

<?php if ($result->num_rows > 0) { ?>

    <?php while ($row = $result->fetch_assoc()) { ?>

<div class="result-card"
     onclick="window.location.href='food.php?id=<?= $row['id'] ?>&from=search'">

    <img 
    src="<?= htmlspecialchars($row['image']) ?>"
    alt="<?= htmlspecialchars($row['food_name']) ?>">

    <div class="result-info">

        <h2><?= htmlspecialchars($row['food_name']) ?></h2>

        <p><?= htmlspecialchars($row['restaurant_name']) ?></p>

        <div class="info-bottom">

            ⭐ <?= htmlspecialchars($row['rating']) ?>

            <p class="price">

            RM <span><?= number_format($row['price'],2) ?></span>

            </p>

        </div>

    </div>

    <div class="card-actions">

    <?php if ($isAdmin): ?>

        <a href="admin/foods/edit_food.php?id=<?= $row['id'] ?>"
           class="edit-btn"
           onclick="event.stopPropagation();">

            ✏ Edit

        </a>

        <a href="admin/foods/delete_food.php?id=<?= $row['id'] ?>"
           class="delete-btn"
           onclick="event.stopPropagation();
                    return confirm('Delete this food?');">

            🗑 Delete

        </a>

    <?php endif; ?>

</div>

</div>

<?php } ?>

<?php } else { ?>

    <div class="no-result">
        <h2>No food matches your search.</h2>
        <p>Try changing your filters.</p>
        
    </div>

<?php } ?>

</div>
        

    </section>

    <aside class="filter-panel" id="filter-panel">

    <input type="hidden" name="cuisine" id="selectedCuisine" value="">
    <input type="hidden" name="rating" id="selectedRating" value="">

        <div class="filter-header">

    <h3>Refine Results</h3>

    <button id="close-filter">

        &times;

    </button>

</div>

<div class="filter-content">
    <div class="filter-group">

    <h4>Budget</h4>

    <div class="budget-input">

        <span>RM</span>

    <input
    type="number"
    name="budget"
    value="<?= htmlspecialchars($_GET['budget'] ?? '') ?>"
    placeholder="e.g. 15.00"
    min="0"
    step="0.01">

    </div>

    <p class="budget-note">
        We'll only show food within your budget.
    </p>

</div>


<div class="filter-group">

    <h4>Cuisine</h4>

    <div class="filter-buttons cuisine-buttons">

        <button type="button" class="active" data-value="All">All</button>

        <button type="button" data-value="Malay">Malay</button>

        <button type="button" data-value="Chinese">Chinese</button>
        
        <button type="button" data-value="Japanese">Japanese</button>

        <button type="button" data-value="Western">Western</button>

    </div>

</div>


<div class="filter-group">

    <h4>Rating</h4>

    <div class="filter-buttons rating-buttons">

        <button type="button" data-value="3">3.0+</button>

        <button type="button" data-value="4">4.0+</button>

        <button type="button" data-value="4.8">4.8+</button>

    </div>

</div>
</div>

        <div class="filter-action">

    <button class="reset-btn">

        Reset

    </button>

    <button
    type="submit"
    class="apply-btn">

    Apply

</button>

</div>

    </aside>

    </form>

</main>

</div>

<script src="js/dashboard.js"></script>

</body>
</html>