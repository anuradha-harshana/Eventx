<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<div class="profile-container">
    <div class="edit-header">
        <a href="<?= SITE_URL ?>sponProf" class="back-link">← Back to Profile</a>
        <h1>Edit Your Sponsor Profile</h1>
    </div>

    <div class="edit-section">
        <form method="POST" action="<?= SITE_URL ?>sponsor/update" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label for="brand_name" class="required">Brand Name</label>
                    <input type="text" id="brand_name" name="brand_name" placeholder="Enter your brand name" value="<?= htmlspecialchars($profile['brand_name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="industry">Industry</label>
                    <input type="text" id="industry" name="industry" placeholder="e.g., Technology, Finance, Retail" value="<?= htmlspecialchars($profile['industry'] ?? '') ?>">
                </div>

                <div class="form-group col-full">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Tell us about your brand..." rows="5"><?= htmlspecialchars($profile['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="website">Website URL</label>
                    <input type="url" id="website" name="website" placeholder="https://example.com" value="<?= htmlspecialchars($profile['website'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="contact_email">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" placeholder="contact@brand.com" value="<?= htmlspecialchars($profile['contact_email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="contact_phone">Contact Phone</label>
                    <input type="tel" id="contact_phone" name="contact_phone" placeholder="+1 (555) 000-0000" value="<?= htmlspecialchars($profile['contact_phone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="budget_range">Budget Range</label>
                    <input type="text" id="budget_range" name="budget_range" placeholder="e.g., 10K-50K" value="<?= htmlspecialchars($profile['budget_range'] ?? '') ?>">
                </div>

                <div class="form-group col-full">
                    <label for="profile_pic">Logo/Profile Picture</label>
                    
                    <?php if(!empty($profile['profile_pic'])): ?>
                        <div class="current-image">
                            <img src="<?= SITE_URL ?>uploads/profile/<?= htmlspecialchars($profile['profile_pic']) ?>" alt="Current logo">
                            <p class="small-text">Current logo</p>
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
                    <small>Leave empty to keep current logo. Recommended: Square image, at least 200x200px</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="<?= SITE_URL ?>sponProf" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .profile-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .edit-header {
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

    .edit-header h1 {
        margin: 0;
        font-size: 2rem;
        color: #333;
    }

    .edit-section {
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.col-full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
        font-size: 0.95rem;
    }

    .form-group label.required::after {
        content: " *";
        color: #ef4444;
    }

    .form-group input,
    .form-group textarea {
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
        font-family: inherit;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .current-image {
        margin-bottom: 1rem;
    }

    .current-image img {
        max-width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 4px;
        border: 2px solid #ddd;
    }

    .current-image .small-text {
        margin: 0.5rem 0 0 0;
        color: #999;
        font-size: 0.85rem;
    }

    .form-group small {
        display: block;
        margin-top: 0.25rem;
        color: #999;
        font-size: 0.85rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn-primary,
    .btn-secondary {
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
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
        background: #e5e7eb;
        color: #333;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.col-full {
            grid-column: 1;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary,
        .btn-secondary {
            width: 100%;
        }
    }
</style>
