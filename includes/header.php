<?php
// Get clean current route (last part of URL)
$current_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$current_path = rtrim($current_path, '/');
$current_route = basename($current_path);

// If empty, we are on home
if ($current_route === '' || $current_route === 'Eventx') {
    $current_route = 'home';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME; ?> - Event Management Platform</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>assets/css/navbar.css">
    
    <!-- declare site url for js functions-->
    <script>const SITE_URL = "<?= SITE_URL ?>";</script>
   

</head>

<body>

<header class="nav-wrapper">
    <div class="nav-container">
        <div class="nav-brand">
            <img src="<?= SITE_URL ?>/assets/images/Eventz.png" alt="logo" />
        </div>

        <div class="nav-content">

        <?php if(isset($_SESSION['user_id'])): ?>

            <?php if($_SESSION['role'] === 'admin'): ?>
                <ul class="nav-list">
                    <li><a class="nav-item <?= $current_route === 'adDash' ? 'active' : '' ?>" href="<?= SITE_URL ?>adDash">Dashboard</a></li>
                    <li><a class="nav-item <?= $current_route === 'adAna' ? 'active' : '' ?>" href="<?= SITE_URL ?>adAna">Analytics</a></li>
                    <li><a class="nav-item <?= $current_route === 'adRep' ? 'active' : '' ?>" href="<?= SITE_URL ?>adRep">Reports</a></li>
                    <li><a class="nav-item <?= $current_route === 'adSet' ? 'active' : '' ?>" href="<?= SITE_URL ?>adSet">Settings</a></li>
                </ul>
                <div class="nav-buttons">
                        <a href="<?= SITE_URL ?>logout">
                            <button class="btn-main">Logout</button>
                        </a>
                </div>

            <?php elseif($_SESSION['role'] === 'participant'): ?>
                <ul class="nav-list">
                    <li><a class="nav-item <?= $current_route === 'parDash' ? 'active' : '' ?>" href="<?= SITE_URL ?>parDash">Dashboard</a></li>
                    <li><a class="nav-item <?= $current_route === 'explore' ? 'active' : '' ?>" href="<?= SITE_URL ?>explore">Explore</a></li>
                    <li><a class="nav-item <?= $current_route === 'myEvents' ? 'active' : '' ?>" href="<?= SITE_URL ?>myEvents">My Events</a></li>
                    <li><a class="nav-item <?= $current_route === 'parProf' ? 'active' : '' ?>" href="<?= SITE_URL ?>parProf">Profile</a></li>
                </ul>
                <div class="nav-buttons">
                        <a href="<?= SITE_URL ?>logout">
                            <button class="btn-main">Logout</button>
                        </a>
                </div>

            <?php elseif($_SESSION['role'] === 'organizer'): ?>
                <ul class="nav-list">
                    <li><a class="nav-item <?= $current_route === 'orgDash' ? 'active' : '' ?>" href="<?= SITE_URL ?>orgDash">Dashboard</a></li>
                    <li><a class="nav-item <?= $current_route === 'explore' ? 'active' : '' ?>" href="<?= SITE_URL ?>explore">Explore</a></li>
                    <li><a class="nav-item <?= $current_route === 'market' ? 'active' : '' ?>" href="<?= SITE_URL ?>market">Market</a></li>
                    <li><a class="nav-item <?= $current_route === 'orgSpon' ? 'active' : '' ?>" href="<?= SITE_URL ?>orgSpon">Sponsors</a></li>
                    <li><a class="nav-item <?= $current_route === 'orgAna' ? 'active' : '' ?>" href="<?= SITE_URL ?>orgAna">Analytics</a></li>
                    <li><a class="nav-item <?= $current_route === 'orgProf' ? 'active' : '' ?>" href="<?= SITE_URL ?>orgProf">Profile</a></li>
                </ul>
                <div class="nav-buttons">
                        <a href="<?= SITE_URL ?>logout">
                            <button class="btn-main">Logout</button>
                        </a>
                </div>

            <?php elseif($_SESSION['role'] === 'sponsor'): ?>
                <ul class="nav-list">
                    <li><a class="nav-item <?= $current_route === 'sponDash' ? 'active' : '' ?>" href="<?= SITE_URL ?>sponDash">Dashboard</a></li>
                    <li><a class="nav-item <?= $current_route === 'explore' ? 'active' : '' ?>" href="<?= SITE_URL ?>explore">Explore</a></li>
                    <li><a class="nav-item <?= $current_route === 'market' ? 'active' : '' ?>" href="<?= SITE_URL ?>market">Market</a></li>
                    <li><a class="nav-item <?= $current_route === 'sponAna' ? 'active' : '' ?>" href="<?= SITE_URL ?>sponAna">Analytics</a></li>
                    <li><a class="nav-item <?= $current_route === 'sponProf' ? 'active' : '' ?>" href="<?= SITE_URL ?>sponProf">Profile</a></li>
                </ul>
                <div class="nav-buttons">
                        <a href="<?= SITE_URL ?>logout">
                            <button class="btn-main">Logout</button>
                        </a>
                </div>

            <?php elseif($_SESSION['role'] === 'supplier'): ?>
                <ul class="nav-list">
                    <li><a class="nav-item <?= $current_route === 'suppDash' ? 'active' : '' ?>" href="<?= SITE_URL ?>suppDash">Dashboard</a></li>
                    <li><a class="nav-item <?= $current_route === 'explore' ? 'active' : '' ?>" href="<?= SITE_URL ?>explore">Explore</a></li>
                    <li><a class="nav-item <?= $current_route === 'market' ? 'active' : '' ?>" href="<?= SITE_URL ?>market">Market</a></li>
                    <li><a class="nav-item <?= $current_route === 'suppAna' ? 'active' : '' ?>" href="<?= SITE_URL ?>suppAna">Analytics</a></li>
                    <li><a class="nav-item <?= $current_route === 'suppProf' ? 'active' : '' ?>" href="<?= SITE_URL ?>suppProf">Profile</a></li>
                </ul>
                <div class="nav-buttons">
                        <a href="<?= SITE_URL ?>logout">
                            <button class="btn-main">Logout</button>
                        </a>
                </div>
                
            <?php endif; ?>

        <?php else: ?>
            <ul class="nav-list">
                <li><a class="nav-item <?= $current_route === 'home' ? 'active' : '' ?>" href="<?= SITE_URL ?>">Home</a></li>
                <li><a class="nav-item" href="<?= SITE_URL ?>#features">Features</a></li>
                <li><a class="nav-item" href="<?= SITE_URL ?>#about">About</a></li>
                <li><a class="nav-item" href="<?= SITE_URL ?>#contact">Contact</a></li>
            </ul>
            <div class="nav-buttons">
                    <a href="<?= SITE_URL ?>login">
                        <button class="btn-outline">Login</button>
                    </a>
                    <a href="<?= SITE_URL ?>register">
                        <button class="btn-main">Signup</button>
                    </a>
            </div>
        <?php endif; ?>

            <div class="nav-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

        </div>
    </div>
</header>

<script src="<?= SITE_URL ?>assets/js/activeLinks.js"></script>
</body>
</html>
