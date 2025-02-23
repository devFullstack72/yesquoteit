<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../includes/DBController.php';

class PartnerController extends DBController
{

    public function __construct() {
        parent::__construct();
    }

    public function getProviderID() {
        if (isset($_SESSION['partner_id'])) {
            return $_SESSION['partner_id'];
        } else {
            return false;
        }
    }
}