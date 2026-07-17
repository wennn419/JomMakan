<?php

require_once "../includes/admin_auth.php";

$message = "";

// 获取restaurant
$restaurantResult = mysqli_query($conn, "
    SELECT id, restaurant_name
    FROM restaurants
    ORDER BY restaurant_name
");

// 处理POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $restaurant_id = $_POST["restaurant_id"];
    $food_name     = trim($_POST["food_name"]);
    $category      = trim($_POST["category"]);
    $price         = $_POST["price"];
    $image         = trim($_POST["image"]);
    $description   = trim($_POST["description"]);

    $stmt = $conn->prepare("
        INSERT INTO foods
        (restaurant_id, food_name, category, price, image, description)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "issdss",
        $restaurant_id,
        $food_name,
        $category,
        $price,
        $image,
        $description
    );

    if($stmt->execute()){

        header("Location: ../../search.php?success=food_added");
        exit;

    }else{

        $message = "Failed to add food.";

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Food</title>

    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<div class="admin-form">

    <h1>Add Food</h1>

    <?php if($message): ?>

        <p class="success-message"><?= $message ?></p>

    <?php endif; ?>

    <form method="POST">

        <label>Restaurant</label>

        <select name="restaurant_id" required>

            <?php while($restaurant = mysqli_fetch_assoc($restaurantResult)): ?>

                <option value="<?= $restaurant['id'] ?>">

                    <?= htmlspecialchars($restaurant['restaurant_name']) ?>

                </option>

            <?php endwhile; ?>

        </select>

        <label>Food Name</label>

        <input
            type="text"
            name="food_name"
            required
        >

        <label>Category</label>

        <input
            type="text"
            name="category"
            required
        >

        <label>Price (RM)</label>

        <input
            type="number"
            step="0.01"
            name="price"
            required
        >

        <label>Image Path</label>

        <input
            type="text"
            name="image"
            placeholder="image/foods/laksa.png"
            required
        >

        <label>Description</label>

        <textarea
            name="description"
            rows="5"
            required
        ></textarea>

        <button type="submit">

            Save Food

        </button>

    </form>

</div>

</body>

</html>