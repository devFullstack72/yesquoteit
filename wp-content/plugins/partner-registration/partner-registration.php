<?php
/**
 * Plugin Name: Partner Registration Plugin
 * Description: A simple plugin to register partners and manage them in the WP Admin.
 * Version: 1.0
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
require_once PR_PLUGIN_DIR . 'includes/class-partner-admin.php';
require_once PR_PLUGIN_DIR . 'includes/class-partner-cf7-handler.php';


class Partner_Registration_Plugin {
    public function __construct() {
        new Partner_Registration_Form();
        new Partner_Admin();
        new Partner_CF7_Handler();
        register_activation_hook(__FILE__, [$this, 'create_plugin_tables']);
    }

    public function create_plugin_tables() {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $charset_collate = $wpdb->get_charset_collate();

        $service_partners_table = $wpdb->prefix . 'service_partners';
        $lead_partners_table = $wpdb->prefix . 'lead_partners';
        $countries_table = $wpdb->prefix . 'countries';

        $sql_service_partners = "CREATE TABLE $service_partners_table (
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
        ) $charset_collate;";

        $sql_lead_partners = "CREATE TABLE $lead_partners_table (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            lead_id BIGINT(20) NOT NULL,
            partner_id BIGINT(20) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $sql_countries = "CREATE TABLE $countries_table (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(10) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        dbDelta($sql_service_partners);
        dbDelta($sql_lead_partners);
        dbDelta($sql_countries);

        // Insert countries data if the table is empty
        $existing_countries = $wpdb->get_var("SELECT COUNT(*) FROM $countries_table");

        if ($existing_countries == 0) {
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
                ['Luxembourg', 'LU'], ['Malaysia', 'MY'], ['Maldives', 'MV'], ['Mexico', 'MX'],
                ['Moldova', 'MD'], ['Monaco', 'MC'], ['Mongolia', 'MN'], ['Morocco', 'MA'],
                ['Myanmar', 'MM'], ['Nepal', 'NP'], ['Netherlands', 'NL'], ['New Zealand', 'NZ'],
                ['Nigeria', 'NG'], ['North Korea', 'KP'], ['Norway', 'NO'], ['Oman', 'OM'],
                ['Pakistan', 'PK'], ['Palestine', 'PS'], ['Panama', 'PA'], ['Peru', 'PE'],
                ['Philippines', 'PH'], ['Poland', 'PL'], ['Portugal', 'PT'], ['Qatar', 'QA'],
                ['Romania', 'RO'], ['Russia', 'RU'], ['Saudi Arabia', 'SA'], ['Serbia', 'RS'],
                ['Singapore', 'SG'], ['Slovakia', 'SK'], ['Slovenia', 'SI'], ['South Africa', 'ZA'],
                ['South Korea', 'KR'], ['Spain', 'ES'], ['Sri Lanka', 'LK'], ['Sudan', 'SD'],
                ['Sweden', 'SE'], ['Switzerland', 'CH'], ['Syria', 'SY'], ['Taiwan', 'TW'],
                ['Tanzania', 'TZ'], ['Thailand', 'TH'], ['Tunisia', 'TN'], ['Turkey', 'TR'],
                ['Ukraine', 'UA'], ['United Arab Emirates', 'AE'], ['United Kingdom', 'GB'],
                ['United States', 'US'], ['Venezuela', 'VE'], ['Vietnam', 'VN'], ['Yemen', 'YE'],
                ['Zambia', 'ZM'], ['Zimbabwe', 'ZW']
            ];

            foreach ($countries as $country) {
                $wpdb->insert($countries_table, [
                    'name' => $country[0],
                    'code' => $country[1]
                ]);
            }
        }
    }
}

new Partner_Registration_Plugin();
?>