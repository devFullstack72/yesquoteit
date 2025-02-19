<?php get_header(); ?>
<style type="text/css">
    .htlfndr-under-header {
        display: none; /* Hide under-header section */
    }

    /* Full-width featured image container */
    .lead-generation-featured-container {
        position: relative;
        width: 100%;
        max-height: 700px; /* Adjust as needed */
        overflow: hidden;
    }

    /* Ensure the featured image covers the section */
    .lead-generation-featured-image img {
        width: 100%;
        height: 600px; /* Adjust based on design */
        object-fit: cover;
    }

    /* Overlay form container */
    .cs-lp-form {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        background: rgba(0, 0, 0, 0.6); /* Semi-transparent background */
        padding: 50px;
        color: white;
        width:50%;
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


    .cls_searchhotl #htlfndr-input-5 > input{
        background: #08c1da none repeat scroll 0 0;
        border: 2px solid #08c1da;
        line-height: 20px;
        padding: 0 15px;
        width: auto;
        border-radius: 5px;height: 38px;
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
.htlfndr-slider-title{
    font-size: 30px;
    font-family: "Roboto", sans-serif
}
</style>
<?php 

// Get search parameters from URL
$selected_category = isset($_GET['htlfndr-category']) ? sanitize_text_field($_GET['htlfndr-category']) : '';
$search_query = isset($_GET['htlfndr-search']) ? sanitize_text_field($_GET['htlfndr-search']) : '';

$slug = $selected_category; // Replace with actual slug

$term = get_term_by('slug', $slug, 'lead_category');

$term_link = get_term_link($term);

if (!empty($search_query)) {
    $term_link .= '?search_query=' . $search_query;
}

if (!empty($term_link)) {
    echo "<script>window.location.href = '" . esc_url($term_link) . "';</script>";
    exit;
}

$category_id = $term->term_id ?? 0;

$categories = get_terms(array(
    'taxonomy'   => 'lead_category',
    'parent'     => $category_id,
    'hide_empty' => false, // Show categories even if no leads
));

$category_image = get_term_meta($category_id, 'lead_category_image', true);

// Default image agar koi category image na ho
if (!$category_image) {
    $category_image = 'https://www.jqueryscript.net/demo/Responsive-Full-Width-jQuery-Image-Slider-Plugin-skdslider/slides/1.jpg';
}
?>
<!-- Featured Image Section with Overlay -->
<div class="lead-generation-featured-container">
   
    <div class="lead-generation-featured-image">
        <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($term->name ?? ''); ?>" />
    </div>
    
     <!-- Overlay Form -->
    <div class="cs-lp-form">
        <div class="cls_htlfinder-align desktop_cls_htlfinder">
            <h1 id="head_text" class="htlfndr-slider-title cls_tit1-cnt">Get the right <?php echo single_term_title(); ?> leads for your needs.</h1><br>
            <div class="cls_under cls_center-under">
                <div class="htlfndr-slider-under-title-line"></div>
            </div>
        </div>
        <?php if (empty($search_query) && !empty($categories)) {  ?>
            <div class="cls_searchhotl cs-cont-btn">
                <!-- Search form aside start -->
                <aside class="htlfndr-form-in-slider htlfndr-search-form-inline" style="background-color: transparent;">
                    <div class="container">
                        <!-- <h5>Where are you looking for?</h5> -->
                            <form action="<?php echo esc_url(get_post_type_archive_link('lead_generation')); ?>" method="GET" name="search-lead" id="search-lead" class="htlfndr-search-form">
                                <div id="htlfndr-input-category" class="htlfndr-input-wrapper">
                                    <label for="htlfndr-category" class="sr-only">Select Category</label>
                                    <select name="htlfndr-category" id="htlfndr-category" class="htlfndr-dropdown">
                                        <option value="">Select Category</option>
                                        <?php
                                        $categories = get_terms(array(
                                            'taxonomy'   => 'lead_category',
                                            'parent'   => $category_id,
                                            'hide_empty' => false,
                                        ));
                                        if (!empty($categories) && !is_wp_error($categories)) {
                                            foreach ($categories as $category) {
                                                echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <span id="category-error" class="error-message" style="color: red; display: none;">Please select a category or enter a search lead text.</span>
                                </div>

                                <div id="htlfndr-input-5" style="margin:1px;margin-right: 10px;width: auto !important;">
                                    <input type="submit" value="Go" />
                                </div>

                                <div id="htlfndr-input-search" class="htlfndr-input-wrapper">
                                    <input type="text" name="htlfndr-search" id="htlfndr-search" class="search-hotel-input" placeholder="Search by lead name" />
                                </div>

                                <div id="htlfndr-input-5" style="margin:1px;width: auto !important;">
                                    <input type="submit" value="Search" />
                                </div>
                            </form>


                    </div><!-- .container -->
                </aside><!-- .htlfndr-form-in-slider.container-fluid -->
            </div>
        <?php } ?>

    </div>
</div>

<!-- Search form aside stop -->
<div class="container" style="margin-bottom:100px;">
    
    <h2 class="htlfndr-section-title bigger-title" id="lead-generation-content-details"><?php single_term_title(); ?> Leads</h2>
    <div class="htlfndr-section-under-title-line"></div>
    
    <div class="row">
        <?php echo do_shortcode('[category_generation_cards category="' . $category_id . '" search="' . $search_query . '"]');  ?>
    </div> <!-- .row -->
</div> <!-- .container -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("scroll-to-content-btn").addEventListener("click", function (event) {
            event.preventDefault(); // Prevent any default behavior

            var targetElement = document.getElementById("lead-generation-content-details");
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 50, // Adjust for fixed header
                    behavior: "smooth" // Enables smooth scrolling
                });
            }
        });
    });
</script>
<?php get_footer(); ?>
