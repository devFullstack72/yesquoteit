<?php 
$errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']); // Clear errors after displaying
?>

<form class="reset-password-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="application/x-www-form-urlencoded">

    <?php wp_nonce_field('reset_password_action', 'reset_password_nonce'); ?>
    <input type="hidden" name="action" value="pr_partner_reset_password">
    <input type="hidden" name="token" value="<?php echo esc_attr($_GET['token'] ?? ''); ?>">
    <input type="hidden" name="email" value="<?php echo esc_attr($_GET['email'] ?? ''); ?>">

    <div class="form-group">
        <label for="new_password">New Password</label>
        <input type="password" class="form-control" name="new_password" required placeholder="Enter new password">
        <span class="error"><?php echo $errors['new_password'] ?? ''; ?></span>
    </div>

    <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" class="form-control" name="confirm_password" required placeholder="Confirm password">
        <span class="error"><?php echo $errors['confirm_password'] ?? ''; ?></span>
    </div>

    <button type="submit" class="btn btn-primary">Reset Password</button>
</form>
