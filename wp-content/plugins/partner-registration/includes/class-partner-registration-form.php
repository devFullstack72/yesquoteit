<?php
if (!defined('ABSPATH')) {
    exit;
}

class Partner_Registration_Form
{

    public function __construct()
    {
        add_shortcode('partner_registration_form', [$this, 'render_registration_form']);
        add_action('admin_post_nopriv_pr_partner_form_submission', [$this, 'handle_form_submission']);
        add_action('admin_post_pr_partner_form_submission', [$this, 'handle_form_submission']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function enqueue_scripts()
    {
        // Ensure jQuery is included
        wp_enqueue_script('jquery');

        // Enqueue Google Maps API
        wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDuoh4RV3jwuAD72LBq02e3rx4-iZa-wLc&libraries=places', [], null, true);

        // Custom script for autocomplete
        wp_enqueue_script('partner-registration-script', plugin_dir_url(__FILE__) . 'js/partner-registration.js', ['jquery', 'google-maps-api'], null, true);
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

        ob_start();

?>
        <h2 class="htlfndr-section-title bigger-title">Become Partner</h2><div class="htlfndr-section-under-title-line"></div>
         <div class="wpcf7 js" style="margin-bottom:100px; padding: 20px;">
            <?php
            // Check for success message
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo '<div class="notice notice-success" style="padding: 10px; border: 1px solid #46b450; background-color: #dff0d8; color: #3c763d; margin-bottom: 15px;">
                        Thank you for registering! We will review it shortly.
                    </div>';
            }
            ?>
        <form id="partner-registration-form" method="POST" class="wpcf7-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"  style="min-width:100%">
            <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
            <input type="hidden" name="action" value="pr_partner_form_submission">

            <input type="hidden" name="lead_id" value="<?php echo !empty($_GET['lead_id']) ? $_GET['lead_id'] : '' ?>">

            <p><label>Name<br>
            <span class="wpcf7-form-control-wrap" data-name="name"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="name" aria-required="true" aria-invalid="false" value="" type="text" name="name"></span> </label>
            </p>
            <p><label>Email<br>
            <span class="wpcf7-form-control-wrap" data-name="email"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="email" aria-required="true" aria-invalid="false" value="" type="email" id="email" name="email"></span> </label>
            </p>

            <p><label>Phone<br>
            <span class="wpcf7-form-control-wrap" data-name="phone"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="phone" aria-required="true" aria-invalid="false" value="" type="text" name="phone"></span> </label>
            </p>

            <p><label>Select Leads:</label></p>
            <div style="align-items: center;margin-bottom:10px;">
                <?php foreach ($leads as $lead): ?>
                    <label style="display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" name="lead_ids[]" value="<?php echo esc_attr($lead->ID); ?>" 
                        <?php echo (!empty($selected_lead_id) && $selected_lead_id == $lead->ID) ? 'checked' : ''; ?>>
                        <?php echo esc_html($lead->post_title); ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <!-- <p><label>Address<br>
            <span class="wpcf7-form-control-wrap" data-name="address"><textarea size="40" maxlength="400" rows="3" cols="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" autocomplete="address" required aria-required="true" aria-invalid="false" value="" type="text" name="address"></textarea></span> </label>
            </p> -->
            <br>       
            <p><label>Address<br>
            <span class="wpcf7-form-control-wrap" data-name="address">
                <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required id="autocomplete" aria-required="true" aria-invalid="false" type="text" name="address">
            </span> </label>
            </p>

             <!-- Display Map Image -->
             <div id="map-preview" style="margin-top: 10px;">
                <img id="map-image" src="" style="display: none; width: 100%; border-radius: 5px;">
            </div>
 
            <br>
            <p>
                <label>Latitude<br>
                    <span class="wpcf7-form-control-wrap" data-name="latitude">
                        <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="latitude" name="latitude">
                    </span>
                </label>
            </p>

            <p>
                <label>Longitude<br>
                    <span class="wpcf7-form-control-wrap" data-name="longitude">
                        <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="longitude" name="longitude">
                    </span>
                </label>
            </p>

            <p>
                <label>Street Number<br>
                    <span class="wpcf7-form-control-wrap" data-name="street_number">
                        <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="street_number" name="street_number">
                    </span>
                </label>
            </p>

            <p>
                <label>Address 1<br>
                    <span class="wpcf7-form-control-wrap" data-name="route">
                        <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="route" name="route">
                    </span>
                </label>
            </p>

            <p>
                <label>Address 2<br>
                    <span class="wpcf7-form-control-wrap" data-name="address2">
                        <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="address2" name="address2">
                    </span>
                </label>
            </p>

            <p>
                <label>Postal Code<br>
                    <span class="wpcf7-form-control-wrap" data-name="postal_code">
                        <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="postal_code" name="postal_code">
                    </span>
                </label>
            </p>

            <p>
                <label>State<br>
                    <span class="wpcf7-form-control-wrap" data-name="state">
                        <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="state" name="state">
                    </span>
                </label>
            </p>

            <p>
                <label>Country<br>
                    <span class="wpcf7-form-control-wrap" data-name="country">
                        <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="country" name="country">
                    </span>
                </label>
            </p>

            <p>
                <label>Service Area<br>
                <select class="cls_slect-radius" onchange="on_country()" name="service_area" id="radius">
                    <option value="5"> 5 KM </option>
                    <option value="10"> 10 KM </option>
                    <option value="25"> 25 KM </option>
                    <option value="50"> 50 KM </option>
                    <option value="100"> 100 KM </option>
                    <option value="250"> 250 KM </option>								
                    <option value="500"> 500 KM </option>
                    <option value="entire"> Entire Country </option>
                    <option value="state"> Entire State </option>
                    <option value="other"> Other Country </option>
                    <option value="every"> Every Where </option>
                    <option value="no_service">Not at this location </option>
                </select>
                </label>
            </p>

            <p id="show_country" style="display:none;">
                <label>Service provided in other Country<br>
                    <select class="cls_slect-radius" name="other_country" id="other_country">
                        <?php foreach($countries as $country){ ?>
                            <option value="<?php echo $country->code ?>"><?php echo $country->name ?></option>
                        <?php } ?>
                    </select>
                </label>
            </p>

            <p><input class="wpcf7-form-control wpcf7-submit has-spinner" type="submit" name="partner_submit" value="Register"></p>
        </form>
    </div>
<?php
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
}
?>