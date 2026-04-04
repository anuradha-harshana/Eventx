


<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/manageEvent.css">

<div class="manager-tabs" data-event-id="<?= htmlspecialchars($event['id']) ?>">

    <div class="tab-nav">
        <button class="tab-btn active" data-tab="participants">Participants</button>
        <button class="tab-btn" data-tab="itinerary">Itinerary</button>
        <button class="tab-btn" data-tab="chat">Chat</button>
    </div>

    <!-- ── Participants Tab ──────────────────────────────── -->
    <div class="tab-panel active" id="tab-participants">

        <!--content for participants tab-->

        <div class="view-all-participants-row">
            <a href="<?= SITE_URL ?>eventParticipants/<?= htmlspecialchars($event['id']) ?>" class="invite-btn">View all participants</a>
        </div>

        <div class="participants-row">

            <section class="get-attendance">
                <div class="section-header">
                    <h3>Check-in Participants</h3>
                    <div class="checkin-stats" id="checkin-stats">
                        <span class="stat-badge" id="stat-checkedin">— checked in</span>
                        <span class="stat-sep">/</span>
                        <span class="stat-badge stat-total" id="stat-total">— registered</span>
                    </div>
                </div>

                <!-- Code entry -->
                <div class="checkin-input-row">
                    <input
                        type="text"
                        id="checkin-code"
                        class="checkin-code-input"
                        placeholder="Enter 16-character ticket code"
                        maxlength="16"
                        autocomplete="off"
                        spellcheck="false"
                    />
                    <button id="checkin-submit-btn" class="checkin-btn">Check In</button>
                </div>

                <!-- Result flash -->
                <div id="checkin-result" class="checkin-result hidden"></div>

                <!-- Recent check-ins log -->
                <div class="checkin-log-header">Recent check-ins</div>
                <div id="checkin-log" class="checkin-log">
                    <p class="empty-state">No check-ins yet.</p>
                </div>
            </section>
        
            <section class="invite-participants">
                <div class="section-header">
                    <h3>Invite Participants</h3>
                </div>
                <div class="invite-controls">
                    <input type="text" class="search-input" placeholder="Search by name or email..." />
                    <button class="invite-btn">Send Invite</button>
                </div>
            </section>
            
        </div>

    </div>

    <!-- ── Chat Tab ──────────────────────────────────────── -->
    <div class="tab-panel" id="tab-chat">

        <section class="event-chat">
            <div class="section-header">
                <h3>Event Chat</h3>
            </div>
            <div class="chat-box">
                <p class="empty-state">No messages yet.</p>
            </div>
        </section>

    </div>

    <!-- ── Itinerary Tab ─────────────────────────────────── -->
    <div class="tab-panel" id="tab-itinerary">

        <section class="itinerary-section">
            <div class="section-header">
                <h3>Itinerary</h3>
                <button class="addToItinerary-btn">+ Add Item</button>
            </div>
            <div class="itinerary-content" id="itinerary-list">
                <p class="empty-state">No itinerary items yet.</p>
            </div>
        </section>

    </div>   

</div>

<!-- ── Itinerary Modal ───────────────────────────────────────────────── -->
<div id="itinerary-modal" class="modal-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="itinerary-modal-title">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="itinerary-modal-title">Add Itinerary Item</h3>
            <button class="modal-close" id="close-itinerary-modal" aria-label="Close">&times;</button>
        </div>
        <form id="itinerary-form" class="modal-body">
            <div class="itin-time-row">
                <div class="form-group">
                    <label for="itin-start-time">Start Time</label>
                    <input type="datetime-local" id="itin-start-time" name="start_time" class="form-control"
                        min="<?= date('Y-m-d\TH:i', strtotime($event['start_at'])) ?>"
                        max="<?= date('Y-m-d\TH:i', strtotime($event['end_at'])) ?>" required>
                </div>
                <div class="form-group">
                    <label for="itin-end-time">End Time</label>
                    <input type="datetime-local" id="itin-end-time" name="end_time" class="form-control"
                        min="<?= date('Y-m-d\TH:i', strtotime($event['start_at'])) ?>"
                        max="<?= date('Y-m-d\TH:i', strtotime($event['end_at'])) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="itin-title">Title</label>
                <input type="text" id="itin-title" name="title" class="form-control" placeholder="e.g. Opening Ceremony" maxlength="120" required>
            </div>
            <div class="form-group">
                <label for="itin-description">Description <span class="optional">(optional)</span></label>
                <textarea id="itin-description" name="description" class="form-control" rows="3" placeholder="Extra notes or description..."></textarea>
            </div>
            <div class="form-group">
                <label for="itin-location">Location <span class="optional">(optional)</span></label>
                <input type="text" id="itin-location" name="location" class="form-control" placeholder="e.g. Main Hall" maxlength="120">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" id="cancel-itinerary-modal">Cancel</button>
                <button type="submit" class="btn-primary" id="itin-save-btn">Save Item</button>
            </div>
        </form>
    </div>
</div>

<script>
    const EVENT_START_AT = "<?= date('Y-m-d\TH:i', strtotime($event['start_at'])) ?>";
    const EVENT_END_AT   = "<?= date('Y-m-d\TH:i', strtotime($event['end_at']))   ?>";
</script>

<script src="<?= SITE_URL ?>/assets/js/manageEvent.js"></script>

