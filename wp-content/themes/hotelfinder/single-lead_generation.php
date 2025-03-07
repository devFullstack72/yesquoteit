<?php

/**
 * Template for displaying single posts of the 'lead_generation' custom post type
 *
 * @package Neve
 */

$container_class = apply_filters('neve_container_class_filter', 'container', 'single-lead_generation');

get_header();

$custom_field_value = get_post_meta(get_the_ID(), '_lead_form_short_code', true);

?>

<style type="text/css">
    .htlfndr-under-header {
        /* display: none; */
        /* Hide under-header section */
    }

    /* Full-width featured image container */
    .lead-generation-featured-container {
        position: relative;
        width: 100%;
        max-height: 700px;
        /* Adjust as needed */
        overflow: hidden;
    }

    /* Ensure the featured image covers the section */
    .lead-generation-featured-image img {
        width: 100%;
        height: 600px;
        /* Adjust based on design */
        object-fit: cover;
    }

    /* Overlay form container */
    .cs-lp-form {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        background: rgba(0, 0, 0, 0.6);
        padding: 50px;
        color: white;
        width: 50%;
        max-width: 90%; /* Ensures it doesn't overflow on smaller screens */
        box-sizing: border-box;
    }

    /* Mobile Styles */
    @media only screen and (max-width: 768px) {
        .cs-lp-form {
            width: 90%; /* Increase width for better fit on mobile */
            padding: 30px; /* Reduce padding to save space */
        }
    }

    @media only screen and (max-width: 480px) {
        .cs-lp-form {
            width: 95%;
            padding: 20px; /* Further reduce padding for smaller screens */
        }
    }


    .cs-lp-form h5 {
        font-size: 18px;
        margin-bottom: 15px;
    }

    .cs-lp-form input[type="Submit"] {
        background-color: #08c1da;
        border: none;
        color: white;
        padding: 12px 24px;
        font-size: 18px;
        cursor: pointer;
        border-radius: 5px;
        transition: 0.3s;
    }

    .cs-lp-form input[type="Submit"]:hover {
        background-color: #067c9a;
    }

    .htlfndr-slider-under-title-line {
        margin: 0 auto;
    }


    .cls_searchhotl #htlfndr-input-5>input {
        background: #08c1da none repeat scroll 0 0;
        border: 2px solid #08c1da;
        line-height: 20px;
        padding: 0 15px;
        width: auto;
        border-radius: 5px;
        height: 38px;
        text-transform: uppercase;
    }

    @media (min-width: 768px) {
        .desktop_cls_htlfinder {
            margin-bottom: 30px;
        }
    }

    @media (min-width: 768px) {
        .desktop_cls_htlfinder .htlfndr-slider-title.cls_tit1-cnt {
            height: auto;
        }
    }

    .htlfndr-slider-title.cls_tit1-cnt {
        float: none;
        margin: 0px;
        text-align: center;
        width: 100%;
        padding: 0;
    }

    .htlfndr-slider-title {
        font-size: 30px;
        font-family: "Roboto", sans-serif
    }

    .associated-leads-container {
        margin-top: 50px;
    }

        .associated-leads-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .associated-leads-list ul {
            list-style: none;
            padding: 0;
            text-align: center;
        }

        .associated-leads-list ul li {
            margin: 10px 0;
            font-size: 18px;
        }

        .associated-leads-list ul li a {
            text-decoration: none;
            color: #08c1da;
            font-weight: bold;
            transition: 0.3s;
        }

        .associated-leads-list ul li a:hover {
            color: #067c9a;
        }

</style>

<!-- Featured Image Section with Overlay -->
<div class="lead-generation-featured-container">
    <?php if (has_post_thumbnail()) : ?>
        <div class="lead-generation-featured-image">
            <?php the_post_thumbnail('extra-large'); ?>
        </div>
    <?php endif; ?>

    <!-- Overlay Form -->
    <div class="cs-lp-form">
        <div class="cls_htlfinder-align desktop_cls_htlfinder">
        <?php
            $custom_text = get_post_meta(get_the_ID(), '_lead_title_text', true);
            if (empty($custom_text)) {
                $custom_text = 'Get the right '.get_the_title().' for your needs.';
            } else {
                $custom_text = esc_html($custom_text);
            }
        ?>

            <h1 id="head_text" class="htlfndr-slider-title cls_tit1-cnt"><?php echo $custom_text ?></h1><br>
            <div class="cls_under cls_center-under">
                <div class="htlfndr-slider-under-title-line"></div>
            </div>
        </div>
        <div class="cls_searchhotl cs-cont-btn">
            <div id="htlfndr-input-5">
                <input type="Submit" value="Get a quote now" data-toggle="modal" data-target="#quoteModalWizard">
                <!-- <a href="<?php echo home_url(); ?>/become-a-partner?lead_id=<?php echo get_the_ID() ?>" class="btn btn-success" style="color:white;margin:0;padding: 6px 15px;margin-top:-5px;">Become a Provider</a> -->
            </div>
        </div>

    </div>
</div>

<!-- Main Content -->
<div class="<?php echo esc_attr($container_class); ?> single-lead-generation-container">
    <div class="row" style="margin-bottom:100px;margin-top:50px;">
        <?php do_action('neve_do_sidebar', 'single-lead_generation', 'left'); ?>
        <article id="post-<?php echo esc_attr(get_the_ID()); ?>"
            class="<?php echo esc_attr(join(' ', get_post_class('nv-single-post-wrap col'))); ?>">
            <?php
            do_action('neve_before_post_content');

            if (have_posts()) {
                while (have_posts()) {
                    the_post();

                    // Display the title of the post
                    echo '<h2 id="lead-generation-content-details" class="htlfndr-section-title htlfndr-lined-title" style="font-size: 21px;"><span>' . get_the_title() . '</span></h2>';

                    // Display the content of the post
                    echo '<div class="lead-generation-content">';
                    the_content();
                    echo '</div>';
                }
            } else {
                get_template_part('template-parts/content', 'none');
            }

            do_action('neve_after_post_content');

            // Fetch Associated Leads
            $associated_leads = get_post_meta(get_the_ID(), '_associated_leads', true);

            if (!empty($associated_leads)) {
                $lead_ids = explode(',', $associated_leads);
            }
            
            ?>
        <?php if(!empty($lead_ids)) { ?>    
            <div class="associated-leads-container">
                <h5 class="associated-leads-title">Associated Leads</h5>
                <div class="htlfndr-section-under-title-line"></div>
                <div class="associated-leads-list">
                    <div class="row">
                        <?php
                        foreach ($lead_ids as $lead_id) {
                            $image = get_the_post_thumbnail_url($lead_id, 'medium');
                            $title = get_the_title($lead_id);
                            $post_link = get_permalink($lead_id);

                            if (!$image) {
                                $image = 'http://localhost/wordpress/wp-content/uploads/2025/02/1581618767-1-1024x683.jpeg';
                            }

                            echo '<div class="col-sm-4 col-xs-6">';
                            echo '<div class="htlfndr-category-box">';
                            echo '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" />';
                            echo '<div class="category-description">';
                            echo '<h3 class="subcategory-name">' . esc_html($title) . '</h3>';
                            echo '<h5 class="category-name">' . esc_html($title) . '</h5>';
                            echo '<a href="' . esc_url($post_link) . '" class="htlfndr-category-permalink"></a>';
                            echo '</div>';
                            echo '<div class="default_title_cat">' . esc_html($title) . '</div>';
                            echo '</div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
    <?php } ?>
        </article>
        <?php do_action('neve_do_sidebar', 'single-lead_generation', 'right'); ?>
    </div>
</div>

<?php if (!empty($custom_field_value)) : ?>
<div class="modal fade" id="quoteModalWizard" tabindex="-1" role="dialog" aria-labelledby="quoteModalWizardLabel" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 43px;">
                <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-7 col-xs-6">
                        <div id="custom-success-message-for-lead" style="display: none;">
                            <div class="panel text-center wpcf7-form">
                                <div class="panel-body">
                                    <h3 style="margin-top: 20px; margin-bottom: 20px;">We are now contacting relevant businesses.</h3>
                                    <p style="margin-top: 20px; margin-bottom: 20px;">Feel free to request quotes for other products and services.</p>
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                            <a href="Javascript:void(0);" class="btn btn-theme-primary go-to-associated-leads">Associated Leads</a>
                                        </div>
                                        <div class="col-md-6 col-xs-6">
                                            <a href="<?php echo home_url() ?>" class="btn btn-theme-primary"><i class="fa fa-home"></i> Home</a>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 20px;">
                                        <div class="col-md-12">
                                            <div class="wpcf7-response-output" aria-hidden="true">Thank you for requesting a quote through <a href="<?php echo esc_url(home_url()); ?>">YesQuoteIt.com</a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="quote-form-section">
                            <?php echo do_shortcode($custom_field_value); ?>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12 col-xl-12 cls_pop_last col-xs-6">
                        <h4 class="text-center">What to expect ?</h4>
                        <div class="modal-body"> <span class="cls_spn_fnt">Up to 5 quotes per category within 12-24 hours.</span> </div>
                        <p> <img class="img-responsive" src="<?php echo esc_url(get_template_directory_uri()); ?>/images/mail_icon.jpg" style="width:140px;max-width:100%;margin: 0 auto;"></p>
                        <div class="modal-body"> <span class="cls_spn_fnt">Quotes from qualified businesses that match your requirements.</span> </div>
                        <p> <img class="img-responsive" src="<?php echo esc_url(get_template_directory_uri()); ?>/images/icon-ups-check.png" style="width:100px;max-width:100%;margin: 0 auto;"></p>
                        <div class="modal-footer cls_mdl_ftr1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php get_footer(); ?>

<script>
    var lead_id = '<?php echo get_the_ID() ?>';
    $('input[name="is_lead"]').val(lead_id);

    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('wpcf7mailsent', function(event) {
            document.getElementById('custom-success-message-for-lead').style.display = 'block';
            document.getElementById('quote-form-section').style.display = 'none';
        }, false);
    });

    $('.go-to-associated-leads').on('click', function() {
        $('#quoteModalWizard').modal('hide');
        $('html, body').animate({
            scrollTop: ($(".associated-leads-container").offset().top - 50)
        }, 2000);
    });

    $('#quoteModalWizard').on('hidden.bs.modal', function () {
        document.getElementById('custom-success-message-for-lead').style.display = 'none';
            document.getElementById('quote-form-section').style.display = 'block';
    });

</script>