<?php
function mytheme_setup() {
    // Add theme support for title tag and post thumbnails
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    
    // Register main navigation menu
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'mytheme'),
    ));

    // Register widget area if needed
    register_sidebar(array(
        'name'          => __('Sidebar', 'mytheme'),
        'id'            => 'sidebar-1',
        'description'   => __('Main sidebar area', 'mytheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('after_setup_theme', 'mytheme_setup');

// // Enqueue styles and scripts
// function mytheme_enqueue_scripts() {
//     // Enqueue the main style sheet
//     wp_enqueue_style('mytheme-style', get_stylesheet_uri());

//     // Enqueue custom stylesheets or JavaScript files
//     wp_enqueue_script('mytheme-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);

//     // Enqueue Bootstrap styles and scripts (if needed)
//     wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
//     wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), null, true);
// }
// add_action('wp_enqueue_scripts', 'mytheme_enqueue_scripts');


function create_posttype_lead_generation() {
    register_post_type( 'lead_generation',
        array(
            'labels' => array(
                'name' => __( 'Lead Generations' ),
                'singular_name' => __( 'Lead Generation' ),
                'add_new' => __( 'Add New' ),
                'add_new_item' => __( 'Add New Lead Generation' ),
                'edit_item' => __( 'Edit Lead Generation' ),
                'new_item' => __( 'New Lead Generation' ),
                'view_item' => __( 'View Lead Generation' ),
                'search_items' => __( 'Search Lead Generations' ),
                'not_found' => __( 'No Lead Generations found' ),
                'not_found_in_trash' => __( 'No Lead Generations found in Trash' ),
                'all_items' => __( 'All Lead Generations' ),
                'archives' => __( 'Lead Generation Archives' ),
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,  // Optional: Enables Gutenberg block editor
            'rewrite' => array( 'slug' => 'leads' ),
            'supports' => array( 'title', 'editor', 'custom-fields', 'thumbnail' ),
        )
    );
}
add_action( 'init', 'create_posttype_lead_generation' );

function lead_generation_cards_shortcode( $atts ) {
    // Set default attributes
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 6, // Default number of posts to show
        ),
        $atts,
        'lead_generation_cards'
    );

    // WP Query to fetch Lead Generation posts
    $args = array(
        'post_type'      => 'lead_generation',  // Custom Post Type
        'posts_per_page' => $atts['posts_per_page'],
        'post_status'    => 'publish',  // Only show published posts
    );

    $query = new WP_Query( $args );

    // Start output buffer
    $output = '';

    if ( $query->have_posts() ) :
        $output .= '<div class="row">';
        
        while ( $query->have_posts() ) : $query->the_post();
            $image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            $post_link = get_permalink();
            $title = get_the_title();

            // Output card HTML
            $output .= '<div class="col-sm-4 col-xs-6">';
            $output .= '<div class="htlfndr-category-box" onclick="void(0)">'; // The "onclick" is used for Safari (IOS)
            $output .= '<img style="height:320px;" src="' . esc_url( $image ) . '"  alt="' . esc_attr( $title ) . '" />';
            $output .= '<div class="category-description">';
            $output .= '<h3 class="subcategory-name">' . esc_html( $title ) . '</h3>';
            $output .= '<a href="' . esc_url( $post_link ) . '" class="htlfndr-category-permalink"></a>'; // Link for overlay
            $output .= '<h5 class="category-name">' . esc_html( $title ) . '</h5>'; // This can be dynamic if needed
            //$output .= '<p class="category-properties"><span>374</span> properties</p>';
            $output .= '</div>'; // .category-description
            $output .= '</div>'; // .htlfndr-category-box
            $output .= '</div>'; // .col-sm-4 .col-xs-6
        endwhile;

        $output .= '</div>'; // .row lead-generation-cards-wrapper
    endif;

    wp_reset_postdata();
    return $output;
}


add_shortcode( 'lead_generation_cards', 'lead_generation_cards_shortcode' );

function register_my_menus() {
    register_nav_menus(
        array(
            'primary-menu' => __( 'Primary Menu' ),
        )
    );
}
add_action( 'after_setup_theme', 'register_my_menus' );

function my_theme_enqueue_styles() {
    wp_enqueue_style('my-theme-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');


// function register_lead_generation_post_type() {
//     $args = array(
//         'labels' => array(
//             'name' => 'Lead Generations',
//             'singular_name' => 'Lead Generation',
//         ),
//         'public' => true,
//         'supports' => array('title', 'editor', 'thumbnail'), // Ensure 'thumbnail' is added here
//     );
//     register_post_type('lead_generation', $args);
// }
// add_action('init', 'register_lead_generation_post_type');