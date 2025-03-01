<?php

$edit_profile_page = !empty($atts['profile']) ? $atts['profile'] : false;

// Retrieve errors
$errors = isset($_SESSION['form_errors']) ? $_SESSION['form_errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

$profile_updated = isset($_SESSION['profile_updated']) ? $_SESSION['profile_updated'] : [];

// Clear session errors after displaying
unset($_SESSION['form_errors']);
unset($_SESSION['form_data']);
unset($_SESSION['profile_updated']);

$current_step = isset($_GET['next_step']) ? $_GET['next_step'] : 1;

$message = 'Thank You for Registering!';
$description = 'We have received your submission. Our team will review it shortly.';

if (isset($profile_updated) && !empty($profile_updated)) {
    $message = 'Profile updated successfully!';
    $description = 'We have updated your profile based on your request.';
}

// Check for success message
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo '<div class="container partner-register-thank-you" style="margin-top: 50px; margin-bottom: 100px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default text-center">
                <div class="panel-heading">
                    <img src="' . get_template_directory_uri() . '/images/footer-logo.png" alt="Thank You" style="max-width: 100px;">
                </div>
                <div class="panel-body">
                    <h2>' . $message . '</h2>
                    <p>' . $description . '</p>
                    <a href="'. home_url() .'" class="btn btn-primary">Go to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>';
}

$total_steps = 4;
?>

<style>
    .register-your-business {
        background-image: url('<?php echo get_template_directory_uri() . '/images/bgimgfull.jpg' ?>');
        background-size: cover;  /* Scales the image to cover the entire container */
        background-position: center;  /* Centers the image */
        background-repeat: no-repeat;  /* Prevents image repetition */
    }
</style>

<?php if ($current_step == 1 && !$edit_profile_page) : ?>
<?php
$partner_register_page_title = 'Register for free to start receiving leads...';
$submit_button_text = 'Next';
include plugin_dir_path(__FILE__) . '../views/partner-register/personal-info.php'
?>
<?php endif; ?>

<?php if ($current_step == 2 && !$edit_profile_page) : ?>
<?php 
$partner_register_page_title = 'Connect with the right customers by adding your service categories...';
$submit_button_text = 'Next';
include plugin_dir_path(__FILE__) . '../views/partner-register/services.php';
?>
<?php endif; ?>

<?php if ($current_step == 3 && !$edit_profile_page) : ?>
<?php 
    $partner_register_page_title = 'What locations does your business service';
    $submit_button_text = 'Next';
    include plugin_dir_path(__FILE__) . '../views/partner-register/address.php';
?>
<?php endif; ?>

<?php if ($current_step == 4 && !$edit_profile_page) : ?>
<?php 
    $partner_register_page_title = 'Submit your brand identity';
    $submit_button_text = 'Register';
    include plugin_dir_path(__FILE__) . '../views/partner-register/branding.php';
?>
<?php endif; ?>

<?php if ($edit_profile_page == true): ?>
    
    <?php if (isset($profile_updated) && !empty($profile_updated)) : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="container" style="margin-top: 50px;">
                <div class="alert alert-success">
                    <?php if (isset($profile_updated['message']) && !empty($profile_updated['message'])) : ?>
                        <div><?php echo $profile_updated['message'] ?></div>
                    <?php else: ?>
                        <div>Profile Updated</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <?php
        $partner_register_page_title = 'Update Personal Info';
        $submit_button_text = 'Save';
        include plugin_dir_path(__FILE__) . '../views/partner-register/personal-info.php';

        $partner_register_page_title = 'Update Services';
        $submit_button_text = 'Save';
        include plugin_dir_path(__FILE__) . '../views/partner-register/services.php';

        $partner_register_page_title = 'Update Branding';
        $submit_button_text = 'Save';
        include plugin_dir_path(__FILE__) . '../views/partner-register/branding.php';
        ?>
    </div>
    <div class="col-md-6">
        <?php 

        $partner_register_page_title = 'Change Password';
        $submit_button_text = 'Save';
        include plugin_dir_path(__FILE__) . '../views/partner-register/change-password.php';


        $partner_register_page_title = 'Update Address';
        $submit_button_text = 'Save';
        include plugin_dir_path(__FILE__) . '../views/partner-register/address.php';
        ?>
    </div>
</div>
<?php endif; ?>

</div>