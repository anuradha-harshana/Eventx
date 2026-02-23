<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Explore Events</title>

    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/explore.css">
</head>
<body>

<main class="explore-wrapper">

<div class="container">

    <header class="page-header">
        <h1>Events in Your Area</h1>
        <p>Explore what's happening near you</p>
    </header>

    <!-- FILTER BAR -->
    <div class="filter-row">

        <input type="text" placeholder="Search events" class="search-input">

        <select>
            <option>All Categories</option>
            <option>Tech</option>
            <option>Business</option>
            <option>Music</option>
        </select>

        <select>
            <option>Date</option>
            <option>Today</option>
            <option>This Week</option>
            <option>This Month</option>
        </select>

    </div>

    <!-- EVENTS GRID -->
    <div class="events-grid">

        <?php foreach ($events as $event): ?>
        <div class="event-card">

            <div class="event-image">
                <img src="<?= SITE_URL . ltrim($event['banner_url'], '/') ?>" alt="">
            </div>

            <div class="event-info">

                <div class="event-date">
                    <?= strtoupper(date("M", strtotime($event['start_at']))) ?>
                    <span><?= date("d", strtotime($event['start_at'])) ?></span>
                </div>

                <div class="event-details">
                    <h3><?= htmlspecialchars($event['title']) ?></h3>
                    <p class="location"><?= htmlspecialchars($event['location_text']) ?></p>
                    <p class="capacity"><?= $event['capacity'] ?> seats available</p>

                    <form method="POST" action="<?= SITE_URL ?>viewEvent">
                        <input type="hidden" value="<?= htmlspecialchars($event['id']) ?>" name="id">
                        <button type="submit" class="btn-view">View Event</button>
                    </form>
                </div>

            </div>

        </div>
        <?php endforeach; ?>

    </div>

</div>

</main>

</body>
</html>
