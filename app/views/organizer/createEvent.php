<?php 
?>

<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">
<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/eventForm.css">

<form method="POST" action="<?=SITE_URL ?>organizer/createEvent" enctype="multipart/form-data">
    <div class="organizer-details">
        
        <div class="organizer-section">
            <div class="form-group">
                <label>Title</label>
                <input type="text" placeholder="Event Name" name="title" required >
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Describe the details of your event" required ></textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Location</label>
                    <input type="text" placeholder="e.g. City Conference Center" name="location_text" required >
                </div>
                <div class="form-group">
                    <label>Location Link</label>
                    <input type="text" placeholder="Google Maps link" name="location_link" required >
                </div>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select name="category_id" id="category" required>
                    <option value="">Select Category</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= $category['id'] ?>">
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="organizer-section">
            <div class="grid-2">
                <div class="form-group">
                    <label>Start Date & Time</label>
                    <input type="datetime-local" name="start_at" required >
                </div>
                <div class="form-group">
                    <label>End Date & Time</label>
                    <input type="datetime-local" name="end_at" required >
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Event Pricing</label>
                    <select name="pricing_type" id="pricing_type">
                        <option value="free">Free</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>

            <!-- Hidden capacity synced by JS -->
            <input type="hidden" name="capacity" id="capacity_field">

            <!-- FREE: single ticket count -->
            <div id="free-section" class="ticket-section">
                <div class="form-group">
                    <label>Free Ticket Count</label>
                    <input type="number" id="free_ticket_count" placeholder="e.g. 300" min="1">
                </div>
            </div>

            <!-- PAID: multiple ticket types -->
            <div id="paid-section" class="ticket-section" style="display:none;">
                <div class="ticket-section-header">
                    <span class="ticket-section-label">Ticket Types</span>
                    <button type="button" id="add-ticket-btn" class="add-ticket-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Add Ticket Type
                    </button>
                </div>
                <div id="ticket-rows"></div>
                <div class="ticket-total">
                    Total Capacity: <strong id="total-capacity-display">0</strong>
                </div>
            </div>

            <div class="form-group" style="margin-top:8px;">
                <label>Banner Pic</label>
                <input type="file" name="banner_url">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit">Create Event</button>
        </div>
    </div>
</form>

<script>
(function () {
    var pricingSelect = document.getElementById('pricing_type');
    var freeSection   = document.getElementById('free-section');
    var paidSection   = document.getElementById('paid-section');
    var freeCount     = document.getElementById('free_ticket_count');
    var capacityField = document.getElementById('capacity_field');
    var ticketRows    = document.getElementById('ticket-rows');
    var addTicketBtn  = document.getElementById('add-ticket-btn');
    var totalDisplay  = document.getElementById('total-capacity-display');
    var ticketIndex   = 0;

    function updateCapacity() {
        if (pricingSelect.value === 'free') {
            capacityField.value = parseInt(freeCount.value) || '';
        } else {
            var total = 0;
            ticketRows.querySelectorAll('.ticket-cap-input').forEach(function (inp) {
                total += parseInt(inp.value) || 0;
            });
            totalDisplay.textContent = total;
            capacityField.value = total > 0 ? total : '';
        }
    }

    function addTicketRow() {
        ticketIndex++;
        var i = ticketIndex;
        var row = document.createElement('div');
        row.className = 'ticket-row';
        row.innerHTML =
            '<div class="ticket-row-grid">' +
                '<div class="form-group">' +
                    '<label>Ticket Name</label>' +
                    '<input type="text" name="tickets[' + i + '][name]" placeholder="e.g. VIP, General" required>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label>Price (MYR)</label>' +
                    '<input type="number" name="tickets[' + i + '][price]" placeholder="0.00" min="0" step="0.01" required>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label>Capacity</label>' +
                    '<input type="number" name="tickets[' + i + '][capacity]" placeholder="e.g. 100" min="1" class="ticket-cap-input" required>' +
                '</div>' +
                '<div class="form-group">' +
                    '<label>Terms / Notes</label>' +
                    '<input type="text" name="tickets[' + i + '][terms]" placeholder="Optional notes">' +
                '</div>' +
            '</div>' +
            '<div style="text-align: right; margin-top: -4px;">' +
                '<button type="button" class="remove-ticket-btn">' +
                    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>' +
                    'Remove' +
                '</button>' +
            '</div>';

        row.querySelector('.remove-ticket-btn').addEventListener('click', function () {
            row.remove();
            updateCapacity();
        });
        row.querySelector('.ticket-cap-input').addEventListener('input', updateCapacity);
        ticketRows.appendChild(row);
        updateCapacity();
    }

    pricingSelect.addEventListener('change', function () {
        if (this.value === 'free') {
            freeSection.style.display = 'block';
            paidSection.style.display = 'none';
        } else {
            freeSection.style.display = 'none';
            paidSection.style.display = 'block';
            if (ticketRows.children.length === 0) addTicketRow();
        }
        updateCapacity();
    });

    freeCount.addEventListener('input', updateCapacity);
    addTicketBtn.addEventListener('click', addTicketRow);

    updateCapacity();
}());
</script>