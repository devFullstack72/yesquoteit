<?php
/**
 * Neve functions.php file
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      17/08/2018
 *
 * @package Neve
 */

define( 'NEVE_VERSION', '4.0.1' );
define( 'NEVE_INC_DIR', trailingslashit( get_template_directory() ) . 'inc/' );
define( 'NEVE_ASSETS_URL', trailingslashit( get_template_directory_uri() ) . 'assets/' );
define( 'NEVE_MAIN_DIR', get_template_directory() . '/' );
define( 'NEVE_BASENAME', basename( NEVE_MAIN_DIR ) );
define( 'NEVE_PLUGINS_DIR', plugin_dir_path( dirname( __DIR__ ) ) . 'plugins/' );

if ( ! defined( 'NEVE_DEBUG' ) ) {
	define( 'NEVE_DEBUG', false );
}
define( 'NEVE_NEW_DYNAMIC_STYLE', true );
/**
 * Buffer which holds errors during theme inititalization.
 *
 * @var WP_Error $_neve_bootstrap_errors
 */
global $_neve_bootstrap_errors;

$_neve_bootstrap_errors = new WP_Error();

if ( version_compare( PHP_VERSION, '7.0' ) < 0 ) {
	$_neve_bootstrap_errors->add(
		'minimum_php_version',
		sprintf(
		/* translators: %s message to upgrade PHP to the latest version */
			__( "Hey, we've noticed that you're running an outdated version of PHP which is no longer supported. Make sure your site is fast and secure, by %1\$s. Neve's minimal requirement is PHP%2\$s.", 'neve' ),
			sprintf(
			/* translators: %s message to upgrade PHP to the latest version */
				'<a href="https://wordpress.org/support/upgrade-php/">%s</a>',
				__( 'upgrading PHP to the latest version', 'neve' )
			),
			'7.0'
		)
	);
}
/**
 * A list of files to check for existance before bootstraping.
 *
 * @var array Files to check for existance.
 */

$_files_to_check = defined( 'NEVE_IGNORE_SOURCE_CHECK' ) ? [] : [
	NEVE_MAIN_DIR . 'vendor/autoload.php',
	NEVE_MAIN_DIR . 'style-main-new.css',
	NEVE_MAIN_DIR . 'assets/js/build/modern/frontend.js',
	NEVE_MAIN_DIR . 'assets/apps/dashboard/build/dashboard.js',
	NEVE_MAIN_DIR . 'assets/apps/customizer-controls/build/controls.js',
];
foreach ( $_files_to_check as $_file_to_check ) {
	if ( ! is_file( $_file_to_check ) ) {
		$_neve_bootstrap_errors->add(
			'build_missing',
			sprintf(
			/* translators: %s: commands to run the theme */
				__( 'You appear to be running the Neve theme from source code. Please finish installation by running %s.', 'neve' ), // phpcs:ignore WordPress.Security.EscapeOutput
				'<code>composer install --no-dev &amp;&amp; yarn install --frozen-lockfile &amp;&amp; yarn run build</code>'
			)
		);
		break;
	}
}
/**
 * Adds notice bootstraping errors.
 *
 * @internal
 * @global WP_Error $_neve_bootstrap_errors
 */
function _neve_bootstrap_errors() {
	global $_neve_bootstrap_errors;
	printf( '<div class="notice notice-error"><p>%1$s</p></div>', $_neve_bootstrap_errors->get_error_message() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ( $_neve_bootstrap_errors->has_errors() ) {
	/**
	 * Add notice for PHP upgrade.
	 */
	add_filter( 'template_include', '__return_null', 99 );
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	add_action( 'admin_notices', '_neve_bootstrap_errors' );

	return;
}

/**
 * Themeisle SDK filter.
 *
 * @param array $products products array.
 *
 * @return array
 */
function neve_filter_sdk( $products ) {
	$products[] = get_template_directory() . '/style.css';

	return $products;
}

add_filter( 'themeisle_sdk_products', 'neve_filter_sdk' );
add_filter(
	'themeisle_sdk_compatibilities/' . NEVE_BASENAME,
	function ( $compatibilities ) {

		$compatibilities['NevePro'] = [
			'basefile'  => defined( 'NEVE_PRO_BASEFILE' ) ? NEVE_PRO_BASEFILE : '',
			'required'  => '2.9',
			'tested_up' => '3.0',
		];

		return $compatibilities;
	}
);
require_once 'globals/migrations.php';
require_once 'globals/utilities.php';
require_once 'globals/hooks.php';
require_once 'globals/sanitize-functions.php';
require_once get_template_directory() . '/start.php';

/**
 * If the new widget editor is available,
 * we re-assign the widgets to hfg_footer
 */
if ( neve_is_new_widget_editor() ) {
	/**
	 * Re-assign the widgets to hfg_footer
	 *
	 * @param array  $section_args The section arguments.
	 * @param string $section_id The section ID.
	 * @param string $sidebar_id The sidebar ID.
	 *
	 * @return mixed
	 */
	function neve_customizer_custom_widget_areas( $section_args, $section_id, $sidebar_id ) {
		if ( strpos( $section_id, 'widgets-footer' ) ) {
			$section_args['panel'] = 'hfg_footer';
		}

		return $section_args;
	}

	add_filter( 'customizer_widgets_section_args', 'neve_customizer_custom_widget_areas', 10, 3 );
}

require_once get_template_directory() . '/header-footer-grid/loader.php';

add_filter(
	'neve_welcome_metadata',
	function() {
		return [
			'is_enabled' => ! defined( 'NEVE_PRO_VERSION' ),
			'pro_name'   => 'Neve Pro Addon',
			'logo'       => get_template_directory_uri() . '/assets/img/dashboard/logo.svg',
			'cta_link'   => tsdk_translate_link( tsdk_utmify( 'https://themeisle.com/themes/neve/upgrade/?discount=LOYALUSER582&dvalue=50', 'neve-welcome', 'notice' ), 'query' ),
		];
	}
);

add_filter( 'themeisle_sdk_enable_telemetry', '__return_true' );


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

    // Check if there are posts
    if ( $query->have_posts() ) {
    $output = '<div class="lead-generation-cards-wrapper">';

    while ( $query->have_posts() ) {
        $query->the_post();
        $image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
        $post_link = get_permalink();

        // Start clickable card container
        $output .= '<a href="' . esc_url( $post_link ) . '" class="lead-generation-card" style="text-decoration: none; color: inherit;">';

        // Display image
        if ( $image ) {
            $output .= '<div class="lead-generation-card-image"><img src="' . esc_url( $image ) . '" alt="' . esc_attr( get_the_title() ) . '"></div>';
        }

        // Display title
        $output .= '<div class="lead-generation-card-content">';
        $output .= '<h3 class="lead-generation-card-title" style="text-align:center;">' . get_the_title() . '</h3>';
        $output .= '</div>'; // Close card-content

        $output .= '</a>'; // Close clickable card
    }

    $output .= '</div>'; // Close cards wrapper

    wp_reset_postdata();
    return $output;
} else {
    return '<p>No Lead Generation posts found.</p>';
}

}
add_shortcode( 'lead_generation_cards', 'lead_generation_cards_shortcode' );


function lead_generation_first_card_shortcode( $atts ) {
   
    // WP Query to fetch Lead Generation posts
    $args = array(
	    'post_type'      => 'lead_generation',  // Custom Post Type
	    'posts_per_page' => 1,
	    'post_status'    => 'publish',  // Only show published posts
	    'orderby'        => 'post_date',  // Order by post date (including time)
	    'order'          => 'ASC',    // Descending order to get the latest post first
	);

    $query = new WP_Query( $args );

    // Check if there are posts
    if ( $query->have_posts() ) {
        $output = '<div class="lead-generation-cards-wrapper wp-block-columns are-vertically-aligned-center is-layout-flex wp-container-core-columns-is-layout-7 wp-block-columns-is-layout-flex">';

        // Get the first post (since we limited posts to 1)
        $query->the_post();

        // Get featured image
        $image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );

        // Start card container
        $output .= '<div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);flex-basis:50%">';

        // Display image
        $image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        if ( $image ) {
	    $output .= '<figure class="wp-block-image size-full has-custom-border is-style-default"><img decoding="async" src="' . esc_url( $image ) . '" alt="' . get_the_title() . '" style="border-radius:16px"></figure>';
			}

        $output .= '</div>'; // Close first column

        // Add title and description
        $output .= '<div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);flex-basis:50%">';
        $output .= '<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>';
        $output .= '<h2 class="wp-block-heading has-text-align-left has-neve-text-color-color has-text-color">' . get_the_title() . '</h2>';
        $output .= '<p class="has-text-align-left has-neve-text-color-color has-text-color" style="font-size:17px">' . get_the_excerpt() . '</p>';
        
        // Add button
        $output .= '<div class="wp-block-buttons has-custom-font-size has-small-font-size is-content-justification-left is-layout-flex wp-container-core-buttons-is-layout-2 wp-block-buttons-is-layout-flex">';
        $output .= '<div class="wp-block-button has-custom-font-size is-style-default" style="font-size:16px"><a class="wp-block-button__link has-nv-text-dark-bg-color has-neve-link-color-background-color has-text-color has-background has-link-color wp-element-button" href="' . get_permalink() . '">Learn more</a></div>';
        $output .= '</div>'; // Close button wrapper

        $output .= '</div>'; // Close second column
        $output .= '</div>'; // Close cards wrapper

        wp_reset_postdata(); // Reset post data

        return $output; // Return the generated HTML
    } else {
        return '<p>No Lead Generation posts found.</p>';
    }
}
add_shortcode( 'lead_generation_first_card', 'lead_generation_first_card_shortcode' );


// Get last Lead 

function lead_generation_last_card_shortcode() {
    // WP Query to fetch Lead Generation posts
	$args = array(
	    'post_type'      => 'lead_generation',  // Custom Post Type
	    'posts_per_page' => 1,
	    'post_status'    => 'publish',  // Only show published posts
	    'orderby'        => 'post_date',  // Order by post date (including time)
	    'order'          => 'DESC',    // Descending order to get the latest post first
	);

    $query = new WP_Query( $args );

    // Check if there are posts
    if ( $query->have_posts() ) {
        $output = '<div class="wp-block-columns are-vertically-aligned-center is-layout-flex wp-container-core-columns-is-layout-8 wp-block-columns-is-layout-flex">';

        // Get the first post (which will be the latest record because of DESC order)
        $query->the_post();

        // Get featured image
        $image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );

        // First column for title and description
        $output .= '<div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);flex-basis:50%">';
        $output .= '<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>';

        // Display title and description
        $output .= '<h2 class="wp-block-heading has-text-align-left has-neve-text-color-color has-text-color">' . get_the_title() . '</h2>';
        $output .= '<p class="has-text-align-left has-neve-text-color-color has-text-color" style="font-size:17px">' . get_the_excerpt() . '</p>';
        
        // Add button
        $output .= '<div class="wp-block-buttons has-custom-font-size has-small-font-size is-content-justification-left is-layout-flex wp-container-core-buttons-is-layout-3 wp-block-buttons-is-layout-flex">';
        $output .= '<div class="wp-block-button has-custom-font-size is-style-default" style="font-size:16px"><a class="wp-block-button__link has-nv-text-dark-bg-color has-neve-link-color-background-color has-text-color has-background has-link-color wp-element-button" href="' . get_permalink() . '">Learn more</a></div>';
        $output .= '</div>'; // Close button wrapper

        $output .= '</div>'; // Close first column

        // Second column for the image
        $output .= '<div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);flex-basis:50%">';
        
        $image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
        // Display image
        if ( $image ) {
	    $output .= '<figure class="wp-block-image size-full has-custom-border is-style-default"><img decoding="async" src="' . esc_url( $image ) . '" alt="' . get_the_title() . '" style="border-radius:16px"></figure>';
			}

        $output .= '</div>'; // Close second column

        $output .= '</div>'; // Close columns wrapper

        wp_reset_postdata(); // Reset post data

        return $output; // Return the generated HTML
    } else {
        return '<p>No Lead Generation posts found.</p>';
    }
}
add_shortcode( 'lead_generation_last_card', 'lead_generation_last_card_shortcode' );

// Modify the excerpt length (optional)
function custom_excerpt_length($length) {
    return 100; // Set the length to your desired word count, e.g., 20
}
add_filter('excerpt_length', 'custom_excerpt_length');

// Remove the "Read More" link in excerpts
function remove_read_more_link($more) {
    return '...'; // Return an empty string to remove the "Read More" link
}
add_filter('excerpt_more', 'remove_read_more_link');

function enqueue_custom_styles() {
    // Check if we are on the homepage or a page that needs the custom styles
    if ( is_front_page() || is_page() || is_single()) {
    	wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/style-custom.css', array(), time() );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_styles' );
