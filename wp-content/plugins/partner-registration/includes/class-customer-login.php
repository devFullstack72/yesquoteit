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
    
        if ($customer && wp_check_password($password, $customer->password)) {
            
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
                    $customer = $wpdb->get_row($wpdb->prepare("SELECT password FROM $table_name WHERE id = %d", $customer_id));

                    if ($customer) {
                        if (!empty($customer->password)) {
                            // Customer has a password → Redirect to login page
                            wp_redirect(home_url('/customer-login'));
                            exit;
                        } else {
                            // Customer needs to set password → Store ID in session and redirect
                            $_SESSION['temp_customer_id'] = $customer_id;
                            wp_redirect(home_url('/customer-change-password'));
                            exit;
                        }
                    }
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
    
    
}
?>