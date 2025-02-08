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
        display: none;
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
        /* Semi-transparent background */
        padding: 50px;
        color: white;
        width: 50%;
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
        font-family: "Montserrat", "Helvetica Neue", Helvetica, Arial, sans-serif
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
            <h1 id="head_text" class="htlfndr-slider-title cls_tit1-cnt">Get the right <?php echo get_the_title(); ?> for your needs.</h1><br>
            <div class="cls_under cls_center-under">
                <div class="htlfndr-slider-under-title-line"></div>
            </div>
        </div>
        <div class="cls_searchhotl cs-cont-btn">
            <div id="htlfndr-input-5">
                <input type="Submit" value="Get a quote now" data-toggle="modal" data-target="#quoteModalWizard">
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
                    echo '<h2 id="lead-generation-content-details" class="htlfndr-section-title htlfndr-lined-title"><span>' . get_the_title() . '</span></h2>';

                    // Display the content of the post
                    echo '<div class="lead-generation-content">';
                    the_content();
                    echo '</div>';
                }
            } else {
                get_template_part('template-parts/content', 'none');
            }

            do_action('neve_after_post_content');
            ?>
        </article>
        <?php do_action('neve_do_sidebar', 'single-lead_generation', 'right'); ?>
    </div>
</div>

<?php if (!empty($custom_field_value)) : ?>
<div class="modal fade" id="quoteModalWizard" tabindex="-1" role="dialog" aria-labelledby="quoteModalWizardLabel">
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
                    <div class="col-md-7">
                        <?php echo do_shortcode($custom_field_value); ?>
                    </div>
                    <div class="col-md-5 col-sm-12 col-xl-12 cls_pop_last">
                        <h4 class="text-center">What to expect ?</h4>
                        <div class="modal-body"> <span class="cls_spn_fnt">Up to 5 quotes per category within 12-24 hours.</span> </div>
                        <p> <img class="img-responsive" src="https://yesquoteit.com/application/css/images/mail_icon.jpg" style="width:140px;max-width:100%;margin: 0 auto;"></p>
                        <div class="modal-body"> <span class="cls_spn_fnt">Quotes from qualified businesses that match your requirements.</span> </div>
                        <p> <img class="img-responsive" src="https://yesquoteit.com/application/css/images/icon-ups.png" style="width:100px;max-width:100%;margin: 0 auto;"></p>
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