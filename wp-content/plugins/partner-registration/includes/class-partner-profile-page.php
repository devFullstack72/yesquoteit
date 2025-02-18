<?php
if (!defined('ABSPATH')) {
    exit;
}

class Partner_Public_Profile
{

    public function __construct()
    {
        add_action('init', [$this, 'custom_provider_rewrite_rule']);
        add_action('query_vars', [$this, 'add_provider_query_var']);
        add_filter('template_include', [$this, 'load_provider_template']);
    }

    public function custom_provider_rewrite_rule() {
        add_rewrite_rule('^provider/([0-9]+)/?', 'index.php?provider_id=$matches[1]', 'top');
    }

    public function add_provider_query_var($vars) {
        $vars[] = 'provider_id';
        return $vars;
    }

    function load_provider_template($template) {
        if (get_query_var('provider_id')) {
            return plugin_dir_path(__FILE__) . '../views/provider-public-profile.php';
        }
        return $template;
    }
}