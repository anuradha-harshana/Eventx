<?php

?>

<style>
    .portfolio-section {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .portfolio-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .portfolio-header h2 {
        font-size: 28px;
        color: #333;
        margin: 0;
        font-weight: 600;
    }

    .portfolio-stats {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .stat-card {
        flex: 1;
        min-width: 150px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .stat-card.approved {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }

    .stat-card.pending {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card.rejected {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    }

    .stat-card h3 {
        margin: 0 0 10px 0;
        font-size: 24px;
        font-weight: 700;
    }

    .stat-card p {
        margin: 0;
        font-size: 14px;
        opacity: 0.9;
    }

    .add-portfolio-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .add-portfolio-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(102, 126, 234, 0.4);
    }

    .portfolio-items {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }

    .portfolio-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .portfolio-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .portfolio-card-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f0f0f0;
    }

    .portfolio-card-content {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .portfolio-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0 0 10px 0;
    }

    .portfolio-card-category {
        display: inline-block;
        background: #667eea;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 10px;
        width: fit-content;
    }

    .portfolio-card-description {
        font-size: 13px;
        color: #666;
        margin: 0 0 12px 0;
        flex: 1;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .portfolio-card-meta {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        color: #999;
        margin-bottom: 12px;
        padding-top: 12px;
        border-top: 1px solid #eee;
    }

    .portfolio-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .portfolio-card-actions {
        display: flex;
        gap: 10px;
        margin-top: 12px;
    }

    .portfolio-card-actions button,
    .portfolio-card-actions a {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        text-align: center;
    }

    .edit-btn {
        background: #667eea;
        color: white;
    }

    .edit-btn:hover {
        background: #5568d3;
    }

    .delete-btn {
        background: #f5576c;
        color: white;
        border: none;
    }

    .delete-btn:hover {
        background: #e63a52;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 20px;
    }

    .empty-state-title {
        font-size: 24px;
        color: #333;
        margin: 0 0 10px 0;
        font-weight: 600;
    }

    .empty-state-text {
        color: #666;
        margin: 0 0 30px 0;
        font-size: 16px;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<div class="portfolio-section">
    <!-- Header -->
    <div class="portfolio-header">
        <div>
            <h2>Portfolio</h2>
            <p style="color: #666; margin: 5px 0 0 0;">Showcase your brand and past collaborations</p>
        </div>
        <a href="<?php echo SITE_URL; ?>sponAddPortfolio" class="add-portfolio-btn">
            + Add Portfolio Item
        </a>
    </div>

    <!-- Alerts -->
    <?php if(isset($_SESSION['success_message'])) { ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success_message'];
            unset($_SESSION['success_message']); ?>
        </div>
    <?php } ?>

    <?php if(isset($_SESSION['error_message'])) { ?>
        <div class="alert alert-error">
            <?php echo $_SESSION['error_message'];
            unset($_SESSION['error_message']); ?>
        </div>
    <?php } ?>

    <!-- Statistics -->
    <?php if($portfolioStats) { ?>
        <div class="portfolio-stats">
            <div class="stat-card">
                <h3><?php echo $portfolioStats['total_items'] ?? 0; ?></h3>
                <p>Total Items</p>
            </div>
            <div class="stat-card approved">
                <h3><?php echo $portfolioStats['approved_items'] ?? 0; ?></h3>
                <p>Approved</p>
            </div>
            <div class="stat-card pending">
                <h3><?php echo $portfolioStats['pending_items'] ?? 0; ?></h3>
                <p>Pending</p>
            </div>
            <div class="stat-card rejected">
                <h3><?php echo $portfolioStats['rejected_items'] ?? 0; ?></h3>
                <p>Rejected</p>
            </div>
        </div>
    <?php } ?>

    <!-- Portfolio Items -->
    <?php if(!empty($portfolioItems)) { ?>
        <div class="portfolio-items">
            <?php foreach($portfolioItems as $item) { ?>
                <div class="portfolio-card">
                    <!-- Image -->
                    <?php 
                        $imgPath = $item['banner_url'];
                        if($imgPath) {
                            if(!str_starts_with($imgPath, '/uploads/')) {
                                $imgPath = 'uploads/portfolio/' . basename($imgPath);
                            }
                            $imgSrc = SITE_URL . ltrim($imgPath, '/');
                        } else {
                            $imgSrc = 'https://via.placeholder.com/300x200?text=Portfolio';
                        }
                    ?>
                    <img src="<?php echo $imgSrc; ?>"
                         alt="<?php echo htmlspecialchars($item['title']); ?>"
                         class="portfolio-card-image"
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=Portfolio';">

                    <!-- Content -->
                    <div class="portfolio-card-content">
                        <h3 class="portfolio-card-title"><?php echo htmlspecialchars($item['title']); ?></h3>

                        <?php if($item['sponsorship_category']) { ?>
                            <span class="portfolio-card-category"><?php echo htmlspecialchars($item['sponsorship_category']); ?></span>
                        <?php } ?>

                        <p class="portfolio-card-description">
                            <?php echo htmlspecialchars(substr($item['brand_description'], 0, 150)); ?>
                            <?php echo strlen($item['brand_description']) > 150 ? '...' : ''; ?>
                        </p>

                        <div class="portfolio-card-meta">
                            <span><?php echo $item['event_name'] ?? 'No event'; ?></span>
                            <span><?php echo $item['year'] ?? date('Y'); ?></span>
                        </div>

                        <div style="text-align: center; padding: 8px 0;">
                            <span class="portfolio-status status-<?php echo strtolower($item['status']); ?>">
                                <?php echo ucfirst($item['status']); ?>
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="portfolio-card-actions">
                            <a href="<?php echo SITE_URL; ?>sponEditPortfolio?id=<?php echo $item['id']; ?>"
                               class="edit-btn">Edit</a>
                            <form method="POST" action="<?php echo SITE_URL; ?>sponsor/deletePortfolio"
                                  style="flex: 1; margin: 0;"
                                  onsubmit="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="delete-btn" style="width: 100%;">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } else { ?>
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">📁</div>
            <h3 class="empty-state-title">No Portfolio Items Yet</h3>
            <p class="empty-state-text">Start building your portfolio by adding your first item to showcase your brand and collaborations.</p>
            <a href="<?php echo SITE_URL; ?>sponAddPortfolio" class="add-portfolio-btn">
                Add Your First Portfolio Item
            </a>
        </div>
    <?php } ?>
</div>
