<div class="partner-login-form">
    
    <?php if (isset($_SESSION['partner_logged_in']) && $_SESSION['partner_logged_in'] === true): ?>
        <p>Welcome, <?php echo esc_html($_SESSION['partner_name']); ?>!</p>
        <a href="<?php echo esc_url(admin_url('admin-post.php?action=partner_logout')); ?>">Logout</a>
    <?php else: ?>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
            <input type="hidden" name="action" value="partner_login">
            
            <div class="wpcf7-form">
                <div class="step-header">
                    <small>Partner Login</small>
                    <h5 class="text-center">Login for free to start receiving leads...</h5>
                </div>
                <div class="form-body">
                    <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control h-50px" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control h-50px" name="password" required minlength="8">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <a href="<?php echo home_url() . '/register-your-business' ?>">Create an account?</a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-theme-primary">Login</button>
                        </div>
                    </div>

                    <?php if (!empty($_SESSION['login_error'])): ?>
                        <p style="color: red;"><?php echo $_SESSION['login_error']; ?></p>
                        <?php unset($_SESSION['login_error']); ?>
                    <?php endif; ?>

                </div>
            </div>
        </form>
    <?php endif; ?>
</div>