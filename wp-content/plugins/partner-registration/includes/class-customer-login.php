<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../includes/Controllers/CustomerController.php';

class Customer_Login extends CustomerController
{

    public $wpdb;

    public function __construct()
    {
        parent::__construct();

        add_shortcode('customer_login', [$this, 'customer_login_shortcode']);

        add_action('admin_post_nopriv_customer_login', [$this, 'handle_customer_login']);
        add_action('admin_post_customer_login', [$this, 'handle_customer_login']);

        add_action('admin_post_nopriv_customer_logout', [$this, 'handle_customer_logout']);
        add_action('admin_post_customer_logout', [$this, 'handle_customer_logout']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_action('init', [$this, 'custom_customer_handler_rewrite_rule']);
        
        add_filter('query_vars', [$this, 'add_customer_handler_query_var']);

        add_action('template_redirect', [$this, 'handle_customer_redirect']);

        add_shortcode('customer_change_password', [$this, 'customer_change_password_shortcode']);

        add_action('admin_post_nopriv_pr_customer_change_password', [$this, 'handle_pr_customer_change_password']);
        add_action('admin_post_pr_customer_change_password', [$this, 'handle_pr_customer_change_password']);

        add_shortcode('customer_forgot_password_form', [$this, 'render_forgot_password_form']);
        
        add_action('admin_post_nopriv_pr_customer_forgot_password', [$this, 'handle_customer_forgot_password']);
        add_action('admin_post_pr_customer_forgot_password', [$this, 'handle_customer_forgot_password']);

        add_shortcode('customer_reset_password_form', [$this, 'render_reset_password_form']);

        add_action('admin_post_nopriv_pr_customer_reset_password', [$this, 'handle_pr_customer_reset_password']);
        add_action('admin_post_pr_customer_reset_password', [$this, 'handle_pr_customer_reset_password']);

        global $wpdb;

        $this->wpdb = $wpdb;

    }

    public function enqueue_scripts()
    {
        
    }


    public function customer_login_shortcode()
    {
        // Start output buffering
        ob_start();

        // Define path to the view file
        include plugin_dir_path(__FILE__) . '../views/customer/login-form.php';

        return ob_get_clean();
        
    }

    public function handle_customer_login() {

        // Verify nonce for security
        if (!isset($_POST['pr_customer_nonce']) || !wp_verify_nonce($_POST['pr_customer_nonce'], 'pr_customer_form_action')) {
            wp_die('Security check failed.');
        }
        
        global $wpdb;
    
        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
    
        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = "Email and Password are required.";
            wp_safe_redirect(wp_get_referer());
            exit;
        }
    
        $customer = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->customer_table} WHERE email = %s", $email));
    
        if ($customer && !empty($customer->password) &&  wp_check_password($password, $customer->password)) {
            
            $this->autoCustomerLogin($customer);
    
            wp_safe_redirect(home_url());
            exit;
        } else {
            $_SESSION['login_error'] = "Invalid email or password.";
            wp_safe_redirect(wp_get_referer());
            exit;
        }
    }

    public function handle_customer_logout() {
        
        session_destroy();
    
        wp_safe_redirect(home_url());
        exit;
    }

    public function custom_customer_handler_rewrite_rule() {
        add_rewrite_rule('handler-events/customer/([^/]+)/?$', 'index.php?customer_handler=$matches[1]', 'top');
    }

    public function add_customer_handler_query_var($query_vars) {
        $query_vars[] = 'customer_handler';
        return $query_vars;
    }

    public function handle_customer_redirect() {
        if (get_query_var('customer_handler')) {
            $encrypted_id = get_query_var('customer_handler');
            
            if ($encrypted_id) {
                // Decrypt Customer ID
                $customer_id = decrypt_customer_id($encrypted_id);
                
                if ($customer_id) {
                    
                    
                    global $wpdb;
                    $table_name = $wpdb->prefix . "yqit_customers";
                    $customer = $wpdb->get_row($wpdb->prepare("SELECT id, name, password FROM $table_name WHERE id = %d", $customer_id));


                    $this->autoCustomerLogin($customer);

                    // Redirect to login page
                    wp_safe_redirect(home_url('/customer-requests'));
                    exit;

                    // if ($customer) {
                    //     if (!empty($customer->password)) {
                    //         wp_redirect(home_url('/customer-login'));
                    //         exit;
                    //     } else {
                    //         $_SESSION['temp_customer_id'] = $customer_id;
                    //         wp_redirect(home_url('/customer-change-password'));
                    //         exit;
                    //     }
                    // }
                }
                wp_die('Customer not found!');
            }
        }
    }

    public function customer_change_password_shortcode()
    {

        $partner_register_page_title = 'Set Password';

        $submit_button_text = 'Save';

        // Start output buffering
        ob_start();

        $customer_id = '';

        if (isset($_SESSION['temp_customer_id'])) {
            $customer_id = $_SESSION['temp_customer_id'];
        } else if (isset($_SESSION['customer_id'])) {
            $customer_id = $_SESSION['customer_id'];
        }

        // Define path to the view file
        include plugin_dir_path(__FILE__) . '../views/customer/set-password.php';

        return ob_get_clean();
        
    }

    public function handle_pr_customer_change_password() {

        // Verify nonce for security
        if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
            wp_die('Security check failed.');
        }
    
        $new_password = sanitize_text_field($_POST['new_password']);
        $confirm_password = sanitize_text_field($_POST['confirm_password']);

        $errors = [];
    
        if (empty($new_password)) {
            $errors['new_password'] = "Password is required";
        } else if (strlen($new_password) < 8) {
            $errors['new_password'] = "Password must alteast of 8 characters";
        }

        if (empty($confirm_password)) {
            $errors['confirm_password'] = "Confirm Password is required";
        }

        if ( !empty($confirm_password) && $new_password != $confirm_password) {
            $errors['new_password'] = "Password and confirm password must match";
        }
    
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            
            $redirect_url = wp_get_referer();
            
            wp_safe_redirect($redirect_url);
            exit;
        }

        if ($customer_id = $this->getCustomerID()) {
            $this->wpdb->update($this->customer_table, [
                'password' => wp_hash_password($new_password),
                'status' => 1
            ], [
                'id' => $customer_id
            ]);

            $this->autoCustomerLogin($this->getCustomer());
            unset($_SESSION['temp_customer_id']);
        }

        $_SESSION['flash_message'] = [
            'message' => 'Password changed successfully'
        ];
        
        $redirect_url = wp_get_referer();
        wp_safe_redirect($redirect_url);
        exit;
    }

    public function render_forgot_password_form($atts)
    {
        ob_start();

        // Define path to the view file
        $view_path = plugin_dir_path(__FILE__) . '../views/customer/forgot-password.php';

        // Ensure file exists before including
        if (file_exists($view_path)) {
            include $view_path;
        } else {
            echo "<p>Error: View file not found!</p>";
        }

        return ob_get_clean();
        
    }

    public function handle_customer_forgot_password() {
        session_start();

        global $wpdb;

        // Security Check
        if (!isset($_POST['pr_customer_nonce']) || !wp_verify_nonce($_POST['pr_customer_nonce'], 'pr_customer_form_action')) {
            wp_die('Security check failed.');
        }

        // Get and sanitize email
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        $errors = [];
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!is_email($email)) {
            $errors['email'] = 'Invalid email format.';
        } else {
            // Check email in wp_service_partners table
            $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->customer_table} WHERE email = %s", $email));
    
            if (!$user) {
                $errors['email'] = 'Email not registered.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['forgot_password_errors'] = $errors;
            $_SESSION['forgot_password_old'] = $_POST;
            wp_redirect(wp_get_referer()); // Redirect back to the form
            exit;
        }

        $reset_key = wp_generate_password(32, false); // You can store this in the DB for verification
        $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour')); 
        // Save reset key in the service partners table
        $wpdb->update(
            $this->customer_table,
            [
                'reset_token' => $reset_key,
                'reset_expires' => $reset_expires // Ensure expiration time is saved
            ],
            ['email' => $email],
            ['%s'],
            ['%s']
        );
    
        // Generate Reset Password Link
        $reset_link = add_query_arg([
            'key' => $reset_key,
            'email' => rawurlencode($email),
        ], site_url('/customer-reset-password')); // Change to your reset password page

        // Send Email
        $subject = 'Password Reset Request';
        // $message = "Hello,\n\nYou requested a password reset. Click the link below to reset your password:\n\n";
        // $message .= $reset_link . "\n\nIf you did not request this, please ignore this email.";

        $message = "
            <html>
            <head>
                <style>
                    .reset-button {
                        background-color: #007bff;
                        color: #ffffff;
                        padding: 10px 15px;
                        text-decoration: none;
                        font-size: 16px;
                        display: inline-block;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>
                <p>Hello,</p>
                <p>You requested a password reset. Click the button below to reset your password:</p>
                <p><a href='$reset_link' class='reset-button'>Reset Password</a></p>
                <p>If you did not request this, please ignore this email.</p>
            </body>
            </html>
        ";
        
        // Set headers to send an HTML email
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8'
        ];

        // Send the email
        wp_mail($email, $subject, $message, $headers);

        // Redirect with success message
        $_SESSION['forgot_password_success'] = 'A reset link has been sent to your email.';
        wp_redirect(wp_get_referer());
        exit;
    }

    function render_reset_password_form() {

        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_GET['key']) || !isset($_GET['email'])) {
            return '<p class="alert alert-danger text-center">Invalid password reset link.</p>';
        }
    
        $errors = isset($_SESSION['forgot_password_errors']) ? $_SESSION['forgot_password_errors'] : [];
        
        ob_start(); ?>
    
        <form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('reset_password_action', 'reset_password_nonce'); ?>
            <input type="hidden" name="action" value="pr_customer_reset_password">
            <input type="hidden" name="email" value="<?php echo esc_attr($_GET['email']); ?>">
            <input type="hidden" name="token" value="<?php echo esc_attr($_GET['key']); ?>">
    
            <div class="wpcf7-form">
                <div class="step step-1">
                    <div class="step-header">
                        <h5 class="text-center">Reset Password</h5>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control h-50px" id="new_password" name="new_password" placeholder="Enter new password" required>
                            <span class="error"><?php echo esc_html($errors['new_password'] ?? ''); ?></span>
                        </div>
    
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control h-50px" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                            <span class="error"><?php echo esc_html($errors['confirm_password'] ?? ''); ?></span>
                        </div>
    
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($success_message)) : ?>
                        <p class="alert alert-success text-center"><?php echo esc_html($success_message); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    
        <?php return ob_get_clean();
    }

    function handle_pr_customer_reset_password() {

        if (!isset($_POST['reset_password_nonce']) || !wp_verify_nonce($_POST['reset_password_nonce'], 'reset_password_action')) {
            wp_die('Security check failed.');
        }
    
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Sanitize input values
        $email = sanitize_email($_POST['email'] ?? '');
        $token = sanitize_text_field($_POST['token'] ?? '');
        $new_password = sanitize_text_field($_POST['new_password'] ?? '');
        $confirm_password = sanitize_text_field($_POST['confirm_password'] ?? '');
    
        $errors = [];
    
        // Validate password length
        if (strlen($new_password) < 8) {
            $errors['new_password'] = "Password must be at least 8 characters.";
        }
    
        // Confirm passwords match
        if ($new_password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }
    
        // If there are errors, store them in session and redirect back to reset form
        if (!empty($errors)) {
            $_SESSION['forgot_password_errors'] = $errors;
    
            // Redirect to reset password form with query params
            $reset_url = add_query_arg([
                'key'   => $token,
                'email' => rawurlencode($email)
            ], site_url('/customer-reset-password'));
            
            
            wp_safe_redirect($reset_url);
            exit;
        }
    
        // Database check for partner
        global $wpdb;
        $customer = $wpdb->get_row($wpdb->prepare(
            "SELECT id, name, reset_token, reset_expires FROM {$this->customer_table} WHERE email = %s", 
            $email
        ));

    
        // Validate token and expiry
        if (!$customer || $customer->reset_token !== $token || empty($customer->reset_expires) || time() > strtotime($customer->reset_expires)) {
            wp_die("Invalid or expired reset token.");
        }

        // Update password and reset token
        $wpdb->update(
            $this->customer_table,
            [
                'password' => wp_hash_password($new_password),
                'reset_token' => null,
                'reset_expires' => null
            ],
            ['id' => $customer->id]
        );
    
        // Store success message in session
        $_SESSION['flash_message_success'] = "Password reset successful.";

        $this->autoCustomerLogin($customer);
    
        // Redirect to login page
        wp_safe_redirect(home_url());
        exit;
    }
    
    
}
?>