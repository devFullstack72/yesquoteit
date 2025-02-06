<?php get_header(); ?>

<main role="main">
    
     <?php
    while ( have_posts() ) :
        the_post();
        the_content(); // Display page content
    endwhile;
    ?>

<?php get_footer(); ?>
