


<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/manageEvent.css">

<div class="manager-tabs">

    <div class="tab-nav">
        <button class="tab-btn active" data-tab="participants">Participants</button>
        <button class="tab-btn" data-tab="itinerary">Itinerary</button>
    </div>

    <!-- ── Participants Tab ──────────────────────────────── -->
    <div class="tab-panel active" id="tab-participants">

        <!--content for participants tab-->
        <div class="participants-row">

            <section class="manage-participants">
                <div class="section-header">
                    <h3>Registered Participants</h3>
                    <span class="participant-count">0 participants</span>
                </div>
                <div class="participant-list">
                    <!-- participant rows go here -->
                    <p class="empty-state">No participants registered yet.</p>
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

        <!--content for itenarary tab-->
        <section name="itenarary_section">
            <!--build event itenarary-->
            <!--assign members-->
        </section>

    </div>

</div>


<script src="<?= SITE_URL ?>/assets/js/manageEvent.js"></script>

