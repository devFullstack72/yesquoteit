<?php
if (!defined('ABSPATH')) {
    exit;
}

class Partner_Registration_Form
{

    public $service_partners_table;
    public $lead_partners_table;
    public $wpdb;

    public function __construct()
    {
        add_shortcode('partner_registration_form', [$this, 'render_registration_form']);
        add_shortcode('partner_forgot_password_form', [$this, 'render_forgot_password_form']);
        // add_shortcode('prospect_reset_password_form', [$this, 'render_reset_password_form']);

        add_shortcode('prospect_reset_password_form', function () {
            return $this->render_reset_password_form('Create Password');
        });

        // add_shortcode('customer_forgot_password_form', [$this, 'render_customer_forgot_password_form']);
        // add_action('admin_post_nopriv_pr_partner_form_submission', [$this, 'handle_form_submission']);
        // add_action('admin_post_pr_partner_form_submission', [$this, 'handle_form_submission']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_1', [$this, 'handle_pr_partner_form_submission_step_1']);
        add_action('admin_post_pr_partner_form_submission_step_1', [$this, 'handle_pr_partner_form_submission_step_1']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_2', [$this, 'handle_pr_partner_form_submission_step_2']);
        add_action('admin_post_pr_partner_form_submission_step_2', [$this, 'handle_pr_partner_form_submission_step_2']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_3', [$this, 'handle_pr_partner_form_submission_step_3']);
        add_action('admin_post_pr_partner_form_submission_step_3', [$this, 'handle_pr_partner_form_submission_step_3']);


        add_action('admin_post_nopriv_pr_partner_form_submission_multiple_address', [$this, 'handle_pr_partner_form_submission_multiple_address']);
        add_action('admin_post_pr_partner_form_submission_multiple_address', [$this, 'handle_pr_partner_form_submission_multiple_address']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_4', [$this, 'handle_pr_partner_form_submission_step_4']);
        add_action('admin_post_pr_partner_form_submission_step_4', [$this, 'handle_pr_partner_form_submission_step_4']);

        add_action('admin_post_nopriv_pr_partner_change_password', [$this, 'handle_pr_partner_change_password']);
        add_action('admin_post_pr_partner_change_password', [$this, 'handle_pr_partner_change_password']);


        add_action('admin_post_nopriv_pr_partner_forgot_password', [$this, 'handle_pr_handle_forgot_password']);
        add_action('admin_post_pr_partner_forgot_password', [$this, 'handle_pr_handle_forgot_password']);


        // add_shortcode('partner_reset_password_form', [$this, 'render_reset_password_form']);
        add_shortcode('partner_reset_password_form', function () {
            return $this->render_reset_password_form('Reset Password');
        });



        add_action('admin_post_nopriv_pr_partner_reset_password', [$this, 'handle_pr_partner_reset_password']);
        add_action('admin_post_pr_partner_reset_password', [$this, 'handle_pr_partner_reset_password']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        if (!session_id()) {
            session_start();
        }

        global $wpdb;

        $this->wpdb = $wpdb;

        $this->service_partners_table = $wpdb->prefix . 'service_partners';
        $this->lead_partners_table = $wpdb->prefix . 'lead_partners';
    }

    public function enqueue_scripts()
    {
        $current_page_slug = get_post_field('post_name', get_queried_object_id()); // Get the current page slug

       

        // if ($current_page_slug == "register-your-business") {
            // Ensure jQuery is included
            wp_enqueue_script('jquery');

            // Enqueue Google Maps API
            // wp_enqueue_script('google-places', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyADTn5LfNUzzbgxNd-TFiNbVwAf0JNoNBw&libraries=places', [], null, true);

            // Custom script for autocomplete

            

            wp_enqueue_script('partner-multiple-registration-script', plugin_dir_url(__FILE__) . 'js/partner-registration-multiple-address.js', ['jquery', 'google-places'], null, true);

            global $wpdb;

            $countries_table = $wpdb->prefix . 'countries';
                
            $countries = $wpdb->get_results("SELECT * FROM {$countries_table}");

            $country_data = [];
            foreach ($countries as $country) {
                $country_data[] = [
                    'code' => $country->code,
                    'name' => $country->name,
                ];
            }

            // Pass country data to JavaScript
            wp_localize_script('partner-multiple-registration-script', 'countryData', ['countries' => $country_data]);
            // wp_enqueue_script('partner-registration-script', plugin_dir_url(__FILE__) . 'js/partner-registration.js', ['jquery', 'google-places'], null, true);
            
            wp_enqueue_style('partner-registration-css', plugin_dir_url(__FILE__) . 'css/partner-registration.css');
        // }

        if ($current_page_slug === "register-your-business") {
            wp_enqueue_script('partner-registration-script', plugin_dir_url(__FILE__) . 'js/partner-registration.js', ['jquery', 'google-places'], null, true);
        }
    }


    public function render_registration_form($atts)
    {

        // Set default attributes and merge with user-specified attributes
        $atts = shortcode_atts([
            'profile'    => false, 
        ], $atts, 'partner_registration_form');

        ob_start(); // Start output buffering
        
        global $wpdb;
    
        // Get the lead ID from URL
        $selected_lead_id = isset($_GET['lead_id']) ? intval($_GET['lead_id']) : null;
    
        // Fetch available leads (modify query as needed)
        $leads = $wpdb->get_results("
            SELECT ID, post_title 
            FROM {$wpdb->posts} 
            WHERE post_type = 'lead_generation' 
            AND post_status = 'publish'
            ORDER BY post_title ASC
        ");

        $countries_table = $wpdb->prefix . 'countries';
        
        $countries = $wpdb->get_results("
            SELECT * 
            FROM {$countries_table}
        ");

        $partner = '';
        $partner_leads = '';
        if (!empty($_SESSION['partner_id'])) {

            $partner_id = $_SESSION['partner_id'];

            // Fetch partner details
            $partner = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$this->service_partners_table} WHERE id = %d",
                $partner_id
            ));

            if (!$partner) {
                return false; // Partner not found
            }

            // Fetch associated leads
            $partner_leads = $wpdb->get_col($wpdb->prepare(
                "SELECT lead_id FROM {$this->lead_partners_table} WHERE partner_id = %d",
                $partner_id
            ));
        }

        // Start output buffering
        ob_start();

        // Define path to the view file
        $view_path = plugin_dir_path(__FILE__) . '../views/partner-registration-form.php';

        // Ensure file exists before including
        if (file_exists($view_path)) {
            include $view_path;
        } else {
            echo "<p>Error: View file not found!</p>";
        }

        return ob_get_clean();
        
    }

    public function handle_form_submission()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['partner_submit'])) {

            // Verify nonce for security
            if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
                wp_die('Security check failed.');
            }

            global $wpdb;

            $name = sanitize_text_field($_POST['name']);
            $email = sanitize_email($_POST['email']);
            $phone = sanitize_text_field($_POST['phone']);
            $address = sanitize_text_field($_POST['address']);
            $latitude = sanitize_text_field($_POST['latitude']);
            $longitude = sanitize_text_field($_POST['longitude']);
            $street_number = sanitize_text_field($_POST['street_number']);
            $route = sanitize_text_field($_POST['route']);
            $address2 = sanitize_text_field($_POST['address2']);
            $postal_code = sanitize_text_field($_POST['postal_code']);
            $state = sanitize_text_field($_POST['state']);
            $country = sanitize_text_field($_POST['country']);
            $lead_ids = isset($_POST['lead_ids']) ? array_map('intval', $_POST['lead_ids']) : [];

            $service_area = sanitize_text_field($_POST['service_area']);
            $other_country = sanitize_text_field($_POST['other_country']);


            $service_partners_table = $wpdb->prefix . 'service_partners';
            $lead_partners_table = $wpdb->prefix . 'lead_partners';

            $wpdb->insert($this->service_partners_table, [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'street_number' => $street_number,
                'route' => $route,
                'address2' => $address2,
                'postal_code' => $postal_code,
                'state' => $state,
                'country' => $country,
                'service_area' => $service_area,
                'other_country' => $other_country,
                'status' => 0
            ]);

            $partner_id = $wpdb->insert_id;

            // $wpdb->insert($lead_partners_table, [
            //     'lead_id' => $_POST['lead_id'],
            //     'partner_id' => $partner_id,
            //     'created_at' => current_time('mysql')
            // ]);
            foreach ($lead_ids as $lead_id) {
                $wpdb->insert($this->lead_partners_table, [
                    'lead_id' => $lead_id,
                    'partner_id' => $partner_id,
                    'created_at' => current_time('mysql')
                ]);
            }

            wp_redirect(add_query_arg('success', '1', $_SERVER['HTTP_REFERER']));
            exit;
        }
    }

    public function handle_pr_partner_form_submission_step_1() {
        // Verify nonce for security
        if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
            wp_die('Security check failed.');
        }

        global $wpdb;

        $name = sanitize_text_field($_POST['name']);
        $business_trading_name = sanitize_text_field($_POST['business_trading_name']);
        $email = sanitize_email($_POST['email']);
        $confirm_email = sanitize_email($_POST['c_email'] ?? '');
        $password = sanitize_text_field($_POST['password'] ?? '');
        $phone = sanitize_text_field($_POST['phone']);

        $profile_edit_mode = $_POST['profile_edit_mode'];

        // Validation errors array
        $errors = [];

        // Required field validation
        if (empty($name)) {
            $errors['name'] = "Name is required.";
        }
        if (empty($business_trading_name)) {
            $errors['business_trading_name'] = "Business Trading Name is required.";
        }
        if (empty($email)) {
            $errors['email'] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email format.";
        }
        if (empty($confirm_email) && !$profile_edit_mode) {
            $errors['confirm_email'] = "Confirm Email is required.";
        } elseif ($email !== $confirm_email && !$profile_edit_mode) {
            $errors['confirm_email'] = "Email and Confirm Email do not match.";
        } else {
            // Check if email already exists in database
            global $wpdb;
        
            if ($profile_edit_mode && isset($_SESSION['partner_id'])) {
                $existing_email = $wpdb->get_var($wpdb->prepare(
                    "SELECT email FROM {$this->service_partners_table} WHERE email = %s AND id != %d",
                    $email,
                    $_SESSION['partner_id'] // The ID of the partner being updated
                ));
            } else {
                $existing_email = $wpdb->get_var($wpdb->prepare(
                    "SELECT email FROM {$this->service_partners_table} WHERE email = %s",
                    $email
                ));
            }
            
        
            if ($existing_email) {
                $errors['email'] = "This email is already registered.";
            }
        }

        if (empty($password) && !$profile_edit_mode) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($password) < 8 && !$profile_edit_mode) {
            $errors['password'] = "Password must be at least 8 characters.";
        }
        if (empty($phone)) {
            $errors['phone'] = "Phone number is required.";
        }

        // If there are errors, redirect back with errors
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST; // Store old values
            wp_safe_redirect(add_query_arg('form_error', '1', wp_get_referer()));
            exit;
        }

        // If no errors, process the form (e.g., insert into database, send email, etc.)
        global $wpdb;

        $data = [
            'name' => $name,
            'business_trading_name' => $business_trading_name,
            'email' => $email,
            'phone' => $phone,
            'status' => 0
        ];

        if (!$profile_edit_mode) {
            $data['password'] = wp_hash_password($password);
            $wpdb->insert($this->service_partners_table, $data);
        } else {
            $wpdb->update($this->service_partners_table, $data, [
                'id' => $_SESSION['partner_id']
            ]);
        }

        $partner_id = $wpdb->insert_id;
        
        // After successful form submission, clear session data
        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);

        if ($profile_edit_mode) {
            $_SESSION['profile_updated'] = true;
            $redirect_url = wp_get_referer();
            wp_safe_redirect($redirect_url);
            exit;
        }

        $_SESSION['partner_id'] = $partner_id;

        // Redirect with both parameters (next-step and partner_id)
        $redirect_url = add_query_arg([
            'next_step' => '2'
        ], wp_get_referer());

        wp_safe_redirect($redirect_url);
        exit;

    }

    public function handle_pr_partner_form_submission_step_2() {
        // Verify nonce for security
        if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
            wp_die('Security check failed.');
        }

        global $wpdb;

        $lead_ids = isset($_POST['lead_ids']) ? array_map('intval', $_POST['lead_ids']) : [];

        $profile_edit_mode = $_POST['profile_edit_mode'];

        // Validation errors array
        $errors = [];

        // Required field validation
        if (empty($lead_ids) || !array_filter($lead_ids)) {
            $errors['services'] = "Select atlease one service";
        }

        // If there are errors, redirect back with errors
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST; // Store old values
            // Redirect with both parameters (next-step and partner_id)
            $redirect_url = add_query_arg([
                'form_error' => 1,
                'next_step' => 2
            ], wp_get_referer());

            wp_safe_redirect($redirect_url);
            exit;
        }

        // If no errors, process the form (e.g., insert into database, send email, etc.)
        global $wpdb;

        if (isset($_SESSION['partner_id'])) {
            // Delete existing records for the partner
            $wpdb->delete($this->lead_partners_table, ['partner_id' => $_SESSION['partner_id']]);
        }

        foreach ($lead_ids as $lead_id) {
            $wpdb->insert($this->lead_partners_table, [
                'lead_id' => $lead_id,
                'partner_id' => $_SESSION['partner_id'],
                'created_at' => current_time('mysql')
            ]);
        }
        
        // After successful form submission, clear session data
        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);

        if ($profile_edit_mode && isset($_SESSION['partner_id'])) {
            $_SESSION['profile_updated'] = true;
            $redirect_url = wp_get_referer();
            wp_safe_redirect($redirect_url);
            exit;
        }

        // Redirect with both parameters (next-step and partner_id)
        $redirect_url = add_query_arg([
            'next_step' => 3
        ], wp_get_referer());

        wp_safe_redirect($redirect_url);
        exit;


    }

    public function handle_pr_partner_form_submission_step_3() {

        // dd($_POST);
        // Verify nonce for security
        if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
            wp_die('Security check failed.');
        }

        $address = sanitize_text_field($_POST['address']);
        $latitude = sanitize_text_field($_POST['latitude']);
        $longitude = sanitize_text_field($_POST['longitude']);
        $street_number = sanitize_text_field($_POST['street_number']);
        $route = sanitize_text_field($_POST['route']);
        $address2 = sanitize_text_field($_POST['address2']);
        $postal_code = sanitize_text_field($_POST['postal_code']);
        $state = sanitize_text_field($_POST['state']);
        $country = sanitize_text_field($_POST['country']);

        $service_area = sanitize_text_field($_POST['service_area']);
        $other_country = sanitize_text_field($_POST['other_country']);

        $profile_edit_mode = $_POST['profile_edit_mode'];

        if ($other_country == 0) {
            $other_country = null;
        }

        // Validation errors array
        // $errors = [];

        // if (empty($address)) {
        //     $errors['address'] = "Address is required.";
        // }

        // if (empty($latitude)) {
        //     $errors['address'] = "Please choose address from google";
        // }

        // if (empty($service_area)) {
        //     $errors['service_area'] = "Please choose service area";
        // }

        // // If there are errors, redirect back with errors
        // if (!empty($errors)) {
        //     $_SESSION['form_errors'] = $errors;
        //     $_SESSION['form_data'] = $_POST; // Store old values
        //     // Redirect with both parameters (next-step and partner_id)
        //     $redirect_url = add_query_arg([
        //         'form_error' => 1,
        //         'next_step' => 3
        //     ], wp_get_referer());

        //     wp_safe_redirect($redirect_url);
        //     exit;
        // }

        // Validation errors array
        $errors = [];

        if (is_array($_POST['address'])) {
            foreach ($_POST['address'] as $key => $address) {
                if (empty($address)) {
                    $errors["address_{$key}"] = "Address is required.";
                }

                if (empty($_POST['latitude'][$key]) || empty($_POST['longitude'][$key])) {
                    $errors["address_{$key}"] = "Please choose address from Google.";
                }

                if (empty($_POST['service_area'][$key])) {
                    $errors["service_area_{$key}"] = "Please choose a service area.";
                }
            }
        } else {
            if (empty($_POST['address'])) {
                $errors['address'] = "Address is required.";
            }

            if (empty($_POST['latitude']) || empty($_POST['longitude'])) {
                $errors['address'] = "Please choose address from Google.";
            }

            if (empty($_POST['service_area'])) {
                $errors['service_area'] = "Please choose a service area.";
            }
        }

        // If there are errors, redirect back with errors
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST; // Store old values

            $redirect_url = add_query_arg([
                'form_error' => 1,
                'next_step' => 3
            ], wp_get_referer());

            wp_safe_redirect($redirect_url);
            exit;
        }


        if (!isset($_SESSION['partner_id'])) {
            $redirect_url = add_query_arg([
                'form_error' => 1,
                'next_step' => 1
            ], wp_get_referer());

            wp_safe_redirect($redirect_url);
            exit;
        }

        global $wpdb;


         // Check if we have multiple addresses
        if (is_array($_POST['address'])) {
            $addresses = $_POST['address'];
            $latitudes = $_POST['latitude'];
            $longitudes = $_POST['longitude'];
            $street_numbers = $_POST['street_number'];
            $routes = $_POST['route'];
            $address2_list = $_POST['address2'];
            $postal_codes = $_POST['postal_code'];
            $states = $_POST['state'];
            $countries = $_POST['country'];
            $service_areas = $_POST['service_area'];
            $other_countries = $_POST['other_country'];

            // Clear old entries (if needed)
            $wpdb->delete('wp_partner_addresses', ['partner_id' => $_SESSION['partner_id']]);

            foreach ($addresses as $key => $address) {
                $wpdb->insert('wp_partner_addresses', [
                    'partner_id'    => $_SESSION['partner_id'],
                    'address'       => sanitize_text_field($address),
                    'latitude'      => sanitize_text_field($latitudes[$key]),
                    'longitude'     => sanitize_text_field($longitudes[$key]),
                    'street_number' => sanitize_text_field($street_numbers[$key]),
                    'route'         => sanitize_text_field($routes[$key]),
                    'address2'      => sanitize_text_field($address2_list[$key]),
                    'postal_code'   => sanitize_text_field($postal_codes[$key]),
                    'state'         => sanitize_text_field($states[$key]),
                    'country'       => sanitize_text_field($countries[$key]),
                    'service_area'  => sanitize_text_field($service_areas[$key]),
                    'other_country' => ($other_countries[$key] == 0) ? null : sanitize_text_field($other_countries[$key]),
                ]);
            }
        }

        // $wpdb->update($this->service_partners_table, [
        //     'address' => $address,
        //     'latitude' => $latitude,
        //     'longitude' => $longitude,
        //     'street_number' => $street_number,
        //     'route' => $route,
        //     'address2' => $address2,
        //     'postal_code' => $postal_code,
        //     'state' => $state,
        //     'country' => $country,
        //     'service_area' => $service_area,
        //     'other_country' => $other_country
        // ], [ 'id' => $_SESSION['partner_id'] ]);

        // After successful form submission, clear session data
        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);

        if ($profile_edit_mode && isset($_SESSION['partner_id'])) {
            $_SESSION['profile_updated'] = true;
            $redirect_url = wp_get_referer();
            wp_safe_redirect($redirect_url);
            exit;
        }

        // Redirect with both parameters (next-step and partner_id)
        $redirect_url = add_query_arg([
            'next_step' => 4
        ], wp_get_referer());

        wp_safe_redirect($redirect_url);
        exit;
    }

    public function handle_pr_partner_form_submission_multiple_address() {

        global $wpdb;
        
        $partner_id = $_POST['partner_id'];
        // Handle multiple addresses
        $addresses_table = $wpdb->prefix . 'partner_addresses';
        
        

        // Delete old addresses for this partner before inserting new ones
        $wpdb->delete($addresses_table, ['partner_id' => $partner_id]);
    
        if (!empty($_POST['addresses'])) {
            foreach ($_POST['addresses'] as $key => $address) {
                $address = sanitize_text_field($address);
                $latitude = sanitize_text_field($_POST['latitude'][$key]);
                $longitude = sanitize_text_field($_POST['longitude'][$key]);
                $street_number = sanitize_text_field($_POST['street_number'][$key]);
                $route = sanitize_text_field($_POST['route'][$key]);
                $address2 = sanitize_text_field($_POST['address2'][$key]);
                $postal_code = sanitize_text_field($_POST['postal_code'][$key]);
                $state = sanitize_text_field($_POST['state'][$key]);
                $country = sanitize_text_field($_POST['country'][$key]);
                $service_area = $_POST['service_area'][$key];
                $other_country = $_POST['other_country'][$key];
    
                // Insert new address into the database
                $wpdb->insert($addresses_table, [
                    'partner_id' => $partner_id,
                    'address' => $address,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'street_number' => $street_number,
                    'route' => $route,
                    'address2' => $address2,
                    'postal_code' => $postal_code,
                    'state' => $state,
                    'country' => $country,
                    'service_area' => $service_area,
                    'other_country' => $other_country
                ]);
            }
        }
    
        // Redirect or display a success message
        $redirect_url = wp_get_referer();
        wp_safe_redirect($redirect_url);
    }

    // public function handle_pr_partner_form_submission_step_4() {
    //     // Verify nonce for security
    //     if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
    //         wp_die('Security check failed.');
    //     }

    //     $website_url = esc_url_raw($_POST['website_url']);

    //     $profile_edit_mode = $_POST['profile_edit_mode'];

    //     // Validation errors array
    //     $errors = [];

    //     if (empty($website_url)) {
    //         $errors['website_url'] = "Website URL is required";
    //     }

    //     // Handle Business Logo Upload
    //     $business_logo_url = ''; // Default empty value
    //     if (!empty($_FILES['business_logo']['name'])) {
    //         require_once ABSPATH . 'wp-admin/includes/file.php';

    //         $uploaded_file = $_FILES['business_logo'];
    //         $upload_overrides = ['test_form' => false]; // Allow uploads outside form validation
    //         $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

    //         if ($movefile && !isset($movefile['error'])) {
    //             $business_logo_url = $movefile['url']; // Store uploaded image URL
    //         } else {
    //             $errors['business_logo'] = 'File upload failed: ' . $movefile['error'];
    //         }
    //     } else {
    //         if (!$profile_edit_mode) {
    //             $errors['business_logo'] = "Business logo is required.";
    //         }
    //     }

    //     // If there are errors, redirect back with errors
    //     if (!empty($errors)) {
    //         $_SESSION['form_errors'] = $errors;
    //         $_SESSION['form_data'] = $_POST; // Store old values
    //         // Redirect with both parameters (next-step and partner_id)
    //         $redirect_url = add_query_arg([
    //             'form_error' => 1,
    //             'next_step' => 4
    //         ], wp_get_referer());

    //         wp_safe_redirect($redirect_url);
    //         exit;
    //     }

    //     global $wpdb;

    //     // Get Partner ID from session
    //     $partner_id = intval($_SESSION['partner_id']);
    //     if (!$partner_id) {
    //         wp_die('Invalid Partner ID.');
    //     }

    //     // Update database with business logo and website URL

    //     $data = [
    //         'website_url' => $website_url
    //     ];

    //     if (!empty($business_logo_url)) {
    //         $data['business_logo'] = $business_logo_url;
    //     }

    //     $result = $wpdb->update(
    //         $this->service_partners_table,
    //         $data,
    //         ['id' => $partner_id]
    //     );

    //     if ($result === false) {
    //         wp_die('Database update failed: ' . $wpdb->last_error);
    //     }

    //     if ($profile_edit_mode && isset($_SESSION['partner_id'])) {
    //         $_SESSION['profile_updated'] = true;
    //         $redirect_url = wp_get_referer();
    //         wp_safe_redirect($redirect_url);
    //         exit;
    //     }

    //     // Redirect on success
    //     $redirect_url = add_query_arg([
    //         'success' => 1,
    //         'next_step' => 5
    //     ], wp_get_referer());

    //     wp_safe_redirect($redirect_url);
    //     exit;

    // }

    public function handle_pr_partner_form_submission_step_4() {
        // Verify nonce for security
        if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
            wp_die('Security check failed.');
        }
    
        $website_url = esc_url_raw($_POST['website_url']);
        $profile_edit_mode = $_POST['profile_edit_mode'];
        $errors = [];
    
        if (empty($website_url)) {
            $errors['website_url'] = "Website URL is required";
        }
    
        // Handle Cropped Image Upload
        $business_logo_url = '';
        if (!empty($_POST['cropped_image'])) {
            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['path'] . '/';
            $upload_url = $upload_dir['url'] . '/';
            
            $cropped_image_data = $_POST['cropped_image'];
            $image_name = 'cropped_' . time() . '.jpg';
            $image_path = $upload_path . $image_name;
            $image_url = $upload_url . $image_name;
    
            // Remove base64 header and decode image
            $cropped_image_data = str_replace('data:image/jpeg;base64,', '', $cropped_image_data);
            $cropped_image_data = base64_decode($cropped_image_data);
    
            // Save the cropped image
            if (file_put_contents($image_path, $cropped_image_data)) {
                $business_logo_url = $image_url;
            } else {
                $errors['business_logo'] = 'Failed to save cropped image.';
            }
        } else {
            if (!$profile_edit_mode) {
                $errors['business_logo'] = "Business logo is required.";
            }
        }
    
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            
            $redirect_url = add_query_arg([
                'form_error' => 1,
                'next_step' => 4
            ], wp_get_referer());
            
            wp_safe_redirect($redirect_url);
            exit;
        }
    
        global $wpdb;
        $partner_id = intval($_SESSION['partner_id']);
        if (!$partner_id) {
            wp_die('Invalid Partner ID.');
        }
    
        $data = ['website_url' => $website_url];
        if (!empty($business_logo_url)) {
            $data['business_logo'] = $business_logo_url;
        }
    
        $result = $wpdb->update(
            $this->service_partners_table,
            $data,
            ['id' => $partner_id]
        );
    
        if ($result === false) {
            wp_die('Database update failed: ' . $wpdb->last_error);
        }
    
        $redirect_url = add_query_arg([
            'success' => 1,
            'next_step' => 5,
            'profile_edit_mode' => 1,
        ], wp_get_referer());
        
        wp_safe_redirect($redirect_url);
        exit;
    }

    public function handle_pr_partner_change_password() {
        // Verify nonce for security
        if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
            wp_die('Security check failed.');
        }
    
        $new_password = sanitize_text_field($_POST['new_password']);
        $confirm_password = sanitize_text_field($_POST['confirm_password']);

        $errors = [];
    
        if (empty($new_password)) {
            $errors['new_password'] = "Password is required";
        } else if (strlen($new_password) < 8) {
            $errors['new_password'] = "Password must alteast of 8 characters";
        }

        if (empty($confirm_password)) {
            $errors['confirm_password'] = "Confirm Password is required";
        }

        if ( !empty($confirm_password) && $new_password != $confirm_password) {
            $errors['new_password'] = "Password and confirm password must match";
        }
    
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST;
            
            $redirect_url = wp_get_referer();
            
            wp_safe_redirect($redirect_url);
            exit;
        }

        if ($partner_id = $this->loggedIn()) {
            $this->wpdb->update($this->service_partners_table, [
                'password' => wp_hash_password($new_password)
            ], [
                'id' => $partner_id
            ]);
        }

        $_SESSION['profile_updated'] = [
            'message' => 'Password changed successfully'
        ];
        
        $redirect_url = wp_get_referer();
        wp_safe_redirect($redirect_url);
        exit;
    }


    public function render_forgot_password_form($atts)
    {
        ob_start();

        // Define path to the view file
        $view_path = plugin_dir_path(__FILE__) . '../views/partner-register/forgot-password.php';

        // Ensure file exists before including
        if (file_exists($view_path)) {
            include $view_path;
        } else {
            echo "<p>Error: View file not found!</p>";
        }

        return ob_get_clean();
        
    }


    public function handle_pr_handle_forgot_password() {
        session_start();

        global $wpdb;

        // Security Check
        if (!isset($_POST['pr_partner_nonce']) || !wp_verify_nonce($_POST['pr_partner_nonce'], 'pr_partner_form_action')) {
            wp_die('Security check failed.');
        }

        // Get and sanitize email
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        $errors = [];
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        } elseif (!is_email($email)) {
            $errors['email'] = 'Invalid email format.';
        } else {
            // Check email in wp_service_partners table
            $table_name = $wpdb->prefix . 'service_partners';
            $user = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE email = %s", $email));
    
            if (!$user) {
                $errors['email'] = 'Email not registered.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['forgot_password_errors'] = $errors;
            $_SESSION['forgot_password_old'] = $_POST;
            wp_redirect(wp_get_referer()); // Redirect back to the form
            exit;
        }

        $reset_key = wp_generate_password(32, false); // You can store this in the DB for verification
        $reset_expires = date('Y-m-d H:i:s', strtotime('+1 hour')); 
        // Save reset key in the service partners table
        $wpdb->update(
            $table_name,
            [
                'reset_token' => $reset_key,
                'reset_expires' => $reset_expires // Ensure expiration time is saved
            ],
            ['email' => $email],
            ['%s'],
            ['%s']
        );
    
        // Generate Reset Password Link
        $reset_link = add_query_arg([
            'key' => $reset_key,
            'email' => rawurlencode($email),
        ], site_url('/partner-reset-password')); // Change to your reset password page

        // Send Email
        $subject = 'Password Reset Request';

        $message = "
            <html>
            <head>
                <style>
                    .reset-button {
                        background-color: #007bff;
                        color: #ffffff;
                        padding: 10px 20px;
                        text-decoration: none;
                        font-size: 16px;
                        display: inline-block;
                        border-radius: 5px;
                    }
                </style>
            </head>
            <body>
                <p>Hello,</p>
                <p>You requested a password reset. Click the button below to reset your password:</p>
                <p><a href='$reset_link' class='reset-button'>Reset Password</a></p>
                <p>If you did not request this, please ignore this email.</p>
            </body>
            </html>
        ";


        // $message = "Hello,\n\nYou requested a password reset. Click the link below to reset your password:\n\n";
        // $message .= $reset_link . "\n\nIf you did not request this, please ignore this email.";

        // wp_mail($email, $subject, $message);
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8'
        ];
        
        // Send the email
        wp_mail($email, $subject, $message, $headers);

        // Redirect with success message
        $_SESSION['forgot_password_success'] = 'A reset link has been sent to your email.';
        wp_redirect(wp_get_referer());
        exit;
    }

    function render_reset_password_form($title = 'Reset Password') {

        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_GET['key']) || !isset($_GET['email'])) {
            return '<p class="alert alert-danger text-center">Invalid password reset link.</p>';
        }

        $checkValidToken = $this->validateToken($_GET['key'], $_GET['email']);

        if ($checkValidToken['status'] == false) {
            $flash_message = [
                'alert_type' => 'danger',
                'message' => $checkValidToken['message']
            ];
            include plugin_dir_path(__FILE__) . '../views/frontend/flash-message.php';
            return;
        }
    
        $errors = isset($_SESSION['forgot_password_errors']) ? $_SESSION['forgot_password_errors'] : [];
        
        ob_start(); ?>
    
        <form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('reset_password_action', 'reset_password_nonce'); ?>
            <input type="hidden" name="action" value="pr_partner_reset_password">
            <input type="hidden" name="email" value="<?php echo esc_attr($_GET['email']); ?>">
            <input type="hidden" name="token" value="<?php echo esc_attr($_GET['key']); ?>">
    
            <div class="wpcf7-form">
                <div class="step step-1">
                    <div class="step-header">
                        <h5 class="text-center"><?php echo esc_html($title); ?></h5>
                    </div>
                    <div class="form-body">
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control h-50px" id="new_password" name="new_password" placeholder="Enter new password" required>
                            <span class="error"><?php echo esc_html($errors['new_password'] ?? ''); ?></span>
                        </div>
    
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control h-50px" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                            <span class="error"><?php echo esc_html($errors['confirm_password'] ?? ''); ?></span>
                        </div>
    
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary"><?php echo esc_html($title); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    
        <?php return ob_get_clean();
    }

    function handle_pr_partner_reset_password() {

        if (!isset($_POST['reset_password_nonce']) || !wp_verify_nonce($_POST['reset_password_nonce'], 'reset_password_action')) {
            wp_die('Security check failed.');
        }
    
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Sanitize input values
        $email = sanitize_email($_POST['email'] ?? '');
        $token = sanitize_text_field($_POST['token'] ?? '');
        $new_password = sanitize_text_field($_POST['new_password'] ?? '');
        $confirm_password = sanitize_text_field($_POST['confirm_password'] ?? '');
    
        $errors = [];
    
        // Validate password length
        if (strlen($new_password) < 8) {
            $errors['new_password'] = "Password must be at least 8 characters.";
        }
    
        // Confirm passwords match
        if ($new_password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        }
    
        // If there are errors, store them in session and redirect back to reset form
        if (!empty($errors)) {
            $_SESSION['forgot_password_errors'] = $errors;
    
            // Redirect to reset password form with query params
            $reset_url = add_query_arg([
                'key'   => $token,
                'email' => rawurlencode($email)
            ], site_url('/partner-reset-password'));
            
            
            wp_safe_redirect($reset_url);
            exit;
        }
    
        // Database check for partner
        global $wpdb;
        $table_name = $wpdb->prefix . 'service_partners';
        $partner = $wpdb->get_row($wpdb->prepare(
            "SELECT id, name, reset_token, reset_expires FROM {$table_name} WHERE email = %s", 
            $email
        ));

    
        // Validate token and expiry
        if (!$partner || $partner->reset_token !== $token || empty($partner->reset_expires) || time() > strtotime($partner->reset_expires)) {
            wp_die("Invalid or expired reset token.");
        }

        // Update password and reset token
        $wpdb->update(
            $table_name,
            [
                'password' => wp_hash_password($new_password),
                'reset_token' => null,
                'reset_expires' => null,
                'status' => 1
            ],
            ['id' => $partner->id]
        );
    
        // Store success message in session
        $_SESSION['flash_message_success'] = "Password reset successful.";

        $this->autoPartnerLogin($partner);
    
        // Redirect to login page
        wp_safe_redirect(home_url('partner-customer-requests'));
        exit;
    }

    protected function autoPartnerLogin($partner) {
        $_SESSION['partner_logged_in'] = true;
        $_SESSION['partner_id'] = $partner->id;
        $_SESSION['partner_name'] = $partner->name;
    }
    
   
    protected function loggedIn() {
        if (isset($_SESSION['partner_id'])) {
            return $_SESSION['partner_id'];
        } else {
            return false;
        }
    }

    public function render_prospect_reset_password_form() {
        
        $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
        $email = isset($_GET['email']) ? $_GET['email'] : '';

        // Validate token
        $prospect = $this->wpdb->get_row($this->wpdb->prepare("SELECT id, reset_token, reset_expires FROM $this->service_partners_table WHERE email = %s", $email));

        if (!$prospect || $prospect->reset_token !== $token || strtotime($prospect->reset_expires) < time()) {
            echo "<p style='color: red;'>Invalid or expired reset link.</p>";
            return ob_get_clean();
        }

        // Reset Form
        ob_start();
        ?>
        <h2>Reset Your Password</h2>
        <form method="post">
            <label>New Password:</label>
            <input type="password" name="new_password" required>
            <button type="submit" name="submit_new_password">Reset Password</button>
        </form>
        <?php

        // Handle Password Reset
        if (isset($_POST['submit_new_password'])) {
            $new_password = sanitize_text_field($_POST['new_password']);

            if (strlen($new_password) < 6) {
                echo "<p style='color: red;'>Password must be at least 6 characters.</p>";
            } else {
                $hashed_password = wp_hash_password($new_password); // Corrected function

                // Update password & remove token
                $this->wpdb->update(
                    $this->service_partners_table,
                    ['password' => $hashed_password, 'reset_token' => NULL, 'reset_expires' => NULL],
                    ['id' => $prospect->id]
                );

                echo "<p style='color: green;'>Password reset successfully! <a href='" . wp_login_url() . "'>Login here</a></p>";
            }
        }

        return ob_get_clean();
    }

    public function validateToken($token, $email) {
        $prospect = $this->wpdb->get_row($this->wpdb->prepare("SELECT id, reset_token, reset_expires FROM $this->service_partners_table WHERE email = %s and reset_token = %s ", $email, $token));

        if (!$prospect || strtotime($prospect->reset_expires) < time()) {
            return [
                'status' => false,
                'message' => 'Unauthorized access'
            ];
        } else {
            return [
                'status' => true
            ];
        }
    }

}
?>