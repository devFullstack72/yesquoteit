<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../includes/PartnerController.php';

class Partner_Customer_Requests extends PartnerController
{

    public function __construct()
    {
        parent::__construct();

        add_shortcode('partner_customer_requests', [$this, 'render_partner_customer_requests']);

        add_action('wp_ajax_archive_multiple_partner_quotes', [$this, 'archive_multiple_partner_quotes']);
        add_action('wp_ajax_nopriv_archive_multiple_partner_quotes', [$this, 'archive_multiple_partner_quotes']);
    }

    public function render_partner_customer_requests() {
        
        global $wpdb;
        
        $provider_id = $this->getProviderID();

        if (!$provider_id) {
            return '<script>window.location.href="' . esc_url(home_url('/partner-login')) . '";</script>';
        }

        $is_archived = isset($_GET['is_archived']) && $_GET['is_archived'] == 1;

        $query = "
            SELECT 
                c.id AS customer_id, 
                c.name, 
                c.email, 
                c.phone, 
                lq.lead_id,
                lq.quote_data,  
                lq.created_at,
                lqp.status,
                lqp.provider_id,
                lqp.id as lead_quote_id,
                lqp.lead_quote_id as l_quote_id,
                p.post_title AS lead_name,
                p.post_date AS post_created_date
            FROM $this->lead_quotes_partners_table lqp
            INNER JOIN $this->lead_quotes_table lq ON lqp.lead_quote_id = lq.id
            INNER JOIN $this->customer_table c ON lq.customer_id = c.id
            INNER JOIN $this->posts_table p ON lq.lead_id = p.ID
            WHERE lqp.provider_id = %d
            AND p.post_type = 'lead_generation' 
            AND p.post_status = 'publish'";

        if ($is_archived) {
            $query .= " AND lqp.is_archived = 1";
        } else {
            $query .= " AND lqp.is_archived = 0";
        }

        $query .= " ORDER BY p.post_date DESC";

        // Execute query with prepared statement for security
        $customer_quotes = $this->database->get_results($this->database->prepare($query, $provider_id));

        
        $provider_details = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}service_partners WHERE id = %d", $provider_id)
        );


        ob_start();

        include plugin_dir_path(__FILE__) . '../views/provider/customer-requests.php';

        return ob_get_clean();
    }

    function archive_multiple_partner_quotes() {
        if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
            wp_send_json_error("Invalid request.");
        }

        global $wpdb;
        $ids = array_map('intval', $_POST['ids']);
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));

        $is_archived = $_POST['is_archived'];
        
        $query = "UPDATE {$wpdb->prefix}yqit_lead_quotes_partners SET is_archived = {$is_archived} WHERE id IN ($placeholders)";
        $result = $wpdb->query($wpdb->prepare($query, $ids));

        if ($result) {
            wp_send_json_success("Quotes archived successfully.");
        } else {
            wp_send_json_error("Failed to archived quotes.");
        }
    }

}