<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/profile.css">

<div class="profile-container">
    <div class="profile-header">
        <h1>Create Your Sponsor Profile</h1>
        <p>Let's get started! Fill in your brand details to create your sponsor profile.</p>
    </div>

    <div class="profile-form-section">
        <form method="POST" action="<?= SITE_URL ?>sponsor/save" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label for="brand_name" class="required">Brand Name</label>
                    <input type="text" id="brand_name" name="brand_name" placeholder="Enter your brand name" required>
                </div>

                <div class="form-group">
                    <label for="industry">Industry</label>
                    <input type="text" id="industry" name="industry" placeholder="e.g., Technology, Finance, Retail">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Tell us about your brand..." rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label for="website">Website URL</label>
                    <input type="url" id="website" name="website" placeholder="https://example.com">
                </div>

                <div class="form-group">
                    <label for="contact_email">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" placeholder="contact@brand.com">
                </div>

                <div class="form-group">
                    <label for="contact_phone">Contact Phone</label>
                    <input type="tel" id="contact_phone" name="contact_phone" placeholder="+1 (555) 000-0000">
                </div>

                <div class="form-group">
                    <label for="budget_range">Budget Range</label>
                    <input type="text" id="budget_range" name="budget_range" placeholder="e.g., 10K-50K">
                </div>

                <div class="form-group">
                    <label for="profile_pic">Logo/Profile Picture</label>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*">
                    <small>Recommended: Square image, at least 200x200px</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Create Profile</button>
                <a href="<?= SITE_URL ?>sponDash" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
    .profile-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
    }

    .profile-header {
        margin-bottom: 2rem;
        text-align: center;
    }

    .profile-header h1 {
        font-size: 2rem;
        color: #333;
        margin: 0 0 0.5rem 0;
    }

    .profile-header p {
        color: #666;
        font-size: 1.1rem;
    }

    .profile-form-section {
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

    .form-group:nth-child(3),
    .form-group:nth-child(4),
    .form-group:nth-child(8) {
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

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="url"],
    .form-group input[type="file"],
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
        transition: background 0.3s;
        display: inline-block;
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

        .form-group:nth-child(3),
        .form-group:nth-child(4),
        .form-group:nth-child(8) {
            grid-column: 1;
        }
    }
</style>
