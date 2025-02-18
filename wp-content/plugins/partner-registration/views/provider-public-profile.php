<?php
/* Template for public provider profile */

global $wpdb;

// Get provider ID from URL
$provider_id = get_query_var('provider_id');

if (!$provider_id) {
    wp_die('Invalid Provider ID');
}

// Fetch provider details from database
$provider = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}service_partners WHERE id = %d",
    $provider_id
));

if (!$provider) {
    wp_die('Provider not found');
}

// Set Content-Type
header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($provider->name); ?> - Profile</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; padding: 20px; background: #f8f8f8; }
        .profile-container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        .profile-header { text-align: center; }
        .profile-header h1 { margin-bottom: 10px; }
        .profile-details { margin-top: 20px; }
        .profile-details p { font-size: 16px; }
    </style>
</head>
<body>

<div class="profile-container">
    <div class="profile-header">
        <h1><?php echo esc_html($provider->name); ?></h1>
        <p><?php echo esc_html($provider->business_trading_name); ?></p>
    </div>
    
    <div class="profile-details">
        <p><strong>Email:</strong> <?php echo esc_html($provider->email); ?></p>
        <p><strong>Phone:</strong> <?php echo esc_html($provider->phone); ?></p>
        <p><strong>Status:</strong> <?php echo $provider->status ? 'Active' : 'Pending'; ?></p>
    </div>
</div>

</body>
</html>
