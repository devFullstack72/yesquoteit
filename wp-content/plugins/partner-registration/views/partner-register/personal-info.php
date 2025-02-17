<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
    <input type="hidden" name="action" value="pr_partner_form_submission_step_1">

    <input type="hidden" name="lead_id" value="<?php echo !empty($_GET['lead_id']) ? $_GET['lead_id'] : '' ?>">
    <input type="hidden" name="profile_edit_mode" value="<?php echo $edit_profile_page ?>">

    <div class="wpcf7-form">
        <div class="step step-1">
            <div class="step-header">
                <?php if (!$edit_profile_page): ?>
                <small>Step 1 of <?php echo $total_steps ?></small>
                <?php endif; ?>
                <h5 class="text-center"><?php echo $partner_register_page_title ?></h5>
            </div>
            <div class="form-body">

                <p>
                    <label>Your Name (Firstname and Lastname)<br>
                        <span class="wpcf7-form-control-wrap" data-name="name">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="name" aria-required="true" aria-invalid="false" value="<?php echo !empty($partner) ? $partner->name : '' ?>" type="text" name="name">
                            <span class="error"><?php echo $errors['name'] ?? ''; ?></span>
                        </span>
                    </label>
                </p>

                <p><label>Business Trading Name<br>
                        <span class="wpcf7-form-control-wrap" data-name="business_trading_name"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="business_trading_name" aria-required="true" aria-invalid="false" value="<?php echo !empty($partner) ? $partner->business_trading_name : '' ?>" type="text" name="business_trading_name">
                        <span class="error"><?php echo $errors['business_trading_name'] ?? ''; ?></span>
                    </span> </label>
                </p>

                <p><label>Business Email (for notifications)<br>
                        <span class="wpcf7-form-control-wrap" data-name="email">
                            <input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="email" aria-required="true" aria-invalid="false" value="<?php echo !empty($partner) ? $partner->email : '' ?>" type="email" id="email" name="email">
                            <span class="error"><?php echo $errors['email'] ?? ''; ?></span>
                        </span> </label>
                </p>

                <?php if (!$edit_profile_page): ?>

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

                <?php endif; ?>

                <p><label>Phone<br>
                        <span class="wpcf7-form-control-wrap" data-name="phone"><input size="40" maxlength="400" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" required autocomplete="phone" aria-required="true" aria-invalid="false" value="<?php echo !empty($partner) ? $partner->phone : '' ?>" type="text" name="phone">
                        <span class="error"><?php echo $errors['phone'] ?? ''; ?></span>
                    </span> </label>
                </p>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary next-step"><?php echo $submit_button_text ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>