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

class Partner_Registration_Plugin {
    public function __construct() {
        new Partner_Registration_Form();
        new Partner_Login_Form();
        new Partner_Admin();
        new Partner_CF7_Handler();
        new Partner_Public_Profile();
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
            ) $charset_collate;"
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
}

// Register activation hook
register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_plugin_tables']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_partner_login_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_partner_register_page']);

register_activation_hook(__FILE__, ['Partner_Registration_Plugin', 'create_partner_profile_page']);


// Initialize the plugin
new Partner_Registration_Plugin();