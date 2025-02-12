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
            AND post_status != 'trash'
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

            <p><label>Address<br>
            <span class="wpcf7-form-control-wrap" data-name="address"><textarea size="40" maxlength="400" rows="3" cols="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" autocomplete="address" required aria-required="true" aria-invalid="false" value="" type="text" name="address"></textarea></span> </label>
            </p>

            <p><label>Select Leads:</label></p>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;margin-bottom:10px;">
                <?php foreach ($leads as $lead): ?>
                    <label style="display: flex; align-items: center; gap: 5px;">
                        <input type="checkbox" name="lead_ids[]" value="<?php echo esc_attr($lead->ID); ?>" 
                        <?php echo (!empty($selected_lead_id) && $selected_lead_id == $lead->ID) ? 'checked' : ''; ?>>
                        <?php echo esc_html($lead->post_title); ?>
                    </label>
                <?php endforeach; ?>
            </div>



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
            $address = sanitize_textarea_field($_POST['address']);
            $lead_ids = isset($_POST['lead_ids']) ? array_map('intval', $_POST['lead_ids']) : [];


            $service_partners_table = $wpdb->prefix . 'service_partners';
            $lead_partners_table = $wpdb->prefix . 'lead_partners';

            $wpdb->insert($service_partners_table, [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
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