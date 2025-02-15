<?php
// Retrieve errors
$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

// Clear session errors after displaying
unset($_SESSION['form_errors']);
unset($_SESSION['form_data']);

$current_step = isset($_GET['next_step']) ? $_GET['next_step'] : 1;

// Check for success message
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="notice notice-success" style="padding: 10px; border: 1px solid #46b450; background-color: #dff0d8; color: #3c763d; margin-bottom: 15px;">
                Thank you for registering! We will review it shortly.
            </div>';
}
?>

<?php if ($current_step == 1) : ?>
<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
    <input type="hidden" name="action" value="pr_partner_form_submission_step_1">

    <input type="hidden" name="lead_id" value="<?php echo !empty($_GET['lead_id']) ? $_GET['lead_id'] : '' ?>">

    <div class="wpcf7-form">
        <div class="step step-1">
            <div class="step-header">
                <small>Step 1 of 3</small>
                <h5 class="text-center">Register for free to start receiving leads...</h5>
            </div>
            <div class="form-body">

                <p>
                    <label>Your Name (Firstname and Lastname)<br>
                        <span class="wpcf7-form-control-wrap" data-name="name">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="name" aria-required="true" aria-invalid="false" value="" type="text" name="name">
                            <span class="error"><?php echo $errors['name'] ?? ''; ?></span>
                        </span>
                    </label>
                </p>

                <p><label>Business Trading Name<br>
                        <span class="wpcf7-form-control-wrap" data-name="business_trading_name"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="business_trading_name" aria-required="true" aria-invalid="false" value="" type="text" name="business_trading_name">
                        <span class="error"><?php echo $errors['business_trading_name'] ?? ''; ?></span>
                    </span> </label>
                </p>

                <p><label>Business Email (for notifications)<br>
                        <span class="wpcf7-form-control-wrap" data-name="email">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="email" aria-required="true" aria-invalid="false" value="" type="email" id="email" name="email">
                            <span class="error"><?php echo $errors['email'] ?? ''; ?></span>
                        </span> </label>
                </p>

                <p><label>Confirm email<br>
                        <span class="wpcf7-form-control-wrap" data-name="c_email"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="c_email" aria-required="true" aria-invalid="false" value="" type="email" id="c_email" name="c_email">
                        <span class="error"><?php echo $errors['confirm_email'] ?? ''; ?></span>
                    </span> </label>
                </p>

                <p><label>Password<br>
                        <span class="wpcf7-form-control-wrap" data-name="password"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="password" aria-required="true" aria-invalid="false" value="" type="password" id="password" name="password">
                        <span class="error"><?php echo $errors['password'] ?? ''; ?></span>
                    </span> </label>
                </p>

                <p><label>Phone<br>
                        <span class="wpcf7-form-control-wrap" data-name="phone"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="phone" aria-required="true" aria-invalid="false" value="" type="text" name="phone">
                        <span class="error"><?php echo $errors['phone'] ?? ''; ?></span>
                    </span> </label>
                </p>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary next-step">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?php elseif ($current_step == 2) : ?>
<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
    <input type="hidden" name="action" value="pr_partner_form_submission_step_2">

    <input type="hidden" name="lead_id" value="<?php echo !empty($_GET['lead_id']) ? $_GET['lead_id'] : '' ?>">
    <div class="step step-2" style="display: none;">
        <div class="step-header">
            <small>Step 1 of 3</small>
            <h5 class="text-center">Connect with the right customers by adding your service categories...</h5>
        </div>
        <div class="form-body">
            <div style="align-items: center;margin-bottom:10px;">
                <?php foreach ($leads as $lead): ?>
                    <label style="display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" name="lead_ids[]" value="<?php echo esc_attr($lead->ID); ?>"
                            <?php echo (!empty($selected_lead_id) && $selected_lead_id == $lead->ID) ? 'checked' : ''; ?>>
                        <?php echo esc_html($lead->post_title); ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-default prev-step">Previous</button>
                    <button type="button" class="btn btn-primary next-step">Next</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php elseif ($current_step == 3) : ?>
<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
    <input type="hidden" name="action" value="pr_partner_form_submission_step_3">

    <input type="hidden" name="lead_id" value="<?php echo !empty($_GET['lead_id']) ? $_GET['lead_id'] : '' ?>">
    <div class="step step-3" style="display: none;">
        <div class="step-header">
            <small>Step 1 of 3</small>
            <h5 class="text-center">Get relevant jobs in the area you prefer</h5>
        </div>
        <div class="form-body">
            <span class="wpcf7-form-control-wrap" data-name="address">
                <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required id="autocomplete" aria-required="true" aria-invalid="false" type="text" name="address">
            </span> </label>
            </p>

            <!-- Display Map Image -->
            <div id="map-preview" style="margin-top: 10px;"></div>

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
                        <?php foreach ($countries as $country) { ?>
                            <option value="<?php echo $country->code ?>"><?php echo $country->name ?></option>
                        <?php } ?>
                    </select>
                </label>
            </p>

            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="button" class="btn btn-default prev-step">Previous</button>
                    <button class="btn btn-primary has-spinner" type="submit" name="partner_submit">Register</button>
                </div>
            </div>
        </div>
    </div>
    </div>
</form>
<?php endif; ?>

</div>