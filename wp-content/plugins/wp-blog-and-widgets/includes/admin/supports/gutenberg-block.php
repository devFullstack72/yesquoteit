<?php
/**
 * Blocks Initializer
 * 
 * @package WP Blog and Widgets
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function wpbaw_register_guten_block() {

	// Block Editor Script
	wp_register_script( 'wpbaw-block-js', WPBAW_URL.'assets/js/blocks.build.js', array( 'wp-blocks', 'wp-block-editor', 'wp-i18n', 'wp-element', 'wp-components' ), WPBAW_VERSION, true );
	wp_localize_script( 'wpbaw-block-js', 'Wpbawf_Block', array(
																'pro_demo_link'		=> 'https://demo.essentialplugin.com/prodemo/pro-blog-and-widgets-plugin-demo/',
																'free_demo_link'	=> 'https://demo.essentialplugin.com/blog-demo/',
																'pro_link'			=> WPBAW_PLUGIN_LINK_UNLOCK,
															));

	// Register block and explicit attributes for grid
	register_block_type( 'wpbaw/blog', array(
		'attributes' => array(
			'grid' => array(
			 				'type'		=> 'number',
							'default'	=> 0,
			 			),
			'show_author' => array(
			 				'type'		=> 'boolean',
			 				'default'	=> true,
			 			),
			'show_date' => array(
			 				'type'		=> 'boolean',
			 				'default'	=> true,
			 			),
			'show_category_name' => array(
			 				'type'		=> 'boolean',
			 				'default'	=> true,
			 			),
			'show_content' => array(
			 				'type'		=> 'boolean',
			 				'default'	=> true,
			 			),
			'show_full_content' => array(
			 				'type'		=> 'boolean',
			 				'default'	=> false,
			 			),
			'content_words_limit' => array(
			 				'type'		=> 'number',
			 				'default'	=> 20,
			 		),
			'limit' => array(
			 				'type'		=> 'number',
			 				'default'	=> 10,
			 			),
			'orderby' => array(
			 				'type'		=> 'string',
			 				'default'	=> 'date',
			 			),
			'order' => array(
			 				'type'		=> 'string',
			 				'default'	=> 'desc',
			 			),
			'category' => array(
			 				'type'		=> 'string',
			 				'default'	=> '',
			 			),
			'pagination_type' => array(
			 				'type'		=> 'string',
			 				'default'	=> 'numeric',
			 			),
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
		),
		'render_callback' => 'wpbaw_get_blog_post',
	));

	//Register block, and explicitly define the attributes for slider
	register_block_type( 'wpbaw/recent-blog-post', array(
		'attributes' => array(
			'grid' => array(
							'type'		=> 'number',
							'default'	=> 0,
						),
			'show_author' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_date' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_category_name' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_content' => array(
							'type'		=> 'boolean',
							'default'	=> true,
						),
			'show_full_content' => array(
							'type'		=> 'boolean',
							'default'	=> false,
						),
			'content_words_limit' => array(
							'type'		=> 'number',
							'default'	=> 20,
						),
			'limit' => array(
							'type'		=> 'number',
							'default'	=> 10,
						),
			'orderby' => array(
							'type'		=> 'string',
							'default'	=> 'date',
						),
			'order' => array(
							'type'		=> 'string',
							'default'	=> 'desc',
						),
			'category' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'align' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
			'className' => array(
							'type'		=> 'string',
							'default'	=> '',
						),
		),
		'render_callback' => 'wpbaw_recent_blog_post',
	));

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'wpbaw-block-js', 'wp-blog-and-widgets', WPBAW_URL . '/languages' );
	}

}
add_action( 'init', 'wpbaw_register_guten_block' );

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 *
 * @since 1.0
 */
function wpbaw_block_assets() {	
}
add_action( 'enqueue_block_assets', 'wpbaw_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * @uses {wp-blocks} for block type registration & related functions.
 * @uses {wp-element} for WP Element abstraction — structure of blocks.
 * @uses {wp-i18n} to internationalize the block's text.
 * @uses {wp-editor} for WP editor styles.
 * 
 * @since 1.0
 */
function wpbaw_editor_assets() {

	// Block Editor CSS
	if( ! wp_style_is( 'wpos-guten-block-css', 'registered' ) ) {
		wp_register_style( 'wpos-guten-block-css', WPBAW_URL.'assets/css/blocks.editor.build.css', array( 'wp-edit-blocks' ), WPBAW_VERSION );
	}

	// Block Editor Script
	wp_enqueue_style( 'wpos-guten-block-css' );
	wp_enqueue_script( 'wpbaw-block-js' );

}
add_action( 'enqueue_block_editor_assets', 'wpbaw_editor_assets' );

/**
 * Adds an extra category to the block inserter
 *
 * @since 1.0
 */
function wpbaw_add_block_category( $categories ) {

	$guten_cats = wp_list_pluck( $categories, 'slug' );

	if( ! in_array( 'wpos_guten_block', $guten_cats ) ) {
		$categories[] = array(
							'slug'	=> 'wpos_guten_block',
							'title'	=> esc_html__('Essential Plugin Blocks', 'wp-blog-and-widgets'),
							'icon'	=> null,
						);
	}

	return $categories;
}
add_filter( 'block_categories_all', 'wpbaw_add_block_category' );