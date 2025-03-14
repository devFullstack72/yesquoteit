<?php
if (!defined('ABSPATH')) {
    exit;
}

class Partner_Login_Form
{

    public $service_partners_table;

    public function __construct()
    {
        add_shortcode('partner_login', [$this, 'partner_login_shortcode']);

        add_action('admin_post_nopriv_partner_login', [$this, 'handle_partner_login']);
        add_action('admin_post_partner_login', [$this, 'handle_partner_login']);

        add_action('admin_post_nopriv_partner_logout', [$this, 'handle_partner_logout']);
        add_action('admin_post_partner_logout', [$this, 'handle_partner_logout']);

        add_action('init', [$this, 'custom_partner_handler_rewrite_rule']);
        
        add_filter('query_vars', [$this, 'add_partner_handler_query_var']);

        add_action('template_redirect', [$this, 'handle_partner_redirect']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_shortcode('partner_loggedin_info', [$this, 'show_partner_loggedin_info']);

        if (!session_id()) {
            session_start();
        }

        global $wpdb;

        $this->service_partners_table = $wpdb->prefix . 'service_partners';
    }

    public function enqueue_scripts()
    {
        
    }

    public function custom_partner_handler_rewrite_rule() {
        add_rewrite_rule('handler-events/partner/([^/]+)/?$', 'index.php?partner_handler=$matches[1]', 'top');
    }

    public function add_partner_handler_query_var($query_vars) {
        $query_vars[] = 'partner_handler';
        return $query_vars;
    }

    public function handle_partner_redirect() {
        if (get_query_var('partner_handler')) {
            $encrypted_id = get_query_var('partner_handler');
            
            if ($encrypted_id) {
                // Decrypt Customer ID
                $partner_id = decrypt_partner_id($encrypted_id);
                
                if ($partner_id) {
                    global $wpdb;
                    $table_name = $wpdb->prefix . "service_partners";
                    $partner = $wpdb->get_row($wpdb->prepare("SELECT id, name, password FROM $table_name WHERE id = %d", $partner_id));


                    $_SESSION['partner_logged_in'] = true;
                    $_SESSION['partner_id'] = $partner->id;
                    $_SESSION['partner_name'] = $partner->name;
                    // wp_safe_redirect(home_url());
                    wp_safe_redirect(home_url('partner-customer-requests'));
                    exit;
                }
                wp_die('Partner not found!');
            }
        }
    }


    public function partner_login_shortcode()
    {
        // Start output buffering
        ob_start();

        // Define path to the view file
        include plugin_dir_path(__FILE__) . '../views/partner-login-form.php';

        return ob_get_clean();
        
    }

    public function handle_partner_login() {

        // Verify nonce for security
        if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
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
    
        $partner = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->service_partners_table} WHERE email = %s", $email));
    
        if ($partner && wp_check_password($password, $partner->password)) {
            $_SESSION['partner_logged_in'] = true;
            $_SESSION['partner_id'] = $partner->id;
            $_SESSION['partner_name'] = $partner->name;
            // wp_safe_redirect(home_url());
            wp_safe_redirect(home_url('partner-customer-requests'));
            exit;
        } else {
            $_SESSION['login_error'] = "Invalid email or password.";
            wp_safe_redirect(wp_get_referer());
            exit;
        }
    }

    public function handle_partner_logout() {
        
        session_destroy();
    
        wp_safe_redirect(home_url());
        exit;
    }

    public function show_partner_loggedin_info() {
        if (isset($_SESSION['partner_logged_in']) && $_SESSION['partner_logged_in'] === true) {
            ?>
            <div class="partner-login-user-area">
                <div class="dropdown">
                    <button class="dropdown-toggle" id="partnerDropdown">
                        <i class="fa fa-user"></i> <?php echo esc_html($_SESSION['partner_name']); ?>
                    </button>
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li><a href="<?php echo esc_url(site_url('/partner-profile')); ?>"><i class="fa fa-pencil"></i> Edit Profile</a></li>
                        <li><a target="_blank" href="<?php echo esc_url(home_url()); ?>/provider/<?php echo $_SESSION['partner_id'] ?>"><i class="fa fa-eye"></i> Public Profile</a></li>
                        <li><a href="<?php echo esc_url(home_url()); ?>/partner-customer-requests"><i class="fa fa-bullhorn"></i> Your Quotes</a></li>
                        <li><a href="<?php echo esc_url(admin_url('admin-post.php?action=partner_logout')); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
            <?php
        } else if (isset($_SESSION['customer_logged_in']) && $_SESSION['customer_logged_in'] === true) {
            ?>
            <div class="partner-login-user-area">
                <div class="dropdown">
                    <button class="dropdown-toggle" id="partnerDropdown">
                        <i class="fa fa-user"></i> <?php echo esc_html($_SESSION['customer_name']); ?>
                    </button>
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li><a href="<?php echo esc_url(home_url()); ?>/customer-requests"><i class="fa fa-bullhorn"></i> Your Quotes</a></li>
                        <li><a href="<?php echo esc_url(site_url('/customer-change-password')); ?>"><i class="fa fa-pencil"></i> Change Password</a></li>
                        <li><a href="<?php echo esc_url(admin_url('admin-post.php?action=customer_logout')); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="partner-login-user-area partner-non-login-user-area">
                <span style="margin-right: 5px;">Login: </span>
                <a href="<?php echo home_url() . '/customer-login' ?>" class="theme-link-color-white"><i class="fa fa-user"></i> Customer</a>
                <a href="<?php echo home_url() . '/partner-login' ?>" class="theme-link-color-white"><i class="fa fa-briefcase"></i> Business</a>
            </div>
            <?php
        }
    }
    
}
?>