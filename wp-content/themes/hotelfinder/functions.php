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

// Enqueue wp head assets
function theme_enqueue_styles() {
    // Bootstrap
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '5.3.0', 'all');

    // Main styles
    wp_enqueue_style('main-style', get_template_directory_uri() . '/css/style.css', array(), '1.0', 'all');

    // IE styles
    wp_enqueue_style('ie-style', get_template_directory_uri() . '/css/ie.css', array(), '1.0', 'all');

    // Font Awesome
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '6.0.0', 'all');

    // OWL Carousel
    wp_enqueue_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), '2.3.4', 'all');

    // jQuery UI
    wp_enqueue_style('jquery-ui', get_template_directory_uri() . '/css/jquery-ui.css', array(), '1.12.1', 'all');

    // Enqueue the main style sheet
    wp_enqueue_style('mytheme-style', get_stylesheet_uri());

}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

function theme_enqueue_scripts() {
    // jQuery is already included in WordPress, so load it from WP
    wp_enqueue_script('jquery');

    // Enqueue custom stylesheets or JavaScript files
    wp_enqueue_script('mytheme-script', get_template_directory_uri() . '/js/mytheme.js', array('jquery'), null, true);

    // Load custom scripts
    wp_enqueue_script('jquery-ui', get_template_directory_uri() . '/js/jquery-ui.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-ui-touch', get_template_directory_uri() . '/js/jquery.ui.touch-punch.min.js', array('jquery-ui'), null, true);
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), null, true);
    wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), null, true);
    wp_enqueue_script('custom-script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_scripts');
// End Enqueue wp head assets

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
            'taxonomies'   => array('lead_category'),
        )
    );
}
add_action( 'init', 'create_posttype_lead_generation' );

// function lead_generation_cards_shortcode( $atts ) {
//     // Set default attributes
//     $atts = shortcode_atts(
//         array(
//             'posts_per_page' => 6, // Default number of posts to show
//         ),
//         $atts,
//         'lead_generation_cards'
//     );

//     // WP Query to fetch Lead Generation posts
//     $args = array(
//         'post_type'      => 'lead_generation',  // Custom Post Type
//         'posts_per_page' => $atts['posts_per_page'],
//         'post_status'    => 'publish',  // Only show published posts
//     );

//     $query = new WP_Query( $args );

//     // Start output buffer
//     $output = '';

//     if ( $query->have_posts() ) :
//         $output .= '<div class="row">';
        
//         while ( $query->have_posts() ) : $query->the_post();
//             $image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
//             $post_link = get_permalink();
//             $title = get_the_title();

//             // Output card HTML
//             $output .= '<div class="col-sm-4 col-xs-6">';
//             $output .= '<div class="htlfndr-category-box" onclick="void(0)">'; // The "onclick" is used for Safari (IOS)
//             $output .= '<img src="' . esc_url( $image ) . '"  alt="' . esc_attr( $title ) . '" />';
//             $output .= '<div class="category-description">';
//             $output .= '<h3 class="subcategory-name">' . esc_html( $title ) . '</h3>';
//             $output .= '<a href="' . esc_url( $post_link ) . '" class="htlfndr-category-permalink"></a>'; // Link for overlay
//             $output .= '<h5 class="category-name">' . esc_html( $title ) . '</h5>'; // This can be dynamic if needed
//             //$output .= '<p class="category-properties"><span>374</span> properties</p>';
//             $output .= '</div>'; // .category-description
//             $output .= '</div>'; // .htlfndr-category-box
//             $output .= '</div>'; // .col-sm-4 .col-xs-6
//         endwhile;

//         $output .= '</div>'; // .row lead-generation-cards-wrapper
//     endif;

//     wp_reset_postdata();
//     return $output;
// }


// add_shortcode( 'lead_generation_cards', 'lead_generation_cards_shortcode' );

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

// Adding Leads Categories

function create_lead_categories_taxonomy() {
    register_taxonomy(
        'lead_category',
        array('lead_generation'), // Ensure this matches your post type
        array(
            'label'             => __('Lead Categories'),
            'rewrite'           => array('slug' => 'lead-category'),
            'hierarchical'      => true, // Allows parent-child relationships
            'show_admin_column' => true,
            'show_ui'           => true, // Ensure UI is enabled
            'show_in_menu'      => true, // Show in admin menu
        )
    );
}
add_action('init', 'create_lead_categories_taxonomy');


// Category Image add

// Add Image Upload Field in Lead Categories
function add_lead_category_image_field($term) {
    $category_id = !empty($term->term_id) ? $term->term_id : '';
    $image_id = get_term_meta($category_id, 'lead_category_image', true);
    $category_page_title = get_term_meta($category_id, 'category_page_title', true);
    $category_page_leads_section_title = get_term_meta($category_id, 'category_page_leads_section_title', true);
    ?>
    <table class="form-table">
        <tr class="form-field form-required term-name-wrap">
            <th scope="row">
                <label for="lead_category_image"><?php _e('Category Image', 'text-domain'); ?></label>
            </th>
            <td>
                <input type="text" id="lead_category_image" name="lead_category_image" value="<?php echo esc_attr($image_id); ?>" />
                <button class="upload_image_button button"><?php _e('Upload Image', 'text-domain'); ?></button>
                <br>
                <?php if ($image_id) { ?>
                    <img src="<?php echo esc_url($image_id); ?>" style="max-width: 200px; display: block; margin-top: 10px;">
                <?php } ?>
            </td>
        </tr>
        <tr class="form-field form-required term-name-wrap">
            <th scope="row">
                <label for="category_page_title"><?php _e('Page Title', 'text-domain'); ?></label>
            </th>
            <td>
                <input type="text" id="category_page_title" name="category_page_title" value="<?php echo esc_attr($category_page_title); ?>" />
            </td>
        </tr>
        <tr class="form-field form-required term-name-wrap">
            <th scope="row">
                <label for="category_page_leads_section_title"><?php _e('Leads section title', 'text-domain'); ?></label>
            </th>
            <td>
                <input type="text" id="category_page_leads_section_title" name="category_page_leads_section_title" value="<?php echo esc_attr($category_page_leads_section_title); ?>" />
            </td>
        </tr>
    </table>

    <?php
}
add_action('lead_category_add_form_fields', 'add_lead_category_image_field', 10);
add_action('lead_category_edit_form_fields', 'add_lead_category_image_field', 10);


// Save Category Image Field
function save_lead_category_custom_fields($term_id) {
    if (isset($_POST['lead_category_image'])) {
        update_term_meta($term_id, 'lead_category_image', esc_url($_POST['lead_category_image']));
    }
    if (isset($_POST['category_page_title'])) {
        update_term_meta($term_id, 'category_page_title', sanitize_text_field($_POST['category_page_title']));
    }
    if (isset($_POST['category_page_leads_section_title'])) {
        update_term_meta($term_id, 'category_page_leads_section_title', sanitize_text_field($_POST['category_page_leads_section_title']));
    }
}
add_action('edited_lead_category', 'save_lead_category_custom_fields', 10, 2);
add_action('created_lead_category', 'save_lead_category_custom_fields', 10, 2);


function enqueue_admin_scripts($hook) {
    if ('edit-tags.php' === $hook || 'term.php' === $hook) { // Only for taxonomy pages
        wp_enqueue_media(); // Ensures WordPress Media Uploader is loaded
        wp_enqueue_script('jquery'); // Ensure jQuery is loaded
    }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');

// Enqueue Media Uploader
function enqueue_media_uploader() {
    wp_enqueue_media();
    ?>
    <script>
        jQuery(document).ready(function($){
            $('.upload_image_button').click(function(e) {
                e.preventDefault();
                var button = $(this);
                var file_frame = wp.media({
                    title: 'Select Category Image',
                    library: { type: 'image' },
                    button: { text: 'Use this image' },
                    multiple: false
                }).open().on('select', function(){
                    var attachment = file_frame.state().get('selection').first().toJSON();
                    button.prev('input').val(attachment.url);
                    button.next('img').attr('src', attachment.url).show();
                });
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'enqueue_media_uploader');

// Category Image add

function lead_generation_cards_shortcode($atts) {
    // Set default attributes
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 6, // Default number of posts to show
        ),
        $atts,
        'lead_generation_cards'
    );

    $output = '<div class="container" style="margin:0;"><div class="row">'; // Single row for both categories & leads

    // Get all categories from custom taxonomy "lead_category"
    $categories = get_terms(array(
        'taxonomy'   => 'lead_category',
        'hide_empty' => false, // Show categories even if no leads
    ));

    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            $category_link  = get_term_link($category);
            $category_name  = esc_html($category->name);
            $category_image = get_term_meta($category->term_id, 'lead_category_image', true);

            // ✅ Fix: Set default image if no category image found
            if (!$category_image) {
                $category_image = 'http://localhost/wordpress/wp-content/uploads/2025/02/1581618767-1-1024x683.jpeg';
            }

            // Generate category HTML
            $output .= '<div class="col-sm-4 col-xs-6">';
            $output .= '<div class="htlfndr-category-box">';
            $output .= '<img src="' . esc_url($category_image) . '" alt="' . esc_attr($category_name) . '" />';
            $output .= '<div class="category-description">';
            $output .= '<h3 class="subcategory-name">' . $category_name . '</h3>';
            $output .= '<h5 class="category-name">' . $category_name . '</h3>';
            $output .= '<a href="' . esc_url($category_link) . '" class="htlfndr-category-permalink"></a>';
            $output .= '</div>'; // .category-description
            $output .= '</div>'; // .htlfndr-category-box
            $output .= '</div>'; // .col-sm-4
        }
    }

    // WP Query to fetch Uncategorized Leads
    $args = array(
        'post_type'      => 'lead_generation',  
        'posts_per_page' => $atts['posts_per_page'],
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'lead_category',
                'operator' => 'NOT EXISTS' // Fetch leads with NO category
            ),
        ),
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $post_link = get_permalink();
            $title = get_the_title();

            // ✅ Fix: Set default image if no featured image found
            if (!$image) {
                $image = 'http://localhost/wordpress/wp-content/uploads/2025/02/1581618767-1-1024x683.jpeg';
            }

            $output .= '<div class="col-sm-4 col-xs-6">';
            $output .= '<div class="htlfndr-category-box">';
            $output .= '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" />';
            $output .= '<div class="category-description">';
            $output .= '<h3 class="subcategory-name">' . esc_html($title) . '</h3>';
            $output .= '<h5 class="category-name">' . esc_html($title) . '</h3>';
            $output .= '<a href="' . esc_url($post_link) . '" class="htlfndr-category-permalink"></a>';
            $output .= '</div>'; // .category-description
            $output .= '</div>'; // .htlfndr-category-box
            $output .= '</div>'; // .col-sm-4
        }
    }

    $output .= '</div></div>'; // Close Row & Container

    wp_reset_postdata();
    return $output;
}

add_shortcode('lead_generation_cards', 'lead_generation_cards_shortcode');



function category_generation_cards_shortcode($atts) {
   
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 6, // Default number of posts to show
            'category' => !empty($atts['category']) ? intval($atts['category']) : 0,
            'search'         => !empty($atts['search']) ? sanitize_text_field($atts['search']) : '',
        ),
        $atts,
        'category_generation_cards'
    );

    $output = '<div class="container" style="margin:0;"><div class="row">'; // Single row for both categories & leads

    // If search is provided, fetch leads directly
    if (!empty($atts['search'])) {
        $args = array(
            'post_type'      => 'lead_generation',
            'posts_per_page' => $atts['posts_per_page'],
            'post_status'    => 'publish',
            's'             => $atts['search'], // Apply search query
        );

        // Apply category filter only if a category is selected
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'lead_category',
                    'field'    => 'term_id',  // Ensure category filter works correctly
                    'terms'    => $atts['category'],
                ),
            );
        }

    } else {

         // If no search, check for categories
         $categories = get_terms(array(
            'taxonomy'   => 'lead_category',
            'parent'     => $atts['category'],
            'hide_empty' => false,
        ));

        if (!empty($categories) && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                $category_link  = get_term_link($category);
                $category_name  = esc_html($category->name);
                $category_image = get_term_meta($category->term_id, 'lead_category_image', true);
    
                // ✅ Fix: Set default image if no category image found
                if (!$category_image) {
                    $category_image = 'http://localhost/wordpress/wp-content/uploads/2025/02/1581618767-1-1024x683.jpeg';
                }
    
                // Generate category HTML
                $output .= '<div class="col-sm-4 col-xs-6">';
                $output .= '<div class="htlfndr-category-box">';
                $output .= '<img src="' . esc_url($category_image) . '" alt="' . esc_attr($category_name) . '" />';
                $output .= '<div class="category-description">';
                $output .= '<h3 class="subcategory-name">' . $category_name . '</h3>';
                $output .= '<h5 class="category-name">' . $category_name . '</h3>';
                $output .= '<a href="' . esc_url($category_link) . '" class="htlfndr-category-permalink"></a>';
                $output .= '</div>'; // .category-description
                $output .= '<div class="default_title_cat">' . $category_name . '</div>';
                $output .= '</div>'; // .htlfndr-category-box
                $output .= '</div>'; // .col-sm-4
            }
        }

        // Fetch Leads If No Categories Found
        if (empty($categories)) {
            $args = array(
                'post_type'      => 'lead_generation',
                'posts_per_page' => $atts['posts_per_page'],
                'post_status'    => 'publish',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'lead_category',
                        'field'    => 'term_id',
                        'terms'    => $atts['category'],
                    ),
                ),
            );
        }
    }

    // Get all categories from custom taxonomy "lead_category"
    // $categories = get_terms(array(
    //     'taxonomy'   => 'lead_category',
    //     'parent'     => !empty($atts['category']) ? intval($atts['category']) : 0,
    //     'hide_empty' => false, // Show categories even if no leads
    // ));


    
    // if (empty($categories)) {
    //     // WP Query to fetch Uncategorized Leads
    //     $args = array(
    //         'post_type'      => 'lead_generation',  
    //         'posts_per_page' => $atts['posts_per_page'],
    //         'post_status'    => 'publish',
    //         's'             => $atts['search'],
    //         'term_taxonomy_id' => $atts['category'],
    //         'tax_query'      => array(
    //             array(
    //                 'taxonomy' => 'lead_category',
    //                 'field'    => 'term_id',   // Match with term ID
    //                 'terms'    => $atts['category'], // Get specific category
    //             ),
    //         ),
    //     );

    //     $query = new WP_Query($args);

    //     if ($query->have_posts()) {
    //         while ($query->have_posts()) {
    //             $query->the_post();
    //             $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
    //             $post_link = get_permalink();
    //             $title = get_the_title();

    //             // ✅ Fix: Set default image if no featured image found
    //             if (!$image) {
    //                 $image = 'http://localhost/wordpress/wp-content/uploads/2025/02/1581618767-1-1024x683.jpeg';
    //             }

    //             $output .= '<div class="col-sm-4 col-xs-6">';
    //             $output .= '<div class="htlfndr-category-box">';
    //             $output .= '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" />';
    //             $output .= '<div class="category-description">';
    //             $output .= '<h3 class="subcategory-name">' . esc_html($title) . '</h3>';
    //             $output .= '<h5 class="category-name">' . esc_html($title) . '</h3>';
    //             $output .= '<a href="' . esc_url($post_link) . '" class="htlfndr-category-permalink"></a>';
    //             $output .= '</div>'; // .category-description
    //             $output .= '</div>'; // .htlfndr-category-box
    //             $output .= '</div>'; // .col-sm-4
    //         }
    //     }
    // }

    if (isset($args)) {
        // Execute WP Query for Leads
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                $post_link = get_permalink();
                $title = get_the_title();

                if (!$image) {
                    $image = 'http://localhost/wordpress/wp-content/uploads/2025/02/1581618767-1-1024x683.jpeg';
                }

                $output .= '<div class="col-sm-4 col-xs-6">';
                $output .= '<div class="htlfndr-category-box">';
                $output .= '<img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" />';
                $output .= '<div class="category-description">';
                $output .= '<h3 class="subcategory-name">' . esc_html($title) . '</h3>';
                $output .= '<h5 class="category-name">' . esc_html($title) . '</h5>';
                $output .= '<a href="' . esc_url($post_link) . '" class="htlfndr-category-permalink"></a>';
                $output .= '</div>';
                $output .= '<div class="default_title_cat">' . $title . '</div>';
                $output .= '</div></div>';
            }
        }
    }

    $output .= '</div></div>';
    wp_reset_postdata();
    return $output;

    $output .= '</div></div>'; // Close Row & Container

    wp_reset_postdata();
    return $output;
}


add_shortcode('category_generation_cards', 'category_generation_cards_shortcode');


function add_lead_generation_meta_box() {
    add_meta_box(
        'lead_form_shortcode_meta_box', 
        'Lead Form Shortcode', 
        'render_lead_form_shortcode_meta_box', 
        'lead_generation',  // Custom post type slug
        'side', 
        'default'
    );
}
add_action('add_meta_boxes', 'add_lead_generation_meta_box');

function render_lead_form_shortcode_meta_box($post) {
    // Retrieve the current meta value
    $shortcode_value = get_post_meta($post->ID, '_lead_form_short_code', true);
    ?>
    <label for="lead_form_shortcode_input">Enter Shortcode:</label>
    <input type="text" id="lead_form_shortcode_input" name="lead_form_shortcode_input" value="<?php echo esc_attr($shortcode_value); ?>" style="width: 100%;">
    <?php
}

function save_lead_form_shortcode_meta($post_id) {
    if (array_key_exists('lead_form_shortcode_input', $_POST)) {
        update_post_meta($post_id, '_lead_form_short_code', sanitize_text_field($_POST['lead_form_shortcode_input']));
    }
}
add_action('save_post', 'save_lead_form_shortcode_meta');

function lead_generation_add_custom_meta_box() {
    add_meta_box(
        'lead_title_meta_box',
        'Title', // Title of the new section
        'lead_title_meta_box_callback',
        'lead_generation', // Change if your post type is different
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'lead_generation_add_custom_meta_box');

function lead_title_meta_box_callback($post) {
    $custom_text = get_post_meta($post->ID, '_lead_title_text', true);
    ?>
    <p>
        <label for="lead_title_input">Enter Title:</label>
        <textarea id="lead_title_input" name="lead_title_input" rows="3" style="width:100%;"><?php echo esc_textarea($custom_text); ?></textarea>
    </p>
    <?php
}

function lead_generation_save_custom_meta_box_data($post_id) {
    if (array_key_exists('lead_title_input', $_POST)) {
        update_post_meta($post_id, '_lead_title_text', sanitize_textarea_field($_POST['lead_title_input']));
    }
}
add_action('save_post', 'lead_generation_save_custom_meta_box_data');

function lead_generation_add_associated_leads_meta_box() {
    add_meta_box(
        'associated_leads_meta_box',
        'Associated Leads', // Title of the section
        'associated_leads_meta_box_callback',
        'lead_generation', // Post type
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'lead_generation_add_associated_leads_meta_box');

function associated_leads_meta_box_callback($post) {
    // Get all lead_generation posts
    $args = [
        'post_type'      => 'lead_generation',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'exclude'        => [$post->ID], // Exclude the current post from the list
    ];
    $leads = get_posts($args);

    // Get previously selected associated leads (comma-separated)
    $selected_leads = get_post_meta($post->ID, '_associated_leads', true);
    $selected_leads_array = !empty($selected_leads) ? explode(',', $selected_leads) : [];

    if ($leads) {
        echo '<p><strong>Select Associated Leads:</strong></p>';
        foreach ($leads as $lead) {
            $checked = in_array($lead->ID, $selected_leads_array) ? 'checked' : '';
            echo '<p>';
            echo '<input type="checkbox" name="associated_leads[]" value="' . esc_attr($lead->ID) . '" ' . $checked . '>';
            echo '<label>' . esc_html($lead->post_title) . '</label>';
            echo '</p>';
        }
    } else {
        echo '<p>No other leads available.</p>';
    }
}

function save_associated_leads_meta_boxes($post_id) {
    // Verify nonce to prevent CSRF
    if (!isset($_POST['post_ID']) || !wp_verify_nonce($_POST['_wpnonce'], 'update-post_' . $post_id)) {
        return;
    }

    // Save the associated leads as a comma-separated string
    if (isset($_POST['associated_leads'])) {
        $associated_leads = implode(',', array_map('sanitize_text_field', $_POST['associated_leads']));
        update_post_meta($post_id, '_associated_leads', $associated_leads);
    } else {
        delete_post_meta($post_id, '_associated_leads'); // Remove meta if no checkboxes are selected
    }
}
add_action('save_post', 'save_associated_leads_meta_boxes');


function add_lead_email_template_metabox() {
    add_meta_box(
        'lead_email_template',
        'Select Email Templates',
        'render_lead_email_template_metabox',
        'lead_generation', // Change this to your actual CPT name
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_lead_email_template_metabox');

function render_lead_email_template_metabox($post) {
    // Get selected templates from post meta
    $selected_customer_template = get_post_meta($post->ID, '_lead_customer_email_template', true);
    $selected_provider_template = get_post_meta($post->ID, '_lead_provider_email_template', true);

    // Fetch available YeeMail templates
    $email_templates = get_posts([
        'post_type'   => 'yeemail_template', // Adjust post type if different
        'numberposts' => -1
    ]);

    echo '<label for="lead_customer_email_template"><strong>Customer Email Template:</strong></label>';
    echo '<select name="lead_customer_email_template" id="lead_customer_email_template">';
    echo '<option value="">Select a template</option>';
    
    foreach ($email_templates as $template) {
        $selected = ($template->ID == $selected_customer_template) ? 'selected' : '';
        echo '<option value="' . esc_attr($template->ID) . '" ' . $selected . '>' . esc_html($template->post_title) . '</option>';
    }

    echo '</select><br><br>';

    echo '<label for="lead_provider_email_template"><strong>Provider Email Template:</strong></label>';
    echo '<select name="lead_provider_email_template" id="lead_provider_email_template">';
    echo '<option value="">Select a template</option>';
    
    foreach ($email_templates as $template) {
        $selected = ($template->ID == $selected_provider_template) ? 'selected' : '';
        echo '<option value="' . esc_attr($template->ID) . '" ' . $selected . '>' . esc_html($template->post_title) . '</option>';
    }

    echo '</select>';
}

function save_lead_email_templates($post_id) {
    if (isset($_POST['lead_customer_email_template'])) {
        update_post_meta($post_id, '_lead_customer_email_template', sanitize_text_field($_POST['lead_customer_email_template']));
    }

    if (isset($_POST['lead_provider_email_template'])) {
        update_post_meta($post_id, '_lead_provider_email_template', sanitize_text_field($_POST['lead_provider_email_template']));
    }
}
add_action('save_post', 'save_lead_email_templates');

function google_places_form_shortcode($atts) {
    $atts = shortcode_atts([
        'address' => '',  // Default value
        'fields' => 'true',  // Default value
    ], $atts, 'google_places_form'); // 'google_places_form' is the shortcode name

    // Get the values
    $default_address = esc_attr($atts['address']);
    $fields = esc_attr($atts['fields']);
    ob_start();
    $extra_fields_type = ($fields == 'true') ? 'text' : 'hidden';
    ?>
    <div class="address-wrapper">
        <div class="address-container">
            <input type="<?php echo $extra_fields_type ?>" id="autocomplete_shortcode" name="google_places_form_address" value="<?php echo $default_address;  ?>" placeholder="Enter address" />
            <input type="<?php echo $extra_fields_type ?>" id="street_number" name="google_places_form_street_number" placeholder="Street Number" readonly />
            <input type="<?php echo $extra_fields_type ?>" id="route" name="google_places_form_street" placeholder="Street Name" readonly />
            <input type="<?php echo $extra_fields_type ?>" id="locality" name="google_places_form_city" placeholder="City" readonly />
            <input type="<?php echo $extra_fields_type ?>" id="administrative_area_level_1" name="google_places_form_state" placeholder="State" readonly />
            <input type="<?php echo $extra_fields_type ?>" id="postal_code" placeholder="Postal Code" name="google_places_form_postalcode" readonly />
            <input type="<?php echo $extra_fields_type ?>" id="country" placeholder="Country" name="google_places_form_country" readonly />

            <!-- Hidden Latitude and Longitude Fields -->
            <input type="hidden" id="latitude" name="google_places_form_latitude" />
            <input type="hidden" id="longitude" name="google_places_form_longitude" />
        </div>
        <div id="map"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('google_places_form', 'google_places_form_shortcode');


function load_google_maps_api() {

    $current_page_slug = get_post_field('post_name', get_queried_object_id()); // Get the current page slug

    wp_enqueue_script('google-places', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyADTn5LfNUzzbgxNd-TFiNbVwAf0JNoNBw&libraries=places', array(), null, true);

    if ($current_page_slug !== "register-your-business") {
        wp_enqueue_script('custom-places-script', get_template_directory_uri() . '/js/custom-places.js', array('jquery', 'google-places'), null, true);
    }

}

add_action('wp_enqueue_scripts', 'load_google_maps_api');

function enqueue_custom_styles() {
    wp_enqueue_style('custom-places-css', get_template_directory_uri() . '/css/custom-places.css');
    wp_enqueue_style('custom-style-css', get_template_directory_uri() . '/css/custom-style.css');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

add_filter('wpcf7_form_elements', function ($content) {
    return do_shortcode($content);
});

// Copy Lead 

function add_duplicate_lead_action_link($actions, $post) {
    if ($post->post_type == 'lead_generation') {
        $actions['duplicate'] = '<a href="' . wp_nonce_url(admin_url('admin-post.php?action=duplicate_lead&post_id=' . $post->ID), 'duplicate_lead_' . $post->ID) . '" title="Duplicate this lead">Copy Lead</a>';
    }
    return $actions;
}
add_filter('post_row_actions', 'add_duplicate_lead_action_link', 10, 2);

function duplicate_lead() {
    if (!isset($_GET['post_id']) || !isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'duplicate_lead_' . $_GET['post_id'])) {
        wp_die('Security check failed.');
    }

    $post_id = absint($_GET['post_id']);
    $post = get_post($post_id);

    if (!$post || $post->post_type !== 'lead_generation') {
        wp_die('Invalid post type.');
    }

    // Create new lead post
    $new_post = [
        'post_title'   => $post->post_title . ' (Copy)',
        'post_content' => $post->post_content,
        'post_status'  => 'draft',
        'post_type'    => 'lead_generation',
        'post_author'  => get_current_user_id(),
    ];

    $new_post_id = wp_insert_post($new_post);

    if ($new_post_id) {
        // Copy all metadata
        $meta_data = get_post_custom($post_id);
        foreach ($meta_data as $meta_key => $meta_values) {
            foreach ($meta_values as $meta_value) {
                update_post_meta($new_post_id, $meta_key, maybe_unserialize($meta_value));
            }
        }

        // Copy categories and taxonomy terms
        $taxonomies = get_object_taxonomies('lead_generation');
        foreach ($taxonomies as $taxonomy) {
            $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
            wp_set_object_terms($new_post_id, $terms, $taxonomy);
        }

        // Copy featured image
        $thumbnail_id = get_post_thumbnail_id($post_id);
        if ($thumbnail_id) {
            set_post_thumbnail($new_post_id, $thumbnail_id);
        }

        // Redirect to edit screen of the new post
        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    }
}
add_action('admin_post_duplicate_lead', 'duplicate_lead');
// Copy Lead 

function custom_site_info_menu() {
    add_menu_page(
        'Site Information',       // Page title
        'Site Information',       // Menu title
        'manage_options',         // Capability
        'site-information',       // Menu slug
        'custom_site_info_page',  // Callback function
        'dashicons-admin-site',   // Icon
        20                        // Position
    );
}
add_action('admin_menu', 'custom_site_info_menu');

function custom_site_info_page() {
    ?>
    <div class="wrap">
        <h1>Site Information</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('site_info_settings');
            do_settings_sections('site-information');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function custom_site_info_settings() {
    register_setting('site_info_settings', 'partner_contact_form_shortcode');
    register_setting('site_info_settings', 'customer_email');
    register_setting('site_info_settings', 'provider_email');
    register_setting('site_info_settings', 'twilio_account_sid');
    register_setting('site_info_settings', 'twilio_auth_token');
    register_setting('site_info_settings', 'twilio_number');

    add_settings_section(
        'site_info_section',
        '<h3 style="margin: 0;">Settings</h3>',
        function() {
            echo '';
        },
        'site-information'
    );

    add_settings_field(
        'partner_contact_form_shortcode',
        'Partner Contact Form Shortcode',
        function() {
            $value = get_option('partner_contact_form_shortcode', '');
            echo '<input type="text" name="partner_contact_form_shortcode" value="' . esc_attr($value) . '" class="regular-text">';
        },
        'site-information',
        'site_info_section'
    );

    add_settings_field(
        'lead_email_subject',
        '<h3 style="margin: 0;">Lead Email Subject</h3>',
        function() {
            echo '';
        },
        'site-information',
        'site_info_section'
    );

    add_settings_field(
        'customer_email',
        'Customer Email',
        function() {
            $value = get_option('customer_email', '');
            echo '<input type="text" name="customer_email" value="' . esc_attr($value) . '" class="regular-text">';
        },
        'site-information',
        'site_info_section'
    );

    add_settings_field(
        'provider_email',
        'Provider Email',
        function() {
            $value = get_option('provider_email', '');
            echo '<input type="text" name="provider_email" value="' . esc_attr($value) . '" class="regular-text">';
        },
        'site-information',
        'site_info_section'
    );

    // Twilio Account Details

    add_settings_field(
        'twilio_account_details',
        '<h3 style="margin: 0;">Twilio Account Details</h3>',
        function() {
            echo '';
        },
        'site-information',
        'site_info_section'
    );

    add_settings_field(
        'twilio_account_sid',
        'Twilio Account SID',
        function() {
            $value = get_option('twilio_account_sid', '');
            echo '<input type="text" name="twilio_account_sid" value="' . esc_attr($value) . '" class="regular-text">';
        },
        'site-information',
        'site_info_section'
    );

    add_settings_field(
        'twilio_auth_token',
        'Twilio Auth Token',
        function() {
            $value = get_option('twilio_auth_token', '');
            echo '<input type="password" name="twilio_auth_token" value="' . esc_attr($value) . '" class="regular-text">';
        },
        'site-information',
        'site_info_section'
    );

    add_settings_field(
        'twilio_number',
        'Twilio Number',
        function() {
            $value = get_option('twilio_number', '');
            echo '<input type="text" name="twilio_number" value="' . esc_attr($value) . '" class="regular-text">';
        },
        'site-information',
        'site_info_section'
    );
}
add_action('admin_init', 'custom_site_info_settings');


function custom_slider_menu() {
    add_menu_page(
        'Home Page Settings', 
        'Home Page Settings', 
        'manage_options', 
        'home-page-settings', 
        'custom_slider_settings_page', 
        'dashicons-images-alt2', 
        20
    );
}
add_action('admin_menu', 'custom_slider_menu');

function custom_slider_settings_page() {
    $slider_items = get_option('custom_slider_items', []);

    // Handle form submission
    if (isset($_POST['save_slider'])) {
        $new_slider_items = [];

        if (!empty($_POST['slider_title'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');

            foreach ($_POST['slider_title'] as $index => $title) {
                $title = sanitize_text_field($title);
                $existing_image = $_POST['existing_slider_image'][$index] ?? '';

                if (!empty($_FILES['slider_image']['name'][$index])) {
                    $file = [
                        'name'     => $_FILES['slider_image']['name'][$index],
                        'type'     => $_FILES['slider_image']['type'][$index],
                        'tmp_name' => $_FILES['slider_image']['tmp_name'][$index],
                        'error'    => $_FILES['slider_image']['error'][$index],
                        'size'     => $_FILES['slider_image']['size'][$index]
                    ];

                    $upload_overrides = ['test_form' => false];
                    $movefile = wp_handle_upload($file, $upload_overrides);

                    if ($movefile && !isset($movefile['error'])) {
                        $image_url = esc_url($movefile['url']);
                    } else {
                        $image_url = $existing_image; // Keep the old image if upload fails
                    }
                } else {
                    $image_url = $existing_image; // Keep the old image if no new image is uploaded
                }

                $new_slider_items[] = [
                    'title' => $title,
                    'image' => $image_url
                ];
            }
        }

        update_option('custom_slider_items', $new_slider_items);
        echo '<div class="updated"><p>Settings saved.</p></div>';

        // Redirect after saving to reflect changes
        echo '<script>window.location.href = window.location.href;</script>';
        exit;
    }
    ?>

    <div class="wrap">
        <h2>Home Page Slider Settings</h2>
        <form method="POST" enctype="multipart/form-data">
            <table class="form-table" id="slider-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($slider_items)) : ?>
                        <?php foreach ($slider_items as $index => $slide) : ?>
                            <tr>
                                <td>
                                    <input type="text" name="slider_title[]" value="<?php echo esc_attr($slide['title']); ?>" class="regular-text">
                                </td>
                                <td>
                                    <input type="file" name="slider_image[]">
                                    <input type="hidden" name="existing_slider_image[]" value="<?php echo esc_attr($slide['image']); ?>">
                                    <?php if (!empty($slide['image'])) : ?>
                                        <br><img src="<?php echo esc_url($slide['image']); ?>" width="100">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="remove-slide button">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <button type="button" id="add-slide" class="button button-secondary">Add New Slide</button>
            <br><br>
            <?php submit_button('Save Changes', 'primary', 'save_slider'); ?>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('add-slide').addEventListener('click', function () {
                let table = document.getElementById('slider-table').getElementsByTagName('tbody')[0];
                let row = table.insertRow();
                
                let cell1 = row.insertCell(0);
                let cell2 = row.insertCell(1);
                let cell3 = row.insertCell(2);

                cell1.innerHTML = '<input type="text" name="slider_title[]" class="regular-text">';
                cell2.innerHTML = '<input type="file" name="slider_image[]"><input type="hidden" name="existing_slider_image[]" value="">';
                cell3.innerHTML = '<button type="button" class="remove-slide button">Remove</button>';

                row.querySelector('.remove-slide').addEventListener('click', function () {
                    row.remove();
                });
            });

            document.querySelectorAll('.remove-slide').forEach(button => {
                button.addEventListener('click', function () {
                    this.closest('tr').remove();
                });
            });
        });
    </script>

    <?php
}

function add_copy_action_link($actions, $post) {
    if ($post->post_type == 'yeemail_template') {
        $copy_url = admin_url('admin-post.php?action=duplicate_email_template&post_id=' . $post->ID);
        $actions['copy'] = '<a href="' . esc_url($copy_url) . '" title="Duplicate this template">Copy</a>';
    }
    return $actions;
}
add_filter('post_row_actions', 'add_copy_action_link', 10, 2);

function duplicate_email_template() {
    if (!isset($_GET['post_id']) || !current_user_can('edit_posts')) {
        wp_die('Invalid request.');
    }

    global $wpdb;

    $post_id = intval($_GET['post_id']);
    $post = get_post($post_id);

    if (!$post || $post->post_type != 'yeemail_template') {
        wp_die('Template not found.');
    }

    // Step 1: Copy the post (title only)
    $new_post = array(
        'post_title'   => $post->post_title . ' (Copy)',
        'post_status'  => 'draft',
        'post_type'    => 'yeemail_template',
        'post_author'  => get_current_user_id(),
    );

    $new_post_id = wp_insert_post($new_post);

    if ($new_post_id) {
        // Step 2: Copy all metadata (content, settings, etc.)
        $meta_data = get_post_meta($post_id);
        foreach ($meta_data as $key => $values) {
            foreach ($values as $value) {
                add_post_meta($new_post_id, $key, esc_sql($value));
            }
        }

        // Step 4: Redirect to the new duplicated template
        wp_redirect(admin_url("post.php?post=$new_post_id&action=edit"));
        exit;
    } else {
        wp_die('Error duplicating template.');
    }
}

add_action('admin_post_duplicate_email_template', 'duplicate_email_template');

function refresh_meta_after_duplicate($post_id) {
    delete_post_meta($post_id, '_edit_lock'); // Remove edit lock
    delete_post_meta($post_id, '_edit_last'); // Reset last edit
}
add_action('save_post', 'refresh_meta_after_duplicate');

function send_customer_email() {

    if (!isset($_POST['email']) || !isset($_POST['subject']) || !isset($_POST['message'])) {
        echo "Invalid request.";
        wp_die();
    }

    $to = sanitize_email($_POST['email']);
    $subject = sanitize_text_field($_POST['subject']);
    $message = wp_kses_post($_POST['message']);
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    if (wp_mail($to, $subject, $message, $headers)) {
        echo "Email sent successfully.";
    } else {
        echo "Failed to send email.";
    }


    if (isset($_POST['quote_id'])) {

        global $wpdb;

        $quote_id = intval($_POST['quote_id']);
        $table_name = $wpdb->prefix . "yqit_lead_quotes_partners";

        // Update the status to "Viewed"
        $updated = $wpdb->update(
            $table_name,
            ['status' => 'Responded'],
            ['id' => $quote_id],
            ['%s'],
            ['%d']
        );
    }

    wp_die();
}

add_action('wp_ajax_send_customer_email', 'send_customer_email');
add_action('wp_ajax_nopriv_send_customer_email', 'send_customer_email'); // Allow for non-logged-in users

// Handle AJAX request to delete a partner quote
add_action('wp_ajax_delete_partner_quote', 'delete_partner_quote');
add_action('wp_ajax_nopriv_delete_partner_quote', 'delete_partner_quote'); // If non-logged-in users need access

function delete_partner_quote() {
    global $wpdb;

    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
        wp_send_json_error(['message' => 'Invalid request.']);
    }

    $quote_id = intval($_POST['id']);
    $table_name = $wpdb->prefix . 'yqit_lead_quotes_partners';

    $deleted = $wpdb->delete($table_name, ['id' => $quote_id], ['%d']);

    if ($deleted) {
        wp_send_json_success(['message' => 'Quote deleted successfully.']);
    } else {
        wp_send_json_error(['message' => 'Failed to delete quote.']);
    }
}

add_action('wp_ajax_delete_multiple_quotes', 'delete_multiple_quotes');
add_action('wp_ajax_nopriv_delete_multiple_quotes', 'delete_multiple_quotes'); 

function delete_multiple_quotes() {
    if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
        wp_send_json_error("Invalid request.");
    }

    global $wpdb;
    $ids = array_map('intval', $_POST['ids']);
    $placeholders = implode(',', array_fill(0, count($ids), '%d'));
    $query = "DELETE FROM {$wpdb->prefix}yqit_lead_quotes_partners WHERE id IN ($placeholders)";
    $result = $wpdb->query($wpdb->prepare($query, $ids));

    if ($result) {
        wp_send_json_success("Quotes deleted successfully.");
    } else {
        wp_send_json_error("Failed to delete quotes.");
    }
}


function update_quote_status() {
    // Ensure the request is valid
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        wp_send_json_error(['message' => 'Invalid request']);
    }

    global $wpdb;
    $quote_id = intval($_POST['id']);
    $table_name = $wpdb->prefix . "yqit_lead_quotes_partners";

    // Update the status to "Viewed"
    $updated = $wpdb->update(
        $table_name,
        ['status' => 'Viewed'],
        ['id' => $quote_id],
        ['%s'],
        ['%d']
    );

    if ($updated !== false) {
        wp_send_json_success(['message' => 'Status updated successfully']);
    } else {
        wp_send_json_error(['message' => 'Failed to update status']);
    }
}

// Register AJAX action
add_action('wp_ajax_update_quote_status', 'update_quote_status');
add_action('wp_ajax_nopriv_update_quote_status', 'update_quote_status'); // For non-logged-in users (if required)

function modify_nav_menu($items, $args) {
    // Get the menu object by location
    if ($args->theme_location == 'primary-menu' || $args->theme_location == 'main-menu') {
        foreach ($items as $key => $item) {
            if ($item->title == 'Leads') { // Change this to the menu item name you want'
                if (isset($_SESSION['customer_logged_in'])) {
                    $item->title = 'Your Quotes';
                    $item->url = home_url() . '/customer-requests';
                }
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'modify_nav_menu', 10, 2);


// function create_customer_partner_chat_tables() {
//     global $wpdb;
//     $charset_collate = $wpdb->get_charset_collate();

//     $customer_partner_quote_chat_table = $wpdb->prefix . 'customer_partner_quote_chat';
//     $customer_partner_quote_chat_messages_table = $wpdb->prefix . 'customer_partner_quote_chat_messages';

//     // Create the customer_partner_quote_chat table first
//     $sql1 = "CREATE TABLE IF NOT EXISTS $customer_partner_quote_chat_table (
//         id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//         partner_id BIGINT(20) UNSIGNED NOT NULL,
//         customer_id BIGINT(20) UNSIGNED NOT NULL,
//         lead_id BIGINT(20) UNSIGNED NOT NULL,
//         created_at DATETIME DEFAULT CURRENT_TIMESTAMP
//     ) ENGINE=InnoDB $charset_collate;";

//     // Create the customer_partner_quote_chat_messages table
//     $sql2 = "CREATE TABLE IF NOT EXISTS $customer_partner_quote_chat_messages_table (
//         id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//         chat_id BIGINT(20) UNSIGNED NOT NULL,
//         sender_id BIGINT(20) UNSIGNED NOT NULL,
//         receiver_id BIGINT(20) UNSIGNED NOT NULL,
//         sender_type ENUM('partner', 'customer') NOT NULL,
//         receiver_type ENUM('partner', 'customer') NOT NULL,
//         message TEXT NOT NULL,
//         is_read TINYINT(1) DEFAULT 0,
//         created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
//         CONSTRAINT fk_chat FOREIGN KEY (chat_id) 
//         REFERENCES $customer_partner_quote_chat_table(id) 
//         ON DELETE CASCADE ON UPDATE CASCADE
//     ) ENGINE=InnoDB $charset_collate;";

//     require_once ABSPATH . 'wp-admin/includes/upgrade.php';
//     dbDelta($sql1);
//     dbDelta($sql2);

//     // Check if the 'is_read' column exists, and add it if not
//     $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $customer_partner_quote_chat_messages_table LIKE 'is_read'");
//     if (empty($column_exists)) {
//         $wpdb->query("ALTER TABLE $customer_partner_quote_chat_messages_table ADD COLUMN is_read TINYINT(1) DEFAULT 0;");
//     }

//     $column_exists = $wpdb->get_results("SHOW COLUMNS FROM $customer_partner_quote_chat_table LIKE 'is_read'");
//     if (empty($column_exists)) {
//         $wpdb->query("ALTER TABLE $customer_partner_quote_chat_table ADD COLUMN is_read TINYINT(1) DEFAULT 0;");
//     }
// }

// add_action('after_setup_theme', 'create_customer_partner_chat_tables');



add_action('wp_ajax_send_chat_message', 'send_chat_message');
add_action('wp_ajax_nopriv_send_chat_message', 'send_chat_message');

function send_chat_message() {
    global $wpdb;

    $customer_partner_quote_chat_table = $wpdb->prefix . 'customer_partner_quote_chat';
    $customer_partner_quote_chat_messages_table = $wpdb->prefix . 'customer_partner_quote_chat_messages';

    $partner_id = intval($_POST['partner_id']);
    $customer_id = intval($_POST['customer_id']);
    $lead_id = intval($_POST['lead_id']);
    $message = sanitize_text_field($_POST['message']);

    // Ensure chat exists
    $chat_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $customer_partner_quote_chat_table WHERE partner_id = %d AND customer_id = %d AND lead_id = %d",
        $partner_id, $customer_id, $lead_id
    ));

    if (!$chat_id) {
        $wpdb->insert($customer_partner_quote_chat_table, [
            'partner_id'  => $partner_id,
            'customer_id' => $customer_id,
            'lead_id'     => $lead_id,
            'created_at'  => current_time('mysql')
        ]);
        $chat_id = $wpdb->insert_id;
    }

    // Insert message
    $result = $wpdb->insert($customer_partner_quote_chat_messages_table, [
        'chat_id'      => $chat_id,
        'sender_id'    => $partner_id,
        'receiver_id'  => $customer_id,
        'sender_type'  => 'partner',
        'receiver_type'=> 'customer',
        'message'      => $message,
        'created_at'   => current_time('mysql')
    ]);


    if ($result) {

        $message_id = $wpdb->insert_id;

        // send_chat_email_notification($customer_id, $partner_id, $message, 'customer');

        wp_send_json_success([
            "message" => "Message sent",
            "chat_message_id" => $message_id,
            "customer_id" => $customer_id,
            "partner_id" => $partner_id,
            "message_text" => $message,
            "view" => 'customer'
        ]);

        wp_send_json_success(["message" => "Message sent", 'chat_message_id' => $result['id']]);
    } else {
        wp_send_json_error(["message" => "Error sending message"]);
    }
}

function send_chat_notification() {
    $customer_id = intval($_POST['customer_id']);
    $partner_id = intval($_POST['partner_id']);
    $message = sanitize_text_field($_POST['message']);
    $view = sanitize_text_field($_POST['view']);

    // Send email notification
    send_chat_email_notification($customer_id, $partner_id, $message, $view);

    wp_send_json_success(["message" => "Notification sent"]);
}
add_action('wp_ajax_send_chat_notification', 'send_chat_notification');
add_action('wp_ajax_nopriv_send_chat_notification', 'send_chat_notification'); // Allow for non-logged-in users if needed


add_action('wp_ajax_send_to_partner_message', 'send_to_partner_message');
add_action('wp_ajax_nopriv_send_to_partner_message', 'send_to_partner_message');

function send_to_partner_message() {
    global $wpdb;

    $customer_partner_quote_chat_table = $wpdb->prefix . 'customer_partner_quote_chat';
    $customer_partner_quote_chat_messages_table = $wpdb->prefix . 'customer_partner_quote_chat_messages';
    $lead_quotes_partners_table = $wpdb->prefix . 'yqit_lead_quotes_partners';

    $partner_id = intval($_POST['partner_id']);
    $customer_id = intval($_POST['customer_id']);
    $lead_id = intval($_POST['lead_id']);
    $message = sanitize_text_field($_POST['message']);

    // Ensure chat exists
    $chat_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $customer_partner_quote_chat_table WHERE partner_id = %d AND customer_id = %d AND lead_id = %d",
        $partner_id, $customer_id, $lead_id
    ));

    // Insert message
    $result = $wpdb->insert($customer_partner_quote_chat_messages_table, [
        'chat_id'      => $chat_id,
        'sender_id'    => $customer_id,
        'receiver_id'  => $partner_id,
        'sender_type'  => 'customer',
        'receiver_type'=> 'partner',
        'message'      => $message,
        'created_at'   => current_time('mysql')
    ]);

    // send_chat_email_notification($customer_id, $partner_id, $message, 'partner');

    if ($result) {
        
        $message_id = $wpdb->insert_id;

        $current_status = $wpdb->get_var($wpdb->prepare(
            "SELECT status FROM $lead_quotes_partners_table WHERE provider_id = %d AND lead_quote_id = %d",
            $partner_id, $lead_id
        ));

        if ($current_status !== 'Responded') {
            $wpdb->update($lead_quotes_partners_table, 
                ['status' => 'Responded'],
                ['provider_id' => $partner_id, 'lead_quote_id' => $lead_id],
                ['%s'],
                ['%d', '%d']
            );
        }

        wp_send_json_success([
            "message" => "Message sent",
            "chat_message_id" => $message_id,
            "customer_id" => $customer_id,
            "partner_id" => $partner_id,
            "message_text" => $message,
            "view" => 'partner'
        ]);

    } else {
        wp_send_json_error(["message" => "Error sending message"]);
    }
}

function send_chat_email_notification($customer_id, $partner_id, $message, $to = 'partner') {
    global $wpdb;

    // Get customer and partner details
    $customer = $wpdb->get_row($wpdb->prepare(
        "SELECT name, email FROM {$wpdb->prefix}yqit_customers WHERE id = %d",
        $customer_id
    ));

    $partner = $wpdb->get_row($wpdb->prepare(
        "SELECT business_trading_name, email FROM {$wpdb->prefix}service_partners WHERE id = %d",
        $partner_id
    ));

    if (!$customer || !$partner) {
        return; // Exit if no valid user found
    }

    /// Get email template dynamically
    $template_title = ($to == 'partner') ? 'Customer to Partner' : 'Partner to Customer';
    
    $template_post = get_page_by_title($template_title, OBJECT, 'quote_tpl');

    if (!$template_post) {
        return; // Exit if template is not found
    }

    // Fetch the subject from post meta
    $email_subject_template = get_post_meta($template_post->ID, 'email_subject', true);
    if (!$email_subject_template) {
        $email_subject_template = "New Message"; // Fallback subject
    }

    // Replace placeholders in the subject
    $email_subject = str_replace(
        ['{recipient_name}', '{sender_name}'],
        [
            ($to == 'partner') ? $partner->business_trading_name : $customer->name,
            ($to == 'partner') ? $customer->name : $partner->business_trading_name,
        ],
        $email_subject_template
    );

    // Replace placeholders in the email body
    $email_body = str_replace(
        ['{recipient_name}', '{sender_name}', '{message}', '{response_url}'],
        [
            ($to == 'partner') ? $partner->business_trading_name : $customer->name,
            ($to == 'partner') ? $customer->name : $partner->business_trading_name,
            nl2br($message),
            home_url(($to == 'partner') ? '/partner-customer-requests' : '/customer-login')
        ],
        $template_post->post_content
    );

    // Email headers
    $headers = ['Content-Type: text/html; charset=UTF-8'];

    // Determine recipient email
    $recipient_email = ($to == 'partner') ? $partner->email : $customer->email;
    
    // Send email
    wp_mail($recipient_email, $email_subject, $email_body, $headers);
}



// Load Chat Messages
add_action('wp_ajax_load_chat_messages', 'load_chat_messages');
add_action('wp_ajax_nopriv_load_chat_messages', 'load_chat_messages');

function load_chat_messages() {
    global $wpdb;

    $chat_table = $wpdb->prefix . 'customer_partner_quote_chat';
    $messages_table = $wpdb->prefix . 'customer_partner_quote_chat_messages';
    $partners_table = $wpdb->prefix . 'service_partners';
    $customers_table = $wpdb->prefix . 'yqit_customers';

    $partner_id = isset($_POST['partner_id']) ? intval($_POST['partner_id']) : 0;
    $customer_id = isset($_POST['customer_id']) ? intval($_POST['customer_id']) : 0;
    $lead_id = isset($_POST['lead_id']) ? intval($_POST['lead_id']) : 0;
    $view = isset($_POST['view']) ? sanitize_text_field($_POST['view']) : '';

    if (!$partner_id || !$customer_id || !$lead_id) {
        wp_send_json_error(['message' => 'Invalid request parameters']);
        return;
    }

    // Get chat ID
    $chat_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $chat_table WHERE partner_id = %d AND customer_id = %d AND lead_id = %d",
        $partner_id, $customer_id, $lead_id
    ));

    if (!$chat_id) {
        wp_send_json_success('');
        return;
    }

    if ($view == 'customer') {
        $wpdb->query($wpdb->prepare(
            "UPDATE $messages_table 
             SET is_read = 1 
             WHERE chat_id = %d AND sender_type = 'partner' AND is_read = 0",
            $chat_id
        ));

        $wpdb->query($wpdb->prepare(
            "UPDATE $chat_table 
             SET is_read = 1 
             WHERE id = %d AND is_read = 0",
            $chat_id
        ));
    }

    // Fetch partner & customer names
    $partner_name = $wpdb->get_var($wpdb->prepare(
        "SELECT business_trading_name FROM $partners_table WHERE id = %d",
        $partner_id
    )) ?: 'Partner';

    $customer_name = $wpdb->get_var($wpdb->prepare(
        "SELECT name FROM $customers_table WHERE id = %d",
        $customer_id
    )) ?: 'Customer';

    // Get chat messages
    $messages = $wpdb->get_results($wpdb->prepare(
        "SELECT sender_type, message, created_at FROM $messages_table WHERE chat_id = %d ORDER BY created_at ASC",
        $chat_id
    ));

    $output = '<div class="chat-container">';
    
    foreach ($messages as $msg) {
        $is_sender_partner = ($msg->sender_type === "partner");

        // Define alignment & sender name based on view
        if ($view === 'customer') {
            $sender_class = $is_sender_partner ? "received" : "sent";
            $sender_name = $is_sender_partner ? $partner_name : "You";
        } else {
            $sender_class = $is_sender_partner ? "sent" : "received";
            $sender_name = $is_sender_partner ? "You" : $customer_name;
        }

        $formatted_time = date("h:i A", strtotime($msg->created_at));

        $output .= "
            <div class='chat-message $sender_class'>
                <div class='chat-bubble'>
                    <strong>{$sender_name}</strong>
                    <p>" . esc_html($msg->message) . "</p>
                    <span class='chat-time'>{$formatted_time}</span>
                </div>
            </div>
        ";
    }

    $output .= '</div>';
    wp_send_json_success($output);
}

add_action('after_setup_theme', function() {
    function has_chat_messages($partner_id, $customer_id, $lead_id) {
        global $wpdb;
        
        $chat_table = $wpdb->prefix . 'customer_partner_quote_chat';
        $messages_table = $wpdb->prefix . 'customer_partner_quote_chat_messages';

        // Check if chat exists
        $chat_id = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $chat_table WHERE partner_id = %d AND customer_id = %d AND lead_id = %d",
            $partner_id, $customer_id, $lead_id
        ));

        if (!$chat_id) {
            return false; // No chat found
        }

        // Check if messages exist in the chat
        $message_count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $messages_table WHERE chat_id = %d",
            $chat_id
        ));

        return $message_count > 0;
    }

    // function get_quote_message_status($quote_id) {
    //     global $wpdb;
    
    //     $query = $wpdb->prepare(
    //         "SELECT 
    //             COUNT(m.id) AS total_messages,
    //             SUM(CASE WHEN m.is_read = 0 THEN 1 ELSE 0 END) AS unread_messages
    //          FROM wp_yqit_lead_quotes q
    //          LEFT JOIN wp_customer_partner_quote_chat c ON q.id = c.lead_id
    //          LEFT JOIN wp_customer_partner_quote_chat_messages m ON c.id = m.chat_id
    //          WHERE q.id = %d AND m.receiver_type = 'customer'",
    //         $quote_id
    //     );
    
    //     return $wpdb->get_row($query);
    // }

    function get_quote_message_status($quote_id) {
        global $wpdb;
    
        $query = $wpdb->prepare(
            "SELECT 
                COUNT(m.id) AS total_messages,
                SUM(CASE WHEN m.is_read = 0 THEN 1 ELSE 0 END) AS unread_messages,
                COUNT(DISTINCT c.partner_id) AS total_chat_partners,
                COUNT(DISTINCT CASE WHEN c.is_read = 0 THEN c.id END) AS total_unread_chats
             FROM wp_yqit_lead_quotes q
             LEFT JOIN wp_customer_partner_quote_chat c ON q.id = c.lead_id
             LEFT JOIN wp_customer_partner_quote_chat_messages m ON c.id = m.chat_id
             WHERE q.id = %d AND m.receiver_type = 'customer'",
            $quote_id
        );
    
        return $wpdb->get_row($query);
    }
    
});

add_action('wp_ajax_get_chat_details', 'get_customer_chat_details_callback');
add_action('wp_ajax_nopriv_get_chat_details', 'get_customer_chat_details_callback'); // For non-logged-in users

function get_customer_chat_details_callback() {
    global $wpdb;

    $lead_id = isset($_GET['lead_id']) ? intval($_GET['lead_id']) : 0;

    if (!$lead_id) {
        wp_send_json_error(['message' => 'Invalid Lead ID']);
    }

    $chat_table = $wpdb->prefix . 'customer_partner_quote_chat';
    $partners_table = $wpdb->prefix . 'service_partners';
    $messages_table = $wpdb->prefix . 'customer_partner_quote_chat_messages';

    // Fetch chats with partner details and check if any message in the chat is unread
    $chats = $wpdb->get_results($wpdb->prepare("
        SELECT chat.*, partner.business_trading_name AS business_name, partner.business_logo,
               (SELECT COUNT(*) FROM $messages_table WHERE chat_id = chat.id AND is_read = 0 AND receiver_type = 'customer') AS unread_count
        FROM $chat_table AS chat
        LEFT JOIN $partners_table AS partner ON chat.partner_id = partner.id
        WHERE chat.lead_id = %d
    ", $lead_id));

    if (!$chats) {
        wp_send_json_error(['message' => 'No chat found for this lead']);
    }

    // Build HTML
    $popupContent = '<div class="chat-list">';
    foreach ($chats as $chat) {
        $business_logo = esc_url($chat->business_logo);
        $business_name = esc_html($chat->business_name);
        $chat_id = intval($chat->id);
        $unread_count = intval($chat->unread_count);

        if(empty($chat->business_logo)) {
            $business_logo = 'https://eu.ui-avatars.com/api/?name='.$business_name.'&size=250';
        }
        
        // If there is at least one unread message, show green, otherwise show yellow
        $status_class = ($unread_count > 0) ? 'green-button' : 'yellow-button';

        $popupContent .= "
            <div class='chat-item'>
                <img src='$business_logo' alt='Business Logo' class='business-logo'>
                <span class='business-name'>$business_name</span>
                <span class='view-quote $status_class' onclick='openChat($chat->partner_id, $chat->customer_id, $chat->lead_id, \"customer\")'>
                    View Message
                </span>
            </div>
        ";
    }
    $popupContent .= '</div>';

    wp_send_json_success(['html' => $popupContent]);

    wp_die(); // Required for WordPress AJAX
}

// delete_option('default_quote_email_templates_added');

// Register Standard Email Templates Post Type
function register_quote_email_templates_post_type() {
    $args = [
        'labels' => [
            'name'          => 'Standard Email Templates',
            'singular_name' => 'Standard Email Template',
            'add_new'       => 'Add New Template',
            'add_new_item' => 'Add New Email Template',
            'edit_item'     => 'Edit Standard Email Template'
        ],
        'public'       => false,
        'show_ui'      => true,
        'menu_icon'    => 'dashicons-email-alt2',
        'supports'     => ['title', 'editor', 'custom-fields'], // Added custom-fields
    ];
    register_post_type('quote_tpl', $args);
}
add_action('init', 'register_quote_email_templates_post_type');

// Add Default Email Templates
function add_default_quote_email_templates() {
    if (get_option('default_quote_email_templates_added')) return;

    $templates = [
        [
            'title'   => 'Customer to Partner',
            'subject' => 'New Message from {sender_name}', // Dynamic subject
            'content' => '<p>Dear {recipient_name},</p>
                          <p>You have received a message from <strong>{sender_name}</strong>:</p>
                          <blockquote>{message}</blockquote>
                          <p>Please <a href="{response_url}">click here</a> to respond.</p>'
        ],
        [
            'title'   => 'Partner to Customer',
            'subject' => 'New Inquiry from {sender_name}',
            'content' => '<p>Dear {recipient_name},</p>
                          <p>You have received an inquiry from <strong>{sender_name}</strong>:</p>
                          <blockquote>{message}</blockquote>
                          <p>Please <a href="{response_url}">click here</a> to respond.</p>'
        ],
    ];

    foreach ($templates as $template) {
        $existing_posts = get_posts([
            'post_type'   => 'quote_tpl',
            'title'       => $template['title'],
            'post_status' => 'any',
            'numberposts' => 1
        ]);

        if (empty($existing_posts)) {
            $post_id = wp_insert_post([
                'post_type'    => 'quote_tpl',
                'post_title'   => $template['title'],
                'post_content' => $template['content'],
                'post_status'  => 'publish',
            ]);

            if ($post_id) {
                update_post_meta($post_id, 'email_subject', $template['subject']); // Store subject in meta
            }
        }
    }

    update_option('default_quote_email_templates_added', true);
}
add_action('init', 'add_default_quote_email_templates');

// Add Meta Box for Subject in Admin
function add_email_subject_meta_box() {
    add_meta_box(
        'email_subject_meta',
        'Email Subject',
        'email_subject_meta_callback',
        'quote_tpl',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_email_subject_meta_box');

// Meta Box Callback
function email_subject_meta_callback($post) {
    $value = get_post_meta($post->ID, 'email_subject', true);
    echo '<label for="email_subject">Subject: </label>';
    echo '<input type="text" id="email_subject" name="email_subject" value="' . esc_attr($value) . '" style="width:100%;" />';
}

// Save Meta Box Data
function save_email_subject_meta($post_id) {
    if (array_key_exists('email_subject', $_POST)) {
        update_post_meta($post_id, 'email_subject', sanitize_text_field($_POST['email_subject']));
    }
}
add_action('save_post', 'save_email_subject_meta');














