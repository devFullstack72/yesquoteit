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


