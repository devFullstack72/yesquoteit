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
        ob_start();
?>
        <form id="partner-registration-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <?php wp_nonce_field('pr_partner_form_action', 'pr_partner_nonce'); ?>
            <input type="hidden" name="action" value="pr_partner_form_submission">

            <input type="hidden" name="lead_id" value="<?php echo !empty($_GET['lead_id']) ? $_GET['lead_id'] : '' ?>">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address" required></textarea><br>

            <input type="submit" name="partner_submit" value="Register">
        </form>
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

            $wpdb->insert($lead_partners_table, [
                'lead_id' => $_POST['lead_id'],
                'partner_id' => $partner_id,
                'created_at' => current_time('mysql')
            ]);

            wp_redirect(add_query_arg('success', '1', $_SERVER['HTTP_REFERER']));
            exit;
        }
    }
}
?>