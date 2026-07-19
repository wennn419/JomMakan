<?php

session_start();

$isAdmin = isset($_SESSION["role"]) && $_SESSION["role"] === "admin";

include "db_connect.php";

$userId = $_SESSION["user_id"] ?? null;

$from = trim($_GET['from'] ?? 'search');

if (!isset($_GET['id'])) {
    die("Food ID not found.");
}

$id = (int)$_GET['id'];

// Save to Recently Viewed (only if logged in)
if ($userId !== null) {

    $sql = "
    INSERT INTO recently_viewed (user_id, food_id)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE
    viewed_at = CURRENT_TIMESTAMP
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $id);
    $stmt->execute();
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
WHERE foods.id = $id
";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Food not found.");
}

$food = $result->fetch_assoc();

$isFavourite = false;

if ($userId !== null) {

    $favouriteSql = "
    SELECT *
    FROM favourites
    WHERE food_id = $id
    AND user_id = $userId
    ";

    $favouriteResult = $conn->query($favouriteSql);

    $isFavourite = $favouriteResult->num_rows > 0;
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['toggleFavourite'])
) {

    if ($userId === null) {

        header("Location: login.php");
        exit;

    }

    if ($isFavourite) {

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

    header("Location: food.php?id=$id&from=$from");
    exit;
}

// Get other foods from the same restaurant
$restaurantId = $food['restaurant_id'];
$currentFoodId = $food['id'];

$relatedSql = "
SELECT *
FROM foods
WHERE restaurant_id = ?
AND id != ?
LIMIT 4
";

$stmt = $conn->prepare($relatedSql);
$stmt->bind_param("ii", $restaurantId, $currentFoodId);
if ($stmt->execute()) {
    die("Insert Success");
} else {
    die($stmt->error);
}
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
if ($from == "compare") {
    $origin = $_GET['origin'] ?? 'search';
    $backLink = "compare.php?group=" . urlencode($_GET['group']) .
                "&id=" . (int)($_GET['food'] ?? 0) .
                "&from=" . urlencode($origin);
} else {
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

        <i class="<?= $isFavourite ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>

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
    href="compare.php?group=<?= urlencode($food['compare_group']) ?>&id=<?= $food['id'] ?>"
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