<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<div class="portfolio-item-container">
    <div class="portfolio-header">
        <a href="<?= SITE_URL ?>sponPortfolio" class="back-link">← Back to Portfolio</a>
        <h1><?= htmlspecialchars($item['title']) ?></h1>
    </div>

    <div class="portfolio-detail-content">
        <!-- Main Image -->
        <div class="portfolio-main-image">
            <?php if(!empty($item['banner_url'])): ?>
                <?php 
                    $bannerPath = $item['banner_url'];
                    if(!str_starts_with($bannerPath, '/uploads/')) {
                        $bannerPath = 'uploads/portfolio/' . basename($bannerPath);
                    }
                ?>
                <img src="<?= SITE_URL . ltrim($bannerPath, '/') ?>" alt="Banner" onerror="this.style.display='none'">
            <?php elseif(!empty($item['image_url'])): ?>
                <?php 
                    $imagePath = $item['image_url'];
                    if(!str_starts_with($imagePath, '/uploads/')) {
                        $imagePath = 'uploads/portfolio/' . basename($imagePath);
                    }
                ?>
                <img src="<?= SITE_URL . ltrim($imagePath, '/') ?>" alt="Image" onerror="this.style.display='none'">
            <?php else: ?>
                <div class="placeholder-image">No Image Available</div>
            <?php endif; ?>
        </div>

        <div class="portfolio-detail-grid">
            <!-- Left Column -->
            <div class="portfolio-left">
                <!-- Logo -->
                <?php if(!empty($item['logo_url'])): ?>
                    <?php 
                        $logoPath = $item['logo_url'];
                        if(!str_starts_with($logoPath, '/uploads/')) {
                            $logoPath = 'uploads/portfolio/' . basename($logoPath);
                        }
                    ?>
                    <div class="portfolio-logo">
                        <img src="<?= SITE_URL . ltrim($logoPath, '/') ?>" alt="Logo" onerror="this.style.display='none'">
                    </div>
                <?php endif; ?>

                <!-- Basic Information -->
                <div class="portfolio-info-card">
                    <h2>Event Information</h2>
                    <div class="info-row">
                        <span class="info-label">Event Name:</span>
                        <span class="info-value"><?= htmlspecialchars($item['event_name'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Year:</span>
                        <span class="info-value"><?= htmlspecialchars($item['year'] ?? 'N/A') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Category:</span>
                        <span class="info-value"><?= htmlspecialchars($item['sponsorship_category'] ?? 'Uncategorized') ?></span>
                    </div>
                    <?php if(!empty($item['past_collaboration'])): ?>
                        <div class="info-row">
                            <span class="info-label">Past Collaboration:</span>
                            <span class="info-value"><?= htmlspecialchars($item['past_collaboration']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Status Badge -->
                <div class="portfolio-status">
                    <span class="status-badge status-<?= strtolower($item['status'] ?? 'pending') ?>">
                        <?= ucfirst($item['status'] ?? 'pending') ?>
                    </span>
                </div>
            </div>

            <!-- Right Column -->
            <div class="portfolio-right">
                <!-- Description -->
                <div class="portfolio-description-card">
                    <h3>Description</h3>
                    <p><?= nl2br(htmlspecialchars($item['brand_description'] ?? 'No description provided')) ?></p>
                </div>

                <!-- Sponsor Information -->
                <?php if(!empty($item['brand_name'])): ?>
                    <div class="portfolio-sponsor-card">
                        <h3>About the Sponsor</h3>
                        <div class="sponsor-details">
                            <div class="sponsor-name">
                                <strong><?= htmlspecialchars($item['brand_name']) ?></strong>
                            </div>
                            <?php if(!empty($item['industry'])): ?>
                                <div class="sponsor-industry">
                                    <span class="label">Industry:</span>
                                    <span class="value"><?= htmlspecialchars($item['industry']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($item['website'])): ?>
                                <div class="sponsor-website">
                                    <span class="label">Website:</span>
                                    <a href="<?= htmlspecialchars($item['website']) ?>" target="_blank" rel="noopener">
                                        <?= htmlspecialchars($item['website']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($item['contact_email'])): ?>
                                <div class="sponsor-contact">
                                    <span class="label">Email:</span>
                                    <a href="mailto:<?= htmlspecialchars($item['contact_email']) ?>">
                                        <?= htmlspecialchars($item['contact_email']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($item['contact_phone'])): ?>
                                <div class="sponsor-phone">
                                    <span class="label">Phone:</span>
                                    <span class="value"><?= htmlspecialchars($item['contact_phone']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Metadata -->
                <div class="portfolio-meta">
                    <div class="meta-item">
                        <span class="meta-label">Created:</span>
                        <span class="meta-value"><?= date('M d, Y', strtotime($item['created_at'] ?? 'now')) ?></span>
                    </div>
                    <?php if(!empty($item['updated_at']) && $item['updated_at'] != $item['created_at']): ?>
                        <div class="meta-item">
                            <span class="meta-label">Updated:</span>
                            <span class="meta-value"><?= date('M d, Y', strtotime($item['updated_at'])) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .portfolio-item-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .portfolio-header {
        margin-bottom: 2rem;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 1rem;
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: #4f46e5;
    }

    .portfolio-header h1 {
        font-size: 2.5rem;
        color: #333;
        margin: 0;
    }

    .portfolio-main-image {
        width: 100%;
        height: 400px;
        margin-bottom: 2rem;
        border-radius: 8px;
        overflow: hidden;
        background: #f0f0f0;
    }

    .portfolio-main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .placeholder-image {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background: #e0e0e0;
        color: #666;
        font-size: 1.2rem;
    }

    .portfolio-detail-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
    }

    .portfolio-left {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .portfolio-logo {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .portfolio-logo img {
        width: 100%;
        height: 200px;
        object-fit: contain;
    }

    .portfolio-info-card,
    .portfolio-sponsor-card,
    .portfolio-description-card,
    .portfolio-meta {
        background: white;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .portfolio-info-card h2,
    .portfolio-sponsor-card h3,
    .portfolio-description-card h3 {
        margin-top: 0;
        margin-bottom: 1rem;
        color: #333;
        font-size: 1.1rem;
    }

    .info-row {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }

    .info-row:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
    }

    .info-value {
        color: #333;
    }

    .portfolio-status {
        text-align: center;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
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

    .portfolio-right {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .portfolio-description-card p {
        line-height: 1.6;
        color: #555;
        margin: 0;
    }

    .sponsor-details {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .sponsor-name {
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .sponsor-industry,
    .sponsor-website,
    .sponsor-contact,
    .sponsor-phone {
        display: grid;
        grid-template-columns: 80px 1fr;
        gap: 1rem;
    }

    .sponsor-details .label {
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
    }

    .sponsor-details .value,
    .sponsor-details a {
        color: #333;
        text-decoration: none;
    }

    .sponsor-details a:hover {
        color: #6366f1;
        text-decoration: underline;
    }

    .portfolio-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .meta-item {
        display: grid;
        grid-template-columns: 80px 1fr;
        gap: 1rem;
    }

    .meta-label {
        font-weight: 600;
        color: #666;
        font-size: 0.9rem;
    }

    .meta-value {
        color: #333;
    }

    @media (max-width: 768px) {
        .portfolio-detail-grid {
            grid-template-columns: 1fr;
        }

        .portfolio-header h1 {
            font-size: 1.8rem;
        }

        .portfolio-main-image {
            height: 250px;
        }

        .info-row,
        .sponsor-industry,
        .sponsor-website,
        .sponsor-contact,
        .sponsor-phone,
        .meta-item {
            grid-template-columns: 1fr;
        }

        .portfolio-meta {
            grid-template-columns: 1fr;
        }
    }
</style>
