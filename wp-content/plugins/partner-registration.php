<?php
/**
 * Plugin Name: Partner Registration
 * Description: A simple form to register partners and store the data in custom database tables.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit;
}

register_activation_hook(__FILE__, 'pr_create_tables');
add_shortcode('partner_registration_form', 'pr_render_registration_form');

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

function pr_render_registration_form() {
    ob_start();
    ?>
    <h2 class="htlfndr-section-title bigger-title">Become Partner</h2><div class="htlfndr-section-under-title-line"></div>
    <div class="wpcf7 js" style="width: 100%;max-width: 600px;margin: 0px auto; padding: 20px;">
        
    <form id="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="pr_partner_form_submission">
        <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>

        <input type="hidden" name="lead_id" value="<?php echo !empty($_GET['lead_id']) ? $_GET['lead_id'] : '' ?>">
        
        <p><label>Name<br>
        <span class="wpcf7-form-control-wrap" data-name="name"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="name" aria-required="true" aria-invalid="false" value="" type="text" name="name"></span> </label>
        </p>
        <p><label>Email<br>
        <span class="wpcf7-form-control-wrap" data-name="email"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="email" aria-required="true" aria-invalid="false" value="" type="email" name="email"></span> </label>
        </p>

        <p><label>Phone<br>
        <span class="wpcf7-form-control-wrap" data-name="phone"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="phone" aria-required="true" aria-invalid="false" value="" type="text" name="phone"></span> </label>
        </p>

        <p><label>Address<br>
        <span class="wpcf7-form-control-wrap" data-name="address"><textarea size="40" maxlength="400" rows="3" cols="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" autocomplete="address" required aria-required="true" aria-invalid="false" value="" type="text" name="address"></textarea></span> </label>
        </p>

        <p><input class="wpcf7-form-control wpcf7-submit has-spinner" style="width: 30%;" type="submit" name="partner_submit" value="Register"></p>
        
    </form>
    </div>
    <?php

    return ob_get_clean();
}

// Handle form submission with nonce verification
add_action('admin_post_nopriv_pr_partner_form_submission', 'pr_handle_form_submission');
add_action('admin_post_pr_partner_form_submission', 'pr_handle_form_submission');

function pr_handle_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['partner_submit'])) {

        // Verify nonce for security
        if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
            wp_die('Security check failed.');
        }

        global $wpdb;

        // Sanitize form inputs
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $address = sanitize_textarea_field($_POST['address']);

        $service_partners_table = $wpdb->prefix . 'service_partners';
        $lead_partners_table = $wpdb->prefix . 'lead_partners';

        // Insert into service_partners table
        $wpdb->insert($service_partners_table, [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'status' => 0 // Pending status
        ]);

        $partner_id = $wpdb->insert_id;

        // Insert into lead_partners table
        $wpdb->insert($lead_partners_table, [
            'lead_id' => $_POST['lead_id'],
            'partner_id' => $partner_id,
            'created_at' => current_time('mysql')
        ]);

        wp_redirect(add_query_arg('success', '1', $_SERVER['HTTP_REFERER']));
        exit;
    }
}
?>