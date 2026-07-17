<?php
session_start();

require_once "../includes/admin_auth.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $restaurant_name  = trim($_POST["restaurant_name"]);
    $cuisine          = trim($_POST["cuisine"]);
    $location         = trim($_POST["location"]);
    $rating           = $_POST["rating"];
    $opening_hours    = trim($_POST["opening_hours"]);
    $address        = trim($_POST["address"]);

    $stmt = $conn->prepare("
        INSERT INTO restaurants
        (
            restaurant_name,
            cuisine,
            location,
            rating,
            opening_hours,
            address
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssdss",
        $restaurant_name,
        $cuisine,
        $location,
        $rating,
        $opening_hours,
        $address
    );

    if ($stmt->execute()) {

        header("Location: ../../search.php?success=restaurant_added");
        exit;

    } else {

        $message = "Failed to add restaurant.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Add Restaurant</title>

    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<div class="admin-form">

    <h1>Add Restaurant</h1>

    <?php if($message): ?>

        <p class="success-message"><?= $message ?></p>

    <?php endif; ?>

    <form method="POST">

        <label>Restaurant Name</label>
        <input
            type="text"
            name="restaurant_name"
            required
        >

        <label>Cuisine</label>
        <input
            type="text"
            name="cuisine"
            required
        >

        <label>Rating</label>
        <input
            type="number"
            name="rating"
            step="0.1"
            min="0"
            max="5"
            required
        >

        <label>Location</label>
        <input
            type="text"
            name="location"
            required
        >

        <label>Opening Hours</label>
        <input
            type="text"
            name="opening_hours"
            required
        >

        <label>Full Address</label>
        <input
            type="text"
            placeholder="e.g. 30 Jalan Kebudayaan 1, Taman Universiti, 81300 Skudai, Johor"
            name="address"
        >

        <button type="submit">

            Save Restaurant

        </button>

    </form>

</div>

</body>

</html>