<?php
/*
Template Name: Reset Password
*/

// Include WordPress functions
get_header();

// Get key and email from URL
$key = isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '';
$email = isset($_GET['email']) ? sanitize_email($_GET['email']) : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password_nonce']) && wp_verify_nonce($_POST['reset_password_nonce'], 'reset_password_action')) {
    $new_password = sanitize_text_field($_POST['new_password']);
    $confirm_password = sanitize_text_field($_POST['confirm_password']);

    if (empty($new_password) || empty($confirm_password)) {
        $error = "Please enter a new password.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $user = get_user_by('email', $email);
        if ($user) {
            // Validate reset key (You might need to store & validate it)
            wp_set_password($new_password, $user->ID);
            $success = "Your password has been reset successfully. You can now <a href='/login/'>Login</a>.";
        } else {
            $error = "Invalid reset link or email.";
        }
    }
}
?>

<div class="container">
    <h2>Reset Your Password</h2>
    <?php if (!empty($error)) : ?>
        <p class="error alert alert-danger"><?php echo esc_html($error); ?></p>
    <?php endif; ?>
    <?php if (!empty($success)) : ?>
        <p class="success alert alert-success"><?php echo wp_kses_post($success); ?></p>
    <?php else : ?>
        <form method="POST">
            <?php wp_nonce_field('reset_password_action', 'reset_password_nonce'); ?>
            <input type="hidden" name="email" value="<?php echo esc_attr($email); ?>">
            <input type="hidden" name="key" value="<?php echo esc_attr($key); ?>">

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
