<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
    <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
    <input type="hidden" name="action" value="pr_partner_form_submission_step_4">
    <input type="hidden" name="profile_edit_mode" value="<?php echo $edit_profile_page ?>">
    <div class="wpcf7-form">
        <div class="step step-3">
            <div class="step-header">
                <?php if (!$edit_profile_page): ?>
                <small>Step 4 of <?php echo $total_steps ?></small>
                <?php endif; ?>
                <h5 class="text-center"><?php echo $partner_register_page_title ?></h5>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label for="business_logo">Business Logo</label>
                    <input type="file" class="form-control h-50px" id="business_logo" name="business_logo" accept="image/*">
                    <span class="error"><?php echo $errors['business_logo'] ?? ''; ?></span>
                    <?php if (!empty($partner->business_logo)): ?>
                        <img src="<?php echo $partner->business_logo ?>" height="100">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="website_url">Website URL</label>
                    <input type="url" class="form-control h-50px" id="website_url" name="website_url" placeholder="https://example.com" value="<?php echo !empty($partner) ? $partner->website_url : '' ?>">
                    <span class="error"><?php echo $errors['website_url'] ?? ''; ?></span>
                </div>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary"><?php echo $submit_button_text ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>