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

// Initialize the plugin
function pr_plugin_init() {
    new Partner_Registration_Form();
    new Partner_Admin();
}
add_action('plugins_loaded', 'pr_plugin_init');

register_activation_hook(__FILE__, 'pr_create_tables');
function pr_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $service_partners_table = $wpdb->prefix . 'service_partners';
    $lead_partners_table = $wpdb->prefix . 'lead_partners';

    $service_partners_sql = "CREATE TABLE $service_partners_table (
        id BIGINT(20) NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(50) NOT NULL,
        address TEXT NOT NULL,
        status TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    $lead_partners_sql = "CREATE TABLE $lead_partners_table (
        id BIGINT(20) NOT NULL AUTO_INCREMENT,
        lead_id BIGINT(20) NOT NULL,
        partner_id BIGINT(20) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($service_partners_sql);
    dbDelta($lead_partners_sql);
}
?>