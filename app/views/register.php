<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/form.css" />

<main class="auth-wrapper">

    <div class="auth-card">
        <div class="auth-left">
            <h2>Join Eventz</h2>
            <p>Create your account and start exploring events today.</p>
        </div>

        <div class="auth-right">

            <h3>Register</h3>

            <?php if(!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= SITE_URL ?>registerdata">

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required minlength="3">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required minlength="6">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role">
                        <option value="participant">Participant</option>
                        <option value="organizer">Organizer</option>
                        <option value="sponsor">Sponsor</option>
                        <option value="supplier">Supplier</option>
                    </select>
                </div>

                <button type="submit" class="auth-btn">Create Account</button>

            </form>

            <p class="auth-switch">
                Already have an account?
                <a href="<?= SITE_URL ?>login">Sign in</a>
            </p>

        </div>
    </div>

</main>
