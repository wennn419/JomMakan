<?php $currentPage = $currentPage ?? ''; ?>
    
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-header">
            <div class="logo">
                <span class="logo-text">JomMakan</span>
            </div>
            <button id="toggle-btn" title="Toggle sidebar">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <div class="sidebar-menu">
            <!-- home gold -->
            <a href="home.php" class="menu-item <?= $currentPage == 'home' ? 'active' : '' ?>">
            <img src="<?= $currentPage == 'home'
                ? 'image/dashboard/home-gold.png'
                : 'image/dashboard/home.png' ?>"
                alt="Home">
            <span class="label">Home</span>
            </a>
            <!-- original -->
            <a href="search.php" class="menu-item <?= $currentPage == 'search' ? 'active' : '' ?>">
            <img src="<?= $currentPage == 'search'
                ? 'image/dashboard/search-gold.png'
                : 'image/dashboard/search.png' ?>"
                alt="Search">
                <span class="label">Search</span>
            </a>
            <a href="favourites.php" class="menu-item <?= $currentPage == 'favourites' ? 'active' : '' ?>">
            <img src="<?= $currentPage == 'favourites'
                ? 'image/icons/heart-gold.png'
                : 'image/icons/heart.png' ?>"
                alt="Favourites">
            <span class="label">Favourites</span>
            </a>
            <a href="recently.php" class="menu-item <?= $currentPage == 'recently' ? 'active' : '' ?>">
            <img src="<?= $currentPage == 'recently'
                ? 'image/dashboard/recently-gold.png'
                : 'image/dashboard/recently.png' ?>"
                alt="Recently">
                <span class="label">Recently Viewed</span>
            </a>
            <a href="surprise.php" class="menu-item <?= $currentPage == 'surprise' ? 'active' : '' ?>">
            <img src="<?= $currentPage == 'surprise'
                ? 'image/dashboard/surprise-gold.png'
                : 'image/dashboard/surprise.png' ?>"
                alt="Surprise">
                <span class="label">Surprise Me</span>
            </a>
        </div>

        <div class="sidebar-divider"></div>

        <div class="sidebar-bottom">
            <a href="profile.php" class="menu-item <?= $currentPage == 'profile' ? 'active' : '' ?>">
                <i class="fa-solid fa-user"></i>
                <span class="label">Profile</span>
            </a>
            <a href="about.php" class="menu-item <?= $currentPage == 'about' ? 'active' : '' ?>">
                <i class="fa-solid fa-circle-question"></i>
                <span class="label">About</span>
            </a>

<!-- logout -->
<?php if(isset($_SESSION["user_id"])): ?>

    <a href="logout.php" class="menu-item logout-item">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span class="label">Log Out</span>
    </a>

    <?php else: ?>

        <a href="login.php" class="menu-item login-item">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span class="label">Log In</span>
        </a>

    <?php endif; ?>
        </div>

    </aside>