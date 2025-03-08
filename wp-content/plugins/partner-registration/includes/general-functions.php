<?php
// Encryption Function
function encrypt_customer_id($customer_id) {
    $key = wp_salt(); // Use WordPress's secure salt
    return urlencode(base64_encode(openssl_encrypt($customer_id, 'AES-256-CBC', $key, 0, substr($key, 0, 16))));
}

// Decryption Function
function decrypt_customer_id($encrypted_id) {
    $key = wp_salt(); // Use WordPress's secure salt
    return openssl_decrypt(base64_decode(urldecode($encrypted_id)), 'AES-256-CBC', $key, 0, substr($key, 0, 16));
}

function dd($data) {
    echo "<pre>";
    print_r($data);
    exit;
}

function getCustomerUrgencyClass($priority) {
    switch($priority) {
        case 'Medium':
            return 'primary';
            break;
        case 'High':
            return 'danger';
            break;
        default:
            return 'info';
            break;
    }
}