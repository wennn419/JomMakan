<?php
session_start();

include "db_connect.php";

$mode = $_GET['mode'] ?? 'personalized';

if ($mode === "personalized" && !isset($_SESSION["surprise"])) {

    header("Location: personalized.php");
    exit;

}

if ($mode === "personalized") {

    $budget = $_SESSION["surprise"]["budget"];
    $quality = $_SESSION["surprise"]["quality"];
    $cuisine = $_SESSION["surprise"]["cuisine"];

    $cuisineList = explode(",", $cuisine);

    $sql = "
    SELECT
        foods.*,
        restaurants.restaurant_name,
        restaurants.rating,
        restaurants.cuisine
    FROM foods
    JOIN restaurants
    ON foods.restaurant_id = restaurants.id
    ";

    $conditions = [];

    // Budget
    if ($budget != 999) {
        $conditions[] = "foods.price <= " . (float)$budget;
    }

    // Rating
    if ($quality == "high") {
        $conditions[] = "restaurants.rating >= 4.5";
    }
    elseif ($quality == "medium") {
        $conditions[] = "restaurants.rating >= 4.0";
    }

    // Cuisine
    if (!empty($cuisine)) {

        $cuisineArray = explode(",", $cuisine);

        $cuisineConditions = [];

        foreach ($cuisineArray as $item) {

            $item = trim($item);

            if (strtolower($item) != "any") {

                $cuisineConditions[] =
                    "restaurants.cuisine='"
                    . $conn->real_escape_string($item)
                    . "'";

            }

        }

        if (!empty($cuisineConditions)) {

            $conditions[] =
                "(" . implode(" OR ", $cuisineConditions) . ")";

        }

    }

    if (!empty($conditions)) {

        $sql .= " WHERE " . implode(" AND ", $conditions);

    }

    $sql .= " ORDER BY RAND() LIMIT 4";

}

else {

    $budget = "";
    $quality = "";
    $cuisine = "";
    $cuisineList = [];

    $sql = "
    SELECT
        foods.*,
        restaurants.restaurant_name,
        restaurants.rating,
        restaurants.cuisine
    FROM foods
    JOIN restaurants
    ON foods.restaurant_id = restaurants.id
    ORDER BY RAND()
    LIMIT 4
    ";

}

$result = $conn->query($sql);

$foods = [];
$previousFoodIds = [];

if ($result && $result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        $previousFoodIds[] = $row['id'];
        $foods[] = $row;

    }

    $_SESSION['last_surprise_ids'] = $previousFoodIds;

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Surprise Result</title>

<link rel="stylesheet" href="css/result.css">

</head>

<body>



<?php

if($result->num_rows > 0){

?>

<div class="result-wrapper">

    <a href="surprise.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i>
        ← Choose another mode
    </a>

    <?php if ($mode === "personalized") { ?>

<h1>Your Personalized Picks ✨</h1>

<?php } else { ?>

<h1>Today's Random Picks 🎲</h1>

<?php } ?>

    <?php if ($mode === "personalized") { ?>

<p class="subtitle">

    Budget RM <?php echo htmlspecialchars($budget); ?>

    •

    <?php echo htmlspecialchars(implode(", ", $cuisineList)); ?>

    •

    <?php
    if($quality == "high"){
        echo "Top Rated";
    }elseif($quality == "medium"){
        echo "Good Rating";
    }else{
        echo "Any Rating";
    }
    ?>

</p>

<?php } else { ?>

<p class="subtitle">
    No rules. Just delicious food.
</p>

<?php } ?>

    <div class="result-grid">

    <?php

    foreach ($foods as $food) {

    ?>

<div
    class="food-card reveal-card"
    data-id="<?php echo $food['id']; ?>"
>

    <img
        src="<?php echo htmlspecialchars($food['image']); ?>"
        alt="<?php echo htmlspecialchars($food['food_name']); ?>"
    >

    <div class="food-info">

        <h3>
            <?php echo htmlspecialchars($food['food_name']); ?>
        </h3>

        <p class="restaurant">
            <?php echo htmlspecialchars($food['restaurant_name']); ?>
        </p>

        <div class="food-footer">

            <span class="rating">
                ⭐ <?php echo number_format($food['rating'], 1); ?>
            </span>

            <span class="price">
                RM <?php echo number_format($food['price'], 2); ?>
            </span>

        </div>

    </div>

</div>

    <?php

    }

    ?>

    </div>

</div>

<div class="result-actions">

    <?php if ($mode === "personalized") { ?>

<a href="personalized.php" class="adjust-btn">
    Adjust Preferences
</a>

<?php } else { ?>

<a href="surprise.php" class="adjust-btn">
    Back to Surprise
</a>

<?php } ?>

<?php if ($mode === "personalized") { ?>

<a href="result.php?mode=personalized" class="surprise-btn">
    🎲 Surprise Again
</a>

<?php } else { ?>

<a href="result.php?mode=total" class="surprise-btn">
    🎲 Surprise Again
</a>

<?php } ?>

</div>

<?php

}else{

?>

<h2>No recommendation found.</h2>

<?php

}

?>

<script src="js/result.js"></script>

</body>

</html>