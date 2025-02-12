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
        if (!empty($approved_partners)) {
            foreach ($approved_partners as $partner) {
                $this->pr_send_yeemail($partner->email, $provider_template_id, $email_data, 'provider');
            }
        }
    
        // Send email to the customer
        if (!empty($user_email)) {
            $this->pr_send_yeemail($user_email, $customer_template_id, $email_data, 'customer');
        }
    }

    // public function send_cf7_to_approved_partners($cf7) {
    //     global $wpdb;

    //     // Target specific form by ID (update this to your actual form ID)
    //     // $target_form_id = 123; // Replace with your Contact Form 7 form ID
    //     // if ($cf7->id() != $target_form_id) {
    //     //     return;
    //     // }

    //     // Fetch approved service partners from the custom table
    //     $approved_partners = $wpdb->get_col(
    //         "SELECT email FROM {$wpdb->prefix}service_partners WHERE status = 1"
    //     );

    //     // If no approved partners, return early
    //     if (empty($approved_partners)) {
    //         return;
    //     }

    //     // Extract form submission data
    //     $submission = WPCF7_Submission::get_instance();
    //     if ($submission) {
    //         $form_data = $submission->get_posted_data();
    //         $user_email = sanitize_email($form_data['your-email']); // Replace 'your-email' with your actual CF7 field name
    //     }

    //     $recipients = $approved_partners;
    //     if (!empty($user_email)) {
    //         $recipients[] = $user_email;
    //     }

    //     // Get the current mail properties
    //     $mail = $cf7->prop('mail');

    //     // Set the recipient emails to approved partners
    //     $mail['recipient'] = implode(',', $recipients);

    //     // Update the mail properties
    //     $cf7->set_properties(['mail' => $mail]);
    // }

    public function pr_send_yeemail($to, $template_id, $email_data, $from = 'provider') {
        if (class_exists('YeeMail')) {
            $email = new YeeMail();
            $email->set_template($template_id);
            $email->set_to($to);
            if ($from == 'provider') {
                $email->set_subject("New Inquiry Received");
            } else {
                $email->set_subject("Thank you for submitting quote");
            }
    
            // Pass CF7 form fields dynamically into the email content
            $email->set_content($email_data);
    
            $email->send();

            echo "aayu";
            exit;
        }
    }
}
