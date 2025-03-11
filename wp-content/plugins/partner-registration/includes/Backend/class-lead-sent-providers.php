<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../../includes/DBController.php';

class LeadSentPartnersController extends DBController
{

    public function __construct()
    {
        parent::__construct();

        add_action('admin_menu', [$this, 'lead_sent_providers_list_menu']);
    }

    public function lead_sent_providers_list_menu() {
        add_menu_page(
            'Lead sent List',
            'Lead sent List',
            'manage_options',
            'lead-sent-providers-list-page',
            [$this, 'lead_sent_providers_list_page_callback'], // Fixed callback reference
            'dashicons-list-view',
            20
        );
    }

    public function lead_sent_providers_list_page_callback()
    {

        global $wpdb;

        // $query = "
        //     SELECT 
        //         p.ID AS lead_id,
        //         p.post_title AS lead_name,
        //         GROUP_CONCAT(DISTINCT t.name SEPARATOR ', ') AS category_names,
        //         q.quote_data AS quote_data,
        //         GROUP_CONCAT(DISTINCT sp.business_trading_name SEPARATOR ', ') AS provider_names,
        //         GROUP_CONCAT(DISTINCT sp.email SEPARATOR ', ') AS provider_emails
        //     FROM {$wpdb->prefix}yqit_lead_quotes q
        //     LEFT JOIN {$wpdb->prefix}posts p ON p.ID = q.lead_id
        //     LEFT JOIN {$wpdb->prefix}yqit_lead_quotes_partners qp ON q.id = qp.lead_quote_id
        //     LEFT JOIN {$wpdb->prefix}service_partners sp ON sp.id = qp.provider_id
        //     LEFT JOIN {$wpdb->prefix}term_relationships tr ON tr.object_id = p.ID
        //     LEFT JOIN {$wpdb->prefix}term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
        //     LEFT JOIN {$wpdb->prefix}terms t ON t.term_id = tt.term_id
        //     WHERE p.post_status = 'publish'
        //     GROUP BY q.id
        //     HAVING provider_names IS NOT NULL AND provider_names <> ''
        //     ORDER BY q.id DESC
        // ";

        $query = "
            SELECT 
                p.ID AS lead_id,
                p.post_title AS lead_name,
                GROUP_CONCAT(DISTINCT t.name SEPARATOR ', ') AS category_names,
                q.quote_data AS quote_data,
                GROUP_CONCAT(
                    DISTINCT CONCAT(sp.business_trading_name, '|||', sp.email) 
                    SEPARATOR '|||SEP|||'
                ) AS provider_data
            FROM {$wpdb->prefix}yqit_lead_quotes q
            LEFT JOIN {$wpdb->prefix}posts p ON p.ID = q.lead_id
            LEFT JOIN {$wpdb->prefix}yqit_lead_quotes_partners qp ON q.id = qp.lead_quote_id
            LEFT JOIN {$wpdb->prefix}service_partners sp ON sp.id = qp.provider_id
            LEFT JOIN {$wpdb->prefix}term_relationships tr ON tr.object_id = p.ID
            LEFT JOIN {$wpdb->prefix}term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
            LEFT JOIN {$wpdb->prefix}terms t ON t.term_id = tt.term_id
            WHERE p.post_status = 'publish'
            GROUP BY q.id
            HAVING provider_data IS NOT NULL AND provider_data <> ''
            ORDER BY q.id DESC
        ";

        $lead_sent_providers_data = $wpdb->get_results($query);

        $view_path = plugin_dir_path(__FILE__) . '../../views/backend/lead-sent-providers-list.php'; // If inside a plugin

        if (file_exists($view_path)) {
            include $view_path;
        } else {
            echo '<div class="error"><p>View file not found at ' . esc_html($view_path) . '.</p></div>';
        }
    }
}