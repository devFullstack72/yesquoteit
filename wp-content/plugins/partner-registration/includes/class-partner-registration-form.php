<?php
if (!defined('ABSPATH')) {
    exit;
}

class Partner_Registration_Form
{

    public $service_partners_table;
    public $lead_partners_table;

    public function __construct()
    {
        add_shortcode('partner_registration_form', [$this, 'render_registration_form']);
        // add_action('admin_post_nopriv_pr_partner_form_submission', [$this, 'handle_form_submission']);
        // add_action('admin_post_pr_partner_form_submission', [$this, 'handle_form_submission']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_1', [$this, 'handle_pr_partner_form_submission_step_1']);
        add_action('admin_post_pr_partner_form_submission_step_1', [$this, 'handle_pr_partner_form_submission_step_1']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_2', [$this, 'handle_pr_partner_form_submission_step_2']);
        add_action('admin_post_pr_partner_form_submission_step_2', [$this, 'handle_pr_partner_form_submission_step_2']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_3', [$this, 'handle_pr_partner_form_submission_step_3']);
        add_action('admin_post_pr_partner_form_submission_step_3', [$this, 'handle_pr_partner_form_submission_step_3']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_4', [$this, 'handle_pr_partner_form_submission_step_4']);
        add_action('admin_post_pr_partner_form_submission_step_4', [$this, 'handle_pr_partner_form_submission_step_4']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        if (!session_id()) {
            session_start();
        }

        global $wpdb;

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
            wp_enqueue_script('partner-registration-script', plugin_dir_url(__FILE__) . 'js/partner-registration.js', ['jquery', 'google-places'], null, true);
            
            wp_enqueue_style('partner-registration-css', plugin_dir_url(__FILE__) . 'css/partner-registration.css');
        // }
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
        $errors = [];

        if (empty($address)) {
            $errors['address'] = "Address is required.";
        }

        if (empty($latitude)) {
            $errors['address'] = "Please choose address from google";
        }

        // if (empty($street_number)) {
        //     $errors['street_number'] = "Please enter street number";
        // }

        // if (empty($route)) {
        //     $errors['address_line_1'] = "Please enter address line";
        // }

        // if (empty($address2)) {
        //     $errors['address_line_2'] = "Please enter address line";
        // }

        // if (empty($postal_code)) {
        //     $errors['postal_code'] = "Please enter postal code";
        // }

        // if (empty($state)) {
        //     $errors['state'] = "Please enter state";
        // }

        // if (empty($country)) {
        //     $errors['country'] = "Please enter country";
        // }

        if (empty($service_area)) {
            $errors['service_area'] = "Please choose service area";
        }

        // If there are errors, redirect back with errors
        if (!empty($errors)) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_data'] = $_POST; // Store old values
            // Redirect with both parameters (next-step and partner_id)
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



        $wpdb->update($this->service_partners_table, [
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
        ], [ 'id' => $_SESSION['partner_id'] ]);

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
            'next_step' => 5
        ], wp_get_referer());
        
        wp_safe_redirect($redirect_url);
        exit;
    }
}
?>