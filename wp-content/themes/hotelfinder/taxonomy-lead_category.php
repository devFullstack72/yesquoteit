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
</div>
<div class="container" style="margin-bottom:100px;">
    <h2 class="htlfndr-section-title bigger-title"><?php single_term_title(); ?> Leads</h2>
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

<?php get_footer(); ?>
