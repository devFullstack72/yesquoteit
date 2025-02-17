<form class="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
    <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
    <input type="hidden" name="action" value="pr_partner_form_submission_step_2">

    <input type="hidden" name="lead_id" value="<?php echo !empty($_GET['lead_id']) ? $_GET['lead_id'] : '' ?>">
    <div class="wpcf7-form">
        <div class="step step-2">
            <div class="step-header">
                <?php if (!$edit_profile_page): ?>
                <small>Step 2 of <?php echo $total_steps ?></small>
                <?php endif; ?>
                <h5 class="text-center"><?php echo $partner_register_page_title ?></h5>
            </div>
            <div class="form-body">
                <div style="align-items: center;margin-bottom:10px;">
                    <?php foreach ($leads as $lead): ?>
                        <label style="display: flex; align-items: center; gap: 5px;">
                            <input type="checkbox" name="lead_ids[]" value="<?php echo esc_attr($lead->ID); ?>"
                                <?php echo (!empty($partner_leads) && in_array($lead->ID, $partner_leads)) ? 'checked' : ''; ?>>
                            <?php echo esc_html($lead->post_title); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <span class="error"><?php echo $errors['services'] ?? ''; ?></span>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button type="submit" class="btn btn-primary next-step"><?php echo $submit_button_text ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>