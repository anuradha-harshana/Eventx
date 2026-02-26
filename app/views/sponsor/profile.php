<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<div class="profile-container">
    <h1>My Sponsor Profile</h1>
    
    <!-- Profile Update Form -->
    <div class="profile-section">
        <h2>Profile Information</h2>
        <form method="POST" action="<?=SITE_URL ?>sponsor/update" enctype="multipart/form-data">
            <div class="organizer-details">

                <label>Brand Name</label>
                <input type="text" placeholder="Brand Name" name="brand_name" value="<?= htmlspecialchars($profile['brand_name'] ?? '') ?>">

                <label>Industry</label>
                <input type="text" placeholder="Tech" name="industry" value="<?= htmlspecialchars($profile['industry'] ?? '') ?>">

                <label>Description</label>
                <textarea name="description" placeholder="Describe your brand..."><?= htmlspecialchars($profile['description'] ?? '') ?></textarea>

                <label>Website Url</label>
                <input type="text" placeholder="https://example.com" name="website" value="<?= htmlspecialchars($profile['website'] ?? '') ?>">

                <label>Contact Email</label>
                <input type="email" placeholder="contact@example.com" name="contact_email" value="<?= htmlspecialchars($profile['contact_email'] ?? '') ?>">

                <label>Phone</label>
                <input type="text" placeholder="077 807 9685" name="contact_phone" value="<?= htmlspecialchars($profile['contact_phone'] ?? '') ?>">

                <label>Budget Range</label>
                <input type="text" placeholder="10K-60K" name="budget_range" value="<?= htmlspecialchars($profile['budget_range'] ?? '') ?>">

                <label>Logo Pic</label>
                <input type="file" name="profile_pic">

                <button type="submit">Update Profile</button>
            </div>
        </form>
    </div>

    <!-- Portfolio Items Section -->
    <div class="portfolio-section">
        <h2>My Portfolio</h2>
        
        <?php if(empty($portfolioItems)): ?>
            <div class="no-items">
                <p>No portfolio items yet.</p>
                <a href="<?= SITE_URL ?>sponAddPortfolio" class="btn-primary">Add First Portfolio Item</a>
            </div>
        <?php else: ?>
            <div class="portfolio-grid">
                <?php foreach($portfolioItems as $item): ?>
                    <div class="portfolio-card">
                        <div class="portfolio-image">
                            <?php if(!empty($item['image_url'])): ?>
                                <img src="<?= SITE_URL ?>uploads/portfolio/<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                            <?php else: ?>
                                <div class="placeholder-image">No Image</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="portfolio-content">
                            <h3><?= htmlspecialchars($item['title']) ?></h3>
                            <p class="category"><?= htmlspecialchars($item['sponsorship_category'] ?? 'Uncategorized') ?></p>
                            
                            <?php if(!empty($item['brand_description'])): ?>
                                <p class="description"><?= htmlspecialchars(substr($item['brand_description'], 0, 100)) ?>...</p>
                            <?php endif; ?>
                            
                            <p class="event-info">
                                <span class="event-name"><?= htmlspecialchars($item['event_name'] ?? 'N/A') ?></span>
                                <span class="year"><?= htmlspecialchars($item['year'] ?? '') ?></span>
                            </p>
                            
                            <div class="portfolio-actions">
                                <a href="<?= SITE_URL ?>sponEditPortfolio?id=<?= $item['id'] ?>" class="btn-edit">Edit</a>
                                <form method="POST" action="<?= SITE_URL ?>sponsor/deletePortfolio" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="portfolio_id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="portfolio-actions-footer">
                <a href="<?= SITE_URL ?>sponAddPortfolio" class="btn-primary">Add New Portfolio Item</a>
                <a href="<?= SITE_URL ?>sponPortfolio" class="btn-secondary">View Portfolio</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .profile-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .profile-container h1 {
        margin-bottom: 2rem;
        font-size: 2rem;
        color: #333;
    }

    .profile-section {
        background: #f9f9f9;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .profile-section h2 {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        color: #333;
    }

    .organizer-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .organizer-details label {
        grid-column: 1 / -1;
        font-weight: 600;
        color: #333;
        margin-top: 0.5rem;
    }

    .organizer-details input,
    .organizer-details textarea {
        grid-column: 1 / -1;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        font-family: inherit;
    }

    .organizer-details textarea {
        resize: vertical;
        min-height: 100px;
    }

    .organizer-details button {
        grid-column: 1 / -1;
        padding: 0.75rem 1.5rem;
        background: #6366f1;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
    }

    .organizer-details button:hover {
        background: #4f46e5;
    }

    .portfolio-section {
        background: #f9f9f9;
        padding: 2rem;
        border-radius: 8px;
    }

    .portfolio-section h2 {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        color: #333;
    }

    .no-items {
        text-align: center;
        padding: 2rem;
        color: #666;
    }

    .no-items .btn-primary {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: #6366f1;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 1rem;
    }

    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .portfolio-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .portfolio-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .portfolio-image {
        width: 100%;
        height: 180px;
        overflow: hidden;
        background: #f0f0f0;
    }

    .portfolio-image img {
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
        font-size: 0.9rem;
    }

    .portfolio-content {
        padding: 1rem;
    }

    .portfolio-content h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
        color: #333;
    }

    .portfolio-content .category {
        margin: 0 0 0.5rem 0;
        font-size: 0.85rem;
        color: #6366f1;
        font-weight: 600;
    }

    .portfolio-content .description {
        margin: 0 0 0.5rem 0;
        font-size: 0.9rem;
        color: #666;
        line-height: 1.4;
    }

    .portfolio-content .event-info {
        margin: 0.5rem 0;
        font-size: 0.9rem;
        color: #999;
    }

    .portfolio-content .event-name {
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .portfolio-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }

    .portfolio-actions a,
    .portfolio-actions button {
        flex: 1;
        padding: 0.5rem;
        text-align: center;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background 0.3s;
    }

    .btn-edit {
        background: #3b82f6;
        color: white;
    }

    .btn-edit:hover {
        background: #2563eb;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .portfolio-actions-footer {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .portfolio-actions-footer a {
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 600;
        transition: background 0.3s;
    }

    .btn-primary {
        background: #6366f1;
        color: white;
    }

    .btn-primary:hover {
        background: #4f46e5;
    }

    .btn-secondary {
        background: #10b981;
        color: white;
    }

    .btn-secondary:hover {
        background: #059669;
    }

    @media (max-width: 768px) {
        .organizer-details {
            grid-template-columns: 1fr;
        }

        .portfolio-grid {
            grid-template-columns: 1fr;
        }

        .portfolio-actions-footer {
            flex-direction: column;
        }
    }
</style>
