<?php if(!empty($profile)): ?>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/profile.css">

<div class="profile-wrapper">

    <form method="POST" action="<?=SITE_URL ?>participant/update" enctype="multipart/form-data" class="profile-card">

        <div class="profile-header">
            <div class="avatar-section">
                <div class="avatar-preview">
                    <img src="<?= htmlspecialchars(SITE_URL.ltrim($profile['profile_pic'], '/') ?? '/assets/images/default-profile.png') ?>" alt="Profile Picture" class="profile-pic">
                </div>
                <label class="upload-btn">
                    Change Photo
                    <input type="file" name="profile_pic" hidden>
                </label>
            </div>

            <div class="profile-title">
                <h2>My Profile</h2>
                <p>Manage your personal information</p>
            </div>
        </div>

        <div class="profile-body">

            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="date_of_birth" value="<?= $profile['date_of_birth'] ?>">
            </div>

            <div class="form-group">
                <label>Location</label>
                <input type="text" name="location" placeholder="City, Country" value="<?= $profile['location'] ?>">
            </div>

            <div class="form-group full-width">
                <label>Bio</label>
                <textarea name="bio" rows="4" placeholder="<?= $profile['bio'] ?>"></textarea>
            </div>

            <div class="form-group">
                <label>Interests</label>
                <input type="text" name="interests" placeholder="Tech, Music, Business" value="<?= $profile['interests'] ?>">
            </div>

            <div class="form-group">
                <label>Occupation</label>
                <input type="text" name="occupation" value="<?= $profile['occupation'] ?>">
            </div>

            <div class="form-group">
                <label>Company</label>
                <input type="text" name="company" value="<?= $profile['company'] ?>">
            </div>

            <div class="form-group">
                <label>Education</label>
                <input type="text" name="education" value="<?= $profile['education'] ?>">
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="<?= $profile['phone'] ?>">
            </div>

        </div>

        <div class="profile-footer">
            <button type="submit" class="btn-primary">Save Changes</button>
        </div>

    </form>

</div>
<?php else: ?>
    echo "No profile found";    
<?php endif; ?>