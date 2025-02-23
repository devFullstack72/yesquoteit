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
    }

    public function render_partner_customer_requests() {
        
        $provider_id = $this->getProviderID();

        $query = "
            SELECT 
                c.id AS customer_id, 
                c.name, 
                c.email, 
                c.phone, 
                lq.lead_id, 
                lq.created_at,
                p.post_title AS lead_name
            FROM $this->lead_quotes_partners_table lqp
            INNER JOIN $this->lead_quotes_table lq ON lqp.lead_quote_id = lq.id
            INNER JOIN $this->customer_table c ON lq.customer_id = c.id
            INNER JOIN $this->posts_table p ON lq.lead_id = p.ID
            WHERE lqp.provider_id = %d
            AND p.post_type = 'lead_generation' 
            AND p.post_status = 'publish'
            ORDER BY c.created_at DESC
        ";

        // Execute query with prepared statement for security
        $customer_quotes = $this->database->get_results($this->database->prepare($query, $provider_id));

        ob_start();

        include plugin_dir_path(__FILE__) . '../views/provider/customer-requests.php';

        return ob_get_clean();
    }
}