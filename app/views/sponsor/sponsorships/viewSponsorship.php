<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<style>
    .spon-view-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .spon-view-header {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .spon-view-header h1 {
        font-size: 2.2rem;
        color: #333;
        margin: 0;
    }

    .spon-view-breadcrumb {
        color: #666;
        font-size: 0.95rem;
        margin-top: 10px;
    }

    .spon-view-breadcrumb a {
        color: #667eea;
        text-decoration: none;
    }

    .spon-view-breadcrumb a:hover {
        text-decoration: underline;
    }

    .spon-main-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
        margin-bottom: 30px;
    }

    .spon-main-content {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .spon-view-event-hero {
        margin-bottom: 30px;
        border-radius: 8px;
        overflow: hidden;
        height: 250px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .spon-view-event-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .spon-event-info-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    .spon-event-info-section:last-child {
        border-bottom: none;
    }

    .spon-section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .spon-event-title-large {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }

    .spon-organizer-info {
        display: flex;
        gap: 15px;
        align-items: center;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .spon-org-logo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        overflow: hidden;
        background: white;
        flex-shrink: 0;
    }

    .spon-org-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .spon-org-details h4 {
        margin: 0;
        font-size: 1rem;
        color: #333;
    }

    .spon-org-details p {
        margin: 5px 0 0 0;
        font-size: 0.9rem;
        color: #666;
    }

    .spon-org-contact {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 10px;
        font-size: 0.9rem;
    }

    .spon-org-contact a {
        color: #667eea;
        text-decoration: none;
    }

    .spon-org-contact a:hover {
        text-decoration: underline;
    }

    .spon-event-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .spon-detail-item {
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        border-left: 3px solid #667eea;
    }

    .spon-detail-label {
        font-size: 0.85rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .spon-detail-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
    }

    .spon-description {
        color: #666;
        line-height: 1.6;
        margin: 15px 0;
    }

    .spon-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .spon-sidebar-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .spon-sidebar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }

    .spon-sidebar-stat {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .spon-sidebar-stat:last-child {
        border-bottom: none;
    }

    .spon-sidebar-stat-label {
        color: #666;
        font-size: 0.95rem;
    }

    .spon-sidebar-stat-value {
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
    }

    .spon-status-badge-large {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        text-align: center;
        width: 100%;
    }

    .spon-status-badge-large.pending {
        background: #fff3cd;
        color: #856404;
    }

    .spon-status-badge-large.approved {
        background: #d4edda;
        color: #155724;
    }

    .spon-status-badge-large.rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .spon-update-form {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .spon-update-form-group {
        margin-bottom: 15px;
    }

    .spon-update-form label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .spon-update-form input,
    .spon-update-form select,
    .spon-update-form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 0.9rem;
        font-family: inherit;
        box-sizing: border-box;
    }

    .spon-update-form input:focus,
    .spon-update-form select:focus,
    .spon-update-form textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .spon-update-form textarea {
        resize: vertical;
        min-height: 80px;
    }

    .spon-update-btn {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .spon-update-btn:hover {
        transform: scale(1.01);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .spon-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .spon-back-link:hover {
        gap: 12px;
    }

    @media (max-width: 768px) {
        .spon-main-grid {
            grid-template-columns: 1fr;
        }

        .spon-event-details-grid {
            grid-template-columns: 1fr;
        }

        .spon-main-content,
        .spon-sidebar-card {
            padding: 20px;
        }
    }
</style>

<div class="spon-view-container">
    <a href="<?= SITE_URL ?>sponMySponsorships" class="spon-back-link">← Back to My Sponsorships</a>

    <div class="spon-view-header">
        <h1><?= htmlspecialchars($sponsorship['event_title']) ?></h1>
        <div class="spon-view-breadcrumb">
            Event by <strong><?= htmlspecialchars($sponsorship['organization_name']) ?></strong>
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

    <div class="spon-main-grid">
        <div class="spon-main-content">
            <!-- Event Banner -->
            <?php if(!empty($sponsorship['event_banner'])): ?>
                <div class="spon-view-event-hero">
                    <img src="<?= SITE_URL ?><?= $sponsorship['event_banner'] ?>" alt="Event">
                </div>
            <?php endif; ?>

            <!-- Event Details -->
            <div class="spon-event-info-section">
                <div class="spon-event-title-large"><?= htmlspecialchars($sponsorship['event_title']) ?></div>
                <p class="spon-description"><?= htmlspecialchars($sponsorship['event_description']) ?></p>

                <div class="spon-event-details-grid">
                    <div class="spon-detail-item">
                        <div class="spon-detail-label">📅 Date</div>
                        <div class="spon-detail-value"><?= date('M d, Y', strtotime($sponsorship['start_at'])) ?></div>
                    </div>
                    <div class="spon-detail-item">
                        <div class="spon-detail-label">⏰ Time</div>
                        <div class="spon-detail-value"><?= date('H:i A', strtotime($sponsorship['start_at'])) ?></div>
                    </div>
                    <div class="spon-detail-item">
                        <div class="spon-detail-label">📍 Location</div>
                        <div class="spon-detail-value"><?= htmlspecialchars($sponsorship['location_text'] ?? 'Online') ?></div>
                    </div>
                    <div class="spon-detail-item">
                        <div class="spon-detail-label">👥 Capacity</div>
                        <div class="spon-detail-value"><?= ($sponsorship['current_participants'] ?? 0) . ' / ' . $sponsorship['capacity'] ?></div>
                    </div>
                </div>
            </div>

            <!-- Organizer Info -->
            <div class="spon-event-info-section">
                <div class="spon-section-title">🏢 Event Organizer</div>
                <div class="spon-organizer-info">
                    <div class="spon-org-logo">
                        <?php if(!empty($sponsorship['organizer_logo'])): ?>
                            <img src="<?= SITE_URL ?><?= $sponsorship['organizer_logo'] ?>" alt="Organizer">
                        <?php else: ?>
                            <div style="width:100%;height:100%;background:#f0f0f0;"></div>
                        <?php endif; ?>
                    </div>
                    <div class="spon-org-details">
                        <h4><?= htmlspecialchars($sponsorship['organization_name']) ?></h4>
                        <div class="spon-org-contact">
                            <a href="mailto:<?= htmlspecialchars($sponsorship['organizer_email']) ?>">
                                📧 <?= htmlspecialchars($sponsorship['organizer_email']) ?>
                            </a>
                            <?php if(!empty($sponsorship['organizer_phone'])): ?>
                                <a href="tel:<?= htmlspecialchars($sponsorship['organizer_phone']) ?>">
                                    📞 <?= htmlspecialchars($sponsorship['organizer_phone']) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Sponsorship Details -->
            <div class="spon-event-info-section">
                <div class="spon-section-title">💰 Current Sponsorship Details</div>
                <div class="spon-event-details-grid">
                    <div class="spon-detail-item">
                        <div class="spon-detail-label">Amount</div>
                        <div class="spon-detail-value">
                            <?php if(!empty($sponsorship['amount'])): ?>
                                $<?= number_format($sponsorship['amount'], 2) ?>
                            <?php else: ?>
                                <span style="color:#999;">TBD</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="spon-detail-item">
                        <div class="spon-detail-label">Branding Level</div>
                        <div class="spon-detail-value" style="text-transform: capitalize;">
                            <?= $sponsorship['branding_level'] ?? 'basic' ?>
                        </div>
                    </div>
                </div>
                <?php if(!empty($sponsorship['resources'])): ?>
                    <div style="margin-top: 15px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                        <strong style="color:#333;">Resources Offered:</strong>
                        <p style="margin:10px 0 0 0;color:#666;white-space:pre-wrap;"><?= htmlspecialchars($sponsorship['resources']) ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Update Funding Form (if still pending) -->
            <?php if($sponsorship['status'] === 'pending'): ?>
                <div class="spon-update-form">
                    <div class="spon-section-title">✏️ Update Sponsorship Details</div>
                    <form method="POST" action="<?= SITE_URL ?>sponsor/updateFunding">
                        <input type="hidden" name="id" value="<?= $sponsorship['id'] ?>">

                        <div class="spon-update-form-group">
                            <label for="amount">Sponsorship Amount</label>
                            <input type="number" id="amount" name="amount" step="0.01" min="0" 
                                   value="<?= !empty($sponsorship['amount']) ? $sponsorship['amount'] : '' ?>"
                                   placeholder="Enter amount">
                        </div>

                        <div class="spon-update-form-group">
                            <label for="branding_level">Branding Level</label>
                            <select id="branding_level" name="branding_level">
                                <option value="basic" <?= $sponsorship['branding_level'] === 'basic' ? 'selected' : '' ?>>Basic</option>
                                <option value="silver" <?= $sponsorship['branding_level'] === 'silver' ? 'selected' : '' ?>>Silver</option>
                                <option value="gold" <?= $sponsorship['branding_level'] === 'gold' ? 'selected' : '' ?>>Gold</option>
                                <option value="platinum" <?= $sponsorship['branding_level'] === 'platinum' ? 'selected' : '' ?>>Platinum</option>
                            </select>
                        </div>

                        <div class="spon-update-form-group">
                            <label for="resources">Resources You Can Provide</label>
                            <textarea id="resources" name="resources" placeholder="Describe items, services, or promotional support..."><?= htmlspecialchars($sponsorship['resources'] ?? '') ?></textarea>
                        </div>

                        <button type="submit" class="spon-update-btn">💾 Save Changes</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="spon-sidebar">
            <div class="spon-sidebar-card">
                <div class="spon-sidebar-title">📊 Sponsorship Status</div>
                <div class="spon-status-badge-large <?= $sponsorship['status'] ?>">
                    <?= ucfirst($sponsorship['status']) ?>
                </div>
                <div style="margin-top: 15px; font-size:0.85rem;color:#999;">
                    Submitted: <?= date('M d, Y', strtotime($sponsorship['created_at'])) ?>
                </div>
            </div>

            <div class="spon-sidebar-card">
                <div class="spon-sidebar-title">📈 Investment Summary</div>
                <div class="spon-sidebar-stat">
                    <span class="spon-sidebar-stat-label">Total Investment</span>
                    <span class="spon-sidebar-stat-value">
                        <?php if(!empty($sponsorship['amount'])): ?>
                            $<?= number_format($sponsorship['amount'], 2) ?>
                        <?php else: ?>
                            <span style="font-size:0.9rem;">TBD</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="spon-sidebar-stat">
                    <span class="spon-sidebar-stat-label">Tier</span>
                    <span class="spon-sidebar-stat-value" style="text-transform: capitalize;">
                        <?= $sponsorship['branding_level'] ?? 'Basic' ?>
                    </span>
                </div>
            </div>

            <div class="spon-sidebar-card">
                <div class="spon-sidebar-title">🎯 Quick Actions</div>
                <a href="<?= SITE_URL ?>sponMySponsorships" style="display: block; padding: 10px; background: #f0f0f0; border-radius: 6px; text-align: center; text-decoration: none; color: #333; font-weight: 600; margin-top: 10px; transition: all 0.3s;">
                    ← Back to Sponsorships
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
