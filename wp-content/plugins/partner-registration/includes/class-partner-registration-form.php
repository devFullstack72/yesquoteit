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
                    <option value="United States"  >United States</option>
                    <option value="Afghanistan"  >Afghanistan</option>
                    <option value="Albania"  >Albania</option>
                    <option value="Algeria"  >Algeria</option>
                    <option value="American Samoa"  >American Samoa</option>
                    <option value="Andorra"  >Andorra</option>
                    <option value="Angola"  >Angola</option>
                    <option value="Anguilla"  >Anguilla</option>
                    <option value="Antigua and Barbuda"  >Antigua and Barbuda</option>
                    <option value="Argentina"  >Argentina</option>
                    <option value="Armenia"  >Armenia</option>
                    <option value="Aruba"  >Aruba</option>
                    <option value="Australia"  >Australia</option>
                    <option value="Austria"  >Austria</option>
                    <option value="Azerbaijan"  >Azerbaijan</option>
                    <option value="Bahamas"  >Bahamas</option>
                    <option value="Bahrain"  >Bahrain</option>
                    <option value="Bangladesh"  >Bangladesh</option>
                    <option value="Barbados"  >Barbados</option>
                    <option value="Belarus"  >Belarus</option>
                    <option value="Belgium"  >Belgium</option>
                    <option value="Belize"  >Belize</option>
                    <option value="Benin"  >Benin</option>
                    <option value="Bermuda"  >Bermuda</option>
                    <option value="Bhutan"  >Bhutan</option>
                    <option value="Bolivia"  >Bolivia</option>
                    <option value="Bosnia and Herzegovina"  >Bosnia and Herzegovina</option>
                    <option value="Botswana"  >Botswana</option>
                    <option value="Bouvet Island"  >Bouvet Island</option>
                    <option value="Brazil"  >Brazil</option>
                    <option value="British Indian Ocean Territory"  >British Indian Ocean Territory</option>
                    <option value="British Virgin Islands"  >British Virgin Islands</option>
                    <option value="Brunei"  >Brunei</option>
                    <option value="Bulgaria"  >Bulgaria</option>
                    <option value="Burkina Faso"  >Burkina Faso</option>
                    <option value="Burundi"  >Burundi</option>
                    <option value="Cambodia"  >Cambodia</option>
                    <option value="Cameroon"  >Cameroon</option>
                    <option value="Canada"  >Canada</option>
                    <option value="Cape Verde"  >Cape Verde</option>
                    <option value="Cayman Islands"  >Cayman Islands</option>
                    <option value="Central African Republic"  >Central African Republic</option>
                    <option value="Chad"  >Chad</option>
                    <option value="Chile"  >Chile</option>
                    <option value="China"  >China</option>
                    <option value="Christmas Island"  >Christmas Island</option>
                    <option value="Cocos (Keeling) Islands"  >Cocos (Keeling) Islands</option>
                    <option value="Colombia"  >Colombia</option>
                    <option value="Comoros"  >Comoros</option>
                    <option value="Congo"  >Congo</option>
                    <option value="Congo - Democratic Republic of"  >Congo - Democratic Republic of</option>
                    <option value="Cook Islands"  >Cook Islands</option>
                    <option value="Costa Rica"  >Costa Rica</option>
                    <option value="Croatia"  >Croatia</option>
                    <option value="Cuba"  >Cuba</option>
                    <option value="Cyprus"  >Cyprus</option>
                    <option value="Czech Republic"  >Czech Republic</option>
                    <option value="Denmark"  >Denmark</option>
                    <option value="Djibouti"  >Djibouti</option>
                    <option value="Dominica"  >Dominica</option>
                    <option value="Dominican Republic"  >Dominican Republic</option>
                    <option value="East Timor"  >East Timor</option>
                    <option value="Ecuador"  >Ecuador</option>
                    <option value="Egypt"  >Egypt</option>
                    <option value="El Salvador"  >El Salvador</option>
                    <option value="Equitorial Guinea"  >Equitorial Guinea</option>
                    <option value="Eritrea"  >Eritrea</option>
                    <option value="Estonia"  >Estonia</option>
                    <option value="Ethiopia"  >Ethiopia</option>
                    <option value="Falkland Islands (Islas Malvinas)"  >Falkland Islands (Islas Malvinas)</option>
                    <option value="Faroe Islands"  >Faroe Islands</option>
                    <option value="Fiji"  >Fiji</option>
                    <option value="Finland"  >Finland</option>
                    <option value="France"  >France</option>
                    <option value="French Guyana"  >French Guyana</option>
                    <option value="French Polynesia"  >French Polynesia</option>
                    <option value="French Southern and Antarctic Lands"  >French Southern and Antarctic Lands</option>
                    <option value="Gabon"  >Gabon</option>
                    <option value="Gambia"  >Gambia</option>
                    <option value="Gaza Strip"  >Gaza Strip</option>
                    <option value="Georgia"  >Georgia</option>
                    <option value="Germany"  >Germany</option>
                    <option value="Ghana"  >Ghana</option>
                    <option value="Gibraltar"  >Gibraltar</option>
                    <option value="Greece"  >Greece</option>
                    <option value="Greenland"  >Greenland</option>
                    <option value="Grenada"  >Grenada</option>
                    <option value="Guadeloupe"  >Guadeloupe</option>
                    <option value="Guam"  >Guam</option>
                    <option value="Guatemala"  >Guatemala</option>
                    <option value="Guinea"  >Guinea</option>
                    <option value="Guinea-Bissau"  >Guinea-Bissau</option>
                    <option value="Guyana"  >Guyana</option>
                    <option value="Haiti"  >Haiti</option>
                    <option value="Heard Island and McDonald Islands"  >Heard Island and McDonald Islands</option>
                    <option value="Holy See (Vatican City)"  >Holy See (Vatican City)</option>
                    <option value="Honduras"  >Honduras</option>
                    <option value="Hong Kong"  >Hong Kong</option>
                    <option value="Hungary"  >Hungary</option>
                    <option value="Iceland"  >Iceland</option>
                    <option value="India"  >India</option>
                    <option value="Indonesia"  >Indonesia</option>
                    <option value="Iran"  >Iran</option>
                    <option value="Iraq"  >Iraq</option>
                    <option value="Ireland"  >Ireland</option>
                    <option value="Israel"  >Israel</option>
                    <option value="Italy"  >Italy</option>
                    <option value="Jamaica"  >Jamaica</option>
                    <option value="Japan"  >Japan</option>
                    <option value="Jordan"  >Jordan</option>
                    <option value="Kazakhstan"  >Kazakhstan</option>
                    <option value="Kenya"  >Kenya</option>
                    <option value="Kiribati"  >Kiribati</option>
                    <option value="Kuwait"  >Kuwait</option>
                    <option value="Kyrgyzstan"  >Kyrgyzstan</option>
                    <option value="Laos"  >Laos</option>
                    <option value="Latvia"  >Latvia</option>
                    <option value="Lebanon"  >Lebanon</option>
                    <option value="Lesotho"  >Lesotho</option>
                    <option value="Liberia"  >Liberia</option>
                    <option value="Libya"  >Libya</option>
                    <option value="Liechtenstein"  >Liechtenstein</option>
                    <option value="Lithuania"  >Lithuania</option>
                    <option value="Luxembourg"  >Luxembourg</option>
                    <option value="Macau"  >Macau</option>
                    <option value="Macedonia - The Former Yugoslav Republic of"  >Macedonia - The Former Yugoslav Republic of</option>
                    <option value="Madagascar"  >Madagascar</option>
                    <option value="Malawi"  >Malawi</option>
                    <option value="Malaysia"  >Malaysia</option>
                    <option value="Maldives"  >Maldives</option>
                    <option value="Mali"  >Mali</option>
                    <option value="Malta"  >Malta</option>
                    <option value="Marshall Islands"  >Marshall Islands</option>
                    <option value="Martinique"  >Martinique</option>
                    <option value="Mauritania"  >Mauritania</option>
                    <option value="Mauritius"  >Mauritius</option>
                    <option value="Mayotte"  >Mayotte</option>
                    <option value="Mexico"  >Mexico</option>
                    <option value="Micronesia - Federated States of"  >Micronesia - Federated States of</option>
                    <option value="Moldova"  >Moldova</option>
                    <option value="Monaco"  >Monaco</option>
                    <option value="Mongolia"  >Mongolia</option>
                    <option value="Montserrat"  >Montserrat</option>
                    <option value="Morocco"  >Morocco</option>
                    <option value="Mozambique"  >Mozambique</option>
                    <option value="Myanmar"  >Myanmar</option>
                    <option value="Namibia"  >Namibia</option>
                    <option value="Naura"  >Naura</option>
                    <option value="Nepal"  >Nepal</option>
                    <option value="Netherlands"  >Netherlands</option>
                    <option value="Netherlands Antilles"  >Netherlands Antilles</option>
                    <option value="New Caledonia"  >New Caledonia</option>
                    <option value="New Zealand"  >New Zealand</option>
                    <option value="Nicaragua"  >Nicaragua</option>
                    <option value="Niger"  >Niger</option>
                    <option value="Nigeria"  >Nigeria</option>
                    <option value="Niue"  >Niue</option>
                    <option value="Norfolk Island"  >Norfolk Island</option>
                    <option value="North Korea"  >North Korea</option>
                    <option value="Northern Mariana Islands"  >Northern Mariana Islands</option>
                    <option value="Norway"  >Norway</option>
                    <option value="Oman"  >Oman</option>
                    <option value="Pakistan"  >Pakistan</option>
                    <option value="Palau"  >Palau</option>
                    <option value="Panama"  >Panama</option>
                    <option value="Papua New Guinea"  >Papua New Guinea</option>
                    <option value="Paraguay"  >Paraguay</option>
                    <option value="Peru"  >Peru</option>
                    <option value="Philippines"  >Philippines</option>
                    <option value="Pitcairn Islands"  >Pitcairn Islands</option>
                    <option value="Poland"  >Poland</option>
                    <option value="Portugal"  >Portugal</option>
                    <option value="Puerto Rico"  >Puerto Rico</option>
                    <option value="Qatar"  >Qatar</option>
                    <option value="Reunion"  >Reunion</option>
                    <option value="Romania"  >Romania</option>
                    <option value="Russia"  >Russia</option>
                    <option value="wanda"  >wanda</option>
                    <option value="Saint Kitts and Nevis"  >Saint Kitts and Nevis</option>
                    <option value="Saint Lucia"  >Saint Lucia</option>
                    <option value="Saint Vincent and the Grenadines"  >Saint Vincent and the Grenadines</option>
                    <option value="Samoa"  >Samoa</option>
                    <option value="San Marino"  >San Marino</option>
                    <option value="Sao Tome and Principe"  >Sao Tome and Principe</option>
                    <option value="Saudi Arabia"  >Saudi Arabia</option>
                    <option value="Senegal"  >Senegal</option>
                    <option value="Serbia and Montenegro"  >Serbia and Montenegro</option>
                    <option value="Seychelles"  >Seychelles</option>
                    <option value="Sierra Leone"  >Sierra Leone</option>
                    <option value="Singapore"  >Singapore</option>
                    <option value="Slovakia"  >Slovakia</option>
                    <option value="Slovenia"  >Slovenia</option>
                    <option value="Solomon Islands"  >Solomon Islands</option>
                    <option value="Somalia"  >Somalia</option>
                    <option value="South Africa"  >South Africa</option>
                    <option value="South Georgia and the South Sandwich Islands"  >South Georgia and the South Sandwich Islands</option>
                    <option value="South Korea"  >South Korea</option>
                    <option value="Spain"  >Spain</option>
                    <option value="Sri Lanka"  >Sri Lanka</option>
                    <option value="St. Helena"  >St. Helena</option>
                    <option value="St. Pierre and Miquelon"  >St. Pierre and Miquelon</option>
                    <option value="Sudan"  >Sudan</option>
                    <option value="Suriname"  >Suriname</option>
                    <option value="Svalbard"  >Svalbard</option>
                    <option value="Swaziland"  >Swaziland</option>
                    <option value="Sweden"  >Sweden</option>
                    <option value="Switzerland"  >Switzerland</option>
                    <option value="Syria"  >Syria</option>
                    <option value="Taiwan"  >Taiwan</option>
                    <option value="Tajikistan"  >Tajikistan</option>
                    <option value="Tanzania"  >Tanzania</option>
                    <option value="Thailand"  >Thailand</option>
                    <option value="Togo"  >Togo</option>
                    <option value="Tokelau"  >Tokelau</option>
                    <option value="Tonga"  >Tonga</option>
                    <option value="Trinidad and Tobago"  >Trinidad and Tobago</option>
                    <option value="Tunisia"  >Tunisia</option>
                    <option value="Turkey"  >Turkey</option>
                    <option value="Turkmenistan"  >Turkmenistan</option>
                    <option value="Turks and Caicos Islands"  >Turks and Caicos Islands</option>
                    <option value="Tuvalu"  >Tuvalu</option>
                    <option value="Uganda"  >Uganda</option>
                    <option value="Ukraine"  >Ukraine</option>
                    <option value="United Arab Emirates"  >United Arab Emirates</option>
                    <option value="United Kingdom"  >United Kingdom</option>
                    <option value="United States Virgin Islands"  >United States Virgin Islands</option>
                    <option value="Uruguay"  >Uruguay</option>
                    <option value="Uzbekistan"  >Uzbekistan</option>
                    <option value="Vanuatu"  >Vanuatu</option>
                    <option value="Venezuela"  >Venezuela</option>
                    <option value="Vietnam"  >Vietnam</option>
                    <option value="Wallis and Futuna"  >Wallis and Futuna</option>
                    <option value="West Bank"  >West Bank</option>
                    <option value="Western Sahara"  >Western Sahara</option>
                    <option value="Yemen"  >Yemen</option>
                    <option value="Zambia"  >Zambia</option>
                    <option value="Zimbabwe"  >Zimbabwe</option>
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