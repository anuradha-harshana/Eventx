<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/form.css" />

<main class="auth-wrapper">

    <div class="auth-card">
        <div class="auth-left">
            <h2>Welcome Back</h2>
            <p>Sign in to continue managing your events and activities.</p>
        </div>

        <div class="auth-right">

            <h3>Login</h3>

            <?php if(!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= SITE_URL ?>logindata">

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" class="auth-btn">Login</button>

            </form>

            <p class="auth-switch">
                No account?
                <a href="<?= SITE_URL ?>register">Create one</a>
            </p>

        </div>
    </div>

</main>
