<?php if(!empty($profile)): ?>
    <div class="profile-card">
        <img src="<?= htmlspecialchars(SITE_URL.ltrim($profile['profile_pic'], '/') ?? '/assets/images/default-profile.png') ?>" alt="Profile Picture" class="profile-pic">
        <h2><?= htmlspecialchars($profile['bio'] ?? 'No Bio') ?></h2>
        <p><strong>Location:</strong> <?= htmlspecialchars($profile['location'] ?? '-') ?></p>
        <p><strong>Interests:</strong> <?= htmlspecialchars($profile['interests'] ?? '-') ?></p>
        <p><strong>Occupation:</strong> <?= htmlspecialchars($profile['occupation'] ?? '-') ?></p>
    </div>
<?php else: ?>
    <p>No profile found.</p>
<?php endif; ?>
