<?php
?>
<style>
    .portfolio-form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .form-header {
        margin-bottom: 30px;
    }

    .form-header h2 {
        font-size: 28px;
        color: #333;
        margin: 0 0 10px 0;
        font-weight: 600;
    }

    .form-header p {
        color: #666;
        margin: 0;
        font-size: 16px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #333;
        font-weight: 600;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-group small {
        display: block;
        color: #999;
        margin-top: 6px;
        font-size: 12px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 600px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .file-upload-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 15px;
        margin-bottom: 24px;
    }

    .file-upload {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }

    .file-upload:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .file-upload input[type="file"] {
        display: none;
    }

    .file-upload label {
        cursor: pointer;
        display: block;
        margin: 0;
        font-weight: 600;
        font-size: 12px;
    }

    .upload-icon {
        font-size: 28px;
        margin-bottom: 8px;
        display: block;
    }

    .upload-text {
        font-size: 11px;
        color: #999;
        margin-top: 6px;
    }

    .preview-image {
        max-width: 100%;
        max-height: 100px;
        border-radius: 6px;
        margin-top: 8px;
    }

    .current-file {
        margin-top: 10px;
        padding: 10px;
        background: #f0f0f0;
        border-radius: 6px;
        font-size: 12px;
        color: #666;
    }

    .current-file img {
        max-width: 100%;
        max-height: 80px;
        border-radius: 4px;
        margin-bottom: 8px;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        flex: 1;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #333;
        flex: 1;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    .required::after {
        content: " *";
        color: #f5576c;
    }

    .status-info {
        padding: 15px;
        background: rgba(102, 126, 234, 0.1);
        border-left: 4px solid #667eea;
        border-radius: 4px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 10px;
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
</style>

<div class="portfolio-form-container">
    <div class="form-header">
        <h2>Edit Portfolio Item</h2>
        <p>Update your portfolio item details and media</p>
    </div>

    <?php if($portfolioItem) { ?>
        <!-- Status Info -->
        <div class="status-info">
            <strong>Current Status:</strong>
            <div style="margin-top: 8px;">
                <span class="status-badge status-<?php echo strtolower($portfolioItem['status']); ?>">
                    <?php echo ucfirst($portfolioItem['status']); ?>
                </span>
            </div>
        </div>

        <form method="POST" action="<?php echo SITE_URL; ?>sponsor/updatePortfolio" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $portfolioItem['id']; ?>">

            <!-- Title -->
            <div class="form-group">
                <label for="title" class="required">Portfolio Item Title</label>
                <input type="text" id="title" name="title"
                       value="<?php echo htmlspecialchars($portfolioItem['title']); ?>"
                       required maxlength="100">
                <small>Give your portfolio item a clear, descriptive title</small>
            </div>

            <!-- Brand Description -->
            <div class="form-group">
                <label for="brand_description" class="required">Brand Description</label>
                <textarea id="brand_description" name="brand_description" required><?php echo htmlspecialchars($portfolioItem['brand_description'] ?? ''); ?></textarea>
                <small>Share details about your brand's role and achievements</small>
            </div>

            <!-- Sponsorship Category -->
            <div class="form-group">
                <label for="sponsorship_category" class="required">Sponsorship Category</label>
                <select id="sponsorship_category" name="sponsorship_category" required>
                    <option value="">-- Select Category --</option>
                    <option value="Title Sponsor" <?php echo $portfolioItem['sponsorship_category'] === 'Title Sponsor' ? 'selected' : ''; ?>>Title Sponsor</option>
                    <option value="Presenting Sponsor" <?php echo $portfolioItem['sponsorship_category'] === 'Presenting Sponsor' ? 'selected' : ''; ?>>Presenting Sponsor</option>
                    <option value="Gold Sponsor" <?php echo $portfolioItem['sponsorship_category'] === 'Gold Sponsor' ? 'selected' : ''; ?>>Gold Sponsor</option>
                    <option value="Silver Sponsor" <?php echo $portfolioItem['sponsorship_category'] === 'Silver Sponsor' ? 'selected' : ''; ?>>Silver Sponsor</option>
                    <option value="Bronze Sponsor" <?php echo $portfolioItem['sponsorship_category'] === 'Bronze Sponsor' ? 'selected' : ''; ?>>Bronze Sponsor</option>
                    <option value="Technology Partner" <?php echo $portfolioItem['sponsorship_category'] === 'Technology Partner' ? 'selected' : ''; ?>>Technology Partner</option>
                    <option value="Community Partner" <?php echo $portfolioItem['sponsorship_category'] === 'Community Partner' ? 'selected' : ''; ?>>Community Partner</option>
                    <option value="Associate Sponsor" <?php echo $portfolioItem['sponsorship_category'] === 'Associate Sponsor' ? 'selected' : ''; ?>>Associate Sponsor</option>
                    <option value="Media Partner" <?php echo $portfolioItem['sponsorship_category'] === 'Media Partner' ? 'selected' : ''; ?>>Media Partner</option>
                    <option value="In-Kind Sponsor" <?php echo $portfolioItem['sponsorship_category'] === 'In-Kind Sponsor' ? 'selected' : ''; ?>>In-Kind Sponsor</option>
                </select>
                <small>Select the sponsorship level or category</small>
            </div>

            <!-- Past Collaboration -->
            <div class="form-group">
                <label for="past_collaboration">Past Collaboration</label>
                <input type="text" id="past_collaboration" name="past_collaboration"
                       value="<?php echo htmlspecialchars($portfolioItem['past_collaboration'] ?? ''); ?>">
                <small>Reference any previous collaborations or partnerships</small>
            </div>

            <!-- Event Name & Year -->
            <div class="form-row">
                <div class="form-group">
                    <label for="event_name">Event Name</label>
                    <input type="text" id="event_name" name="event_name"
                           value="<?php echo htmlspecialchars($portfolioItem['event_name'] ?? ''); ?>">
                    <small>Name of the event you sponsored</small>
                </div>
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="number" id="year" name="year" min="2000" max="<?php echo date('Y'); ?>"
                           value="<?php echo $portfolioItem['year'] ?? date('Y'); ?>">
                    <small>Year of sponsorship</small>
                </div>
            </div>

            <!-- File Uploads -->
            <h3 style="margin: 30px 0 20px 0; color: #333; font-weight: 600;">Media Files</h3>
            <div class="file-upload-group">
                <!-- Logo Upload -->
                <div class="file-upload" onclick="document.getElementById('logo').click()">
                    <input type="file" id="logo" name="logo" accept="image/*">
                    <span class="upload-icon">🏢</span>
                    <label>Logo</label>
                    <div class="upload-text">Click to update</div>
                    <?php if($portfolioItem['logo_url']) { 
                        $logoPath = $portfolioItem['logo_url'];
                        if(!str_starts_with($logoPath, '/uploads/')) {
                            $logoPath = 'uploads/portfolio/' . basename($logoPath);
                        }
                    ?>
                        <div class="current-file">
                            <img src="<?php echo SITE_URL . ltrim($logoPath, '/'); ?>"
                                 alt="Logo" onerror="this.style.display='none'">
                            <div>Current Logo</div>
                        </div>
                    <?php } ?>
                </div>

                <!-- Banner Upload -->
                <div class="file-upload" onclick="document.getElementById('banner').click()">
                    <input type="file" id="banner" name="banner" accept="image/*">
                    <span class="upload-icon">🖼️</span>
                    <label>Banner</label>
                    <div class="upload-text">Click to update</div>
                    <?php if($portfolioItem['banner_url']) { 
                        $bannerPath = $portfolioItem['banner_url'];
                        if(!str_starts_with($bannerPath, '/uploads/')) {
                            $bannerPath = 'uploads/portfolio/' . basename($bannerPath);
                        }
                    ?>
                        <div class="current-file">
                            <img src="<?php echo SITE_URL . ltrim($bannerPath, '/'); ?>"
                                 alt="Banner" onerror="this.style.display='none'">
                            <div>Current Banner</div>
                        </div>
                    <?php } ?>
                </div>

                <!-- Image Upload -->
                <div class="file-upload" onclick="document.getElementById('image').click()">
                    <input type="file" id="image" name="image" accept="image/*">
                    <span class="upload-icon">📸</span>
                    <label>Image</label>
                    <div class="upload-text">Click to update</div>
                    <?php if($portfolioItem['image_url']) { 
                        $imgPath = $portfolioItem['image_url'];
                        if(!str_starts_with($imgPath, '/uploads/')) {
                            $imgPath = 'uploads/portfolio/' . basename($imgPath);
                        }
                    ?>
                        <div class="current-file">
                            <img src="<?php echo SITE_URL . ltrim($imgPath, '/'); ?>"
                                 alt="Image" onerror="this.style.display='none'">
                            <div>Current Image</div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="<?php echo SITE_URL; ?>sponPortfolio" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Portfolio Item</button>
            </div>
        </form>
    <?php } else { ?>
        <p style="color: #666; text-align: center; padding: 40px 0;">
            Portfolio item not found. <a href="<?php echo SITE_URL; ?>sponPortfolio">Go back to portfolio</a>
        </p>
    <?php } ?>
</div>

<script>
    // Preview images when selected
    function previewImage(inputId, displayId) {
        const input = document.getElementById(inputId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Show preview
                const previewDiv = document.getElementById(displayId);
                if (previewDiv) {
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" class="preview-image" alt="Preview">
                        <div style="margin-top: 8px; font-size: 12px; color: #666;">New Image Selected</div>
                    `;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('logo').addEventListener('change', function() {
        previewImage('logo', 'logoPreview');
    });

    document.getElementById('banner').addEventListener('change', function() {
        previewImage('banner', 'bannerPreview');
    });

    document.getElementById('image').addEventListener('change', function() {
        previewImage('image', 'imagePreview');
    });
</script>
