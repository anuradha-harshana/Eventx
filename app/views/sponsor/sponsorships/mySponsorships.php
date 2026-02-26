<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<style>
    .spon-my-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .spon-my-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .spon-my-header h1 {
        font-size: 2.5rem;
        color: #333;
        margin: 0;
    }

    .spon-my-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .spon-stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .spon-stat-card.secondary {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .spon-stat-card.success {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .spon-stat-card.warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .spon-stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .spon-stat-label {
        font-size: 0.95rem;
        opacity: 0.95;
    }

    .spon-my-table-wrapper {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .spon-my-table {
        width: 100%;
        border-collapse: collapse;
    }

    .spon-my-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .spon-my-table th {
        padding: 18px 20px;
        text-align: left;
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }

    .spon-my-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.3s;
    }

    .spon-my-table tbody tr:hover {
        background: #f9f9f9;
    }

    .spon-my-table td {
        padding: 18px 20px;
        font-size: 0.95rem;
        color: #666;
    }

    .spon-my-table td strong {
        color: #333;
    }

    .spon-status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .spon-status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .spon-status-approved {
        background: #d4edda;
        color: #155724;
    }

    .spon-status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .spon-actions {
        display: flex;
        gap: 8px;
    }

    .spon-action-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .spon-action-btn.view {
        background: #e3f2fd;
        color: #1976d2;
    }

    .spon-action-btn.view:hover {
        background: #1976d2;
        color: white;
    }

    .spon-empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .spon-empty-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .spon-empty-title {
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .spon-empty-text {
        color: #666;
        margin-bottom: 25px;
    }

    .spon-cta-btn {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .spon-cta-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    @media (max-width: 768px) {
        .spon-my-table {
            font-size: 0.85rem;
        }

        .spon-my-table th,
        .spon-my-table td {
            padding: 12px 10px;
        }
    }
</style>

<div class="spon-my-container">
    <div class="spon-my-header">
        <div>
            <h1>💼 My Sponsorships</h1>
            <p style="color:#666;margin:5px 0 0 0;">Track and manage your event sponsorship commitments</p>
        </div>
        <a href="<?= SITE_URL ?>sponFundEvent" class="spon-cta-btn">+ Find Events to Sponsor</a>
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

    <!-- Statistics Cards -->
    <?php if(!empty($stats)): ?>
        <div class="spon-my-stats">
            <div class="spon-stat-card">
                <div class="spon-stat-value"><?= $stats['total_sponsorships'] ?? 0 ?></div>
                <div class="spon-stat-label">Total Sponsorships</div>
            </div>
            <div class="spon-stat-card success">
                <div class="spon-stat-value"><?= $stats['approved_sponsorships'] ?? 0 ?></div>
                <div class="spon-stat-label">Approved</div>
            </div>
            <div class="spon-stat-card warning">
                <div class="spon-stat-value"><?= $stats['pending_sponsorships'] ?? 0 ?></div>
                <div class="spon-stat-label">Pending</div>
            </div>
            <div class="spon-stat-card secondary">
                <div class="spon-stat-value">
                    $<?= number_format($stats['total_funded'] ?? 0, 2) ?>
                </div>
                <div class="spon-stat-label">Total Funded</div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sponsorships Table -->
    <?php if(!empty($sponsoredEvents)): ?>
        <div class="spon-my-table-wrapper">
            <table class="spon-my-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Organizer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Branding Level</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($sponsoredEvents as $event): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($event['event_title']) ?></strong></td>
                            <td><?= htmlspecialchars($event['organization_name'] ?? 'Event Organizer') ?></td>
                            <td><?= date('M d, Y', strtotime($event['start_at'])) ?></td>
                            <td>
                                <?php if(!empty($event['amount'])): ?>
                                    <strong>$<?= number_format($event['amount'], 2) ?></strong>
                                <?php else: ?>
                                    <span style="color:#999;">To be negotiated</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="text-transform: capitalize; font-weight: 600;">
                                    <?= $event['branding_level'] ?? 'basic' ?>
                                </span>
                            </td>
                            <td>
                                <span class="spon-status-badge spon-status-<?= $event['status'] ?>">
                                    <?= ucfirst($event['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="spon-actions">
                                    <a href="<?= SITE_URL ?>sponSponsor?id=<?= $event['id'] ?>" class="spon-action-btn view">
                                        👁️ View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="spon-empty-state">
            <div class="spon-empty-icon">📋</div>
            <div class="spon-empty-title">No Sponsorships Yet</div>
            <p class="spon-empty-text">You haven't submitted any sponsorship requests yet. Start exploring events and find opportunities to promote your brand!</p>
            <a href="<?= SITE_URL ?>sponFundEvent" class="spon-cta-btn">🎯 Browse Events Now</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
