<?php
if (!defined('ABSPATH')) {
    exit;
}

class Partner_Admin
{

    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_init', [$this, 'handle_partner_actions']);
    }

    public function register_admin_menu()
    {
        add_menu_page(
            'Providers',
            'Providers',
            'manage_options',
            'service-partners',
            [$this, 'render_service_partners_page'],
            'dashicons-groups',
            20
        );
    }

    public function render_service_partners_page()
    {
        global $wpdb;
        $service_partners_table = $wpdb->prefix . 'service_partners';
        $lead_partners_table = $wpdb->prefix . 'lead_partners';
        $wp_posts_table = $wpdb->prefix . 'posts';

        // $partners = $wpdb->get_results("SELECT * FROM $service_partners_table");
        $partners = $wpdb->get_results("
            SELECT sp.*, 
                   GROUP_CONCAT(p.ID, '|', p.post_title SEPARATOR ',') AS leads
            FROM {$service_partners_table} sp
            LEFT JOIN {$lead_partners_table} lp ON sp.id = lp.partner_id
            LEFT JOIN {$wp_posts_table} p ON lp.lead_id = p.ID AND p.post_type = 'lead_generation'
            GROUP BY sp.id
        ");
?>
        <div class="wrap">
            <h1>Providers</h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Lead</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($partners as $partner): ?>
                        <tr>
                            <td><?php echo $partner->id; ?></td>
                            <td><?php echo $partner->name; ?></td>
                            <td><?php echo $partner->email; ?></td>
                            <td><?php echo $partner->phone; ?></td>
                            <td><?php echo $partner->address; ?></td>
                            <td>
                            <?php if (!empty($partner->leads)): 
                                    $leads = explode(',', $partner->leads);
                                    foreach ($leads as $lead_data): 
                                        list($lead_id, $lead_name) = explode('|', $lead_data);
                                        ?>
                                        <a href="<?php echo esc_url(get_permalink($lead_id)); ?>" target="_blank">
                                            <?php echo esc_html($lead_name); ?>
                                        </a><br>
                                    <?php endforeach;
                                else: ?>
                                    No Leads Assigned
                                <?php endif; ?>
                            </td>
                            <td><?php echo $partner->status == 1 ? 'Approved' : ($partner->status == 2 ? 'Rejected' : 'Pending'); ?></td>
                            <td>
                                <?php if ($partner->status == 0): ?>
                                    <a href="?page=service-partners&action=approve&id=<?php echo $partner->id; ?>" class="button">Approve</a>
                                    <a href="?page=service-partners&action=reject&id=<?php echo $partner->id; ?>" class="button">Reject</a>
                                <?php endif; ?>

                                <a href="?page=service-partners&action=edit&id=<?php echo $partner->id; ?>" class="button">Edit</a>
                                <a href="?page=service-partners&action=delete&id=<?php echo $partner->id; ?>" class="button">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
<?php
    }

    public function handle_partner_actions() {
        if (!isset($_GET['page']) || $_GET['page'] !== 'service-partners') {
            return;
        }

        global $wpdb;

        if (isset($_POST['update_partner'])) {
            $partner_id = intval($_POST['partner_id']);
            $name = sanitize_text_field($_POST['name']);
            $email = sanitize_email($_POST['email']);
            $phone = sanitize_text_field($_POST['phone']);
            $address = sanitize_text_field($_POST['address']);
            $status = intval($_POST['status']);
            $selected_leads = isset($_POST['leads']) ? array_map('intval', $_POST['leads']) : [];
        
            // Update partner details
            $wpdb->update(
                $wpdb->prefix . 'service_partners',
                ['name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address, 'status' => $status],
                ['id' => $partner_id],
                ['%s', '%s', '%s', '%s', '%d'],
                ['%d']
            );
        
            // Update assigned leads
            $lead_partners_table = $wpdb->prefix . 'lead_partners';
        
            // Delete existing leads
            $wpdb->delete($lead_partners_table, ['partner_id' => $partner_id], ['%d']);
        
            // Insert new leads
            foreach ($selected_leads as $lead_id) {
                $wpdb->insert($lead_partners_table, [
                    'partner_id' => $partner_id,
                    'lead_id' => $lead_id
                ], ['%d', '%d']);
            }
        
            // Redirect back to list page
            wp_redirect(admin_url('admin.php?page=service-partners'));
            exit;
        }

        if (isset($_GET['action']) && isset($_GET['id'])) {
            $service_partners_table = $wpdb->prefix . 'service_partners';
            $lead_partners_table = $wpdb->prefix . 'lead_partners';
            $partner_id = intval($_GET['id']);

            if ($_GET['action'] === 'approve') {
                $wpdb->update(
                    $service_partners_table,
                    ['status' => 1],
                    ['id' => $partner_id],
                    ['%d'],
                    ['%d']
                );
                wp_redirect(admin_url('admin.php?page=service-partners'));
                exit;
            }

            if ($_GET['action'] === 'reject') {
                $wpdb->update(
                    $service_partners_table,
                    ['status' => 2],
                    ['id' => $partner_id],
                    ['%d'],
                    ['%d']
                );
                wp_redirect(admin_url('admin.php?page=service-partners'));
                exit;
            }

            if ($_GET['action'] === 'delete') {
                // First, delete related leads
                $wpdb->delete($lead_partners_table, ['partner_id' => $partner_id], ['%d']);
    
                // Then, delete the service partner itself
                $wpdb->delete($service_partners_table, ['id' => $partner_id], ['%d']);
    
                wp_redirect(admin_url('admin.php?page=service-partners'));
                exit;
            }

            if ($_GET['action'] === 'edit') {
                $partner = $wpdb->get_row("SELECT * FROM $service_partners_table WHERE id = $partner_id");
                if ($partner) {
                    add_action('admin_notices', function () use ($partner) {
                        $this->render_edit_form($partner);
                    });
                }
                return;
            }
        }
    }

    public function render_edit_form($partner)
    {
        global $wpdb;
        $wp_posts_table = $wpdb->prefix . 'posts';
        $lead_partners_table = $wpdb->prefix . 'lead_partners';
    
        // Get all leads (Post type: 'lead_generation')
        $all_leads = $wpdb->get_results("SELECT ID, post_title FROM $wp_posts_table WHERE post_type = 'lead_generation'");
    
        // Get assigned leads for this partner
        $assigned_leads = $wpdb->get_col("SELECT lead_id FROM $lead_partners_table WHERE partner_id = {$partner->id}");
    
        ?>
        <div class="wrap">
            <h1>Edit Provider</h1>
            <form method="post" action="">
                <input type="hidden" name="partner_id" value="<?php echo esc_attr($partner->id); ?>">
                <table class="form-table">
                    <tr>
                        <th><label for="name">Name</label></th>
                        <td><input type="text" name="name" id="name" value="<?php echo esc_attr($partner->name); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="email">Email</label></th>
                        <td><input type="email" name="email" id="email" value="<?php echo esc_attr($partner->email); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="phone">Phone</label></th>
                        <td><input type="text" name="phone" id="phone" value="<?php echo esc_attr($partner->phone); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="address">Address</label></th>
                        <td><input type="text" name="address" id="address" value="<?php echo esc_attr($partner->address); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="status">Status</label></th>
                        <td>
                            <select name="status" id="status">
                                <option value="0" <?php selected($partner->status, 0); ?>>Pending</option>
                                <option value="1" <?php selected($partner->status, 1); ?>>Approved</option>
                                <option value="2" <?php selected($partner->status, 2); ?>>Rejected</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                    <th><label>Assign Leads</label></th>
                    <td>
                        <?php foreach ($all_leads as $lead): ?>
                            <label>
                                <input type="checkbox" name="leads[]" value="<?php echo $lead->ID; ?>" 
                                    <?php checked(in_array($lead->ID, $assigned_leads)); ?>>
                                <?php echo esc_html($lead->post_title); ?>
                            </label><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
                </table>
                <p><input type="submit" name="update_partner" value="Save Changes" class="button button-primary"></p>
            </form>
        </div>
        <?php
    }
}
?>