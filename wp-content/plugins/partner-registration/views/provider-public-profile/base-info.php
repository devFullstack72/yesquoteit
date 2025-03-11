<div class="profile-header">
    <img style="height: 100px;" alt="business logo" src="<?php echo esc_url($provider->business_logo); ?>" />
    <div>
        <h1><?php echo esc_html($provider->business_trading_name); ?></h1>
        <div class="provider-rating-container" style="display: flex;">
            <?php echo renderStars($average_rating, $total_reviews); ?>
            <div style="font-size: 12px;margin-left: 10px;text-align: right;display: inline-block;margin-top: 2px;">
            <i class="fa fa-check-circle" style="color: #08c1da;font-size: 15px;"></i>
             <i>Verified member</i>
         </div>
        </div>
    </div>
</div>