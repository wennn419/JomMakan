<?php

require_once "../includes/admin_auth.php";

$message = "";

if (!isset($_GET["id"])) {

    header("Location: ../../search.php");
    exit;

}

$id = (int)$_GET["id"];

$stmt = $conn->prepare("
    SELECT *
    FROM foods
    WHERE id = ?
");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$food = $result->fetch_assoc();

if (!$food) {

    die("Food not found.");

}

$restaurantResult = mysqli_query($conn,"
    SELECT id, restaurant_name
    FROM restaurants
    ORDER BY restaurant_name
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $restaurant_id = $_POST["restaurant_id"];
    $food_name     = trim($_POST["food_name"]);
    $category      = trim($_POST["category"]);
    $price         = $_POST["price"];
    $image         = trim($_POST["image"]);
    $description   = trim($_POST["description"]);

    $stmt = $conn->prepare("
        UPDATE foods
        SET

            restaurant_id = ?,
            food_name = ?,
            category = ?,
            price = ?,
            image = ?,
            description = ?

        WHERE id = ?
    ");

    $stmt->bind_param(
        "issdssi",
        $restaurant_id,
        $food_name,
        $category,
        $price,
        $image,
        $description,
        $id
    );

    if($stmt->execute()){

        header("Location: ../../search.php?success=food_updated");

        exit;

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

    <h1>Edit Food</h1>

    <?php if($message): ?>

        <p class="success-message"><?= $message ?></p>

    <?php endif; ?>

    <form method="POST">

        <label>Restaurant</label>

        <select name="restaurant_id" required>

            <?php while($restaurant = mysqli_fetch_assoc($restaurantResult)): ?>

                <option
                value="<?= $restaurant['id'] ?>"
                <?= $restaurant['id'] == $food['restaurant_id'] ? "selected" : "" ?>
                >
                <?= htmlspecialchars($restaurant['restaurant_name']) ?>
                </option>

            <?php endwhile; ?>

        </select>

        <label>Food Name</label>

        <input
        type="text"
        name="food_name"
        value="<?= htmlspecialchars($food['food_name']) ?>">

        <label>Category</label>

        <input
        type="text"
        name="category"
        value="<?= htmlspecialchars($food['category']) ?>">

        <label>Price (RM)</label>

        <input
        type="text"
        name="price"
        value="<?= $food['price'] ?>">

        <label>Image Path</label>

        <input
        type="text"
        name="image"
        value="<?= htmlspecialchars($food['image']) ?>">

        <label>Description</label>

        <textarea
        name="description"><?= htmlspecialchars($food['description']) ?>
        </textarea>
        

        <button type="submit">

            Update Food

        </button>

    </form>

</div>

</body>

</html>