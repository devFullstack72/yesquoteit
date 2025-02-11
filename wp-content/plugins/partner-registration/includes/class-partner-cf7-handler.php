<?php

if (!defined('ABSPATH')) {
    exit;
}

class Partner_CF7_Handler {

    public function __construct() {
        add_action('wpcf7_before_send_mail', [$this, 'send_cf7_to_approved_partners']);
    }

    public function send_cf7_to_approved_partners($cf7) {
        global $wpdb;

        // Target specific form by ID (update this to your actual form ID)
        // $target_form_id = 123; // Replace with your Contact Form 7 form ID
        // if ($cf7->id() != $target_form_id) {
        //     return;
        // }

        // Fetch approved service partners from the custom table
        $approved_partners = $wpdb->get_col(
            "SELECT email FROM {$wpdb->prefix}service_partners WHERE status = 1"
        );

        // If no approved partners, return early
        if (empty($approved_partners)) {
            return;
        }

        // Extract form submission data
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $form_data = $submission->get_posted_data();
            $user_email = sanitize_email($form_data['your-email']); // Replace 'your-email' with your actual CF7 field name
        }

        $recipients = $approved_partners;
        if (!empty($user_email)) {
            $recipients[] = $user_email;
        }

        // Get the current mail properties
        $mail = $cf7->prop('mail');

        // Set the recipient emails to approved partners
        $mail['recipient'] = implode(',', $recipients);

        // Update the mail properties
        $cf7->set_properties(['mail' => $mail]);
    }
}
