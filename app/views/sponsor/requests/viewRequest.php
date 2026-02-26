<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<style>
    .req-detail-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .req-detail-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .req-detail-header h1 {
        font-size: 2.2rem;
        color: #333;
        margin: 0;
    }

    .req-detail-breadcrumb {
        color: #666;
        font-size: 0.95rem;
        margin-top: 10px;
    }

    .req-detail-breadcrumb a {
        color: #667eea;
        text-decoration: none;
    }

    .req-detail-breadcrumb a:hover {
        text-decoration: underline;
    }

    .req-main-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
        margin-bottom: 30px;
    }

    .req-main-content {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .req-event-hero {
        margin-bottom: 30px;
        border-radius: 8px;
        overflow: hidden;
        height: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .req-event-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .req-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .req-section:last-child {
        border-bottom: none;
    }

    .req-section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .req-event-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }

    .req-org-info {
        display: flex;
        gap: 15px;
        align-items: center;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .req-org-logo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        background: white;
        flex-shrink: 0;
    }

    .req-org-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .req-org-details h4 {
        margin: 0;
        font-size: 1rem;
        color: #333;
    }

    .req-org-details p {
        margin: 5px 0 0 0;
        font-size: 0.9rem;
        color: #666;
    }

    .req-org-contact {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 10px;
        font-size: 0.9rem;
    }

    .req-org-contact a {
        color: #667eea;
        text-decoration: none;
    }

    .req-org-contact a:hover {
        text-decoration: underline;
    }

    .req-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .req-detail-item {
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        border-left: 3px solid #667eea;
    }

    .req-detail-label {
        font-size: 0.85rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .req-detail-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
    }

    .req-description {
        color: #666;
        line-height: 1.6;
        margin: 15px 0;
    }

    .req-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .req-sidebar-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .req-sidebar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }

    .req-sidebar-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .req-sidebar-stat:last-child {
        border-bottom: none;
    }

    .req-sidebar-stat-label {
        color: #666;
        font-size: 0.95rem;
    }

    .req-sidebar-stat-value {
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
    }

    .req-status-badge-large {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        text-align: center;
        width: 100%;
    }

    .req-status-badge-large.pending {
        background: #fff3cd;
        color: #856404;
    }

    .req-status-badge-large.approved {
        background: #d4edda;
        color: #155724;
    }

    .req-status-badge-large.rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .req-status-badge-large.negotiating {
        background: #cfe2ff;
        color: #084298;
    }

    .req-action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .req-action-buttons button {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .req-accept-btn {
        background: #d4edda;
        color: #155724;
    }

    .req-accept-btn:hover {
        background: #28a745;
        color: white;
    }

    .req-reject-btn {
        background: #f8d7da;
        color: #721c24;
    }

    .req-reject-btn:hover {
        background: #dc3545;
        color: white;
    }

    .req-negotiate-btn {
        background: #cfe2ff;
        color: #084298;
    }

    .req-negotiate-btn:hover {
        background: #0c63e4;
        color: white;
    }

    .req-modal-backdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        z-index: 1000;
    }

    .req-modal-backdrop.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .req-modal {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
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

    .req-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 25px;
        border-bottom: 1px solid #f0f0f0;
    }

    .req-modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
        color: #333;
    }

    .req-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #999;
        cursor: pointer;
    }

    .req-modal-close:hover {
        color: #333;
    }

    .req-modal-content {
        padding: 25px;
    }

    .req-form-group {
        margin-bottom: 20px;
    }

    .req-form-group label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .req-form-group input,
    .req-form-group select,
    .req-form-group textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.95rem;
        font-family: inherit;
        box-sizing: border-box;
    }

    .req-form-group input:focus,
    .req-form-group select:focus,
    .req-form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .req-form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .req-form-actions {
        display: flex;
        gap: 12px;
        margin-top: 25px;
    }

    .req-submit-btn {
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

    .req-submit-btn:hover {
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .req-cancel-btn {
        flex: 1;
        padding: 12px;
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
    }

    .req-cancel-btn:hover {
        background: #e0e0e0;
    }

    .req-notes-section {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 2px solid #f0f0f0;
    }

    .req-existing-notes {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        max-height: 200px;
        overflow-y: auto;
        border-left: 3px solid #667eea;
    }

    .req-note-item {
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .req-note-item:last-child {
        border-bottom: none;
    }

    .req-note-label {
        font-size: 0.8rem;
        color: #999;
        text-transform: uppercase;
    }

    .req-note-text {
        margin: 5px 0 0 0;
        color: #333;
        line-height: 1.5;
        white-space: pre-wrap;
    }

    .req-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .req-back-link:hover {
        gap: 12px;
    }

    @media (max-width: 768px) {
        .req-main-grid {
            grid-template-columns: 1fr;
        }

        .req-details-grid {
            grid-template-columns: 1fr;
        }

        .req-main-content,
        .req-sidebar-card {
            padding: 20px;
        }

        .req-action-buttons {
            flex-direction: column;
        }
    }
</style>

<div class="req-detail-container">
    <a href="<?= SITE_URL ?>sponRequests" class="req-back-link">← Back to Requests</a>

    <div class="req-detail-header">
        <h1><?= htmlspecialchars($request['event_title']) ?></h1>
        <div class="req-detail-breadcrumb">
            From <strong><?= htmlspecialchars($request['organization_name']) ?></strong> | Requested <?= date('M d, Y', strtotime($request['created_at'])) ?>
        </div>
    </div>

    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;">
            ✓ <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            ✕ <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="req-main-grid">
        <div class="req-main-content">
            <!-- Event Banner -->
            <?php if(!empty($request['event_banner'])): ?>
                <div class="req-event-hero">
                    <img src="<?= SITE_URL ?><?= $request['event_banner'] ?>" alt="Event">
                </div>
            <?php endif; ?>

            <!-- Event Details -->
            <div class="req-section">
                <div class="req-event-title"><?= htmlspecialchars($request['event_title']) ?></div>
                <p class="req-description"><?= htmlspecialchars($request['event_description']) ?></p>

                <div class="req-details-grid">
                    <div class="req-detail-item">
                        <div class="req-detail-label">📅 Date</div>
                        <div class="req-detail-value"><?= date('M d, Y', strtotime($request['start_at'])) ?></div>
                    </div>
                    <div class="req-detail-item">
                        <div class="req-detail-label">⏰ Time</div>
                        <div class="req-detail-value"><?= date('H:i A', strtotime($request['start_at'])) ?></div>
                    </div>
                    <div class="req-detail-item">
                        <div class="req-detail-label">📍 Location</div>
                        <div class="req-detail-value"><?= htmlspecialchars($request['location_text'] ?? 'Online') ?></div>
                    </div>
                    <div class="req-detail-item">
                        <div class="req-detail-label">👥 Capacity</div>
                        <div class="req-detail-value"><?= ($request['current_participants'] ?? 0) . ' / ' . $request['capacity'] ?></div>
                    </div>
                </div>
            </div>

            <!-- Organizer Info -->
            <div class="req-section">
                <div class="req-section-title">🏢 Event Organizer</div>
                <div class="req-org-info">
                    <div class="req-org-logo">
                        <?php if(!empty($request['organizer_logo'])): ?>
                            <img src="<?= SITE_URL ?><?= $request['organizer_logo'] ?>" alt="Organizer">
                        <?php else: ?>
                            <div style="width:100%;height:100%;background:#f0f0f0;"></div>
                        <?php endif; ?>
                    </div>
                    <div class="req-org-details">
                        <h4><?= htmlspecialchars($request['organization_name']) ?></h4>
                        <div class="req-org-contact">
                            <a href="mailto:<?= htmlspecialchars($request['organizer_email']) ?>">
                                📧 <?= htmlspecialchars($request['organizer_email']) ?>
                            </a>
                            <?php if(!empty($request['organizer_phone'])): ?>
                                <a href="tel:<?= htmlspecialchars($request['organizer_phone']) ?>">
                                    📞 <?= htmlspecialchars($request['organizer_phone']) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sponsorship Details -->
            <div class="req-section">
                <div class="req-section-title">💰 Sponsorship Request Details</div>
                <div class="req-details-grid">
                    <div class="req-detail-item">
                        <div class="req-detail-label">Amount</div>
                        <div class="req-detail-value">
                            <?php if(!empty($request['amount'])): ?>
                                $<?= number_format($request['amount'], 2) ?>
                            <?php else: ?>
                                <span style="color:#999;">TBD</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="req-detail-item">
                        <div class="req-detail-label">Branding Level</div>
                        <div class="req-detail-value" style="text-transform: capitalize;">
                            <?= $request['branding_level'] ?? 'basic' ?>
                        </div>
                    </div>
                </div>

                <?php if(!empty($request['organizer_notes'])): ?>
                    <div style="margin-top: 15px; padding: 15px; background: #f9f9f9; border-radius: 8px; border-left: 3px solid #667eea;">
                        <strong style="color:#333;">📝 Organizer's Message:</strong>
                        <p style="margin:10px 0 0 0;color:#666;white-space:pre-wrap;"><?= htmlspecialchars($request['organizer_notes']) ?></p>
                    </div>
                <?php endif; ?>

                <?php if(!empty($request['resources'])): ?>
                    <div style="margin-top: 15px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                        <strong style="color:#333;">📦 Resources Requested:</strong>
                        <p style="margin:10px 0 0 0;color:#666;white-space:pre-wrap;"><?= htmlspecialchars($request['resources']) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Response Actions -->
            <div class="req-section">
                <div class="req-section-title">📋 Your Response</div>
                <div class="req-action-buttons">
                    <button class="req-accept-btn" onclick="openModal('acceptModal')">✓ Accept</button>
                    <button class="req-negotiate-btn" onclick="openModal('negotiateModal')">↔️ Negotiate</button>
                    <button class="req-reject-btn" onclick="openModal('rejectModal')">✕ Decline</button>
                </div>
            </div>

            <!-- Sponsor Notes Section -->
            <div class="req-notes-section">
                <div class="req-section-title">💬 Your Notes</div>
                
                <?php if(!empty($request['sponsor_notes'])): ?>
                    <div class="req-existing-notes">
                        <div class="req-note-item">
                            <div class="req-note-label">Your Previous Notes</div>
                            <div class="req-note-text"><?= htmlspecialchars($request['sponsor_notes']) ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= SITE_URL ?>sponsor/addRequestNotes">
                    <input type="hidden" name="id" value="<?= $request['id'] ?>">
                    <div class="req-form-group">
                        <label for="notes">Add Notes</label>
                        <textarea id="notes" name="notes" placeholder="Add your notes, questions, or comments..."></textarea>
                    </div>
                    <button type="submit" style="padding: 12px 24px; background: #667eea; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">💾 Save Notes</button>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="req-sidebar">
            <div class="req-sidebar-card">
                <div class="req-sidebar-title">📊 Status</div>
                <div class="req-status-badge-large <?= $request['status'] ?>">
                    <?= ucfirst($request['status']) ?>
                </div>
                <div style="margin-top: 10px; font-size:0.85rem;color:#999;">
                    Received: <?= date('M d, Y', strtotime($request['created_at'])) ?>
                </div>
            </div>

            <div class="req-sidebar-card">
                <div class="req-sidebar-title">💰 Investment</div>
                <div class="req-sidebar-stat">
                    <span class="req-sidebar-stat-label">Requested Amount</span>
                    <span class="req-sidebar-stat-value">
                        <?php if(!empty($request['amount'])): ?>
                            $<?= number_format($request['amount'], 2) ?>
                        <?php else: ?>
                            <span style="font-size:0.9rem;">TBD</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="req-sidebar-stat">
                    <span class="req-sidebar-stat-label">Tier</span>
                    <span class="req-sidebar-stat-value" style="text-transform: capitalize;">
                        <?= $request['branding_level'] ?? 'Basic' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accept Modal -->
<div class="req-modal-backdrop" id="acceptModal">
    <div class="req-modal">
        <div class="req-modal-header">
            <h2>✓ Accept Request</h2>
            <button class="req-modal-close" onclick="closeModal('acceptModal')">✕</button>
        </div>
        <div class="req-modal-content">
            <form method="POST" action="<?= SITE_URL ?>sponsor/updateRequestStatus">
                <input type="hidden" name="id" value="<?= $request['id'] ?>">
                <input type="hidden" name="status" value="approved">
                
                <div class="req-form-group">
                    <label for="acceptNotes">Additional Notes (Optional)</label>
                    <textarea id="acceptNotes" name="notes" placeholder="Add any notes about your acceptance..."></textarea>
                </div>

                <div class="req-form-actions">
                    <button type="submit" class="req-submit-btn">✓ Accept Request</button>
                    <button type="button" class="req-cancel-btn" onclick="closeModal('acceptModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Negotiate Modal -->
<div class="req-modal-backdrop" id="negotiateModal">
    <div class="req-modal">
        <div class="req-modal-header">
            <h2>↔️ Start Negotiation</h2>
            <button class="req-modal-close" onclick="closeModal('negotiateModal')">✕</button>
        </div>
        <div class="req-modal-content">
            <form method="POST" action="<?= SITE_URL ?>sponsor/updateRequestStatus">
                <input type="hidden" name="id" value="<?= $request['id'] ?>">
                <input type="hidden" name="status" value="negotiating">
                
                <div class="req-form-group">
                    <label for="negotiateNotes">Your Negotiation Points (Required)</label>
                    <textarea id="negotiateNotes" name="notes" placeholder="Describe what you'd like to negotiate - budget, deliverables, timeline, etc..." required></textarea>
                </div>

                <div class="req-form-actions">
                    <button type="submit" class="req-submit-btn">↔️ Start Negotiation</button>
                    <button type="button" class="req-cancel-btn" onclick="closeModal('negotiateModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="req-modal-backdrop" id="rejectModal">
    <div class="req-modal">
        <div class="req-modal-header">
            <h2>✕ Decline Request</h2>
            <button class="req-modal-close" onclick="closeModal('rejectModal')">✕</button>
        </div>
        <div class="req-modal-content">
            <form method="POST" action="<?= SITE_URL ?>sponsor/updateRequestStatus">
                <input type="hidden" name="id" value="<?= $request['id'] ?>">
                <input type="hidden" name="status" value="rejected">
                
                <div class="req-form-group">
                    <label for="rejectNotes">Reason for Declining (Optional)</label>
                    <textarea id="rejectNotes" name="notes" placeholder="Help the organizer understand why you're declining..."></textarea>
                </div>

                <div class="req-form-actions">
                    <button type="submit" class="req-submit-btn" style="background: #dc3545;">✕ Decline Request</button>
                    <button type="button" class="req-cancel-btn" onclick="closeModal('rejectModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.add('active');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.remove('active');
    }

    // Close modal when clicking outside
    document.querySelectorAll('.req-modal-backdrop').forEach(backdrop => {
        backdrop.addEventListener('click', function(e) {
            if(e.target === this) {
                this.classList.remove('active');
            }
        });
    });
</script>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
