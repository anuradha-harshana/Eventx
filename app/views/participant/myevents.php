<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/dashboard.css">

<div class="dashboard">

    <header class="dashboard-top">
        <div>
            <h1>My Registered Events</h1>
            <p class="subtitle">Events you have signed up for</p>
        </div>

        <a href="<?= SITE_URL ?>explore" class="btn-primary">
            + Explore Events
        </a>
    </header>

    <section class="stats-grid">
        <div class="stat-card">
            <h3><?= $total_registered ?? 0 ?></h3>
            <p>Total Registered</p>
        </div>

        <div class="stat-card">
            <h3><?= $upcoming ?? 0 ?></h3>
            <p>Upcoming</p>
        </div>

        <div class="stat-card">
            <h3><?= $past ?? 0 ?></h3>
            <p>Past Events</p>
        </div>
    </section>

    <section class="card">
        <div class="card-header">
            <h2>Registered Events</h2>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                <?php if(!empty($events)): ?>
                    <?php foreach($events as $event): ?>
                        <?php
                            $now = time();
                            $start = strtotime($event['start_at']);
                            $end = strtotime($event['end_at']);

                            if ($end < $now) {
                                $eventStatus = 'past';
                            } elseif ($start > $now) {
                                $eventStatus = 'upcoming';
                            } else {
                                $eventStatus = 'ongoing';
                            }
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($event['title']) ?></td>
                            <td><?= date('M d, Y', $start) ?></td>
                            <td><?= date('M d, Y', $end) ?></td>
                            <td><?= htmlspecialchars($event['location_text']) ?></td>
                            <td>
                                <span class="badge <?= $eventStatus ?>">
                                    <?= ucfirst($eventStatus) ?>
                                </span>
                            </td>
                            <td class="actions">
                                <?php if($eventStatus === 'upcoming'): ?>
                                    <button class="btn-small danger"
                                        onclick="confirmCancel(<?= $event['id'] ?>)">
                                        Cancel
                                    </button>
                                <?php else: ?>
                                    <span class="muted">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="empty">
                            You haven’t registered for any events yet.
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </section>

</div>

<form id="cancelEventForm" method="POST"
      action="<?= SITE_URL ?>cancel"
      style="display:none;">
    <input type="hidden" name="event_id" id="cancelEventId">
</form>

<script>
function confirmCancel(eventId) {
    if(confirm("Are you sure you want to cancel this registration?")) {
        document.getElementById('cancelEventId').value = eventId;
        document.getElementById('cancelEventForm').submit();
    }
}
</script>
