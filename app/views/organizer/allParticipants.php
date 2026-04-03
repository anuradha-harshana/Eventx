<?php ?>

<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/manageEvent.css">
<link rel="stylesheet" href="<?= SITE_URL ?>assets/css/allParticipants.css">

<div class="all-participants-page">

    <div class="ap-header">
        <a href="<?= SITE_URL ?>manageEvent/<?= htmlspecialchars($event['id']) ?>" class="ap-back-btn">&#8592; Back to Event</a>
        <div class="ap-title-group">
            <h1 class="ap-title">Participants</h1>
            <span class="ap-subtitle"><?= htmlspecialchars($event['title']) ?></span>
        </div>
        <span class="ap-count"><?= count($participants) ?> registered</span>
    </div>

    <?php if (empty($participants)): ?>
        <div class="ap-empty">
            <p>No participants have registered for this event yet.</p>
        </div>
    <?php else: ?>

        <div class="ap-table-wrap">
            <table class="ap-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Participant</th>
                        <th>Contact</th>
                        <th>Details</th>
                        <th>Registration</th>
                        <th>Check-in</th>
                        <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($participants as $i => $p): ?>
                    <tr>
                        <!-- # -->
                        <td class="ap-num"><?= $i + 1 ?></td>

                        <!-- Participant identity -->
                        <td class="ap-identity">
                            <img
                                class="ap-avatar"
                                src="<?= $p['profile_pic'] ? SITE_URL . 'uploads/profile/' . htmlspecialchars($p['profile_pic']) : SITE_URL . 'assets/images/default-avatar.png' ?>"
                                alt="avatar"
                            >
                            <div>
                                <div class="ap-name"><?= htmlspecialchars($p['full_name'] ?: $p['username']) ?></div>
                                <div class="ap-username">@<?= htmlspecialchars($p['username']) ?></div>
                                <?php if ($p['badges_earned']): ?>
                                    <div class="ap-badges">🏅 <?= (int)$p['badges_earned'] ?> badge<?= $p['badges_earned'] != 1 ? 's' : '' ?></div>
                                <?php endif; ?>
                            </div>
                        </td>

                        <!-- Contact -->
                        <td class="ap-contact">
                            <div><?= htmlspecialchars($p['email']) ?></div>
                            <?php if ($p['phone']): ?>
                                <div class="ap-secondary"><?= htmlspecialchars($p['phone']) ?></div>
                            <?php endif; ?>
                            <?php if ($p['location']): ?>
                                <div class="ap-secondary">📍 <?= htmlspecialchars($p['location']) ?></div>
                            <?php endif; ?>
                        </td>

                        <!-- Professional details -->
                        <td class="ap-details">
                            <?php if ($p['occupation'] || $p['company']): ?>
                                <div><?= htmlspecialchars(implode(', ', array_filter([$p['occupation'], $p['company']]))) ?></div>
                            <?php endif; ?>
                            <?php if ($p['education']): ?>
                                <div class="ap-secondary"><?= htmlspecialchars($p['education']) ?></div>
                            <?php endif; ?>
                            <?php if ($p['date_of_birth']): ?>
                                <div class="ap-secondary">DOB: <?= htmlspecialchars($p['date_of_birth']) ?></div>
                            <?php endif; ?>
                            <?php if (!$p['occupation'] && !$p['company'] && !$p['education'] && !$p['date_of_birth']): ?>
                                <span class="ap-none">—</span>
                            <?php endif; ?>
                        </td>

                        <!-- Registration info -->
                        <td class="ap-reg">
                            <span class="ap-status ap-status--<?= htmlspecialchars($p['registration_status']) ?>">
                                <?= ucfirst(str_replace('_', ' ', htmlspecialchars($p['registration_status']))) ?>
                            </span>
                            <div class="ap-secondary"><?= htmlspecialchars(date('d M Y, H:i', strtotime($p['registration_date']))) ?></div>
                        </td>

                        <!-- Check-in -->
                        <td class="ap-checkin">
                            <?php if ($p['checkin_time']): ?>
                                <div class="ap-checkin-time"><?= htmlspecialchars(date('d M Y, H:i', strtotime($p['checkin_time']))) ?></div>
                                <?php if ($p['checkin_method']): ?>
                                    <div class="ap-secondary"><?= htmlspecialchars(strtoupper($p['checkin_method'])) ?></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="ap-none">Not checked in</span>
                            <?php endif; ?>
                        </td>

                        <!-- Feedback / Rating -->
                        <td class="ap-feedback">
                            <?php if ($p['rating']): ?>
                                <div class="ap-stars">
                                    <?= str_repeat('★', (int)$p['rating']) . str_repeat('☆', 5 - (int)$p['rating']) ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($p['feedback']): ?>
                                <div class="ap-feedback-text"><?= htmlspecialchars($p['feedback']) ?></div>
                            <?php else: ?>
                                <span class="ap-none">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</div>
