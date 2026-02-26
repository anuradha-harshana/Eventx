<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse & Explore Events</title>

    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/explore.css">
    <style>
        .browse-tabs {
            display: flex;
            gap: 0;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .browse-tab {
            padding: 1rem 1.5rem;
            border: none;
            background: none;
            font-size: 1rem;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .browse-tab:hover {
            color: #667eea;
        }

        .browse-tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }

        .filter-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .search-input,
        .filter-row select {
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
        }

        .search-input:focus,
        .filter-row select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        @media (max-width: 768px) {
            .filter-row {
                grid-template-columns: 1fr;
            }

            .browse-tabs {
                flex-wrap: wrap;
            }

            .browse-tab {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }

        .no-events {
            text-align: center;
            padding: 3rem 2rem;
            color: #666;
        }

        .no-events-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .no-events h3 {
            margin: 1rem 0;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<main class="explore-wrapper">

<div class="container">

    <header class="page-header">
        <h1>🔍 Browse & Explore Events</h1>
        <p>Discover upcoming events in your area</p>
    </header>

    <!-- BROWSE TABS -->
    <div class="browse-tabs">
        <a href="<?= SITE_URL ?>explore?filter=all" class="browse-tab <?= (!isset($_GET['filter']) || $_GET['filter'] === 'all') ? 'active' : '' ?>">
            📅 All Events
        </a>
        <a href="<?= SITE_URL ?>explore?filter=upcoming" class="browse-tab <?= (isset($_GET['filter']) && $_GET['filter'] === 'upcoming') ? 'active' : '' ?>">
            ⏰ Upcoming
        </a>
        <a href="<?= SITE_URL ?>explore?filter=past" class="browse-tab <?= (isset($_GET['filter']) && $_GET['filter'] === 'past') ? 'active' : '' ?>">
            ✓ Past Events
        </a>
        <a href="<?= SITE_URL ?>explore?filter=popular" class="browse-tab <?= (isset($_GET['filter']) && $_GET['filter'] === 'popular') ? 'active' : '' ?>">
            🔥 Most Popular
        </a>
    </div>

    <!-- FILTER BAR -->
    <form method="GET" action="<?= SITE_URL ?>explore" style="margin-bottom: 2rem;">
        <div class="filter-row">
            <input 
                type="text" 
                name="search" 
                placeholder="🔎 Search events by name or keyword..." 
                class="search-input"
                value="<?= htmlspecialchars($searchTerm ?? '') ?>">

            <select name="category">
                <option value="">All Categories</option>
                <?php if(!empty($categories)): ?>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($selectedCategory == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <input 
                type="text" 
                name="location" 
                placeholder="📍 Search by location..." 
                value="<?= htmlspecialchars($selectedLocation ?? '') ?>">

            <button type="submit" style="padding: 0.75rem 1.5rem; background: #667eea; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                Filter
            </button>
        </div>
    </form>

    <!-- EVENTS GRID -->
    <?php if(!empty($events)): ?>
        <div class="events-grid">

            <?php foreach ($events as $event): ?>
            <div class="event-card">

                <div class="event-image">
                    <img src="<?= SITE_URL . ltrim($event['banner_url'] ?? 'assets/images/default-event.jpg', '/') ?>" alt="<?= htmlspecialchars($event['title']) ?>" onerror="this.src='<?= SITE_URL ?>assets/images/default-event.jpg'">
                </div>

                <div class="event-info">

                    <div class="event-date">
                        <?= strtoupper(date("M", strtotime($event['start_at']))) ?>
                        <span><?= date("d", strtotime($event['start_at'])) ?></span>
                    </div>

                    <div class="event-details">
                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                        
                        <p style="font-size: 0.85rem; color: #999; margin: 0.25rem 0;">
                            📂 <?= htmlspecialchars($event['category_name'] ?? 'Uncategorized') ?>
                        </p>
                        
                        <p class="location">📍 <?= htmlspecialchars($event['location_text']) ?></p>
                        
                        <p class="capacity">
                            👥 <?= $event['current_participants'] ?? 0 ?> attending  | 
                            🎫 <?= $event['capacity'] ?? 'Unlimited' ?> capacity
                        </p>

                        <form method="POST" action="<?= SITE_URL ?>viewEvent">
                            <input type="hidden" value="<?= htmlspecialchars($event['id']) ?>" name="id">
                            <button type="submit" class="btn-view">View Event Details →</button>
                        </form>
                    </div>

                </div>

            </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <div class="no-events">
            <div class="no-events-icon">📭</div>
            <h3>No Events Found</h3>
            <p>Try adjusting your filters or search terms</p>
            <a href="<?= SITE_URL ?>explore" style="color: #667eea; text-decoration: none; font-weight: 600;">← Back to All Events</a>
        </div>
    <?php endif; ?>

</div>

</main>

</body>
</html>
