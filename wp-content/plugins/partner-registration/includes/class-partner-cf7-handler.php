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

    public $partner_addresses_tbl;

    public function __construct() {
        add_action('wpcf7_before_send_mail', [$this, 'submit_partner_contact_inquiry']);
        add_action('wpcf7_mail_sent', [$this, 'pr_send_custom_cf7_emails']);
        add_action('pr_send_emails_background', [$this, 'pr_send_emails_background'], 10, 5);
        add_action('prospects_send_emails_background', [$this, 'prospects_send_emails_background'], 10, 1);

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

        $this->partner_addresses_tbl = $wpdb->prefix . 'partner_addresses';

        add_action('wpcf7_save_contact_form', [$this, 'save_cf7_fields_labels'], 10, 1);

        // add_action('init', [$this, 'sync_existing_lead_quote_data']);

        // add_action('init', [$this, 'add_cf7_default_fields_lables']);

        add_action('init', [$this, 'sync_partner_addresses_data']);
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


        $partner_ids = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT partner_id FROM $this->partner_addresses_tbl as pa WHERE (
                    (pa.service_area IS NULL OR pa.service_area = '') -- No restriction
                    OR (pa.service_area = 'entire' AND pa.country = %s) -- Entire country
                    OR (pa.service_area = 'state' AND pa.state = %s) -- Entire state
                    OR (pa.service_area = 'other' AND pa.country != %s) -- Other countries
                    OR (pa.service_area = 'every') -- Serves everywhere
                    OR (pa.service_area REGEXP '^[0-9]+$' AND CAST(pa.service_area AS UNSIGNED) > 0 
                        AND (6371 * acos(
                            cos(radians(%f)) * cos(radians(pa.latitude)) *
                            cos(radians(pa.longitude) - radians(%f)) +
                            sin(radians(%f)) * sin(radians(pa.latitude))
                        )) <= CAST(pa.service_area AS UNSIGNED) -- Numeric radius filtering
                    )
                )",
                $customer_country, $customer_state, $customer_country, 
                $customer_lat, $customer_lng, $customer_lat
            )
        );


        $approved_partners = $wpdb->get_results(
            $wpdb->prepare("
                SELECT sp.id as provider_id, sp.email, sp.phone, sp.service_area, sp.latitude, sp.longitude, sp.country, sp.state, sp.status
                FROM $service_partners_table AS sp
                INNER JOIN $lead_partners_table AS lp ON sp.id = lp.partner_id
                WHERE lp.lead_id = %d 
                    AND (sp.status = 1 OR sp.status = 3)
                    AND sp.id IN ('". implode(',', $partner_ids) ."')
            ", 
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

        $customer_id = $this->saveCustomer($email_data);

        $created_lead_quote_id = $this->saveLeadQuote($customer_id, $email_data);

        $email_data['customer_login_link'] = home_url() . '/handler-events/customer/' . encrypt_customer_id($customer_id);

        $email_data['partner_cost_hotlink'] = home_url() . '/partner-customer-requests';
        

        $approved_and_prospect_partners = [];
        // Send email to approved service providers
        $approved_partners_emails = [];
        if (!empty($approved_partners)) {
            foreach ($approved_partners as $partner) {

                if ($partner->status == 3) {
                    $approved_and_prospect_partners[] = $partner;

                    $this->linkLeadQuoteForPartner([
                        'lead_quote_id' => $created_lead_quote_id,
                        'provider_id' => $partner->provider_id
                    ]);
                }

                if ($partner->status == 1) {

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

        // $prospects_partners = $wpdb->get_results(
        //     $wpdb->prepare("
        //         SELECT sp.id as provider_id, sp.email, sp.phone, sp.service_area, sp.latitude, sp.longitude, sp.country, sp.state, 
        //             (6371 * acos(
        //                 cos(radians(%f)) * cos(radians(sp.latitude)) *
        //                 cos(radians(sp.longitude) - radians(%f)) +
        //                 sin(radians(%f)) * sin(radians(sp.latitude))
        //             )) AS distance
        //         FROM $service_partners_table AS sp
        //         INNER JOIN $lead_partners_table AS lp ON sp.id = lp.partner_id
        //         WHERE lp.lead_id = %d 
        //             AND sp.status = 2
        //             AND (
        //                 (sp.service_area IS NULL OR sp.service_area = '') -- No restriction
        //                 OR (sp.service_area = 'entire' AND sp.country = %s) -- Entire country
        //                 OR (sp.service_area = 'state' AND sp.state = %s) -- Entire state
        //                 OR (sp.service_area = 'other' AND sp.country != %s) -- Other countries
        //                 OR (sp.service_area = 'every') -- Serves everywhere
        //                 OR (sp.service_area REGEXP '^[0-9]+$' AND CAST(sp.service_area AS UNSIGNED) > 0 
        //                     AND (6371 * acos(
        //                         cos(radians(%f)) * cos(radians(sp.latitude)) *
        //                         cos(radians(sp.longitude) - radians(%f)) +
        //                         sin(radians(%f)) * sin(radians(sp.latitude))
        //                     )) <= CAST(sp.service_area AS UNSIGNED) -- Numeric radius filtering
        //                 )
        //             )
        //     ", 
        //     $customer_lat, $customer_lng, $customer_lat, 
        //     $lead_id, 
        //     $customer_country, $customer_state, $customer_country, 
        //     $customer_lat, $customer_lng, $customer_lat
        //     )
        // );

        // $prospects_partners_data = [];
        // if (!empty($prospects_partners)) {
        //     foreach ($prospects_partners as $partner) {
        //         $prospects_partners_data[] = $partner;
        //     }
        // }

        // $this->prospects_send_emails_background($prospects_partners_data);
        
        wp_schedule_single_event(
            time() + 10, 
            'prospects_send_emails_background', 
            array($approved_and_prospect_partners)
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

    public function prospects_send_emails_background($prospects_partners_data) {
        
        if (!empty($prospects_partners_data)) {
            foreach ($prospects_partners_data as $prospects_partner) {
                
                // Check if user exists in wp_service_partners table
                $table_name = $this->database->prefix . "service_partners";
                $user = $this->database->get_row($this->database->prepare("SELECT id, email, business_trading_name, status FROM $table_name WHERE email = %s", $prospects_partner->email));
    
                if (!$user) {
                    continue; // Skip if user does not exist
                }

                if ($user->status != 3) {
                    continue;
                }

                // Get email template dynamically
                $template_title = 'Prospects';
                $template_post = get_page_by_title($template_title, OBJECT, 'quote_tpl');
    
                if (!$template_post) {
                    continue; // Skip if template is not found
                }
    
                // Fetch the subject from post meta
                $email_subject_template = get_post_meta($template_post->ID, 'email_subject', true);
                if (!$email_subject_template) {
                    $email_subject_template = "Reset Your Password"; // Fallback subject
                }
    
                // Generate a secure token and expiration time
                $reset_token = wp_generate_password(32, false);
                $expiry_time = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expires in 1 hour
    
                // Store the reset token in the database
                $this->database->update(
                    $table_name,
                    ['reset_token' => $reset_token, 'reset_expires' => $expiry_time],
                    ['id' => $user->id]
                );
    
                // Generate password reset link
                $reset_url = add_query_arg([
                    'key'  => $reset_token,
                    'email'  => rawurlencode($user->email),
                ], site_url('/prospect-reset-password'));
    
                // Replace placeholders in the email body
                $email_body = str_replace(
                    ['{recipient_name}', '{change_password_link}'],
                    [
                        esc_html($user->business_trading_name),
                        esc_url($reset_url)
                    ],
                    wpautop($template_post->post_content)
                );

                // Email headers
                $headers = ['Content-Type: text/html; charset=UTF-8'];
    
                // Send email
                wp_mail(sanitize_email($user->email), esc_html($email_subject_template), $email_body, $headers);
            }
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

        // $headers[] = 'Bcc: info@wisencode.com';

		$data = wp_mail( $to, $subject, $content);
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

        $field_labels = $this->getCF7FieldsLabels();

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

            $this->saveCF7FieldLabel($label, $field_name);

        }
    }

    public function sync_existing_lead_quote_data() {
        $lead_quotes = $this->database->get_results("SELECT id, quote_data FROM {$this->lead_quotes_table}", ARRAY_A);

        // Fetch data from the table
        $field_labels = $this->getCF7FieldsLabels();
        
        foreach($lead_quotes as $lead_quote_row) {
            if (!empty($lead_quote_row['quote_data'])) {
                $lead_quote = json_decode($lead_quote_row['quote_data'], TRUE);
                if (!$this->is_multidimensional_array($lead_quote)) {
                    $posted_data = $this->replaceFieldNameWithLabel($lead_quote);

                    $this->database->update($this->lead_quotes_table, [
                        'quote_data' => json_encode($posted_data)
                    ], [
                        'id' => $lead_quote_row['id']
                    ]);
                } else {
                    foreach($lead_quote as &$lead_quote_data) {
                        $field_key = $lead_quote_data['label'];
                        $field_label = isset($field_labels[$field_key]) ? $field_labels[$field_key] : $field_key;
                        $lead_quote_data = [
                            'label' => $field_label,
                            'value' => $lead_quote_data['value']
                        ];
                    }
                    $this->database->update($this->lead_quotes_table, [
                        'quote_data' => json_encode($lead_quote)
                    ], [
                        'id' => $lead_quote_row['id']
                    ]);
                }
            }
        }
    }

    private function is_multidimensional_array($array) {
        foreach ($array as $value) {
            if (is_array($value)) {
                return true; // Multi-dimensional array detected
            }
        }
        return false; // Single-dimensional array
    }

    public function add_cf7_default_fields_lables() {
        $default_fields_labels = [
            'google_places_form_address' => 'Address',
            'google_places_form_street_number' => 'Street No.',
            'google_places_form_street' => 'Street',
            'google_places_form_city' => 'City',
            'google_places_form_state' => 'State',
            'google_places_form_postalcode' => 'Postalcode',
            'google_places_form_country' => 'Country',
            'google_places_form_latitude' => 'Latitude',
            'google_places_form_longitude' => 'Longitude'
        ];
        foreach($default_fields_labels as $field_name => $label) {
            $this->saveCF7FieldLabel($label, $field_name);
        }
    }

    private function saveCF7FieldLabel($label, $field_name) {
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

    public function getCF7FieldsLabels() {
        // Fetch data from the table
        $results = $this->database->get_results("SELECT field_name, field_label FROM {$this->cf7_fields_labels_table}", ARRAY_A);

        // Convert results into an associative array
        $field_labels = [];
        foreach ($results as $row) {
            $field_labels[$row['field_name']] = $row['field_label'];
        }

        return $field_labels;
    }

    public function sync_partner_addresses_data() {
        global $wpdb;
        
        $service_partners_table = $wpdb->prefix . 'service_partners';
        $partner_addresses_table = $wpdb->prefix . 'partner_addresses';
    
        // Get existing partners with addresses
        $partners = $wpdb->get_results("
            SELECT id, address, latitude, longitude, street_number, route, address2, postal_code, state, country, service_area, other_country
            FROM {$service_partners_table}
            WHERE address IS NOT NULL AND address != ''
        ");
    
        if (!empty($partners)) {
            foreach ($partners as $partner) {
                // Check if this partner's address already exists in the partner_addresses table
                $exists = $wpdb->get_var($wpdb->prepare("
                    SELECT COUNT(*) FROM {$partner_addresses_table}
                    WHERE partner_id = %d AND address = %s
                ", $partner->id, $partner->address));
    
                // If the address does not exist, insert it
                if (!$exists) {
                    $wpdb->insert(
                        $partner_addresses_table,
                        [
                            'partner_id'    => $partner->id,
                            'address'       => $partner->address,
                            'latitude'      => $partner->latitude,
                            'longitude'     => $partner->longitude,
                            'street_number' => $partner->street_number,
                            'route'         => $partner->route,
                            'address2'      => $partner->address2,
                            'postal_code'   => $partner->postal_code,
                            'state'         => $partner->state,
                            'country'       => $partner->country,
                            'service_area'  => $partner->service_area,
                            'other_country' => $partner->other_country,
                            'created_at'    => current_time('mysql')
                        ],
                        [
                            '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'
                        ]
                    );
                }
            }
        }
    }
    
}
