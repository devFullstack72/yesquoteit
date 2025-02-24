<div class="partner-login-form">
    
    <?php if (isset($_SESSION['partner_logged_in']) && $_SESSION['partner_logged_in'] === true): ?>
        <div class="alert alert-info">You are already loggedin as customer</div>
        <p>Welcome, <?php echo esc_html($_SESSION['partner_name']); ?>!</p>
        <a href="<?php echo esc_url(admin_url('admin-post.php?action=partner_logout')); ?>">Logout</a>
    <?php elseif (isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] === true): ?>
        <div class="alert alert-info">You are already loggedin as customer</div>
        <p>Welcome, <?php echo esc_html($_SESSION['customer_name']); ?>!</p>
        <a href="<?php echo esc_url(admin_url('admin-post.php?action=partner_logout')); ?>">Logout</a>
    <?php else: ?>
        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
            <?php wp_nonce_field('pr_customer_form_action', 'pr_customer_nonce'); ?>
            <input type="hidden" name="action" value="customer_login">
            
            <div class="wpcf7-form">
                <div class="step-header">
                    <small>Customer Login</small>
                    <h5 class="text-center">Login for free to start submit leads...</h5>
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
                            <a href="<?php echo home_url() . '/customer-forgot-password' ?>">Forgot Password?</a>
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