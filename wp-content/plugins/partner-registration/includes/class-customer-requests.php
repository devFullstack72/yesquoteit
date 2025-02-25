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
    }

    public function render_customer_requests() {
        
        $customer_id = $this->getCustomerID();

        if (!$customer_id) {
            return '<script>window.location.href="' . esc_url(home_url('/customer-login')) . '";</script>';
        }

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
                p.post_title AS lead_name
            FROM $this->lead_quotes_table lq
            INNER JOIN $this->customer_table c ON lq.customer_id = c.id
            INNER JOIN $this->posts_table p ON lq.lead_id = p.ID
            WHERE lq.customer_id = %d
            AND p.post_type = 'lead_generation' 
            AND p.post_status = 'publish'
            ORDER BY c.created_at DESC
        ";

        // Execute query with prepared statement for security
        $customer_quotes = $this->database->get_results($this->database->prepare($query, $customer_id));

        ob_start();

        include plugin_dir_path(__FILE__) . '../views/customer/requests.php';

        return ob_get_clean();
    }
}