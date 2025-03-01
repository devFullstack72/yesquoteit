<?php

if (!defined('ABSPATH')) {
    exit;
}

require_once ABSPATH . 'vendor/autoload.php';


use Twilio\Rest\Client;


class Partner_CF7_Handler {

    public $database;

    public $customer_table;

    public $lead_quotes_table;

    public $lead_quotes_partners_table;

    public $cf7_fields_labels_table;

    public function __construct() {
        add_action('wpcf7_before_send_mail', [$this, 'submit_partner_contact_inquiry']);
        add_action('wpcf7_mail_sent', [$this, 'pr_send_custom_cf7_emails']);
        add_action('pr_send_emails_background', [$this, 'pr_send_emails_background'], 10, 5);

        add_filter('wpcf7_skip_mail', function($skip_mail, $contact_form) {

            $submission = WPCF7_Submission::get_instance();
            if (!$submission) {
                return;
            }

            // Retrieve lead_id passed via shortcode
            $lead_id = isset($_POST['is_lead']) ? $_POST['is_lead'] : '';
            if (!empty($lead_id)) {
                return true; // Prevent default email sending
            }
        }, 10, 2);

        global $wpdb;

        $this->database = $wpdb;

        $this->customer_table = $wpdb->prefix . 'yqit_customers';

        $this->lead_quotes_table = $wpdb->prefix . 'yqit_lead_quotes';

        $this->lead_quotes_partners_table = $wpdb->prefix . 'yqit_lead_quotes_partners';

        $this->cf7_fields_labels_table = $wpdb->prefix . 'cf7_fields_labels';

        add_action('wpcf7_save_contact_form', [$this, 'save_cf7_fields_labels'], 10, 1);
    }

    public function submit_partner_contact_inquiry($contact_form) {
        
        if (!empty($_POST['is_partner_contact_form'])) {

            $mail = $contact_form->prop('mail');

            // Modify the 'To' email address to the provider's email
            $mail['to'] = sanitize_email($_POST['is_partner_contact_form']); // Assuming the provider's email is stored in the `email` field

            // Set the modified email back to the contact form object
            $contact_form->set_properties(['mail' => $mail]);

        }

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

        $customer_lat = isset($posted_data['google_places_form_latitude']) ? floatval($posted_data['google_places_form_latitude']) : '';
        $customer_lng = isset($posted_data['google_places_form_longitude']) ? floatval($posted_data['google_places_form_longitude']) : '';
        $customer_state = isset($posted_data['google_places_form_state']) ? sanitize_text_field($posted_data['google_places_form_state']) : '';
        $customer_country = isset($posted_data['google_places_form_country']) ? sanitize_text_field($posted_data['google_places_form_country']) : '';
      
        global $wpdb;
        $service_partners_table = $wpdb->prefix . 'service_partners'; // Main partners table
        $lead_partners_table = $wpdb->prefix . 'lead_partners'; // Junction table

        // $approved_partners = $wpdb->get_results(
        //     $wpdb->prepare(
        //         "SELECT sp.email 
        //         FROM $service_partners_table AS sp
        //         INNER JOIN $lead_partners_table AS lp ON sp.id = lp.partner_id
        //         WHERE lp.lead_id = %d AND sp.status = 1",
        //         $lead_id
        //     )
        // );


        $approved_partners = $wpdb->get_results(
            $wpdb->prepare("
                SELECT sp.id as provider_id, sp.email, sp.phone, sp.service_area, sp.latitude, sp.longitude, sp.country, sp.state, 
                    (6371 * acos(
                        cos(radians(%f)) * cos(radians(sp.latitude)) *
                        cos(radians(sp.longitude) - radians(%f)) +
                        sin(radians(%f)) * sin(radians(sp.latitude))
                    )) AS distance
                FROM $service_partners_table AS sp
                INNER JOIN $lead_partners_table AS lp ON sp.id = lp.partner_id
                WHERE lp.lead_id = %d 
                    AND sp.status = 1
                    AND (
                        (sp.service_area IS NULL OR sp.service_area = '') -- No restriction
                        OR (sp.service_area = 'entire' AND sp.country = %s) -- Entire country
                        OR (sp.service_area = 'state' AND sp.state = %s) -- Entire state
                        OR (sp.service_area = 'other' AND sp.country != %s) -- Other countries
                        OR (sp.service_area = 'every') -- Serves everywhere
                        OR (sp.service_area REGEXP '^[0-9]+$' AND CAST(sp.service_area AS UNSIGNED) > 0 
                            AND (6371 * acos(
                                cos(radians(%f)) * cos(radians(sp.latitude)) *
                                cos(radians(sp.longitude) - radians(%f)) +
                                sin(radians(%f)) * sin(radians(sp.latitude))
                            )) <= CAST(sp.service_area AS UNSIGNED) -- Numeric radius filtering
                        )
                    )
            ", 
            $customer_lat, $customer_lng, $customer_lat, 
            $lead_id, 
            $customer_country, $customer_state, $customer_country, 
            $customer_lat, $customer_lng, $customer_lat
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

        $customer_id = $this->saveCustomer($email_data);

        $created_lead_quote_id = $this->saveLeadQuote($customer_id, $email_data);

        $email_data['customer_login_link'] = home_url() . '/handler-events/customer/' . encrypt_customer_id($customer_id);

        $email_data['partner_cost_hotlink'] = home_url() . '/partner-customer-requests';
        

        // Send email to approved service providers
        $approved_partners_emails = [];
        if (!empty($approved_partners)) {
            foreach ($approved_partners as $partner) {
                $approved_partners_emails[] = $partner->email;
                // $this->pr_send_yeemail($partner->email, $provider_template_id, $email_data, 'provider');
                $this->linkLeadQuoteForPartner([
                    'lead_quote_id' => $created_lead_quote_id,
                    'provider_id' => $partner->provider_id
                ]);

                $message = "New Quote Received from". $email_data['your-name'];

                $this->sendSMS($message, $partner->phone);
            }
        }

        // Send email to the customer
        // if (!empty($approved_partners_emails)) {
        //     $this->pr_send_yeemail($approved_partners_emails, $provider_template_id, $email_data, 'provider');
        // }
    
        // Send email to the customer
        // if (!empty($user_email)) {
        //     $this->pr_send_yeemail($user_email, $customer_template_id, $email_data, 'customer');
        // }

        // Schedule background email sending
        wp_schedule_single_event(
            time() + 10, 
            'pr_send_emails_background', 
            array($user_email, $approved_partners_emails, $customer_template_id, $provider_template_id, $email_data)
        );
    }

    public function sendSMS($message, $to){
         // SMS Message
         $account_sid = get_option('twilio_account_sid', '');
         $auth_token = get_option('twilio_auth_token', '');
         $twilio_number = get_option('twilio_number', '');

         // Send SMS using Twilio
         try {
             $client = new Client($account_sid, $auth_token);
             $response = $client->messages->create(
                 $to,
                 [
                     'from' => $twilio_number,
                     'body' => $message
                 ]
             );

         } catch (Exception $e) {
             error_log('Twilio SMS Error: ' . $e->getMessage());
         }
    }

    // Background email processing
    public function pr_send_emails_background($user_email, $partner_emails, $customer_template_id, $provider_template_id, $email_data = []) {
        if (!empty($partner_emails)) {
            foreach ($partner_emails as $email) {
                $this->pr_send_yeemail($email, $provider_template_id, $email_data, 'provider');
            }
        }

        if (!empty($user_email)) {
            $this->pr_send_yeemail($user_email, $customer_template_id, $email_data, 'customer');
        }
    }

    public function pr_send_yeemail($to, $template_id, $email_data, $from = 'provider') {
        
        if ($from == 'provider') {
            $subject = get_option('provider_email');
        } else {
            $subject = get_option('customer_email');
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

        // if ($from == 'customer') {
        //     $content = $this->replaceDynamicPlaceholders($content);
        // }

		$data = wp_mail( $to, $subject, $content );
        
    }

    protected function saveCustomer($data) {

        $customer_id = $this->database->get_var( $this->database->prepare(
            "SELECT id FROM $this->customer_table WHERE email = %s LIMIT 1", 
            $data['your-email']
        ));

        if ($customer_id) {
            return $customer_id;
        }

        $this->database->insert($this->customer_table, [
            'name' => $data['your-name'],
            'email' => $data['your-email'],
            'phone' => $data['tel-601'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $customer_id = $this->database->insert_id;

        return $customer_id;

    }

    protected function replaceDynamicPlaceholders($content) {
        $content = str_replace('{customer_login_link}', home_url() . '/customer-login', $content);

        return $content;
    }

    protected function saveLeadQuote($customer_id, $email_data) {
        
        unset($email_data['g-recaptcha-response']);

        $quote_info = $this->replaceFieldNameWithLabel($email_data);

        $this->database->insert($this->lead_quotes_table, [
            'customer_id' => $customer_id,
            'lead_id' => $email_data['is_lead'],
            'quote_data' => json_encode($quote_info)
        ]);

        $quote_id = $this->database->insert_id;

        return $quote_id;

    }

    protected function replaceFieldNameWithLabel($posted_data) {

        // Fetch data from the table
        $results = $this->database->get_results("SELECT field_name, field_label FROM {$this->cf7_fields_labels_table}", ARRAY_A);

        // Convert results into an associative array
        $field_labels = [];
        foreach ($results as $row) {
            $field_labels[$row['field_name']] = $row['field_label'];
        }

        $quote_info = [];

        foreach ($posted_data as $field_key => $field_value) {
            $field_label = isset($field_labels[$field_key]) ? $field_labels[$field_key] : $field_key;
            $quote_info[$field_key] = [
                'label' => $field_label,
                'value' => $field_value
            ];
        }

        return $quote_info;
    }

    protected function linkLeadQuoteForPartner($data) {

        $this->database->insert($this->lead_quotes_partners_table, [
            'lead_quote_id' => $data['lead_quote_id'],
            'provider_id' => $data['provider_id']
        ]);

        return true;
        
    }

    public function save_cf7_fields_labels($contact_form) {
        
        $form_properties = $contact_form->get_properties();
        $form_fields = $form_properties['form'];
        
        // Updated regex to capture full field names (e.g., number-266)
        preg_match_all('/<label>\s*(.*?)\s*<\/label>\s*\[([a-zA-Z0-9-*]+)\s+([a-zA-Z0-9-_]+)[^\]]*\]/', $form_fields, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            
            $label = sanitize_text_field(trim($match[1]));  // Extract label text
            $field_name = sanitize_text_field(trim($match[3]));  // Extract actual field name

            if (in_array($field_name, ['is_lead']))
                continue;

            // Check if the field already exists
            $existing = $this->database->get_var($this->database->prepare(
                "SELECT COUNT(*) FROM {$this->cf7_fields_labels_table} WHERE field_name = %s",
                $field_name
            ));

            if ($existing) {
                // Update existing field label
                $this->database->update(
                    $this->cf7_fields_labels_table,
                    ['field_label' => $label],  // Update field_label
                    ['field_name' => $field_name],  // Where condition
                    ['%s'], ['%s']
                );
            } else {
                // Insert new field
                $this->database->insert(
                    $this->cf7_fields_labels_table,
                    [
                        'field_name' => $field_name,
                        'field_label' => $label
                    ],
                    ['%s', '%s']
                );
            }
        }
    }
}
