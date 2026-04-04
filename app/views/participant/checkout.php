<style>
/* ── Checkout page ──────────────────────────────────────────── */
.checkout-wrapper {
    max-width: 980px;
    margin: 0 auto;
    padding: 36px 20px 60px;
    font-family: 'Inter', sans-serif;
}

.sandbox-notice {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 10px;
    padding: 12px 20px;
    font-size: 13px;
    font-weight: 600;
    color: #92400e;
    letter-spacing: 0.3px;
    margin-bottom: 32px;
    text-align: center;
}

.checkout-grid {
    display: grid;
    grid-template-columns: 5fr 6fr;
    gap: 28px;
    align-items: start;
}

/* ── Order Summary ──────────────────────────────────────────── */
.checkout-summary {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    padding: 28px;
}

.co-section-title {
    font-size: 19px;
    font-weight: 700;
    margin-bottom: 22px;
    color: #111827;
}

.event-mini-card {
    display: flex;
    gap: 14px;
    align-items: flex-start;
    padding-bottom: 22px;
    border-bottom: 1px solid #f3f4f6;
    margin-bottom: 22px;
}

.event-mini-card img {
    width: 76px;
    height: 58px;
    object-fit: cover;
    border-radius: 8px;
    flex-shrink: 0;
    border: 1px solid #e5e7eb;
}

.event-mini-title {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 5px;
    line-height: 1.3;
}

.event-mini-detail {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 2px;
}

.breakdown-label {
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 12px;
}

.breakdown-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 9px 0;
    border-bottom: 1px solid #f9fafb;
    font-size: 14px;
    color: #374151;
}

.breakdown-row:last-of-type {
    border-bottom: none;
}

.breakdown-qty {
    display: inline-block;
    background: #f3f4f6;
    color: #6b7280;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 4px;
    margin-right: 7px;
}

.breakdown-subtotal {
    font-weight: 600;
    color: #111827;
    white-space: nowrap;
}

.breakdown-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 2px solid #111827;
    font-size: 18px;
    font-weight: 800;
    color: #111827;
}

/* ── Payment Form ───────────────────────────────────────────── */
.checkout-payment {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    padding: 28px;
}

/* Credit card visual */
.card-visual {
    background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 55%, #7c3aed 100%);
    border-radius: 14px;
    padding: 22px 24px 20px;
    margin-bottom: 26px;
    color: #fff;
    position: relative;
    box-shadow: 0 12px 32px rgba(37, 99, 235, 0.28);
    overflow: hidden;
}

.card-visual::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 160px; height: 160px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}

.card-visual::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 30px;
    width: 200px; height: 200px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}

.card-chip {
    width: 38px;
    height: 28px;
    background: linear-gradient(135deg, #d4b896, #f0d9b5, #c8a87b);
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid rgba(255,255,255,0.3);
    position: relative;
    z-index: 1;
}

.card-number-display {
    font-size: 16px;
    letter-spacing: 3px;
    margin-bottom: 20px;
    color: rgba(255,255,255,0.9);
    font-family: 'Courier New', monospace;
    position: relative;
    z-index: 1;
}

.card-footer-row {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: rgba(255,255,255,0.7);
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    z-index: 1;
}

.card-footer-row span:first-child {
    max-width: 60%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Form fields */
.co-form-group {
    margin-bottom: 16px;
}

.co-form-group label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.co-form-group input {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: 15px;
    outline: none;
    transition: border 0.18s, box-shadow 0.18s;
    background: #fafafa;
    font-family: inherit;
    color: #111827;
    box-sizing: border-box;
}

.co-form-group input:focus {
    border-color: #2563eb;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.co-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.secure-badge {
    font-size: 12px;
    color: #6b7280;
    text-align: center;
    margin: 18px 0 20px;
    padding: 10px 12px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.pay-btn {
    width: 100%;
    padding: 15px;
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.18s, transform 0.1s;
    letter-spacing: 0.2px;
    font-family: inherit;
}

.pay-btn:hover { background: #1d4ed8; }
.pay-btn:active { transform: scale(0.99); }

.back-link {
    display: block;
    text-align: center;
    margin-top: 14px;
    font-size: 13px;
    color: #6b7280;
    text-decoration: none;
}

.back-link:hover { color: #374151; text-decoration: underline; }

/* Responsive */
@media (max-width: 720px) {
    .checkout-grid { grid-template-columns: 1fr; }
    .co-form-row   { grid-template-columns: 1fr; }
}
</style>

<div class="checkout-wrapper">

    <div class="sandbox-notice">
        🧪&nbsp; PAYMENT SANDBOX — This is a test environment. No real charges will be made.
    </div>

    <div class="checkout-grid">

        <!-- ── Order Summary ── -->
        <div class="checkout-summary">
            <h2 class="co-section-title">Order Summary</h2>

            <!-- Event mini-card -->
            <div class="event-mini-card">
                <img src="<?= SITE_URL . ltrim($order['event']['banner_url'], '/') ?>" alt="Event Banner">
                <div>
                    <div class="event-mini-title"><?= htmlspecialchars($order['event']['title']) ?></div>
                    <div class="event-mini-detail">📅 <?= date("F j, Y g:i A", strtotime($order['event']['start_at'])) ?></div>
                    <div class="event-mini-detail">📍 <?= htmlspecialchars($order['event']['location_text']) ?></div>
                </div>
            </div>

            <!-- Ticket breakdown -->
            <div class="breakdown-label">Tickets</div>

            <?php foreach ($order['items'] as $item): ?>
                <div class="breakdown-row">
                    <div>
                        <span class="breakdown-qty"><?= $item['quantity'] ?>×</span>
                        <?= htmlspecialchars($item['name']) ?>
                    </div>
                    <div class="breakdown-subtotal">$<?= number_format($item['subtotal'], 2) ?></div>
                </div>
            <?php endforeach; ?>

            <div class="breakdown-total-row">
                <span>Total</span>
                <span>$<?= number_format($order['total'], 2) ?></span>
            </div>
        </div>

        <!-- ── Payment Details ── -->
        <div class="checkout-payment">
            <h2 class="co-section-title">Payment Details</h2>

            <!-- Card visual preview -->
            <div class="card-visual">
                <div class="card-chip"></div>
                <div class="card-number-display" id="card-display">•••• •••• •••• ••••</div>
                <div class="card-footer-row">
                    <span id="name-display">YOUR NAME</span>
                    <span id="expiry-display">MM/YY</span>
                </div>
            </div>

            <form method="POST" action="<?= rtrim(SITE_URL, '/') ?>/payment/complete">

                <div class="co-form-group">
                    <label>Name on Card</label>
                    <input type="text" name="card_name" id="card-name-input"
                           placeholder="John Smith" autocomplete="cc-name" required>
                </div>

                <div class="co-form-group">
                    <label>Card Number</label>
                    <input type="text" name="card_number" id="card-number-input"
                           placeholder="1234 5678 9012 3456" maxlength="19"
                           autocomplete="cc-number" required>
                </div>

                <div class="co-form-row">
                    <div class="co-form-group">
                        <label>Expiry Date</label>
                        <input type="text" name="card_expiry" id="expiry-input"
                               placeholder="MM/YY" maxlength="5"
                               autocomplete="cc-exp" required>
                    </div>
                    <div class="co-form-group">
                        <label>CVV</label>
                        <input type="text" name="card_cvv"
                               placeholder="123" maxlength="3"
                               autocomplete="cc-csc" required>
                    </div>
                </div>

                <div class="secure-badge">
                    🔒 Secure Sandbox Payment &mdash; powered by EventX Pay
                </div>

                <button type="submit" class="pay-btn">
                    Complete Payment &mdash; $<?= number_format($order['total'], 2) ?>
                </button>

            </form>

            <a href="javascript:history.back()" class="back-link">← Go back</a>
        </div>

    </div>

</div>

<script>
(function () {
    const cardDisplay   = document.getElementById('card-display');
    const nameDisplay   = document.getElementById('name-display');
    const expiryDisplay = document.getElementById('expiry-display');

    // Card number — live format + preview
    document.getElementById('card-number-input')?.addEventListener('input', function () {
        const raw = this.value.replace(/\D/g, '').substring(0, 16);
        this.value = raw.match(/.{1,4}/g)?.join(' ') || '';
        const padded = raw.padEnd(16, '•').match(/.{1,4}/g).join(' ');
        cardDisplay.textContent = padded;
    });

    // Card name — live preview
    document.getElementById('card-name-input')?.addEventListener('input', function () {
        nameDisplay.textContent = this.value.toUpperCase() || 'YOUR NAME';
    });

    // Expiry — auto-slash + live preview
    document.getElementById('expiry-input')?.addEventListener('input', function () {
        let raw = this.value.replace(/\D/g, '').substring(0, 4);
        if (raw.length >= 3) raw = raw.substring(0, 2) + '/' + raw.substring(2);
        this.value = raw;
        expiryDisplay.textContent = this.value || 'MM/YY';
    });
})();
</script>
