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

    public $providers_table;

    public $provider_reviews_table;

    public $cp_chat_table;

    public $cp_chat_messages_table;

    public function __construct() {
        global $wpdb;

        $this->database = $wpdb;

        $this->customer_table = $wpdb->prefix . 'yqit_customers';

        $this->lead_quotes_table = $wpdb->prefix . 'yqit_lead_quotes';

        $this->lead_quotes_partners_table = $wpdb->prefix . 'yqit_lead_quotes_partners';

        $this->posts_table = $this->database->prefix . 'posts';

        $this->providers_table = $this->database->prefix . 'service_partners';

        $this->provider_reviews_table = $this->database->prefix . 'yqit_partner_reviews';

        $this->cp_chat_table = $this->database->prefix . 'customer_partner_quote_chat';

        $this->cp_chat_messages_table = $this->database->prefix . 'customer_partner_quote_chat_messages';
    }
}