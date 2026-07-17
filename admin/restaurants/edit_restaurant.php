<?php
session_start();
require_once "../includes/admin_auth.php";

$message = "";

if (!isset($_GET["id"])) {

    header("Location: ../../search.php");
    exit;

}

$id = (int)$_GET["id"];

$stmt = $conn->prepare("
    SELECT *
    FROM restaurants
    WHERE id = ?
");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$restaurant = $result->fetch_assoc();

if (!$restaurant) {

    die("Restaurant not found.");

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $restaurant_name = trim($_POST["restaurant_name"]);
    $cuisine         = trim($_POST["cuisine"]);
    $location        = trim($_POST["location"]);
    $rating          = $_POST["rating"];
    $opening_hours   = trim($_POST["opening_hours"]);
    $address       = trim($_POST["address"]);

    $stmt = $conn->prepare("
        UPDATE restaurants
        SET
            restaurant_name = ?,
            cuisine = ?,
            location = ?,
            rating = ?,
            opening_hours = ?,
            address = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "sssdssi",
        $restaurant_name,
        $cuisine,
        $location,
        $rating,
        $opening_hours,
        $address,
        $id
    );

    if ($stmt->execute()) {

        header("Location: ../../search.php?success=restaurant_updated");
        exit;

    } else {

        $message = "Failed to update restaurant.";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Edit Restaurant</title>

    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<div class="admin-form">

    <h1>Edit Restaurant</h1>

    <?php if($message): ?>

        <p class="success-message"><?= $message ?></p>

    <?php endif; ?>

    <form method="POST">

        <label>Restaurant Name</label>
        <input
            type="text"
            name="restaurant_name"
            value="<?= htmlspecialchars($restaurant['restaurant_name']) ?>"
            required
        >

        <label>Cuisine</label>
        <input
            type="text"
            name="cuisine"
            value="<?= htmlspecialchars($restaurant['cuisine']) ?>"
            required
        >

        <label>Rating</label>
        <input
            type="number"
            name="rating"
            step="0.1"
            min="0"
            max="5"
            value="<?= $restaurant['rating'] ?>"
            required
        >

        <label>Location</label>
        <input
            type="text"
            name="location"
            value="<?= htmlspecialchars($restaurant['location']) ?>"
            required
        >

        <label>Opening Hours</label>
        <input
            type="text"
            name="opening_hours"
            value="<?= htmlspecialchars($restaurant['opening_hours']) ?>"
            required
        >

        <label>Full Address</label>
        <input
            type="text"
            name="address"
            placeholder="e.g. 30 Jalan Kebudayaan 1, Taman Universiti, 81300 Skudai, Johor"
            value="<?= htmlspecialchars($restaurant['address']) ?>"
        >
        

        <button type="submit">

            Update Restaurant

        </button>

    </form>

</div>

</body>

</html>