<?php 
$errors = isset($_SESSION['forgot_password_errors']) ? $_SESSION['forgot_password_errors'] : [];
$old_values = isset($_SESSION['forgot_password_old']) ? $_SESSION['forgot_password_old'] : [];
unset($_SESSION['forgot_password_errors'], $_SESSION['forgot_password_old']);
?>

<?php 
$success_message = isset($_SESSION['forgot_password_success']) ? $_SESSION['forgot_password_success'] : '';
unset($_SESSION['forgot_password_success']);
?>

<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field('pr_customer_form_action', 'pr_customer_nonce'); ?>
    <input type="hidden" name="action" value="pr_customer_forgot_password">
    
    <div class="wpcf7-form">
        <div class="step step-1">
            <div class="step-header">
                <h5 class="text-center">Forgot Password</h5>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label for="email">Enter your email</label>
                    <input type="email" class="form-control h-50px" id="email" name="email" placeholder="Enter your email" value="<?php echo esc_attr($old_values['email'] ?? ''); ?>">
                    <span class="error"><?php echo esc_html($errors['email'] ?? ''); ?></span>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary">Send Reset Link</button>
                    </div>
                </div>
            </div>
            <?php if (!empty($success_message)) : ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="alert alert-success"><?php echo esc_html($success_message); ?></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</form>
