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
    font-family: "Montserrat", "Helvetica Neue", Helvetica, Arial, sans-serif
}
</style>
<?php 
$term = get_queried_object(); 
$category_image = get_term_meta($term->term_id, 'lead_category_image', true);

// Default image agar koi category image na ho
if (!$category_image) {
    $category_image = 'http://localhost/wordpress/wp-content/uploads/2025/02/1581618767-1-1024x683.jpeg';
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
            <h1 id="head_text" class="htlfndr-slider-title cls_tit1-cnt">Get the right <?php echo single_term_title(); ?> leads for your needs.</h1><br>
            <div class="cls_under cls_center-under">
                <div class="htlfndr-slider-under-title-line"></div>
            </div>
        </div>
        <div class="cls_searchhotl cs-cont-btn">
            <div id="htlfndr-input-5">
                <input id="scroll-to-content-btn" type="Submit" value="Looking for <?php echo single_term_title(); ?> leads">
            </div>
        </div>

    </div>
</div>
<div class="container" style="margin-bottom:100px;">
    <h2 class="htlfndr-section-title bigger-title" id="lead-generation-content-details"><?php single_term_title(); ?> Leads</h2>
    <div class="htlfndr-section-under-title-line"></div>
    
    <div class="row">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
                $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                $post_link = get_permalink();
                $title = get_the_title();
        ?>
                <div class="col-sm-4 col-xs-6">
                    <div class="htlfndr-category-box">
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" />
                        <div class="category-description">
                            <h3 class="subcategory-name"><?php echo esc_html($title); ?></h3>
                            <a href="<?php echo esc_url($post_link); ?>" class="htlfndr-category-permalink"></a>
                        </div>
                    </div>
                </div>
        <?php
            endwhile;
        else :
            echo '<p class="text-center">No leads found in this category.</p>';
        endif;
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
