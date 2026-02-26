<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/dashboard.css">

<div class="dashboard">

    <header class="dashboard-top">
        <div>
            <h1>Organizer Dashboard</h1>
            <p class="subtitle">Manage and monitor your events</p>
        </div>

        <a href="<?= SITE_URL ?>createEvent" class="btn-primary">
            + Create Event
        </a>
    </header>

    <section class="stats-grid">
        <div class="stat-card">
            <h3><?= $stats['total_events'] ?? 0 ?></h3>
            <p>Total Events</p>
        </div>

        <div class="stat-card">
            <h3><?= $stats['published_events'] ?? 0 ?></h3>
            <p>Published</p>
        </div>

        <div class="stat-card">
            <h3><?= $stats['draft_events'] ?? 0 ?></h3>
            <p>Drafts</p>
        </div>
    </section>

    <section class="card">
        <div class="card-header">
            <h2>My Events</h2>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($events)): ?>
                    <?php foreach($events as $event): ?>
                        <tr>
                            <td><?= htmlspecialchars($event['title']) ?></td>
                            <td><?= date('M d, Y', strtotime($event['start_at'])) ?></td>
                            <td><?= date('M d, Y', strtotime($event['end_at'])) ?></td>
                            <td><?= htmlspecialchars($event['location_text']) ?></td>
                            <td><?= htmlspecialchars($event['capacity']) ?></td>
                            <td>
                                <span class="badge <?= $event['status'] ?>">
                                    <?= htmlspecialchars($event['status']) ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a href="<?= SITE_URL ?>editEvent/<?= $event['id'] ?>" class="btn-small">
                                    Edit
                                </a>
                                <button class="btn-small danger"
                                    onclick="confirmDelete(<?= $event['id'] ?>)">
                                    Delete
                                </button>
                                <button class="btn-small danger"
                                    onclick="goToManageEvent(<?= $event['id'] ?>)">
                                    Manage
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="empty">
                            No events yet. Start by creating one.
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>

<form id="deleteEventForm" method="POST"
      action="<?= SITE_URL ?>organizer/deleteEvent"
      style="display:none;">
    <input type="hidden" name="event_id" id="deleteEventId">
</form>

<script src="<?= SITE_URL ?>/assets/js/eventActions.js"></script>
