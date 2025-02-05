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
            'rewrite' => array( 'slug' => 'lead-generation' ),
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

        // Loop through the posts
        while ( $query->have_posts() ) {
            $query->the_post();

            // Get featured image
            $image = get_the_post_thumbnail_url( get_the_ID(), 'medium' );

            // Start card container
            $output .= '<div class="lead-generation-card">';
            
            // Display image
            if ( $image ) {
                $output .= '<div class="lead-generation-card-image"><img src="' . esc_url( $image ) . '" alt="' . get_the_title() . '"></div>';
            }

            // Display title and link to the single post page
            $output .= '<div class="lead-generation-card-content">';
            $output .= '<h3 class="lead-generation-card-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
            $output .= '</div>'; // Close card-content

            $output .= '</div>'; // Close card container
        }

        $output .= '</div>'; // Close cards wrapper

        wp_reset_postdata(); // Reset post data

        return $output; // Return the generated HTML
    } else {
        return '<p>No Lead Generation posts found.</p>';
    }
}
add_shortcode( 'lead_generation_cards', 'lead_generation_cards_shortcode' );


function enqueue_custom_styles() {
    // Check if we are on the homepage or a page that needs the custom styles
    if ( is_front_page() || is_page() ) {
    	wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/style-custom.css', array(), time() );
    }
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_styles' );
