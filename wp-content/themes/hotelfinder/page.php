<?php get_header(); ?>

<?php 
    // Get the current page slug
    $page_slug = get_post_field( 'post_name', get_the_ID() );
?>

<div class="container <?php echo ($page_slug == 'about-us') ? 'about-us-page' : ''; ?>">
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
