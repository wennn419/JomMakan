<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="navbar">

    <div class="logo">
        <h1>JomMakan</h1>
    </div>

    <nav>
        <ul class="nav-links">

            <li><a href="index.php">Home</a></li>

        </ul>
    </nav>

    <div class="nav-right">

        <a href="favourites.php">
            <img src="image/icons/heart.png" alt="Favourite">
        </a>

        <a href="#">
            <img src="image/icons/notification.png" alt="Notification">
        </a>

        <?php if(!isset($_SESSION["user_id"])): ?>

    <a href="login.php">

        <button class="login-btn">

            Log In

        </button>

    </a>

<?php endif; ?>

    </div>

</header>