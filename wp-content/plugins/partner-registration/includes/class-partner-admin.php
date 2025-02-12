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

        if (isset($_GET['action']) && isset($_GET['id'])) {
            global $wpdb;
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
        }
    }
}
?>