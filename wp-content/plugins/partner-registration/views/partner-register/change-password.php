<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
    <input type="hidden" name="action" value="pr_partner_change_password">
    
    <div class="wpcf7-form">
        <div class="step step-1">
            <div class="step-header">
                <h5 class="text-center"><?php echo $partner_register_page_title ?></h5>
            </div>
            <div class="form-body">
                <div class="form-group">
                    <label for="website_url">New password</label>
                    <input type="password" class="form-control h-50px" id="new_password" name="new_password" placeholder="Enter password" value="">
                    <span class="error"><?php echo $errors['new_password'] ?? ''; ?></span>
                </div>
                <div class="form-group">
                    <label for="website_url">Confirm password</label>
                    <input type="password" class="form-control h-50px" id="confirm_password" name="confirm_password" placeholder="Enter password" value="">
                    <span class="error"><?php echo $errors['confirm_password'] ?? ''; ?></span>
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