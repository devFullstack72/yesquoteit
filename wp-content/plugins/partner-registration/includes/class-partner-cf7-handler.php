<?php

if (!defined('ABSPATH')) {
    exit;
}

class Partner_CF7_Handler {

    public function __construct() {
        // add_action('wpcf7_before_send_mail', [$this, 'send_cf7_to_approved_partners']);
        add_action('wpcf7_mail_sent', [$this, 'pr_send_custom_cf7_emails']);
    }

    public function pr_send_custom_cf7_emails($contact_form) {
        $submission = WPCF7_Submission::get_instance();
        if (!$submission) {
            return;
        }

        // Retrieve lead_id passed via shortcode
        $lead_id = isset($_POST['is_lead']) ? $_POST['is_lead'] : '';

        $customer_template_id = get_post_meta($lead_id, '_lead_customer_email_template', true);
        $provider_template_id = get_post_meta($lead_id, '_lead_provider_email_template', true);

        if (empty($lead_id))
            return;

        // Get submitted form fields
        $posted_data = $submission->get_posted_data();
        
        // Get user email from the form (modify the key based on your CF7 form)
        $user_email = isset($posted_data['your-email']) ? sanitize_email($posted_data['your-email']) : '';

        global $wpdb;
        $service_partners_table = $wpdb->prefix . 'service_partners'; // Main partners table
        $lead_partners_table = $wpdb->prefix . 'lead_partners'; // Junction table

        $approved_partners = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT sp.email 
                FROM $service_partners_table AS sp
                INNER JOIN $lead_partners_table AS lp ON sp.id = lp.partner_id
                WHERE lp.lead_id = %d AND sp.status = 1",
                $lead_id
            )
        );

        
        // Prepare data for email template
        $email_data = [];
        foreach ($posted_data as $key => $value) {
            if (!is_array($value)) {
                $email_data[$key] = sanitize_text_field($value);
            } else {
                $email_data[$key] = implode(', ', array_map('sanitize_text_field', $value));
            }
        }
    
        // Send email to approved service providers
        $approved_partners_emails = [];
        if (!empty($approved_partners)) {
            foreach ($approved_partners as $partner) {
                $approved_partners_emails[] = $partner->email;
            }
        }

        // Send email to the customer
        if (!empty($approved_partners_emails)) {
            $this->pr_send_yeemail($approved_partners_emails, $provider_template_id, $posted_data, 'provider');
        }
    
        // Send email to the customer
        if (!empty($user_email)) {
            $this->pr_send_yeemail($user_email, $customer_template_id, $email_data, 'customer');
        }
    }

    public function pr_send_yeemail($to, $template_id, $email_data, $from = 'provider') {
        
        if ($from == 'provider') {
            $subject = "New Quote Received";
        } else {
            $subject = "Thank you for submitting quote";
        }

        $content = Yeemail_Builder_Frontend_Functions::creator_template(array(
            "id_template" => $template_id,
            "type" => "full",
            "html" => "",
            "datas" => $email_data,
        ));

        $template_data = [];
        foreach ($email_data as $key => $value) {
            $template_data["[" . $key . "]"] = $value; // Convert to {key} format
        }

        // Replace placeholders manually (if not working inside the function)
        $content = str_replace(array_keys($template_data), array_values($template_data), $content);

		$data = wp_mail( $to, $subject, $content );
        
    }
}
