<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<style>
    .spon-fund-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .spon-fund-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .spon-fund-header h1 {
        font-size: 2.5rem;
        color: #333;
        margin: 0;
    }

    .spon-fund-subtitle {
        color: #666;
        margin-top: 5px;
    }

    .spon-events-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }

    .spon-event-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .spon-event-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        transform: translateY(-5px);
    }

    .spon-event-banner {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        overflow: hidden;
        position: relative;
    }

    .spon-event-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .spon-event-content {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .spon-event-category {
        display: inline-block;
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 10px;
        width: fit-content;
    }

    .spon-event-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .spon-event-org {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 0.9rem;
        color: #666;
    }

    .spon-event-org-logo {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #f0f0f0;
        overflow: hidden;
    }

    .spon-event-org-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .spon-event-details {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 12px;
    }

    .spon-event-details-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }

    .spon-event-details-item strong {
        color: #333;
    }

    .spon-event-capacity {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        border-top: 1px solid #f0f0f0;
        margin: 12px 0 0 0;
        font-size: 0.85rem;
        color: #666;
    }

    .spon-capacity-bar {
        width: 100%;
        height: 6px;
        background: #f0f0f0;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 6px;
    }

    .spon-capacity-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px;
    }

    .spon-event-footer {
        display: flex;
        gap: 10px;
        margin-top: auto;
        padding-top: 15px;
    }

    .spon-sponsor-btn {
        flex: 1;
        padding: 10px 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .spon-sponsor-btn:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .spon-view-details-btn {
        flex: 1;
        padding: 10px 15px;
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .spon-view-details-btn:hover {
        background: #e0e0e0;
    }

    .spon-modal-backdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        z-index: 1000;
    }

    .spon-modal-backdrop.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .spon-modal {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .spon-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 25px;
        border-bottom: 1px solid #f0f0f0;
    }

    .spon-modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
        color: #333;
    }

    .spon-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #999;
        cursor: pointer;
        transition: color 0.3s;
    }

    .spon-modal-close:hover {
        color: #333;
    }

    .spon-modal-content {
        padding: 25px;
    }

    .spon-form-group {
        margin-bottom: 20px;
    }

    .spon-form-group label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .spon-form-group input,
    .spon-form-group select,
    .spon-form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
        font-family: inherit;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    .spon-form-group input:focus,
    .spon-form-group select:focus,
    .spon-form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .spon-form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .spon-form-actions {
        display: flex;
        gap: 12px;
        margin-top: 25px;
    }

    .spon-submit-btn {
        flex: 1;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .spon-submit-btn:hover {
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .spon-cancel-btn {
        flex: 1;
        padding: 12px;
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .spon-cancel-btn:hover {
        background: #e0e0e0;
    }

    .spon-no-events {
        text-align: center;
        padding: 60px 20px;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .spon-no-events-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .spon-no-events-text {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 10px;
    }

    .spon-no-events-subtext {
        color: #999;
    }
</style>

<div class="spon-fund-container">
    <div class="spon-fund-header">
        <div>
            <h1>🎯 Promote & Fund Events</h1>
            <p class="spon-fund-subtitle">Discover events and submit sponsorship requests</p>
        </div>
    </div>

    <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?>" style="margin-bottom: 20px;">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($availableEvents)): ?>
        <div class="spon-events-grid">
            <?php foreach($availableEvents as $event): ?>
                <div class="spon-event-card">
                    <div class="spon-event-banner">
                        <?php if(!empty($event['event_banner'])): ?>
                            <img src="<?= SITE_URL ?><?= $event['event_banner'] ?>" alt="<?= htmlspecialchars($event['title']) ?>">
                        <?php else: ?>
                            📅
                        <?php endif; ?>
                    </div>

                    <div class="spon-event-content">
                        <?php if(!empty($event['category_name'])): ?>
                            <span class="spon-event-category"><?= htmlspecialchars($event['category_name']) ?></span>
                        <?php endif; ?>

                        <h3 class="spon-event-title"><?= htmlspecialchars($event['title']) ?></h3>

                        <div class="spon-event-org">
                            <div class="spon-event-org-logo">
                                <?php if(!empty($event['organizer_logo'])): ?>
                                    <img src="<?= SITE_URL ?><?= $event['organizer_logo'] ?>" alt="Organizer">
                                <?php else: ?>
                                    <div style="width:100%;height:100%;background:#f0f0f0;"></div>
                                <?php endif; ?>
                            </div>
                            <span><?= htmlspecialchars($event['organization_name'] ?? 'Event Organizer') ?></span>
                        </div>

                        <div class="spon-event-details">
                            <div class="spon-event-details-item">
                                <span>📍</span>
                                <strong><?= htmlspecialchars($event['location_text'] ?? 'Online Event') ?></strong>
                            </div>
                            <div class="spon-event-details-item">
                                <span>📅</span>
                                <strong><?= date('M d, Y', strtotime($event['start_at'])) ?></strong>
                            </div>
                            <div class="spon-event-details-item">
                                <span>⏰</span>
                                <strong><?= date('H:i A', strtotime($event['start_at'])) ?></strong>
                            </div>
                        </div>

                        <?php if($event['capacity']): ?>
                            <div class="spon-event-capacity">
                                <span><?= $event['current_participants'] ?? 0 ?> / <?= $event['capacity'] ?> Participants</span>
                            </div>
                            <div class="spon-capacity-bar">
                                <div class="spon-capacity-fill" style="width: <?= ($event['current_participants'] / $event['capacity'] * 100) ?>%"></div>
                            </div>
                        <?php endif; ?>

                        <div class="spon-event-footer">
                            <button class="spon-sponsor-btn" onclick="openSponsorModal(<?= $event['id'] ?>, '<?= htmlspecialchars($event['title'], ENT_QUOTES) ?>')">
                                💰 Sponsor Event
                            </button>
                            <button class="spon-view-details-btn" onclick="viewEventDetails(<?= $event['id'] ?>)">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="spon-no-events">
            <div class="spon-no-events-icon">📭</div>
            <p class="spon-no-events-text">No Available Events</p>
            <p class="spon-no-events-subtext">There are currently no events available for sponsorship. Check back soon!</p>
        </div>
    <?php endif; ?>
</div>

<!-- Sponsorship Request Modal -->
<div class="spon-modal-backdrop" id="sponsorModal">
    <div class="spon-modal">
        <div class="spon-modal-header">
            <h2>Sponsor Event: <span id="modalEventTitle"></span></h2>
            <button class="spon-modal-close" onclick="closeSponsorModal()">✕</button>
        </div>
        <div class="spon-modal-content">
            <form method="POST" action="<?= SITE_URL ?>sponsor/sendRequest">
                <input type="hidden" name="event_id" id="modalEventId">

                <div class="spon-form-group">
                    <label for="amount">Sponsorship Amount (Optional)</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0" placeholder="e.g., 5000.00">
                    <small style="color:#999;">Leave empty if you prefer to discuss with organizer</small>
                </div>

                <div class="spon-form-group">
                    <label for="branding_level">Branding Level</label>
                    <select id="branding_level" name="branding_level" required>
                        <option value="basic">Basic</option>
                        <option value="silver">Silver</option>
                        <option value="gold">Gold</option>
                        <option value="platinum">Platinum</option>
                    </select>
                </div>

                <div class="spon-form-group">
                    <label for="resources">Resources You Can Provide (Optional)</label>
                    <textarea id="resources" name="resources" placeholder="Describe items, services, or promotional support you can offer..."></textarea>
                </div>

                <div class="spon-form-actions">
                    <button type="submit" class="spon-submit-btn">🚀 Send Request</button>
                    <button type="button" class="spon-cancel-btn" onclick="closeSponsorModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openSponsorModal(eventId, eventTitle) {
        document.getElementById('modalEventId').value = eventId;
        document.getElementById('modalEventTitle').textContent = eventTitle;
        document.getElementById('sponsorModal').classList.add('active');
    }

    function closeSponsorModal() {
        document.getElementById('sponsorModal').classList.remove('active');
    }

    function viewEventDetails(eventId) {
        // Redirect to event details page
        window.location.href = '<?= SITE_URL ?>viewEvent?id=' + eventId;
    }

    // Close modal when clicking outside
    document.getElementById('sponsorModal').addEventListener('click', function(e) {
        if(e.target === this) {
            closeSponsorModal();
        }
    });
</script>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
