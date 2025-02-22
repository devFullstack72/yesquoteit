<?php
if (!defined('ABSPATH')) {
    exit;
}

class DBController
{

    public $database;

    public $customer_table;

    public $lead_quotes_table;

    public $lead_quotes_partners_table;

    public $posts_table;

    public function __construct() {
        global $wpdb;

        $this->database = $wpdb;

        $this->customer_table = $wpdb->prefix . 'yqit_customers';

        $this->lead_quotes_table = $wpdb->prefix . 'yqit_lead_quotes';

        $this->lead_quotes_partners_table = $wpdb->prefix . 'yqit_lead_quotes_partners';

        $this->posts_table = $this->database->prefix . 'posts';
    }
}