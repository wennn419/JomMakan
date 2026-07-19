<?php
session_start();
include "db_connect.php";

/* ==========================================================
   GET POPULAR COMPARE DISHES
   ----------------------------------------------------------
   Get all compare groups that appear in more than one
   restaurant so users can actually compare prices.
   ========================================================== */



/* ==========================================================
   GET POPULAR COMPARE DISHES
   ----------------------------------------------------------
   Retrieve compare groups with restaurant count and
   price range.
   ========================================================== */

$sql = "

SELECT

    compare_group,

    MIN(food_name) AS food_name,

    MIN(image) AS image,

    COUNT(DISTINCT restaurant_id) AS restaurant_count,

    MIN(price) AS lowest_price,

    MAX(price) AS highest_price

FROM foods

WHERE compare_group IS NOT NULL
AND compare_group <> ''

GROUP BY compare_group

HAVING COUNT(DISTINCT restaurant_id) > 1

ORDER BY restaurant_count DESC

LIMIT 6

";

$result = mysqli_query($conn,$sql);

$currentPage = "compare";
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>JomMakan | Compare Prices</title>

<link rel="preconnect" href="https://fonts.googleapis.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<link rel="stylesheet" href="css/dashboard.css">

<link rel="stylesheet" href="css/compare_home.css">

</head>

<body>

<main class="main-content compare-page">

<!-- ==========================================================
     HERO SECTION
=========================================================== -->

<section class="compare-hero">

    <h1>Compare Prices</h1>

    <p>

        Compare the prices of the same dish across different restaurants
        and discover the best value before you decide.

    </p>

</section>

<!-- ==========================================================
     POPULAR DISHES
=========================================================== -->

<section class="popular-compare">

    <div class="section-title">

        <i class="fa-solid fa-fire"></i>

        <h2>Popular Dishes to Compare</h2>

    </div>

    <div class="compare-grid">

<?php if(mysqli_num_rows($result) > 0): ?>

    <?php while($dish = mysqli_fetch_assoc($result)): ?>

    <div class="compare-card">

        <!-- Food Image -->

        <img
            src="<?= htmlspecialchars($dish['image']) ?>"
            alt="<?= htmlspecialchars($dish['food_name']) ?>">

        <div class="compare-content">

            <!-- Food Name -->

            <h3>

                <?= htmlspecialchars($dish['food_name']) ?>

            </h3>

            <!-- Restaurant Count -->

            <div class="restaurant-count">

                <i class="fa-solid fa-store"></i>

                <span>

                    <?= $dish['restaurant_count'] ?>

                    Restaurants Available

                </span>

            </div>

<!-- Price Range -->

<div class="price-range">

    <i class="fa-solid fa-tag"></i>

    <span>

        RM <?= number_format($dish['lowest_price'],2) ?>

        -

        RM <?= number_format($dish['highest_price'],2) ?>

    </span>

</div>

            <!-- Compare Button  -->

            <a
            href="compare.php?group=<?= urlencode($dish['compare_group']) ?>&from=compare_home"
            class="compare-btn">

                Compare Now

                <i class="fa-solid fa-arrow-right"></i>

            </a>

        </div>

    </div>

    <?php endwhile; ?>

<?php else: ?>

<div class="empty-message">

    <i class="fa-solid fa-circle-info"></i>

    <p>

        No dishes are currently available for comparison.

    </p>

</div>

<?php endif; ?>

    </div>

</section>

<!-- ==========================================================
     BROWSE ALL DISHES
=========================================================== -->

<section class="browse-banner">

    <div class="browse-icon">

        <i class="fa-solid fa-magnifying-glass"></i>

    </div>

    <div class="browse-content">

        <h2>

            Can't find the dish you want?

        </h2>

        <p>

            Browse all dishes and choose one to compare prices from different restaurants.

        </p>

    </div>

    <a href="search.php" class="browse-button">

        Browse All Dishes

        <i class="fa-solid fa-arrow-right"></i>

    </a>

</section>

</main>

</body>

</html>