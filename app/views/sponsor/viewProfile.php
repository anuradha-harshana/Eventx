<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<div class="profile-container">
    <!-- Hero Section with Profile Picture and Title -->
    <div class="profile-hero">
        <div class="hero-content">
            <div class="profile-pic-large">
                <?php if(!empty($profile['profile_pic'])): ?>
                    <?php 
                        // Handle both absolute relative paths and filenames
                        $picPath = $profile['profile_pic'];
                        if(!str_starts_with($picPath, '/uploads/')) {
                            $picPath = 'uploads/profile/' . basename($picPath);
                        }
                    ?>
                    <img src="<?= SITE_URL . ltrim($picPath, '/') ?>" alt="<?= htmlspecialchars($profile['brand_name']) ?>" onerror="this.style.display='none'">
                <?php else: ?>
                    <div class="pic-placeholder">
                        <span>🏢</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="hero-info">
                <div class="title-badge">
                    <h1><?= htmlspecialchars($profile['brand_name'] ?? 'Your Brand') ?></h1>
                    <?php if($profile['verified']): ?>
                        <span class="verified-badge">✓ Verified</span>
                    <?php endif; ?>
                </div>

                <?php if(!empty($profile['industry'])): ?>
                    <p class="industry-text"><strong>Industry:</strong> <?= htmlspecialchars($profile['industry']) ?></p>
                <?php endif; ?>

                <div class="action-buttons">
                    <a href="<?= SITE_URL ?>sponEditProf" class="btn-primary-action">✎ Edit Profile</a>
                    <button class="btn-danger-action" onclick="openDeleteModal()">🗑 Delete Profile</button>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="content-wrapper">
        <section class="section about-section">
            <h2>About Your Brand</h2>
            <div class="about-card">
                <?php if(!empty($profile['description'])): ?>
                    <p><?= nl2br(htmlspecialchars($profile['description'])) ?></p>
                <?php else: ?>
                    <p class="empty-state">No description provided yet. <a href="<?= SITE_URL ?>sponEditProf">Add one here</a></p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Contact Information Section -->
        <section class="section contact-section">
            <h2>Contact Information</h2>
            <div class="contact-grid">
                <div class="contact-card">
                    <div class="contact-icon">📧</div>
                    <div class="contact-info">
                        <h4>Email</h4>
                        <?php if(!empty($profile['contact_email'])): ?>
                            <a href="mailto:<?= htmlspecialchars($profile['contact_email']) ?>" class="contact-link">
                                <?= htmlspecialchars($profile['contact_email']) ?>
                            </a>
                        <?php else: ?>
                            <p class="not-provided">Not provided</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">📞</div>
                    <div class="contact-info">
                        <h4>Phone</h4>
                        <?php if(!empty($profile['contact_phone'])): ?>
                            <a href="tel:<?= htmlspecialchars($profile['contact_phone']) ?>" class="contact-link">
                                <?= htmlspecialchars($profile['contact_phone']) ?>
                            </a>
                        <?php else: ?>
                            <p class="not-provided">Not provided</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">🌐</div>
                    <div class="contact-info">
                        <h4>Website</h4>
                        <?php if(!empty($profile['website'])): ?>
                            <a href="<?= htmlspecialchars($profile['website']) ?>" target="_blank" rel="noopener" class="contact-link">
                                <?= htmlspecialchars($profile['website']) ?>
                            </a>
                        <?php else: ?>
                            <p class="not-provided">Not provided</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-icon">💰</div>
                    <div class="contact-info">
                        <h4>Budget Range</h4>
                        <?php if(!empty($profile['budget_range'])): ?>
                            <p class="budget-value"><?= htmlspecialchars($profile['budget_range']) ?></p>
                        <?php else: ?>
                            <p class="not-provided">Not provided</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Account Information Section -->
        <section class="section account-section">
            <h2>Account Information</h2>
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-label">Username</div>
                    <div class="info-value"><?= htmlspecialchars($profile['username'] ?? 'N/A') ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">Account Email</div>
                    <div class="info-value"><?= htmlspecialchars($profile['email'] ?? 'N/A') ?></div>
                </div>

                <div class="info-card">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <?php if($profile['verified']): ?>
                            <span class="status-verified">✓ Verified</span>
                        <?php else: ?>
                            <span class="status-pending">○ Pending Verification</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value"><?= date('M d, Y', strtotime($profile['updated_at'] ?? 'now')) ?></div>
                </div>
            </div>
        </section>

        <!-- Portfolio Section -->
        <section class="section portfolio-section">
            <div class="section-header">
                <h2>My Portfolio</h2>
                <a href="<?= SITE_URL ?>sponAddPortfolio" class="btn-add">+ Add Item</a>
            </div>

            <?php if(empty($portfolioItems)): ?>
                <div class="empty-portfolio">
                    <div class="empty-icon">📁</div>
                    <p>No portfolio items yet</p>
                    <a href="<?= SITE_URL ?>sponAddPortfolio" class="btn-secondary">Create Your First Portfolio Item</a>
                </div>
            <?php else: ?>
                <div class="portfolio-list">
                    <?php foreach($portfolioItems as $item): ?>
                        <div class="portfolio-item">
                            <div class="item-image">
                                <?php 
                                    // Try banner first, then image, then logo
                                    $itemImg = null;
                                    foreach(['banner_url', 'image_url', 'logo_url'] as $imgField) {
                                        if(!empty($item[$imgField])) {
                                            $itemImg = $item[$imgField];
                                            break;
                                        }
                                    }
                                ?>
                                <?php if($itemImg): ?>
                                    <?php 
                                        if(!str_starts_with($itemImg, '/uploads/')) {
                                            $itemImg = 'uploads/portfolio/' . basename($itemImg);
                                        }
                                    ?>
                                    <img src="<?= SITE_URL . ltrim($itemImg, '/') ?>" alt="<?= htmlspecialchars($item['title']) ?>" onerror="this.style.display='none'">
                                <?php else: ?>
                                    <div class="image-placeholder">📸</div>
                                <?php endif; ?>
                                <span class="item-status status-<?= strtolower($item['status'] ?? 'pending') ?>">
                                    <?= ucfirst($item['status'] ?? 'pending') ?>
                                </span>
                            </div>

                            <div class="item-details">
                                <h3><?= htmlspecialchars($item['title']) ?></h3>
                                <p class="item-category">📁 <?= htmlspecialchars($item['sponsorship_category'] ?? 'Uncategorized') ?></p>

                                <?php if(!empty($item['event_name'])): ?>
                                    <p class="item-event">📍 <?= htmlspecialchars($item['event_name']) ?> • <?= htmlspecialchars($item['year'] ?? '') ?></p>
                                <?php endif; ?>

                                <?php if(!empty($item['brand_description'])): ?>
                                    <p class="item-description">
                                        <?= htmlspecialchars(substr($item['brand_description'], 0, 150)) ?>
                                        <?php if(strlen($item['brand_description']) > 150): ?>...<?php endif; ?>
                                    </p>
                                <?php endif; ?>

                                <div class="item-actions">
                                    <a href="<?= SITE_URL ?>sponPortfolio" class="action-link view">👁 View All</a>
                                    <a href="<?= SITE_URL ?>sponEditPortfolio?id=<?= $item['id'] ?>" class="action-link edit">✎ Edit</a>
                                    <form method="POST" action="<?= SITE_URL ?>sponsor/deletePortfolio" style="display:inline;" onsubmit="return confirm('Delete this portfolio item?')">
                                        <input type="hidden" name="portfolio_id" value="<?= $item['id'] ?>">
                                        <button type="submit" class="action-link delete">🗑 Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal-overlay" style="display:none;" onclick="closeDeleteModal(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h2>🗑 Delete Your Profile</h2>
        </div>

        <div class="modal-body">
            <p><strong>Are you sure?</strong> This action <strong>cannot be undone</strong>.</p>

            <p>Deleting your profile will permanently remove:</p>
            <ul class="delete-list">
                <li>✗ Your profile information</li>
                <li>✗ All portfolio items (<?= count($portfolioItems) ?> items)</li>
                <li>✗ All sponsorship records</li>
                <li>✗ All related data</li>
            </ul>
        </div>

        <div class="modal-actions">
            <form method="POST" action="<?= SITE_URL ?>sponsor/delete" style="display:inline;">
                <input type="hidden" name="confirm_delete" value="1">
                <button type="submit" class="btn-delete-confirm">Yes, Delete Everything</button>
            </form>
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal(event) {
        if(event && event.target !== document.getElementById('deleteModal')) return;
        document.getElementById('deleteModal').style.display = 'none';
    }
</script>

<style>
    .profile-container {
        max-width: 1400px;
        margin: 0 auto;
        background: #f8f9fa;
        min-height: 100vh;
    }

    /* Hero Section */
    .profile-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 2rem;
    }

    .hero-content {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 3rem;
        align-items: center;
    }

    .profile-pic-large {
        width: 200px;
        height: 200px;
        border-radius: 12px;
        overflow: hidden;
        border: 5px solid white;
        flex-shrink: 0;
        background: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .profile-pic-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pic-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f0f0f0;
        font-size: 3rem;
    }

    .title-badge {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }

    .hero-info h1 {
        margin: 0;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .verified-badge {
        display: inline-block;
        background: #10b981;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .industry-text {
        font-size: 1.1rem;
        margin: 0.5rem 0 1.5rem 0;
        opacity: 0.95;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-primary-action,
    .btn-danger-action {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .btn-primary-action {
        background: white;
        color: #667eea;
    }

    .btn-primary-action:hover {
        background: #f0f0f0;
        transform: translateY(-2px);
    }

    .btn-danger-action {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 1px solid white;
    }

    .btn-danger-action:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
    }

    /* Content Wrapper */
    .content-wrapper {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Sections */
    .section {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .section h2 {
        margin: 0 0 1.5rem 0;
        color: #333;
        font-size: 1.5rem;
        border-bottom: 2px solid #667eea;
        padding-bottom: 1rem;
    }

    /* About Section */
    .about-card {
        line-height: 1.8;
        color: #555;
    }

    .about-card p {
        margin: 0;
    }

    .empty-state {
        color: #999;
        font-style: italic;
    }

    .empty-state a {
        color: #667eea;
        text-decoration: none;
    }

    /* Contact Section */
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .contact-card {
        display: grid;
        grid-template-columns: 60px 1fr;
        gap: 1.5rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        align-items: start;
    }

    .contact-icon {
        font-size: 2rem;
        line-height: 1;
    }

    .contact-info h4 {
        margin: 0 0 0.5rem 0;
        color: #667eea;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .contact-link {
        color: #333;
        text-decoration: none;
        word-break: break-word;
        transition: color 0.3s;
    }

    .contact-link:hover {
        color: #667eea;
        text-decoration: underline;
    }

    .not-provided {
        margin: 0;
        color: #999;
        font-style: italic;
    }

    .budget-value {
        margin: 0;
        color: #333;
        font-weight: 600;
        font-size: 1.1rem;
    }

    /* Account Info Section */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .info-card {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #667eea;
    }

    .info-label {
        color: #667eea;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .info-value {
        color: #333;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .status-verified {
        background: #d1fae5;
        color: #065f46;
        padding: 0.3rem 0.75rem;
        border-radius: 12px;
        font-size: 0.9rem;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
        padding: 0.3rem 0.75rem;
        border-radius: 12px;
        font-size: 0.9rem;
    }

    /* Portfolio Section */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .section-header h2 {
        margin: 0;
        border: none;
        padding: 0;
        flex: 1;
        min-width: 200px;
    }

    .btn-add {
        background: #667eea;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-add:hover {
        background: #5568d3;
    }

    .empty-portfolio {
        text-align: center;
        padding: 3rem 2rem;
        border: 2px dashed #ddd;
        border-radius: 8px;
        color: #666;
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .empty-portfolio p {
        margin: 0 0 1rem 0;
        font-size: 1.1rem;
    }

    .btn-secondary {
        display: inline-block;
        background: #10b981;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.3s;
    }

    .btn-secondary:hover {
        background: #059669;
    }

    .portfolio-list {
        display: grid;
        gap: 1.5rem;
    }

    .portfolio-item {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        transition: all 0.3s;
    }

    .portfolio-item:hover {
        background: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .item-image {
        position: relative;
        width: 200px;
        height: 150px;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e5e7eb;
        font-size: 2rem;
    }

    .item-status {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        padding: 0.3rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .item-details h3 {
        margin: 0 0 0.5rem 0;
        color: #333;
        font-size: 1.2rem;
    }

    .item-category {
        margin: 0 0 0.5rem 0;
        color: #667eea;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .item-event {
        margin: 0 0 0.5rem 0;
        color: #999;
        font-size: 0.9rem;
    }

    .item-description {
        margin: 0.5rem 0 1rem 0;
        color: #666;
        line-height: 1.5;
    }

    .item-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .action-link {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
        display: inline-block;
    }

    .action-link.view {
        background: #dbeafe;
        color: #1e40af;
    }

    .action-link.view:hover {
        background: #bfdbfe;
    }

    .action-link.edit {
        background: #dcfce7;
        color: #166534;
    }

    .action-link.edit:hover {
        background: #bbf7d0;
    }

    .action-link.delete {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-link.delete:hover {
        background: #fecaca;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-box {
        background: white;
        border-radius: 12px;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        background: #fee2e2;
        padding: 1.5rem;
        border-bottom: 1px solid #fecaca;
        border-radius: 12px 12px 0 0;
    }

    .modal-header h2 {
        margin: 0;
        color: #991b1b;
        border: none;
        padding: 0;
        font-size: 1.3rem;
    }

    .modal-body {
        padding: 1.5rem;
        color: #666;
    }

    .modal-body p {
        margin: 0.5rem 0;
        line-height: 1.6;
    }

    .delete-list {
        margin: 1rem 0;
        padding-left: 1.5rem;
        color: #666;
    }

    .delete-list li {
        margin-bottom: 0.5rem;
        color: #dc2626;
    }

    .modal-actions {
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 1rem;
    }

    .modal-actions form {
        flex: 1;
    }

    .btn-delete-confirm,
    .btn-cancel {
        flex: 1;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .btn-delete-confirm {
        background: #dc2626;
        color: white;
    }

    .btn-delete-confirm:hover {
        background: #b91c1c;
    }

    .btn-cancel {
        background: #e5e7eb;
        color: #333;
    }

    .btn-cancel:hover {
        background: #d1d5db;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .title-badge {
            flex-direction: column;
            align-items: flex-start;
        }

        .hero-info h1 {
            font-size: 1.8rem;
        }

        .profile-pic-large {
            width: 150px;
            height: 150px;
        }

        .action-buttons {
            width: 100%;
        }

        .action-buttons a,
        .action-buttons button {
            flex: 1;
        }

        .section {
            padding: 1.5rem;
        }

        .contact-grid,
        .info-grid {
            grid-template-columns: 1fr;
        }

        .portfolio-item {
            grid-template-columns: 1fr;
        }

        .item-image {
            width: 100%;
            height: 200px;
        }

        .item-actions {
            width: 100%;
        }

        .action-link {
            flex: 1;
            text-align: center;
        }

        .modal-box {
            margin: 1rem;
        }
    }
</style>
