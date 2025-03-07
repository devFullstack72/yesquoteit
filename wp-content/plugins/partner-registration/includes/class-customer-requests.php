<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../includes/Controllers/CustomerController.php';

class Customer_Requests extends CustomerController
{

    public function __construct()
    {
        parent::__construct();

        add_shortcode('customer_requests', [$this, 'render_customer_requests']);

        add_action('wp_ajax_archive_multiple_customer_quotes', [$this, 'archive_multiple_customer_quotes']);
        add_action('wp_ajax_nopriv_archive_multiple_customer_quotes', [$this, 'archive_multiple_customer_quotes']);

        add_action('wp_ajax_delete_multiple_customer_quotes', [$this, 'delete_multiple_customer_quotes']);
        add_action('wp_ajax_nopriv_delete_multiple_customer_quotes', [$this, 'delete_multiple_customer_quotes']);

        add_action('wp_ajax_delete_customer_quote', [$this, 'delete_customer_quote']);
        add_action('wp_ajax_nopriv_delete_customer_quote', [$this, 'delete_customer_quote']);

    } 

    public function render_customer_requests() {
        
        $customer_id = $this->getCustomerID();

        if (!$customer_id) {
            return '<script>window.location.href="' . esc_url(home_url('/customer-login')) . '";</script>';
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
                lq.id as lead_quote_id,
                lq.created_at,
                p.post_title AS lead_name,
                lq.created_at AS lead_created_at
            FROM $this->lead_quotes_table lq
            INNER JOIN $this->customer_table c ON lq.customer_id = c.id
            INNER JOIN $this->posts_table p ON lq.lead_id = p.ID
            WHERE lq.customer_id = %d
            AND p.post_type = 'lead_generation' 
            AND p.post_status = 'publish'";

        if ($is_archived) {
            $query .= " AND lq.is_archived = 1";
        } else {
            $query .= " AND lq.is_archived = 0";
        }

        $query .= " ORDER BY lq.created_at DESC";

        // Execute query with prepared statement for security
        $customer_quotes = $this->database->get_results($this->database->prepare($query, $customer_id));

        ob_start();

        include plugin_dir_path(__FILE__) . '../views/customer/requests.php';

        return ob_get_clean();
    }

    public function archive_multiple_customer_quotes() {
        if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
            wp_send_json_error("Invalid request.");
        }

        global $wpdb;
        $ids = array_map('intval', $_POST['ids']);
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));

        $is_archived = $_POST['is_archived'];
        
        $query = "UPDATE {$wpdb->prefix}yqit_lead_quotes SET is_archived = {$is_archived} WHERE id IN ($placeholders)";
        $result = $wpdb->query($wpdb->prepare($query, $ids));

        if ($result) {
            wp_send_json_success("Quotes archived successfully.");
        } else {
            wp_send_json_error("Failed to archived quotes.");
        }
    }

    public function delete_multiple_customer_quotes() {
        if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
            wp_send_json_error("Invalid request.");
        }
    
        global $wpdb;
        $ids = array_map('intval', $_POST['ids']);
        $placeholders = implode(',', array_fill(0, count($ids), '%d'));
        $query = "DELETE FROM {$wpdb->prefix}yqit_lead_quotes_partners WHERE lead_quote_id IN ($placeholders)";
        $result = $wpdb->query($wpdb->prepare($query, $ids));
    
        $query = "DELETE FROM {$wpdb->prefix}yqit_lead_quotes WHERE id IN ($placeholders)";
        $result = $wpdb->query($wpdb->prepare($query, $ids));
    
        if ($result) {
            wp_send_json_success("Quotes deleted successfully.");
        } else {
            wp_send_json_error("Failed to delete quotes.");
        }
    }

    public function delete_customer_quote() {
        global $wpdb;
    
        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
            wp_send_json_error(['message' => 'Invalid request.']);
        }
    
        $quote_id = intval($_POST['id']);
        $table_name = $wpdb->prefix . 'yqit_lead_quotes_partners';
        $deleted = $wpdb->delete($table_name, ['lead_quote_id' => $quote_id], ['%d']);
    
        $table_name = $wpdb->prefix . 'yqit_lead_quotes';
        $deleted = $wpdb->delete($table_name, ['id' => $quote_id], ['%d']);
    
        if ($deleted) {
            wp_send_json_success(['message' => 'Quote deleted successfully.']);
        } else {
            wp_send_json_error(['message' => 'Failed to delete quote.']);
        }
    }
}