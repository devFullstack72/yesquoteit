<div class="profile-header">
    <img style="height: 100px;" alt="business logo" src="<?php echo esc_url($provider->business_logo); ?>" />
    <div>
        <h1><?php echo esc_html($provider->business_trading_name); ?></h1>
        <div class="provider-rating-container">
            <?php echo renderStars($average_rating, $total_reviews); ?>
        </div>
    </div>
</div>