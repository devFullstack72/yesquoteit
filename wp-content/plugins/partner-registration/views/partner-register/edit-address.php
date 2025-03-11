<?php

global $wpdb;
$countries_table = $wpdb->prefix . 'countries';
$partner_addresses_table = $wpdb->prefix . 'partner_addresses'; // Assuming you store multiple addresses in a separate table.

$countries = $wpdb->get_results("SELECT * FROM {$countries_table}");
// Fetch multiple addresses for this partner
$partner_addresses = $wpdb->get_results("SELECT * FROM {$partner_addresses_table} WHERE partner_id = {$partner->id}");

?>
<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="partner_id" value="<?php echo esc_attr($partner->id); ?>">
        <input type="hidden" name="action" value="pr_partner_form_submission_multiple_address">
        <input type="hidden" name="profile_edit_mode" value="<?php echo $edit_profile_page ?>">


        <div class="wpcf7-form">
            <div class="step">
                <div class="step-header">
                    <h5 class="text-center">Update Address</h5>
                </div>
                <div class="form-body" id="address-container">

                <table class="form-table">

                    <!-- Address Section -->
                    <tbody id="address-list">
                        <?php if (!empty($partner_addresses)): ?>
                            <?php foreach ($partner_addresses as $index => $address): ?>
                                <tr class="address-container">
                                    <th><label>Address</label></th>
                                    <td class="address-group">
                                        <input type="text" name="addresses[]" class="regular-text address-field" value="<?php echo esc_attr($address->address); ?>" placeholder="Enter address">
                                        <input type="text" name="latitude[]" class="latitude" value="<?php echo esc_attr($address->latitude); ?>" placeholder="Latitude" readonly>
                                        <input type="text" name="longitude[]" class="longitude" value="<?php echo esc_attr($address->longitude); ?>" placeholder="Longitude" readonly>
                                        <input type="text" name="street_number[]" class="street_number" value="<?php echo esc_attr($address->street_number); ?>" placeholder="Street Number" readonly>
                                        <input type="text" name="route[]" class="route" value="<?php echo esc_attr($address->route); ?>" placeholder="Route" readonly>
                                        <input type="text" name="address2[]" class="address2" value="<?php echo esc_attr($address->address2); ?>" placeholder="Address 2" readonly>
                                        <input type="text" name="postal_code[]" class="postal_code" value="<?php echo esc_attr($address->postal_code); ?>" placeholder="Postal Code" readonly>
                                        <input type="text" name="state[]" class="state" value="<?php echo esc_attr($address->state); ?>" placeholder="State" readonly>
                                        <input type="text" name="country[]" class="country" value="<?php echo esc_attr($address->country); ?>" placeholder="Country" readonly>

                                        <!-- Service Area Selection -->
                                        <br><br><select name="service_area[]" class="cls_slect-radius service-area">
                                            <option value="5" <?php echo ($address->service_area == "5") ? 'selected' : ''; ?>> 5 KM </option>
                                            <option value="10" <?php echo ($address->service_area == "10") ? 'selected' : ''; ?>> 10 KM </option>
                                            <option value="25" <?php echo ($address->service_area == "25") ? 'selected' : ''; ?>> 25 KM </option>
                                            <option value="50" <?php echo ($address->service_area == "50") ? 'selected' : ''; ?>> 50 KM </option>
                                            <option value="100" <?php echo ($address->service_area == "100") ? 'selected' : ''; ?>> 100 KM </option>
                                            <option value="250" <?php echo ($address->service_area == "250") ? 'selected' : ''; ?>> 250 KM </option>
                                            <option value="500" <?php echo ($address->service_area == "500") ? 'selected' : ''; ?>> 500 KM </option>
                                            <option value="entire" <?php echo ($address->service_area == "entire") ? 'selected' : ''; ?>> Entire Country </option>
                                            <option value="state" <?php echo ($address->service_area == "state") ? 'selected' : ''; ?>> Entire State </option>
                                            <option value="other" <?php echo ($address->service_area == "other") ? 'selected' : ''; ?>> Other Country </option>
                                            <option value="every" <?php echo ($address->service_area == "every") ? 'selected' : ''; ?>> Everywhere </option>
                                            <option value="no_service" <?php echo ($address->service_area == "no_service") ? 'selected' : ''; ?>> Not at this location </option>
                                        </select>

                                        <!-- Other Country Selection -->
                                        <br><br><div class="other-country-container" <?php if($address->service_area != 'other') echo 'style="display: none;"'; ?>>
                                        <select name="other_country[]" class="cls_slect-radius">
                                            <option value="">Select</option>
                                            <?php foreach($countries as $country) { ?>
                                                <option value="<?php echo $country->code; ?>" <?php echo ($address->other_country == $country->code) ? 'selected' : ''; ?>>
                                                    <?php echo esc_html($country->name); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        </div>
                                        
                                        <div class="map-preview" style="height: 50px;"></div>
                                        <button type="button" class="button remove-address">Remove</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- If no addresses exist, show one empty field -->
                            <tr class="address-container">
                                <th><label>Address</label></th>
                                <td class="address-group">
                                    <input type="text" name="addresses[]" class="regular-text address-field" placeholder="Enter address">
                                    <input type="text" name="latitude[]" class="latitude" placeholder="Latitude" readonly>
                                    <input type="text" name="longitude[]" class="longitude" placeholder="Longitude" readonly>
                                    <input type="text" name="street_number[]" class="street_number" placeholder="Street Number" readonly>
                                    <input type="text" name="route[]" class="route" placeholder="Route" readonly>
                                    <input type="text" name="address2[]" class="address2" placeholder="Address 2" readonly>
                                    <input type="text" name="postal_code[]" class="postal_code" placeholder="Postal Code" readonly>
                                    <input type="text" name="state[]" class="state" placeholder="State" readonly>
                                    <input type="text" name="country[]" class="country" placeholder="Country" readonly>

                                    <!-- Service Area Selection -->
                                    <br><br><select name="service_area[]" class="cls_slect-radius service-area">
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
                                        <option value="every"> Everywhere </option>
                                        <option value="no_service"> Not at this location </option>
                                    </select>

                                    <!-- Other Country Selection -->
                                    <br><br><div class="other-country-container" style="display: none;">
                                    <select name="other_country[]" class="cls_slect-radius">
                                        <option value="">Select</option>
                                        <?php foreach($countries as $country) { ?>
                                            <option value="<?php echo $country->code; ?>">
                                                <?php echo esc_html($country->name); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    </div>

                                    <div class="map-preview" style="height: 50px;"></div>
                                    <button type="button" class="button remove-address">Remove</button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                    <tr>
                        <td colspan="2">
                            <br><br><button type="button" id="add-address">+Add Address</button>
                        </td>
                    </tr>

                    </table>

                    <div class="row" style="padding: 20px;">
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary has-spinner" type="submit"><?php echo $submit_button_text ?></button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </form>