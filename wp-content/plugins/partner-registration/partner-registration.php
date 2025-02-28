<?php
/**
 * Plugin Name: Partner Registration Plugin
 * Description: A simple plugin to register partners and manage them in the WP Admin.
 * Version: 1.1
 * Author: Your Name
 * Text Domain: partner-registration
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PR_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Include required files
require_once PR_PLUGIN_DIR . 'includes/class-partner-registration-form.php';
require_once PR_PLUGIN_DIR . 'includes/class-partner-login-form.php';
require_once PR_PLUGIN_DIR . 'includes/class-partner-admin.php';
require_once PR_PLUGIN_DIR . 'includes/class-partner-cf7-handler.php';
require_once PR_PLUGIN_DIR . 'includes/class-partner-profile-page.php';
require_once PR_PLUGIN_DIR . 'includes/class-partner-customer-requests.php';
require_once PR_PLUGIN_DIR . 'includes/class-customer-requests.php';

require_once PR_PLUGIN_DIR . 'includes/class-customer-login.php';

require_once PR_PLUGIN_DIR . 'includes/general-functions.php';

class Partner_Registration_Plugin {
    public function __construct() {
        new Partner_Registration_Form();
        new Partner_Login_Form();
        new Partner_Admin();
        new Partner_CF7_Handler();
        new Partner_Public_Profile();
        new Partner_Customer_Requests();
        new Customer_Requests();

        new Customer_Login();
    }

    public static function create_plugin_tables() {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();

        $tables = [
            'service_partners' => "CREATE TABLE {$wpdb->prefix}service_partners (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone VARCHAR(50) NOT NULL,
                address TEXT NOT NULL,
                latitude VARCHAR(50) NULL,
                longitude VARCHAR(50) NULL,
                street_number VARCHAR(50) NULL,
                route VARCHAR(255) NULL,
                address2 VARCHAR(255) NULL,
                postal_code VARCHAR(50) NULL,
                state VARCHAR(100) NULL,
                country VARCHAR(100) NULL,
                service_area VARCHAR(100) NULL,
                other_country VARCHAR(100) NULL,
                status TINYINT(1) DEFAULT 0,
                reset_token VARCHAR(255) NULL,
                reset_expires DATETIME NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;",

            'lead_partners' => "CREATE TABLE {$wpdb->prefix}lead_partners (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                lead_id BIGINT(20) NOT NULL,
                partner_id BIGINT(20) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;",

            'countries' => "CREATE TABLE {$wpdb->prefix}countries (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                code VARCHAR(10) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;",

            'customers' => "CREATE TABLE {$wpdb->prefix}yqit_customers (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NULL,
                email VARCHAR(255) NULL,
                phone VARCHAR(50) NULL,
                password VARCHAR(255) NULL,
                status TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;",

            'lead_quotes' => "CREATE TABLE {$wpdb->prefix}yqit_lead_quotes (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                customer_id BIGINT(20) NOT NULL,
                lead_id BIGINT(20) NOT NULL,
                quote_data TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) $charset_collate;",

            'lead_quotes_partners' => "CREATE TABLE {$wpdb->prefix}yqit_lead_quotes_partners (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                lead_quote_id BIGINT(20) NOT NULL,
                provider_id BIGINT(20) NOT NULL,
                status ENUM('New Lead', 'Viewed', 'Responded') NOT NULL DEFAULT 'New Lead'
            ) $charset_collate;",

            'customer_partner_quote_chat' => "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}customer_partner_quote_chat (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                partner_id BIGINT(20) UNSIGNED NOT NULL,
                customer_id BIGINT(20) UNSIGNED NOT NULL,
                lead_id BIGINT(20) UNSIGNED NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB $charset_collate;",

            'customer_partner_quote_chat_messages' => "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}customer_partner_quote_chat_messages (
                id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                chat_id BIGINT(20) UNSIGNED NOT NULL,
                sender_id BIGINT(20) UNSIGNED NOT NULL,
                receiver_id BIGINT(20) UNSIGNED NOT NULL,
                sender_type ENUM('partner', 'customer') NOT NULL,
                receiver_type ENUM('partner', 'customer') NOT NULL,
                message TEXT NOT NULL,
                is_read TINYINT(1) DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_chat FOREIGN KEY (chat_id) 
                REFERENCES {$wpdb->prefix}customer_partner_quote_chat(id) 
                ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB $charset_collate;"

        ];

        @ob_start();
        foreach ($tables as $table => $sql) {
            dbDelta($sql);
        }
        

        self::insert_countries();

        // Check and add new columns if they don't exist
        $existing_columns = $wpdb->get_col("DESC {$wpdb->prefix}service_partners", 0);

        if (!in_array('business_trading_name', $existing_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}service_partners ADD COLUMN business_trading_name VARCHAR(255) NULL AFTER name;");
        }

        if (!in_array('password', $existing_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}service_partners ADD COLUMN password VARCHAR(255) NULL AFTER email;");
        }

        if (!in_array('business_logo', $existing_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}service_partners ADD COLUMN business_logo VARCHAR(255) NULL AFTER phone;");
        }

        if (!in_array('website_url', $existing_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}service_partners ADD COLUMN website_url VARCHAR(255) NULL AFTER business_logo;");
        }

        $existing_customers_columns = $wpdb->get_col("DESC {$wpdb->prefix}yqit_customers", 0);

        if (!in_array('reset_token', $existing_customers_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}yqit_customers ADD COLUMN reset_token VARCHAR(255) NULL AFTER password;");
        }

        if (!in_array('reset_expires', $existing_customers_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}yqit_customers ADD COLUMN reset_expires DATETIME NULL AFTER reset_token;");
        }

        $existing_lead_quotes_columns = $wpdb->get_col("DESC {$wpdb->prefix}yqit_lead_quotes", 0);

        if (!in_array('is_archived', $existing_lead_quotes_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}yqit_lead_quotes ADD COLUMN is_archived VARCHAR(255) NULL DEFAULT 0 AFTER quote_data;");
        }

        $existing_lead_quotes_columns = $wpdb->get_col("DESC {$wpdb->prefix}yqit_lead_quotes_partners", 0);

        if (!in_array('is_archived', $existing_lead_quotes_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}yqit_lead_quotes_partners ADD COLUMN is_archived VARCHAR(255) NULL DEFAULT 0 AFTER status;");
        }

        $existing_customer_partner_quote_chat_columns = $wpdb->get_col("DESC {$wpdb->prefix}customer_partner_quote_chat", 0);

        if (!in_array('is_read', $existing_customer_partner_quote_chat_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}customer_partner_quote_chat ADD COLUMN is_read TINYINT(1) DEFAULT 0 AFTER lead_id;");
        }

        $existing_customer_partner_quote_chat_messages_columns = $wpdb->get_col("DESC {$wpdb->prefix}customer_partner_quote_chat_messages", 0);

        if (!in_array('is_read', $existing_customer_partner_quote_chat_messages_columns)) {
            $wpdb->query("ALTER TABLE {$wpdb->prefix}customer_partner_quote_chat_messages ADD COLUMN is_read TINYINT(1) DEFAULT 0 AFTER message;");
        }

        @ob_end_clean();
    }

    private static function insert_countries() {
        global $wpdb;
        $countries_table = $wpdb->prefix . 'countries';

        $existing_countries = (int) $wpdb->get_var("SELECT COUNT(*) FROM $countries_table");
        if ($existing_countries > 0) {
            return;
        }

        $countries = [
            ['Afghanistan', 'AF'], ['Albania', 'AL'], ['Algeria', 'DZ'], ['Andorra', 'AD'],
            ['Angola', 'AO'], ['Argentina', 'AR'], ['Armenia', 'AM'], ['Australia', 'AU'],
            ['Austria', 'AT'], ['Azerbaijan', 'AZ'], ['Bahamas', 'BS'], ['Bahrain', 'BH'],
            ['Bangladesh', 'BD'], ['Barbados', 'BB'], ['Belarus', 'BY'], ['Belgium', 'BE'],
            ['Belize', 'BZ'], ['Benin', 'BJ'], ['Bhutan', 'BT'], ['Bolivia', 'BO'],
            ['Bosnia and Herzegovina', 'BA'], ['Botswana', 'BW'], ['Brazil', 'BR'],
            ['Brunei', 'BN'], ['Bulgaria', 'BG'], ['Burkina Faso', 'BF'], ['Burundi', 'BI'],
            ['Cambodia', 'KH'], ['Cameroon', 'CM'], ['Canada', 'CA'], ['Chad', 'TD'],
            ['Chile', 'CL'], ['China', 'CN'], ['Colombia', 'CO'], ['Congo', 'CG'],
            ['Costa Rica', 'CR'], ['Croatia', 'HR'], ['Cuba', 'CU'], ['Cyprus', 'CY'],
            ['Czech Republic', 'CZ'], ['Denmark', 'DK'], ['Dominican Republic', 'DO'],
            ['Ecuador', 'EC'], ['Egypt', 'EG'], ['El Salvador', 'SV'], ['Estonia', 'EE'],
            ['Ethiopia', 'ET'], ['Finland', 'FI'], ['France', 'FR'], ['Gabon', 'GA'],
            ['Germany', 'DE'], ['Ghana', 'GH'], ['Greece', 'GR'], ['Guatemala', 'GT'],
            ['Honduras', 'HN'], ['Hungary', 'HU'], ['Iceland', 'IS'], ['India', 'IN'],
            ['Indonesia', 'ID'], ['Iran', 'IR'], ['Iraq', 'IQ'], ['Ireland', 'IE'],
            ['Israel', 'IL'], ['Italy', 'IT'], ['Jamaica', 'JM'], ['Japan', 'JP'],
            ['Jordan', 'JO'], ['Kazakhstan', 'KZ'], ['Kenya', 'KE'], ['Kuwait', 'KW'],
            ['Latvia', 'LV'], ['Lebanon', 'LB'], ['Libya', 'LY'], ['Lithuania', 'LT'],
            ['Luxembourg', 'LU'], ['Malaysia', 'MY'], ['Maldives', 'MV'], ['Mexico', 'MX']
        ];

        foreach ($countries as $country) {
            $wpdb->insert($countries_table, [
                'name' => sanitize_text_field($country[0]),
                'code' => sanitize_text_field($country[1])
            ]);
        }
    }

    public static function create_partner_login_page() {
        $page_title = 'Partner Login';
        $page_slug = 'partner-login';
        $page_content = '[partner_login]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }

    public static function create_partner_register_page() {
        $page_title = 'Register your business';
        $page_slug = 'register-your-business';
        $page_content = '[partner_registration_form]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }

    public static function create_partner_profile_page() {
        $page_title = 'Partner Profile';
        $page_slug = 'partner-profile';
        $page_content = '[partner_registration_form profile="true"]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }

    public static function create_customer_login_page() {
        $page_title = 'Customer Login';
        $page_slug = 'customer-login';
        $page_content = '[customer_login]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }

    public static function create_partner_customer_requests_page() {
        $page_title = 'Partner customer requests';
        $page_slug = 'partner-customer-requests';
        $page_content = '[partner_customer_requests]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }

    public static function create_customer_requests_page() {
        $page_title = 'Customer requests';
        $page_slug = 'customer-requests';
        $page_content = '[customer_requests]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }


    public static function create_customer_set_password_page() {
        $page_title = 'Customer Set Password';
        $page_slug = 'customer-change-password';
        $page_content = '[customer_change_password]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }

    public static function create_partner_forgot_password_page() {
        $page_title = 'Forgot Password';
        $page_slug = 'partner-forgot-password';
        $page_content = '[partner_forgot_password_form]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }

    public static function create_partner_reset_password_page() {
        $page_title = 'Partner Reset Password';
        $page_slug = 'partner-reset-password';
        $page_content = '[partner_reset_password_form]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }

    public static function create_customer_forgot_password_page() {
        $page_title = 'Forgot Password';
        $page_slug = 'customer-forgot-password';
        $page_content = '[customer_forgot_password_form]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }

    public static function create_customer_reset_password_page() {
        $page_title = 'Customer Reset Password';
        $page_slug = 'customer-reset-password';
        $page_content = '[customer_reset_password_form]'; // Use the shortcode
    
        // Check if page already exists by slug
        $page_check = get_page_by_path($page_slug);
    
        if (!$page_check) {
            $page_id = wp_insert_post([
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => $page_content,
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'post_author'   => get_current_user_id()
            ]);
        }
    }
}

// Register activation hook
register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_plugin_tables']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_partner_login_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_partner_register_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_partner_profile_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_partner_forgot_password_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_partner_reset_password_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_partner_customer_requests_page']);


// Customer hooks
register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_customer_requests_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_customer_login_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_customer_forgot_password_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_customer_reset_password_page']);


// Initialize the plugin
new Partner_Registration_Plugin();