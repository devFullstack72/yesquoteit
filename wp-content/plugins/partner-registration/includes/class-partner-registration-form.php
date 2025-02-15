<?php
if (!defined('ABSPATH')) {
    exit;
}

class Partner_Registration_Form
{

    public function __construct()
    {
        add_shortcode('partner_registration_form', [$this, 'render_registration_form']);
        // add_action('admin_post_nopriv_pr_partner_form_submission', [$this, 'handle_form_submission']);
        // add_action('admin_post_pr_partner_form_submission', [$this, 'handle_form_submission']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_1', [$this, 'handle_pr_partner_form_submission_step_1']);
        add_action('admin_post_pr_partner_form_submission_step_1', [$this, 'handle_pr_partner_form_submission_step_1']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_2', [$this, 'handle_pr_partner_form_submission_step_2']);
        add_action('admin_post_pr_partner_form_submission_step_2', [$this, 'handle_pr_partner_form_submission_step_2']);

        add_action('admin_post_nopriv_pr_partner_form_submission_step_2', [$this, 'handle_pr_partner_form_submission_step_2']);
        add_action('admin_post_pr_partner_form_submission_step_2', [$this, 'handle_pr_partner_form_submission_step_2']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        if (!session_id()) {
            session_start();
        }
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


    public function render_registration_form()
    {
        global $wpdb;
    
        // Get the lead ID from URL
        $selected_lead_id = isset($_GET['lead_id']) ? intval($_GET['lead_id']) : null;
    
        // Fetch available leads (modify query as needed)
        $leads = $wpdb->get_results("
            SELECT ID, post_title 
            FROM {$wpdb->posts} 
            WHERE post_type = 'lead_generation' 
            AND post_status = 'publish'
        ");

        $countries_table = $wpdb->prefix . 'countries';
        
        $countries = $wpdb->get_results("
            SELECT * 
            FROM {$countries_table}
        ");

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

            $wpdb->insert($service_partners_table, [
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
                $wpdb->insert($lead_partners_table, [
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
        $confirm_email = sanitize_email($_POST['c_email']);
        $password = sanitize_text_field($_POST['password']);
        $phone = sanitize_text_field($_POST['phone']);

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
        if (empty($confirm_email)) {
            $errors['confirm_email'] = "Confirm Email is required.";
        } elseif ($email !== $confirm_email) {
            $errors['confirm_email'] = "Email and Confirm Email do not match.";
        }
        if (empty($password)) {
            $errors['password'] = "Password is required.";
        } elseif (strlen($password) < 8) {
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

        $service_partners_table = $wpdb->prefix . 'service_partners';

        $wpdb->insert($service_partners_table, [
            'name' => $name,
            'business_trading_name' => $business_trading_name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'status' => 0
        ]);

        $partner_id = $wpdb->insert_id;
        
        // After successful form submission, clear session data
        unset($_SESSION['form_errors']);
        unset($_SESSION['form_data']);

        $_SESSION['partner_id'] = $partner_id;

        // Redirect with both parameters (next-step and partner_id)
        $redirect_url = add_query_arg([
            'next_step' => '2'
        ], wp_get_referer());

        wp_safe_redirect($redirect_url);
        exit;

    }
}
?>