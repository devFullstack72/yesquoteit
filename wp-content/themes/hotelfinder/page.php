<?php get_header(); ?>

<?php 
    // Get the current page slug
    $page_slug = get_post_field( 'post_name', get_the_ID() );
    $page_class = $page_slug;
    if ($page_slug == 'aboutus') {
        $page_class = 'about-us-page';
    }
?>

<div class="container <?php echo $page_class; ?>">
    <?php 
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                the_content();
            }
        }
    ?>
</div>

<?php get_footer(); ?>
