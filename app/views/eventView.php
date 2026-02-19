<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($event['title']) ?></title>

    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/event-view.css">
</head>
<body>

<main class="page-content">

    <!-- HERO IMAGE -->
    <section class="event-hero">
        <img src="<?= SITE_URL . ltrim($event['banner_url'], '/') ?>" alt="Event Banner">
    </section>

    <div class="container event-layout">

        <!-- LEFT CONTENT -->
        <div class="event-main">

            <h1 class="event-title">
                <?= htmlspecialchars($event['title']) ?>
            </h1>

            <div class="event-meta">
                <div>📅 <?= date("F j, Y g:i A", strtotime($event['start_at'])) ?></div>
                <div>📍 <?= htmlspecialchars($event['location_text']) ?></div>
                <div> <?= htmlspecialchars($event['category_name']) ?></div>
            </div>

            <div class="event-section">
                <h3>About this event</h3>
                <p>
                    <?= nl2br(htmlspecialchars($event['description'])) ?>
                </p>
            </div>

            <div class="event-section">
                <h3>Event Details</h3>
                <ul>
                    <li><strong>Capacity:</strong> <?= $event['capacity'] ?> attendees</li>
                    <li><strong>Start:</strong> <?= date("F j, Y g:i A", strtotime($event['start_at'])) ?></li>
                    <li><strong>End:</strong> <?= date("F j, Y g:i A", strtotime($event['end_at'])) ?></li>
                </ul>
            </div>

        </div>

        <!-- RIGHT SIDEBAR -->
        <aside class="event-sidebar">

            <div class="ticket-card">

                <div class="ticket-price">
                    Free
                </div>

                <div class="ticket-info">
                    <?= $event['capacity'] ?> spots available
                </div>

                <button class="ticket-button">
                    Register
                </button>

            </div>

        </aside>

    </div>

</main>

</body>
</html>
