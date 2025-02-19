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
$term = get_queried_object(); 


$categories = get_terms(array(
    'taxonomy'   => 'lead_category',
    'parent'     => $term->term_id,
    'hide_empty' => false, // Show categories even if no leads
));

$category_image = get_term_meta($term->term_id, 'lead_category_image', true);
$category_page_title = get_term_meta($term->term_id, 'category_page_title', true);
$category_page_leads_section_title = get_term_meta($term->term_id, 'category_page_leads_section_title', true);

// Default image agar koi category image na ho
if (!$category_image) {
    $category_image = 'https://www.jqueryscript.net/demo/Responsive-Full-Width-jQuery-Image-Slider-Plugin-skdslider/slides/1.jpg';
}
?>
<!-- Featured Image Section with Overlay -->
<div class="lead-generation-featured-container">
    <div class="lead-generation-featured-image">
        <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($term->name); ?>" />
    </div>
     <!-- Overlay Form -->
    <div class="cs-lp-form">
        <div class="cls_htlfinder-align desktop_cls_htlfinder">
            <h1 id="head_text" class="htlfndr-slider-title cls_tit1-cnt">
                <?php if (!empty($category_page_title)): ?>
                    <?php echo $category_page_title ?>
                <?php else: ?>
                    Get the right <?php echo single_term_title(); ?> leads for your needs.
                <?php endif; ?>
            </h1><br>
            <div class="cls_under cls_center-under">
                <div class="htlfndr-slider-under-title-line"></div>
            </div>
        </div>
        <div class="cls_searchhotl cs-cont-btn">
            <!-- <div id="htlfndr-input-5">
                <input id="scroll-to-content-btn" type="Submit" value="Looking for <?php echo single_term_title(); ?> leads">
            </div> -->
            <?php if (!empty($categories)) {  ?>
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
                                        'parent'   => $term->term_id,
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

                            <div id="htlfndr-input-5" style="margin:1px;margin-right: 10px;">
                                <input type="submit" value="Go" />
                            </div>

                            <div id="htlfndr-input-search" class="htlfndr-input-wrapper">
                                <input type="text" name="htlfndr-search" id="htlfndr-search" class="search-hotel-input" placeholder="Search by lead name" />
                            </div>

                            <div id="htlfndr-input-5" style="margin:1px;">
                                <input type="submit" value="Search" />
                            </div>
                        </form>


                </div><!-- .container -->
            </aside><!-- .htlfndr-form-in-slider.container-fluid -->
            <?php } ?>

        </div>
    </div>

 

</div>


<!-- Search form aside stop -->
<div class="container" style="margin-bottom:100px;">
    
    <h2 class="htlfndr-section-title bigger-title" id="lead-generation-content-details">
        <?php if (!empty($category_page_leads_section_title)): ?>
            <?php echo $category_page_leads_section_title ?>
        <?php else: ?>
            <?php single_term_title(); ?> Leads
        <?php endif; ?>
    </h2>
    <div class="htlfndr-section-under-title-line"></div>
    
    <div class="row">

    

        <?php echo do_shortcode('[category_generation_cards category="' . $term->term_id . '"]');  ?>


         <?php
        // if (have_posts()) :
        //     while (have_posts()) : the_post();
        //         $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        //         $post_link = get_permalink();
        //         $title = get_the_title();
        ?>
                <!-- <div class="col-sm-4 col-xs-6">
                    <div class="htlfndr-category-box">
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" />
                        <div class="category-description">
                            <h3 class="subcategory-name"><?php echo esc_html($title); ?></h3>
                            <h5 class="category-name"><?php echo esc_html($title); ?></h3>
                            <a href="<?php echo esc_url($post_link); ?>" class="htlfndr-category-permalink"></a>
                        </div>
                    </div>
                </div> -->
        <?php
        //     endwhile;
        // else :
        //     echo '<p class="text-center">No leads found in this category.</p>';
        // endif;
        ?> 
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
