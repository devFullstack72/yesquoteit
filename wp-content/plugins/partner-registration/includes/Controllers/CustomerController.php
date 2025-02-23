<?php
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . '../../includes/DBController.php';

class CustomerController extends DBController
{

    public function __construct()
    {
        parent::__construct();

        if (!session_id()) {
            session_start();
        }
    }

    public function getCustomerID() {
        
        if (isset($_SESSION['temp_customer_id'])) {
            return $_SESSION['temp_customer_id'];
        } else if (isset($_SESSION['customer_id'])) {
            return $_SESSION['customer_id'];
        } else {
            return false;
        }
    }

    public function autoCustomerLogin($customer) {
        if (!empty($customer)) {
            $_SESSION['customer_logged_in'] = true;
            $_SESSION['customer_id'] = $customer->id;
            $_SESSION['customer_name'] = $customer->name;
        }
    }

    public function getCustomer() {
        $customer_id = $this->getCustomerID();

        $customer = $this->database->get_row($this->database->prepare(
            "SELECT * FROM {$this->customer_table} WHERE id = %d",
            $customer_id
        ));

        return $customer;
    }
}