<?php
require_once "includes/auth.php";
include "db_connect.php";
$userId = $_SESSION["user_id"];

// Remove favourite
if (isset($_POST['removeFavourite'])) {

    $foodId = (int)$_POST['food_id'];

    $sql = "DELETE FROM favourites
        WHERE food_id = ?
        AND user_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $foodId, $userId);
    $stmt->execute();

    header("Location: favourites.php");
    exit();
}

$currentPage = 'favourites';

$sql = "
SELECT
    favourites.id AS favourite_id,
    foods.id,
    foods.food_name,
    foods.price,
    foods.image,
    restaurants.restaurant_name,
    restaurants.rating
FROM favourites
JOIN foods
    ON favourites.food_id = foods.id
JOIN restaurants
    ON foods.restaurant_id = restaurants.id
WHERE favourites.user_id = ?
ORDER BY favourites.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();

?>

<?php
$currentPage = 'favourites';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Favourites</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/favourites.css">
</head>

<body>

<div class="dashboard">

    <?php include "includes/sidebar.php"; ?>

    <main class="main-content">

        <h1>❤️ My Favourite Foods</h1>

        <p class="favourite-count">
            <?php if ($result->num_rows > 0): ?>
                You have <strong><?= $result->num_rows ?></strong> favourite meal<?= $result->num_rows > 1 ? 's' : '' ?>.
            <?php else: ?>
                You haven't added any favourite meals yet.
            <?php endif; ?>
        </p>

    <?php if ($result->num_rows == 0): ?>

    <div class="empty-state">

    <div class="empty-icon">❤️</div>

    <h2>No favourite foods yet</h2>

    <p>
        Start exploring and save your favourite meals.
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

    <form method="POST" class="remove-form">

    <input
        type="hidden"
        name="food_id"
        value="<?= $row['id'] ?>">

    <button
        type="submit"
        name="removeFavourite"
        class="remove-btn"
        onclick="return confirm('Are you sure you want to remove this food from your favourites?');"
        title="Remove Favourite">

        <img src="image/icons/heart-red.png" alt="Remove Favourite">

    </button>

</form>

    <button
        class="compare-btn"
        onclick="window.location.href='food.php?id=<?= $row['id'] ?>&from=favourites'">

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