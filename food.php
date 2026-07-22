<?php

session_start();

// Check whether the logged-in user is an administrator
$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";

include "db_connect.php";

// Get the logged-in user's ID, or null if the user is not logged in
$userId = $_SESSION["user_id"] ?? null;

// Get the source page from the URL, default to "search"
$from = trim($_GET['from'] ?? 'search');

// Stop the program if the food ID is missing
if (!isset($_GET['id'])) {
    // Stop the script and display an error message
    die("Food ID not found.");
}

$id = (int)$_GET['id'];

// Save to Recently Viewed (only if logged in) 
// Save recently viewed food only for logged-in users
if ($userId !== null) {

// Insert a recently viewed record or update the view time if it already exists
    $sql = "
    INSERT INTO recently_viewed (user_id, food_id)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE
    viewed_at = CURRENT_TIMESTAMP
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $id);
    $stmt->execute();

    // Because this SQL statement inserts data into the database. 
    // It does not return a result set, so get_result() is not needed.
}

$sql = "
SELECT
    foods.*,
    restaurants.restaurant_name,
    restaurants.rating,
    restaurants.cuisine,
    restaurants.location,
    restaurants.opening_hours,
    restaurants.address
FROM foods
JOIN restaurants
ON foods.restaurant_id = restaurants.id
WHERE foods.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Food not found.");
}

$food = $result->fetch_assoc();

// Assume the food is not in the user's favourites
$isFavourite = false;

// Check favourite status only for logged-in users
if ($userId !== null) {

    $favouriteSql = "
    SELECT *
    FROM favourites
    WHERE food_id = ?
    AND user_id = ?
    ";
    // Check whether this food is already in the user's favourites

    $stmt = $conn->prepare($favouriteSql);
    $stmt->bind_param("ii", $id, $userId);
    $stmt->execute();

    $favouriteResult = $stmt->get_result();

    // Set the favourite status based on whether a matching record exists
    // food detail page will show "Favourited" if the user has already favourited this food
    $isFavourite = $favouriteResult->num_rows > 0;
}

// Process the favourite button only when the form is submitted
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['toggleFavourite'])
) {

    if ($userId === null) {

        header("Location: login.php");
        exit;

    }

    // Check the current favourite status
    if ($isFavourite) {

        // Remove the food from the current user's favourites
        $conn->query("
            DELETE FROM favourites
            WHERE food_id = $id
            AND user_id = $userId
        ");

    } else {

        if (!$conn->query("
        INSERT INTO favourites (user_id, food_id)
        VALUES ($userId, $id)
")) {

    die($conn->error);

}

    }

    // Redirect back to the Food Detail page
    header("Location: food.php?id=$id&from=$from");
    exit;
}

// Get other foods from the same restaurant
// Store the current restaurant ID and food ID
$restaurantId = $food['restaurant_id'];
$currentFoodId = $food['id'];

$relatedSql = "
SELECT *
FROM foods
WHERE restaurant_id = ?
AND id != ?
LIMIT 4
";
// != no equal ; Retrieve other foods from the same restaurant except the current food

$stmt = $conn->prepare($relatedSql);
$stmt->bind_param("ii", $restaurantId, $currentFoodId);
$stmt->execute();

// Get the related food records for display
$relatedFoods = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($food['food_name']) ?></title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/food.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<div class="food-details-page">

    <div class="food-image">

    <?php
    // Determine the correct Back button destination
    if ($from == "compare") {

    // Preserve the original source page if compare come from search or result page,
    // so that the back button can return to the correct page
    $origin = $_GET['origin'] ?? 'search';

    // Return to the Compare page
    $backLink =
        "compare.php?group=" . urlencode($_GET['group']) .
        "&id=" . (int)($_GET['food'] ?? 0) .
        "&from=" . urlencode($origin);

    }

    elseif ($from == "result") {

        $mode = $_GET['mode'] ?? 'personalized';

        // Return to the Result page
        $backLink = "result.php?mode=" . urlencode($mode);

    }

    else {

    // Return to the previous page
        $backLink = $from . ".php";

    }
    ?>

    <a href="<?= htmlspecialchars($backLink) ?>" class="back-button">
        <i class="fa-solid fa-arrow-left"></i>
    </a>

        <img src="<?= htmlspecialchars($food['image']) ?>"
            alt="<?= htmlspecialchars($food['food_name']) ?>">

    </div>

    <div class="food-info">

        <h1><?= htmlspecialchars($food['food_name']) ?></h1>

        <!-- Clickable restaurant information -->
        <!-- # Placeholder link handled by JavaScript -->
        <a
        href="#"
        class="restaurant-name"
        id="restaurantInfoBtn"
        >

        <img src="image/icons/location.png" alt="Location">

        <span><?= htmlspecialchars($food['restaurant_name']) ?></span>

        </a>

        <p class="food-price">
            RM <?= number_format($food['price'],2) ?>
        </p>

       <div class="food-meta">

        <p class="food-rating">
            ⭐ <?= htmlspecialchars($food['rating']) ?>
        </p>

        <p class="food-cuisine">
            <?= htmlspecialchars($food['cuisine']) ?> Cuisine
        </p>

    </div>

    <div class="food-actions">

    <form method="POST">

        <button
            type="submit"
            name="toggleFavourite"
            class="action-btn save-btn <?= $isFavourite ? 'active' : '' ?>"
        >

            <!-- Apply the active style if the food is already favourited -->
            <!-- Display a solid or regular heart icon based on the favourite status -->
            <i class="<?= $isFavourite ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>

            <!-- Text indicating favourite status -->
            <span>
                <?= $isFavourite ? "Favourited" : "Favourite" ?>
            </span>

        </button>

    </form>

        <button class="action-btn share-btn" id="shareButton">

        <img src="image/icons/share.png" alt="Share">

        <span>Share</span>

        </button>

    </div>

        <div class="food-description">

        <h3>Description</h3>

        <p>
        <?= nl2br(htmlspecialchars($food['description'])) ?>
        </p>

        <div class="compare-section">

        <h3>Compare Similar Foods</h3>

        <p>
            Find similar dishes from other restaurants and compare prices and ratings.
        </p>

        <?php if (!empty($food['compare_group'])): ?>

        <a
        href="compare.php?group=<?= urlencode($food['compare_group']) ?>&id=<?= $food['id'] ?>&from=<?= urlencode($from) ?>"
        class="compare-link">

        Compare Now →

        </a>

        <?php endif; ?>

        </div>

        </div>

    </div>
</div>

    <div class="related-foods">

    <div class="related-header">

        <h2>More from <?= htmlspecialchars($food['restaurant_name']) ?></h2>

</div>

    <div class="related-food-grid">

        <?php while($row = $relatedFoods->fetch_assoc()): ?>

            <a class="related-card" href="food.php?id=<?= $row['id'] ?>&from=<?= urlencode($from) ?>">

                <img src="<?= htmlspecialchars($row['image']) ?>"
                     alt="<?= htmlspecialchars($row['food_name']) ?>">

                <div class="related-info">

                    <h3><?= htmlspecialchars($row['food_name']) ?></h3>

                    <p>RM <?= number_format($row['price'],2) ?></p>

                </div>

            </a>

        <?php endwhile; ?>

    </div>

</div>

<script>

const shareButton = document.getElementById("shareButton");

shareButton.addEventListener("click", async () => {

    try {

        await navigator.clipboard.writeText(window.location.href);

        alert("Link copied to clipboard!");

    }

    catch(error){

        alert("Unable to copy link.");

    }

});

</script>

</body>

<div id="restaurantModal" class="restaurant-modal">

    <div class="restaurant-modal-content">

        <button id="closeRestaurantModal" class="close-modal">
            &times;
        </button>

        <h2 id="modalRestaurantName">
        <?= htmlspecialchars($food['restaurant_name']) ?>
        </h2>

        <div class="restaurant-info-grid">

        <div class="info-card">
            <span class="info-label">Rating</span>
            <span class="info-value">
                ⭐ <?= htmlspecialchars($food['rating']) ?>
            </span>
        </div>

        <div class="info-card">
            <span class="info-label">Cuisine</span>
            <span class="info-value">
                <?= htmlspecialchars($food['cuisine']) ?>
            </span>
        </div>

        <div class="info-card full-width">
            <span class="info-label">Location</span>
            <span class="info-value">
                📍 <?= htmlspecialchars($food['location']) ?>
            </span>
        </div>

        <div class="info-card full-width">
            <span class="info-label">Opening Hours</span>
            <span class="info-value">
                🕒 <?= htmlspecialchars($food['opening_hours']) ?>
            </span>
        </div>

        </div>

        <div class="direction-dropdown">

    <button
        type="button"
        class="direction-btn"
        id="directionBtn"
    >
        📍 Get Directions
        <i class="fa-solid fa-chevron-down"></i>
    </button>

    <div class="direction-menu" id="directionMenu">

        <a
            href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($food['address']) ?>"
            target="_blank"
        >
            <i class="fa-solid fa-map-location-dot"></i>
            Google Maps
        </a>

        <a
            href="https://www.waze.com/ul?q=<?= urlencode($food['address']) ?>"
            target="_blank"
        >
            <i class="fa-brands fa-waze"></i>
            Waze
        </a>

    </div>

</div>

        <?php if ($isAdmin): ?>

        <div class="restaurant-admin-actions">

            <a href="admin/restaurants/edit_restaurant.php?id=<?= $food['restaurant_id'] ?>"
            class="edit-btn">

                ✏ Edit

            </a>

            <a href="admin/restaurants/delete_restaurant.php?id=<?= $food['restaurant_id'] ?>"
            class="delete-btn"
            onclick="return confirm('Deleting this restaurant will also delete all related data.\n\nContinue?');">

                🗑 Delete

            </a>

        </div>

        <?php endif; ?>

    </div>

</div>

<script>

const restaurantBtn = document.getElementById("restaurantInfoBtn");
const restaurantModal = document.getElementById("restaurantModal");
const closeRestaurantModal = document.getElementById("closeRestaurantModal");

restaurantBtn.addEventListener("click", (e) => {

    e.preventDefault();

    restaurantModal.style.display = "flex";

});

closeRestaurantModal.addEventListener("click", () => {
    restaurantModal.style.display = "none";
});

// 点击任意地方关闭
restaurantModal.addEventListener("click", (e) => {

    if (e.target === restaurantModal) {

        restaurantModal.style.display = "none";

    }

});

const directionBtn = document.getElementById("directionBtn");
const directionMenu = document.getElementById("directionMenu");

directionBtn.addEventListener("click", () => {

    directionMenu.classList.toggle("show");

});

document.addEventListener("click", (e) => {

    if (!e.target.closest(".direction-dropdown")) {

        directionMenu.classList.remove("show");

    }

});

</script>

</html>