<?php

session_start();

require_once "db_connect.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>JomMakan</title>

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>

<!-- navigation -->
    <?php include "includes/navbar.php"; ?>

<!-- Main Content -->
   <main>

<?php

// Available budget options
$budgets = [8, 10, 12, 15, 20];

// Randomly select one budget
$selectedBudget = $budgets[array_rand($budgets)];

// Get 3 random foods within the selected budget
$sql = "
SELECT
    f.id,
    f.food_name,
    f.price,
    f.image,
    r.restaurant_name
FROM foods f
INNER JOIN restaurants r
ON f.restaurant_id = r.id
WHERE f.price <= $selectedBudget
ORDER BY RAND()
LIMIT 3
";

$result = mysqli_query($conn, $sql);

$budgetFoods = [];

while ($row = mysqli_fetch_assoc($result)) {

    $budgetFoods[] = $row;

}

$countSql = "
SELECT COUNT(*) AS total
FROM foods
WHERE price <= $selectedBudget
";

$countResult = mysqli_query($conn, $countSql);

$totalMeals = mysqli_fetch_assoc($countResult);

// ==============================
// Popular Today
// ==============================

$popularSql = "
SELECT
    f.id,
    f.food_name,
    f.price,
    f.image,
    r.restaurant_name,
    r.rating
FROM foods f
INNER JOIN restaurants r
    ON f.restaurant_id = r.id
ORDER BY r.rating DESC
LIMIT 4
";

$popularResult = mysqli_query($conn, $popularSql);

$popularFoods = [];

while ($row = mysqli_fetch_assoc($popularResult)) {

    $popularFoods[] = $row;

}
?>

<!-- ================= HERO ================= -->

<section class="hero">

    <!-- LEFT SIDE -->

    <div class="hero-left">

        <div class="hero-tag">

            ❤️ For Students. For Young Adults. For You.

        </div>

        <h1 class="hero-title">

            Good Food <br>

            Doesn't Have <br>

            to Be <span>Expensive.</span>

        </h1>

        <p class="hero-description">

            Discover delicious meals within your budget.<br>

            Compare prices effortlessly and make smarter<br>

            food decisions every day.

        </p>

        <form action="search.php" method="GET" class="hero-search-box">

    <div class="hero-search-input">

        <i class="fa-solid fa-magnifying-glass"></i>

        <input
            type="text"
            name="keyword"
            placeholder="Search meals."
        >

    </div>

    <button type="submit">

        Search

    </button>

</form>

        <div class="quick-search">

            <span>Try:</span>

            <a href="#">Chicken Rice</a>

            <span class="dot">•</span>

            <a href="#">Laksa</a>

            <span class="dot">•</span>

            <a href="#">Pizza</a>

        </div>

    </div>



    <!-- RIGHT SIDE -->

    <div class="hero-right">

        <div class="hero-budget-card">

            <div class="hero-budget-header">

                <h3>Today's Pick</h3>

<h1>
    RM <?php echo number_format($selectedBudget, 2); ?>
</h1>

            </div>

            <div class="hero-budget-subtitle">

                Recommended meals within your budget.

            </div>



<?php foreach($budgetFoods as $food){ ?>

<a
    href="food.php?id=<?php echo $food['id']; ?>"
    class="budget-item-link"
>

    <div class="budget-item">

        <img
            src="<?php echo htmlspecialchars($food['image']); ?>"
            alt="<?php echo htmlspecialchars($food['food_name']); ?>"
        >

        <div class="budget-info">

            <h4>

                <?php echo htmlspecialchars($food['food_name']); ?>

            </h4>

            <p>

                <?php echo htmlspecialchars($food['restaurant_name']); ?>

            </p>

        </div>

        <div class="budget-price">

            RM <?php echo number_format($food['price'],2); ?>

        </div>

    </div>

</a>

<?php } ?>


<a
    href="search.php?budget=<?php echo $selectedBudget; ?>"
    class="hero-budget-footer"
>

    Explore More

    <i class="fa-solid fa-arrow-right"></i>

</a>

        </div>

    </div>

</section>

<!-- =============== END HERO =============== -->

<section class="usp-section">

    <h2>Food decisions made simple.</h2>

    <p class="usp-subtitle">
        Everything you need to discover your next meal.
    </p>

    <div class="usp-container">

    <!-- Budget Friendly -->
<a href="search.php" class="usp-item-link">

<div class="usp-item">

    <div class="usp-icon">
        <img src="image/icons/money.png" alt="money">
    </div>

    <h3>Budget Friendly</h3>

    <p>
        Discover delicious meals
        that fit your budget.
    </p>

    <span class="usp-btn">

    Explore →

    </span>

</div>
</a>

<!-- Surprise Me -->
<a href="surprise.php" class="usp-item-link">

<div class="usp-item featured">

    <div class="usp-icon">
        <img src="image/surprise/dice.png" alt="dice">
    </div>

    <h3>Surprise Me</h3>

    <p>
        Can't decide?
        Let JomMakan pick for you.
    </p>

    <span class="usp-btn">

    Try Now →

    </span>
</div>
</a>

<!-- Compare Prices -->
<a href="compare_home.php" class="usp-item-link">

<div class="usp-item">

    <div class="usp-icon">
        <img src="image/icons/compare.png" alt="compare">
    </div>

    <h3>Compare Prices</h3>

    <p>
        Compare the prices of the same food
        across different restaurants.
    </p>

    <!-- span 是显示 套动画 套css -->
    <span class="usp-btn">

    Explore →

    </span>

</div>
</a>

    </div>

</section>

<section class="popular">

    <div class="section-title">

        <h2>Popular Today</h2>

        <a href="search.php" class="see-all-btn">

        See All

        <i class="fa-solid fa-arrow-right"></i>

        </a>

    </div>

    <div class="card-container">

        <?php foreach ($popularFoods as $food) { ?>

        <a href="food.php?id=<?php echo $food['id']; ?>" class="food-card-link">

        <div class="food-card">

        <div class="card-image">

            <img
                src="<?php echo htmlspecialchars($food['image']); ?>"
                alt="<?php echo htmlspecialchars($food['food_name']); ?>"
            >

            <button class="favorite-btn">

                <img src="image/icons/heart.png" alt="Favourite">

            </button>

        </div>

        <div class="card-content">

            <h3>

                <?php echo htmlspecialchars($food['food_name']); ?>

            </h3>

            <h4>

                RM <?php echo number_format($food['price'], 2); ?>

            </h4>

            <div class="rating">

                ⭐

                <span>

                    <?php echo number_format($food['rating'], 1); ?>

                </span>

            </div>

            <span>

                📍 <?php echo htmlspecialchars($food['restaurant_name']); ?>

            </span>

            <div class="compare-link">

                View Details →

            </div>

        </div>

        </div>

        </a>

        <?php } ?>
    </div>

</section>

</main>

    <script src="js/script.js"></script>

</body>

</html>