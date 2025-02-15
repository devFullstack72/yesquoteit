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
    $image_id = get_term_meta($term->term_id, 'lead_category_image', true);
    ?>
    <div class="form-field">
        <label for="lead_category_image"><?php _e('Category Image', 'text-domain'); ?></label>
        <input type="text" id="lead_category_image" name="lead_category_image" value="<?php echo esc_attr($image_id); ?>" />
        <button class="upload_image_button button"><?php _e('Upload Image', 'text-domain'); ?></button>
        <br>
        <?php if ($image_id) { ?>
            <img src="<?php echo esc_url($image_id); ?>" style="max-width: 200px; display: block; margin-top: 10px;">
        <?php } ?>
    </div>
    <?php
}
add_action('lead_category_add_form_fields', 'add_lead_category_image_field', 10);
add_action('lead_category_edit_form_fields', 'add_lead_category_image_field', 10);


// Save Category Image Field
function save_lead_category_image($term_id) {
    if (isset($_POST['lead_category_image'])) {
        update_term_meta($term_id, 'lead_category_image', esc_url($_POST['lead_category_image']));
    }
}
add_action('edited_lead_category', 'save_lead_category_image', 10, 2);
add_action('created_lead_category', 'save_lead_category_image', 10, 2);


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
                $output .= '</div></div></div>';
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
    ], $atts, 'google_places_form'); // 'google_places_form' is the shortcode name

    // Get the values
    $default_address = esc_attr($atts['address']);
    ob_start();
    ?>
    <div class="address-wrapper">
        <div class="address-container">
            <input type="text" id="autocomplete_shortcode" name="google_places_form_address" placeholder="Enter address" />
            <input type="text" id="street_number" name="google_places_form_street_number" placeholder="Street Number" readonly />
            <input type="text" id="route" name="google_places_form_street" placeholder="Street Name" readonly />
            <input type="text" id="locality" name="google_places_form_city" placeholder="City" readonly />
            <input type="text" id="administrative_area_level_1" name="google_places_form_state" placeholder="State" readonly />
            <input type="text" id="postal_code" placeholder="Postal Code" name="google_places_form_postalcode" readonly />
            <input type="text" id="country" placeholder="Country" name="google_places_form_country" readonly />
        </div>
        <div id="map"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('google_places_form', 'google_places_form_shortcode');

function load_google_maps_api() {
    wp_enqueue_script('google-places', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDuoh4RV3jwuAD72LBq02e3rx4-iZa-wLc&libraries=places', array(), null, true);

    if ( !is_page('Become a Partner') ) {
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