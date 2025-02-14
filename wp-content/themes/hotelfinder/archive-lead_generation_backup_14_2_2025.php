<?php
get_header(); 

// Get search parameters from URL
$selected_category = isset($_GET['htlfndr-category']) ? sanitize_text_field($_GET['htlfndr-category']) : '';
$search_query = isset($_GET['htlfndr-search']) ? sanitize_text_field($_GET['htlfndr-search']) : '';

// Pagination setup
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// Query arguments
$args = array(
    'post_type'      => 'lead_generation',
    'paged'         => $paged,
    'posts_per_page' => 12,
    'orderby'       => 'date',
    'order'         => 'DESC',
    's'             => $search_query, // Search by lead title
);

// Filter by category if selected
if (!empty($selected_category)) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'lead_category',
            'field'    => 'slug',
            'terms'    => $selected_category,
        ),
    );
}

// Execute query
$query = new WP_Query($args);
?>

<div class="container archive-container" style="margin-bottom:100px;">
    <div class="row">
        <?php 
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                $post_link = get_permalink();
                $title = get_the_title();
                ?>

                <div class="col-sm-4 col-xs-6">
                    <div class="htlfndr-category-box">
                        <img src="<?php echo esc_url($image); ?>" height="311" width="370" alt="<?php echo esc_attr($title); ?>" />
                        <div class="category-description">
                            <h2 class="subcategory-name"><?php echo esc_html($title); ?></h2>
                            <a href="<?php echo esc_url($post_link); ?>" class="htlfndr-category-permalink"></a>
                            <h5 class="category-name"><?php echo esc_html($title); ?></h5>
                        </div>
                    </div>
                </div>

                <?php
            endwhile;
        else :
            echo '<p>No matching leads found.</p>';
        endif;
        ?>

        <!-- Pagination -->
        <div class="pagination">
            <?php
            echo paginate_links(array(
                'prev_text' => __('« Prev'),
                'next_text' => __('Next »'),
                'total'     => $query->max_num_pages,
                'current'   => $paged,
            ));
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
