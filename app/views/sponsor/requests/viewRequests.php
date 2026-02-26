<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>

<style>
    .req-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .req-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }

    .req-header h1 {
        font-size: 2.5rem;
        color: #333;
        margin: 0;
    }

    .req-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .req-stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .req-stat-card.pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .req-stat-card.negotiating {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .req-stat-card.approved {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .req-stat-card.rejected {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .req-stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .req-stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .req-filters {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .req-filter-btn {
        padding: 10px 20px;
        border: 2px solid #ddd;
        background: white;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .req-filter-btn:hover {
        border-color: #667eea;
        color: #667eea;
    }

    .req-filter-btn.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    .req-table-wrapper {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .req-table {
        width: 100%;
        border-collapse: collapse;
    }

    .req-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
    }

    .req-table th {
        padding: 18px 20px;
        text-align: left;
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }

    .req-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: background 0.3s;
    }

    .req-table tbody tr:hover {
        background: #f9f9f9;
    }

    .req-table td {
        padding: 18px 20px;
        font-size: 0.95rem;
        color: #666;
    }

    .req-table td strong {
        color: #333;
    }

    .req-status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .req-status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .req-status-negotiating {
        background: #cfe2ff;
        color: #084298;
    }

    .req-status-approved {
        background: #d4edda;
        color: #155724;
    }

    .req-status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .req-action-btn {
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
        background: #e3f2fd;
        color: #1976d2;
    }

    .req-action-btn:hover {
        background: #1976d2;
        color: white;
    }

    .req-empty {
        text-align: center;
        padding: 60px 20px;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .req-empty-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .req-empty-title {
        font-size: 1.3rem;
        color: #333;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .req-empty-text {
        color: #666;
    }

    @media (max-width: 768px) {
        .req-table {
            font-size: 0.85rem;
        }

        .req-table th,
        .req-table td {
            padding: 12px 10px;
        }
    }
</style>

<div class="req-container">
    <div class="req-header">
        <div>
            <h1>📬 Sponsorship Requests</h1>
            <p style="color:#666;margin:5px 0 0 0;">Review sponsorship requests from event organizers</p>
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

    <!-- Statistics Cards -->
    <?php if(!empty($stats)): ?>
        <div class="req-stats">
            <div class="req-stat-card">
                <div class="req-stat-value"><?= $stats['total_requests'] ?? 0 ?></div>
                <div class="req-stat-label">Total Requests</div>
            </div>
            <div class="req-stat-card pending">
                <div class="req-stat-value"><?= $stats['pending_requests'] ?? 0 ?></div>
                <div class="req-stat-label">Pending</div>
            </div>
            <div class="req-stat-card negotiating">
                <div class="req-stat-value"><?= $stats['negotiating_requests'] ?? 0 ?></div>
                <div class="req-stat-label">Negotiating</div>
            </div>
            <div class="req-stat-card approved">
                <div class="req-stat-value"><?= $stats['approved_requests'] ?? 0 ?></div>
                <div class="req-stat-label">Approved</div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="req-filters">
        <a href="<?= SITE_URL ?>sponRequests">
            <button class="req-filter-btn <?= empty($filter) ? 'active' : '' ?>">All</button>
        </a>
        <a href="<?= SITE_URL ?>sponRequests?status=pending">
            <button class="req-filter-btn <?= $filter === 'pending' ? 'active' : '' ?>">Pending</button>
        </a>
        <a href="<?= SITE_URL ?>sponRequests?status=negotiating">
            <button class="req-filter-btn <?= $filter === 'negotiating' ? 'active' : '' ?>">Negotiating</button>
        </a>
        <a href="<?= SITE_URL ?>sponRequests?status=approved">
            <button class="req-filter-btn <?= $filter === 'approved' ? 'active' : '' ?>">Approved</button>
        </a>
        <a href="<?= SITE_URL ?>sponRequests?status=rejected">
            <button class="req-filter-btn <?= $filter === 'rejected' ? 'active' : '' ?>">Rejected</button>
        </a>
    </div>

    <!-- Requests Table -->
    <?php if(!empty($incomingRequests)): ?>
        <div class="req-table-wrapper">
            <table class="req-table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Organizer</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Tier</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($incomingRequests as $req): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($req['event_title']) ?></strong></td>
                            <td><?= htmlspecialchars($req['organization_name'] ?? 'Organizer') ?></td>
                            <td><?= date('M d, Y', strtotime($req['start_at'])) ?></td>
                            <td>
                                <?php if(!empty($req['amount'])): ?>
                                    <strong>$<?= number_format($req['amount'], 2) ?></strong>
                                <?php else: ?>
                                    <span style="color:#999;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="text-transform: capitalize; font-weight: 600;">
                                    <?= $req['branding_level'] ?? 'basic' ?>
                                </span>
                            </td>
                            <td>
                                <span class="req-status-badge req-status-<?= $req['status'] ?>">
                                    <?= ucfirst($req['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d', strtotime($req['created_at'])) ?></td>
                            <td>
                                <a href="<?= SITE_URL ?>sponRequest?id=<?= $req['id'] ?>" class="req-action-btn">
                                    👁️ Review
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="req-empty">
            <div class="req-empty-icon">📭</div>
            <div class="req-empty-title">No Sponsorship Requests</div>
            <p class="req-empty-text">You don't have any sponsorship requests at this time. Check back later!</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>
