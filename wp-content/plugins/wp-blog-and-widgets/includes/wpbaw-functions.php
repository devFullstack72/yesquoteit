<?php
/**
 * Plugin generic functions file
 *
 * @package WP Blog and Widgets
 * @since 1.1.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * 
 * @since 2.3
 */
function wpbaw_clean( $var ) {

	if ( is_array( $var ) ) {
		return array_map( 'wpbaw_clean', $var );
	} else {
		$data = is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		return wp_unslash($data);
	}
}

/**
 * Sanitize number value and return fallback value if it is blank
 * 
 * @since 2.3
 */
function wpbaw_clean_number( $var, $fallback = null, $type = 'int' ) {

	$var = trim( $var );
	$var = is_numeric( $var ) ? $var : 0;

	if ( $type == 'number' ) {
		$data = intval( $var );
	} else if ( $type == 'abs' ) {
		$data = abs( $var );
	} else if ( $type == 'float' ) {
		$data = (float)$var;
	} else {
		$data = absint( $var );
	}

	return ( empty( $data ) && isset($fallback) ) ? $fallback : $data;
}

/**
 * Sanitize Multiple HTML class
 * 
 * @since 1.0.0
 */
function wpbaw_get_sanitize_html_classes( $classes, $sep = " " ) {
	$return = "";

	if( ! is_array( $classes ) ) {
		$classes = explode($sep, $classes);
	}

	if( ! empty( $classes ) ) {
		foreach( $classes as $class ){
			$return .= sanitize_html_class( $class ) . " ";
		}
		$return = trim( $return );
	}

	return $return;
}

/**
 * Function to get post excerpt
 * 
 * @since 1.1.7
 */
function wpbaw_get_post_excerpt( $post_id = null, $content = '', $word_length = '55', $more = '...' ) {
 
	global $post;

	if( empty( $post_id ) ) {
		$post_id = isset( $post->ID ) ? $post->ID : $post_id;
	}

	$word_length = ! empty( $word_length ) ? $word_length : 55;

	// If post id is passed
	if( ! empty( $post_id ) ) {
		if( has_excerpt( $post_id ) ) {
			$content = get_the_excerpt( $post_id );
		} else {
			$content = ! empty( $content ) ? $content : get_the_content( NULL, FALSE, $post_id );
		}
	}

	if( ! empty( $content ) ) {
		$content = strip_shortcodes( $content ); // Strip shortcodes
		$content = wp_trim_words( $content, $word_length, $more );
	}

	return $content;
}

/**
 * Function to unique number value
 * 
 * @since 1.1.5
 */
function wpbaw_get_unique() {

	static $unique = 0;
	$unique++;

	// For Elementor & Beaver Builder
	if( ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_POST['action'] ) && $_POST['action'] == 'elementor_ajax' )
	|| ( class_exists('FLBuilderModel') && ! empty( $_POST['fl_builder_data']['action'] ) )
	|| ( function_exists('vc_is_inline') && vc_is_inline() ) ) {
		$unique = current_time('timestamp') . '-' . rand();
	}

	return $unique;
}

/**
 * Function to pagination
 * 
 * @since 1.1.5
 */
function wpbaw_blog_pagination( $args = array() ){

	$big                = 999999999; // need an unlikely integer
	$page_links_temp    = array();  
	$pagination_type    = isset( $args['pagination_type'] ) ? $args['pagination_type'] : 'numeric';
	$multi_page         = ! empty( $args['multi_page'] )    ? 1 : 0;
	$add_fragment       = apply_filters( 'wpbaw_paging_add_fragment', true, $args );

	$paging = array(
		'base'          => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'        => '?paged=%#%',
		'current'       => max( 1, $args['paged'] ),
		'total'         => $args['total'],
		'prev_next'     => true,
		'prev_text'     => '&laquo; '.esc_html__( 'Previous', 'wp-blog-and-widgets' ),
		'next_text'     => esc_html__( 'Next', 'wp-blog-and-widgets' ).' &raquo;',
		'add_fragment'  => $add_fragment ? '#wpbaw-blog-'.$args['unique'] : false,
	);

	if( $pagination_type == 'next-prev' ) {
		$paging['type']     = 'array';
		$paging['show_all'] = false;
		$paging['end_size'] = 1;
		$paging['mid_size'] = 0;
	}

	// If pagination is next-prev and shortcode is placed in single post
	if( $multi_page ) {
		$paging['base']     = esc_url_raw( add_query_arg( 'blog_page', '%#%', false ) );
		$paging['format']   = '?blog_page=%#%';
	}

	$page_links = paginate_links( apply_filters( 'wpbaw_pro_paging_args', $paging ) );

	// For single post shortcode we just fetch the next-prev link
	if( $pagination_type == 'next-prev' && $page_links && is_array( $page_links ) ) {

		foreach ($page_links as $page_link_key => $page_link) {
			if( strpos( $page_link, 'next page-numbers') !== false || strpos( $page_link, 'prev page-numbers') !== false ) {
				$page_links_temp[ $page_link_key ] = $page_link;
			}
		}
		return join( "\n", $page_links_temp );
	}

	return $page_links;
}

/**
 * Function to get post featured image
 * 
 * @since 1.1.7
 */
function wpbaw_get_post_featured_image( $post_id = '', $size = 'full' ) {

	global $post;

	if( empty( $post_id ) ) {
		$post_id = isset( $post->ID ) ? $post->ID : $post_id;
	}

	$size   = ! empty( $size ) ? $size : 'full';
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );

	if( ! empty( $image ) ) {
		$image = isset( $image[0] ) ? $image[0] : '';
	}

	return $image;

}