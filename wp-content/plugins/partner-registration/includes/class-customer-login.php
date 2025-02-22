<?php
if (!defined('ABSPATH')) {
    exit;
}

class Customer_Login
{

    public $customer_table;

    public function __construct()
    {
        add_shortcode('customer_login', [$this, 'customer_login_shortcode']);

        add_action('admin_post_nopriv_customer_login', [$this, 'handle_customer_login']);
        add_action('admin_post_customer_login', [$this, 'handle_customer_login']);

        add_action('admin_post_nopriv_customer_logout', [$this, 'handle_customer_logout']);
        add_action('admin_post_customer_logout', [$this, 'handle_customer_logout']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        if (!session_id()) {
            session_start();
        }

        global $wpdb;

        $this->customer_table = $wpdb->prefix . 'yqit_customers';
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
    
        $partner = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->customer_table} WHERE email = %s", $email));
    
        if ($partner && wp_check_password($password, $partner->password)) {
            $_SESSION['customer_logged_in'] = true;
            $_SESSION['customer_id'] = $partner->id;
            $_SESSION['customer_name'] = $partner->name;
    
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
    
}
?>