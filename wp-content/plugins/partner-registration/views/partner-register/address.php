<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
    <input type="hidden" name="action" value="pr_partner_form_submission_step_3">
    <input type="hidden" name="profile_edit_mode" value="<?php echo $edit_profile_page ?>">

    <input type="hidden" name="lead_id" value="<?php echo !empty($_GET['lead_id']) ? $_GET['lead_id'] : '' ?>">
    <div class="wpcf7-form">
        <div class="step step-3">
            <div class="step-header">
                <?php if (!$edit_profile_page): ?>
                <small>Step 3 of <?php echo $total_steps ?></small>
                <?php endif; ?>
                <h5 class="text-center"><?php echo $partner_register_page_title ?></h5>
            </div>
            <div class="form-body">
                <span class="wpcf7-form-control-wrap" data-name="address">
                    <input class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" id="autocomplete" aria-required="true" aria-invalid="false" type="text" name="address" value="<?php echo !empty($partner) ? $partner->address : '' ?>">
                    <span class="error"><?php echo $errors['address'] ?? ''; ?></span>
                </span>

                <!-- Display Map Image -->
                <div id="map-preview" style="margin-top: 10px;"></div>

                <br>
                <p>
                    <label>Latitude<br>
                        <span class="wpcf7-form-control-wrap" data-name="latitude">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="latitude" name="latitude" value="<?php echo !empty($partner) ? $partner->latitude : '' ?>">
                        </span>
                    </label>
                </p>

                <p>
                    <label>Longitude<br>
                        <span class="wpcf7-form-control-wrap" data-name="longitude">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="longitude" name="longitude" value="<?php echo !empty($partner) ? $partner->longitude : '' ?>">
                        </span>
                    </label>
                </p>

                <p>
                    <label>Street Number<br>
                        <span class="wpcf7-form-control-wrap" data-name="street_number">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="street_number" name="street_number" value="<?php echo !empty($partner) ? $partner->street_number : '' ?>">
                            <span class="error"><?php echo $errors['street_number'] ?? ''; ?></span>
                        </span>
                    </label>
                </p>

                <p>
                    <label>Address 1<br>
                        <span class="wpcf7-form-control-wrap" data-name="route">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="route" name="route" value="<?php echo !empty($partner) ? $partner->route : '' ?>">
                            <span class="error"><?php echo $errors['address_line_1'] ?? ''; ?></span>
                        </span>
                    </label>
                </p>

                <p>
                    <label>Address 2<br>
                        <span class="wpcf7-form-control-wrap" data-name="address2">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="address2" name="address2" value="<?php echo !empty($partner) ? $partner->address2 : '' ?>">
                            <span class="error"><?php echo $errors['address_line_2'] ?? ''; ?></span>
                        </span>
                    </label>
                </p>

                <p>
                    <label>Postal Code<br>
                        <span class="wpcf7-form-control-wrap" data-name="postal_code">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="postal_code" name="postal_code" value="<?php echo !empty($partner) ? $partner->postal_code : '' ?>">
                            <span class="error"><?php echo $errors['postal_code'] ?? ''; ?></span>
                        </span>
                    </label>
                </p>

                <p>
                    <label>State<br>
                        <span class="wpcf7-form-control-wrap" data-name="state">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="state" name="state" value="<?php echo !empty($partner) ? $partner->state : '' ?>">
                            <span class="error"><?php echo $errors['state'] ?? ''; ?></span>
                        </span>
                    </label>
                </p>

                <p>
                    <label>Country<br>
                        <span class="wpcf7-form-control-wrap" data-name="country">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text" readonly type="text" id="country" name="country" value="<?php echo !empty($partner) ? $partner->country : '' ?>">
                            <span class="error"><?php echo $errors['country'] ?? ''; ?></span>
                        </span>
                    </label>
                </p>

                <p>
                    <label>Service Area<br>
                        <select class="cls_slect-radius" onchange="on_country()" name="service_area" id="radius">
                            <option value="5" <?php echo !empty($partner) && $partner->service_area == 5 ? 'selected' : '' ?>> 5 KM </option>
                            <option value="10" <?php echo !empty($partner) && $partner->service_area == 10 ? 'selected' : '' ?>> 10 KM </option>
                            <option value="25" <?php echo !empty($partner) && $partner->service_area == 25 ? 'selected' : '' ?>> 25 KM </option>
                            <option value="50" <?php echo !empty($partner) && $partner->service_area == 50 ? 'selected' : '' ?>> 50 KM </option>
                            <option value="100" <?php echo !empty($partner) && $partner->service_area == 100 ? 'selected' : '' ?>> 100 KM </option>
                            <option value="250" <?php echo !empty($partner) && $partner->service_area == 250 ? 'selected' : '' ?>> 250 KM </option>
                            <option value="500" <?php echo !empty($partner) && $partner->service_area == 500 ? 'selected' : '' ?>> 500 KM </option>
                            <option value="entire" <?php echo !empty($partner) && $partner->service_area == 'entire' ? 'selected' : '' ?>> Entire Country </option>
                            <option value="state" <?php echo !empty($partner) && $partner->service_area == 'state' ? 'selected' : '' ?>> Entire State </option>
                            <option value="other" <?php echo !empty($partner) && $partner->service_area == 'other' ? 'selected' : '' ?>> Other Country </option>
                            <option value="every" <?php echo !empty($partner) && $partner->service_area == 'every' ? 'selected' : '' ?>> Every Where </option>
                            <option value="no_service" <?php echo !empty($partner) && $partner->service_area == 'no_service' ? 'selected' : '' ?>>Not at this location </option>
                        </select>
                        <span class="error"><?php echo $errors['service_area'] ?? ''; ?></span>
                    </label>
                </p>

                <p id="show_country" style="display:none;">
                    <label>Service provided in other Country<br>
                        <select class="cls_slect-radius" name="other_country" id="other_country">
                            <option value="0">Select</option>
                            <?php foreach ($countries as $country) { ?>
                                <option value="<?php echo $country->code ?>" <?php echo !empty($partner) && $partner->other_country == $country->code ? 'selected' : '' ?>><?php echo $country->name ?></option>
                            <?php } ?>
                        </select>
                    </label>
                </p>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary has-spinner" type="submit"><?php echo $submit_button_text ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>