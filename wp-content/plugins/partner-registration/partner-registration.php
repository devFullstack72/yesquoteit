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

        $sql_service_partners = "CREATE TABLE $service_partners_table (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(50) NOT NULL,
            address TEXT NOT NULL,
            status TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        $sql_lead_partners = "CREATE TABLE $lead_partners_table (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            lead_id BIGINT(20) NOT NULL,
            partner_id BIGINT(20) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";

        dbDelta($sql_service_partners);
        dbDelta($sql_lead_partners);
    }
}

new Partner_Registration_Plugin();
?>