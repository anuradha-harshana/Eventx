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

<main class="explore-page">

<div class="container">

    <header class="explore-header">
        <h1>Explore Events</h1>
        <p>Discover experiences happening around you</p>
    </header>

    <!-- Controls -->
    <div class="explore-controls">

        <input type="text" placeholder="Search events..." class="search-input">

        <select class="filter-select">
            <option>All Categories</option>
            <option>Tech</option>
            <option>Business</option>
            <option>Music</option>
        </select>

        <select class="filter-select">
            <option>Sort by Date</option>
            <option>Most Popular</option>
            <option>Newest</option>
        </select>

    </div>

    <!-- Events Grid -->
    <div class="events-grid">

        <?php foreach ($events as $event): ?>

        <div class="event-card">

            <div class="event-image">
                <img src="<?= SITE_URL . ltrim($event['banner_url'], '/') ?>" alt="">
            </div>

            <div class="event-content">
                <h3><?= htmlspecialchars($event['title']) ?></h3>

                <div class="meta-group">
                    <span>📍 <?= htmlspecialchars($event['location_text']) ?></span>
                    <span>📅 <?= date("F j, Y", strtotime($event['start_at'])) ?></span>
                </div>

                <div class="card-footer">
                    <span class="capacity"><?= $event['capacity'] ?> seats</span>

                    <form method="POST" action="<?= SITE_URL ?>viewEvent">
                        <input type="hidden" value="<?= htmlspecialchars($event['id']) ?>" name="id">
                        <button class="view" type="submit">View Event</button>
                    </form>
                </div>
            </div>

        </div>

        <?php endforeach; ?>

    </div>

    <!-- Pagination -->
    <div class="pagination">
        <button>Previous</button>
        <button class="active">1</button>
        <button>2</button>
        <button>3</button>
        <button>Next</button>
    </div>

</div>

</main>

</body>
</html>
