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
        $is_lead = isset($_POST['is_lead']) ? $_POST['is_lead'] : '';

        if (empty($is_lead))
            return;

        // Get submitted form fields
        $posted_data = $submission->get_posted_data();
        
        // Get user email from the form (modify the key based on your CF7 form)
        $user_email = isset($posted_data['your-email']) ? sanitize_email($posted_data['your-email']) : '';
    
        // Fetch all approved service provider emails
        global $wpdb;
        $service_partners_table = $wpdb->prefix . 'service_partners';
        $approved_partners = $wpdb->get_results("SELECT email FROM $service_partners_table WHERE status = 1");
        
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
                $this->pr_send_yeemail($partner->email, 'Provider Email Template', $email_data);
            }
        }
    
        // Send email to the customer
        if (!empty($user_email)) {
            $this->pr_send_yeemail($user_email, 'Customer Quote Email', $email_data);
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

    public function pr_send_yeemail($to, $template_name, $email_data) {
        if (class_exists('YeeMail')) {
            $email = new YeeMail();
            $email->set_template($template_name);
            $email->set_to($to);
            $email->set_subject("New Inquiry Received");
    
            // Pass CF7 form fields dynamically into the email content
            $email->set_content($email_data);
    
            $email->send();
        }
    }
}
