<?php
session_start();

$mode = $_GET['mode'] ?? 'personalized';

// First time: coming from Personalized
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $budget = $_POST["budget"] ?? "";
    $quality = $_POST["quality"] ?? "";
    $cuisine = $_POST["cuisine"] ?? "";

    // Basic validation
    if (
        empty($budget) ||
        empty($quality) ||
        empty($cuisine)
    ) {
        header("Location: personalized.php");
        exit;
    }

    // Save into Session
    $_SESSION["surprise"] = [
        "budget" => $budget,
        "quality" => $quality,
        "cuisine" => $cuisine
    ];

}
// Personalized mode
elseif ($mode === "personalized") {

    if (!isset($_SESSION["surprise"])) {

        header("Location: personalized.php");
        exit;

    }

}

// Total Surprise mode
elseif ($mode === "total") {

    // No session needed.
    // Continue to loading animation.

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="css/loading.css">

<title>Finding Your Food...</title>

</head>

<body>

<div class="container">

<div class="loader"></div>

<h2>Finding your perfect meal</h2>

<p>

Please wait

<span class="dot">.</span>

<span class="dot">.</span>

<span class="dot">.</span>

</p>

</div>

<script>
    const mode = "<?php echo $mode; ?>";
</script>

<script src="js/loading.js"></script>

</body>

</html>