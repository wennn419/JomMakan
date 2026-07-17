<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="navbar">

    <div class="logo">
        <h1>JomMakan</h1>
    </div>

    <div class="nav-right">

        <?php if(!isset($_SESSION["user_id"])): ?>

    <a href="login.php">

        <button class="login-btn">

            Log In

        </button>

    </a>

<?php endif; ?>

    </div>

</header>