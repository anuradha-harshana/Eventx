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
        <?php if($_SESSION['role'] === 'participant'): ?>
            <aside class="event-sidebar">

                <?php if($event['pricing_type'] === 'free'): ?>

                    <div class="ticket-card">
                        <div class="ticket-price">Free</div>
                        <div class="ticket-info"><?= $event['capacity'] ?> spots available</div>
                        <form id="register-form">
                            <input type="hidden" id="event_id" value="<?= $event['id'] ?>">
                            <button id="register-btn" class="ticket-button" type="button">
                                <?= $isRegistered ? 'Cancel Registration' : 'Register' ?>
                            </button>
                        </form>
                        <div id="message" style="margin-top:10px;color:green;"></div>
                    </div>

                <?php else: ?>

                    <!-- PAID TICKET SELECTION -->
                    <div class="tickets-panel">

                        <div class="tickets-panel-header">
                            <h3>Tickets</h3>
                            <span><?= count($tickets) ?> type<?= count($tickets) !== 1 ? 's' : '' ?> available</span>
                        </div>

                        <?php if (empty($tickets)): ?>
                            <p class="tickets-empty">No tickets available at this time.</p>
                        <?php else: ?>

                            <div class="ticket-types-list">
                                <?php foreach ($tickets as $ticket): ?>
                                    <div class="ticket-type-row" data-id="<?= $ticket['id'] ?>" data-price="<?= $ticket['price'] ?>">
                                        <div class="ticket-type-info">
                                            <div class="ticket-type-name"><?= htmlspecialchars($ticket['name']) ?></div>
                                            <div class="ticket-type-price">$<?= number_format((float)$ticket['price'], 2) ?></div>
                                            <?php if (!empty($ticket['terms'])): ?>
                                                <div class="ticket-type-terms"><?= htmlspecialchars($ticket['terms']) ?></div>
                                            <?php endif; ?>
                                            <div class="ticket-type-avail">
                                                <?php if ($ticket['available_count'] > 0): ?>
                                                    <?= $ticket['available_count'] ?> remaining
                                                <?php else: ?>
                                                    <span class="sold-out">Sold Out</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="ticket-type-action">
                                            <?php if ($ticket['available_count'] > 0): ?>
                                                <div class="qty-selector">
                                                    <button class="qty-btn qty-minus" type="button">−</button>
                                                    <span class="qty-val">0</span>
                                                    <button class="qty-btn qty-plus" type="button" data-max="<?= (int)$ticket['available_count'] ?>">+</button>
                                                </div>
                                            <?php else: ?>
                                                <span class="sold-out-badge">Sold Out</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="tickets-footer">
                                <div class="tickets-total" id="tickets-total">Total: $0.00</div>
                                <button class="ticket-button" id="get-tickets-btn" type="button" disabled>Get Tickets</button>
                                <div id="message" style="margin-top:10px;color:green;"></div>
                            </div>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

            </aside>
        <?php endif; ?> 

    </div>

</main>

</body>
<script>
    const SITE_URL = "<?= SITE_URL ?>";
</script>
<script src="<?= SITE_URL ?>/assets/js/registration.js"></script>

<?php if (isset($event['pricing_type']) && $event['pricing_type'] !== 'free' && !empty($tickets)): ?>
<script>
(function () {
    const rows   = document.querySelectorAll('.ticket-type-row');
    const totalEl = document.getElementById('tickets-total');
    const getBtn  = document.getElementById('get-tickets-btn');

    function recalc() {
        let total = 0;
        let anySelected = false;
        rows.forEach(row => {
            const qty   = parseInt(row.querySelector('.qty-val').textContent, 10);
            const price = parseFloat(row.dataset.price);
            total += qty * price;
            if (qty > 0) anySelected = true;
        });
        totalEl.textContent = 'Total: $' + total.toFixed(2);
        getBtn.disabled = !anySelected;
    }

    rows.forEach(row => {
        const minusBtn = row.querySelector('.qty-minus');
        const plusBtn  = row.querySelector('.qty-plus');
        const qtyVal   = row.querySelector('.qty-val');
        if (!minusBtn || !plusBtn) return;

        const max = parseInt(plusBtn.dataset.max, 10) || 1;

        plusBtn.addEventListener('click', () => {
            let q = parseInt(qtyVal.textContent, 10);
            if (q < max) { q++; qtyVal.textContent = q; }
            minusBtn.disabled = q <= 0;
            plusBtn.disabled  = q >= max;
            recalc();
        });

        minusBtn.addEventListener('click', () => {
            let q = parseInt(qtyVal.textContent, 10);
            if (q > 0) { q--; qtyVal.textContent = q; }
            minusBtn.disabled = q <= 0;
            plusBtn.disabled  = q >= max;
            recalc();
        });

        minusBtn.disabled = true; // starts at 0
    });

    getBtn.addEventListener('click', () => {
        const selections = [];
        rows.forEach(row => {
            const qty = parseInt(row.querySelector('.qty-val').textContent, 10);
            if (qty > 0) selections.push({ ticket_id: row.dataset.id, quantity: qty });
        });

        const msgEl = document.getElementById('message');
        getBtn.disabled = true;
        getBtn.textContent = 'Processing…';

        fetch(SITE_URL + 'payment/initiate', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                event_id: <?= (int)$event['id'] ?>,
                tickets:  selections
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                msgEl.textContent  = data.message;
                msgEl.style.color  = '#ef4444';
                getBtn.textContent = 'Get Tickets';
                getBtn.disabled    = false;
            }
        })
        .catch(() => {
            msgEl.textContent  = 'Something went wrong. Please try again.';
            msgEl.style.color  = '#ef4444';
            getBtn.textContent = 'Get Tickets';
            getBtn.disabled    = false;
        });
    });
})();
</script>
<?php endif; ?>
</html>
