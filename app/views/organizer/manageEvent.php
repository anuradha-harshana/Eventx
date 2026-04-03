


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
                    <h3>Attend Participants</h3>
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

        <!--content for itenarary tab-->
        <section name="itenarary_section">
           <div class="itinerary-content"></div>
           <button class="addToItinerary-btn">add item</button>
        </section>

    </div>

</div>


<script src="<?= SITE_URL ?>/assets/js/manageEvent.js"></script>

