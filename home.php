<?php
session_start();
$currentPage = "home";
require_once "db_connect.php";

$popularResult = mysqli_query($conn, "

SELECT
    id,
    food_name,
    price,
    image,
    restaurant_id

FROM foods

ORDER BY RAND()

LIMIT 10;
");
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

<!-- sidebar -->
    <?php
$currentPage = "home";
include "includes/sidebar.php";
?>

    <main class="main-content">

    <section class="home-search">

    <?php if (isset($_SESSION["username"])) : ?>

<!-- welcome back -->
<div class="welcome-section">

    <h2>
        Welcome back,
        <span>
            <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </span>
        👋
    </h2>

    <p>
        What would you like to eat today?
    </p>

</div>

<?php endif; ?>

<form action="search.php" method="GET" class="search-box">

        <img
            src="image/dashboard/search.png"
            class="search-icon"
            alt="Search">

        <input
            type="text"
            name="keyword"
            placeholder="Search food or restaurant...">

    </form>
</section>


    <section class="popular-section">

    <div class="section-title">

        <h2>Recommend For You</h2>

    </div>

<div class="popular-grid">

<?php while($food = mysqli_fetch_assoc($popularResult)): ?>

<a href="food.php?id=<?= $food['id'] ?>" class="food-card">

    <img
        src="<?= htmlspecialchars($food['image']) ?>"
        alt="<?= htmlspecialchars($food['food_name']) ?>">

    <div class="food-content">

        <h3><?= htmlspecialchars($food['food_name']) ?></h3>

        <p class="food-price">
            RM <?= number_format($food['price'],2) ?>
        </p>

    </div>

</a>

<?php endwhile; ?>

</div>

</section>

</main>

</div>

<script src="js/dashboard.js"></script>

</body>
</html>