<?php

require_once "includes/auth.php";
include "db_connect.php";

$currentPage = 'recently';

$sql = "
SELECT
    foods.*,
    restaurants.restaurant_name,
    restaurants.rating,
    MAX(recently_viewed.viewed_at) AS viewed_at
FROM recently_viewed
JOIN foods
    ON recently_viewed.food_id = foods.id
JOIN restaurants
    ON foods.restaurant_id = restaurants.id
GROUP BY foods.id
ORDER BY viewed_at DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recently Viewed</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/recently.css">
</head>

<body>

<div class="dashboard">

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content">

<h1>🕒 Recently Viewed</h1>

<?php if ($result->num_rows == 0): ?>

<div class="empty-state">

    <div class="empty-icon">🕒</div>

    <h2>No recently viewed foods</h2>

    <p>
        Start exploring foods and they will appear here.
    </p>

    <a href="search.php" class="explore-btn">
        Explore Foods
    </a>

</div>

<?php endif; ?>


<?php if ($result->num_rows > 0): ?>

<div class="food-grid">

    <?php while ($row = $result->fetch_assoc()): ?>

        <div class="result-card">

    <img
        src="<?= htmlspecialchars($row['image']) ?>"
        alt="<?= htmlspecialchars($row['food_name']) ?>">

    <div class="result-info">

        <h2><?= htmlspecialchars($row['food_name']) ?></h2>

        <p><?= htmlspecialchars($row['restaurant_name']) ?></p>

        <div class="info-bottom">

            ⭐ <?= htmlspecialchars($row['rating']) ?>

            <p class="price">
                RM <?= number_format($row['price'], 2) ?>
            </p>

        </div>

    </div>

   <div class="card-actions">

    <button
        class="compare-btn"
        onclick="window.location.href='food.php?id=<?= $row['id'] ?>&from=recently'">

        View Details →

    </button>

</div>

</div>

    <?php endwhile; ?>

</div>

<?php endif; ?>

    </main>

</div>

<script src="js/dashboard.js"></script>

</body>
</html>