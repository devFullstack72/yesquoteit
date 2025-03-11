<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package WP Blog and Widgets
 * @since 1.3.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpbaw_Admin {

	function __construct() {

		// Action to add admin menu
		add_action( 'admin_menu', array( $this, 'wpbaw_register_menu' ) );

		// Action to add metabox
		add_action( 'add_meta_boxes', array( $this, 'wpbaw_post_sett_metabox' ) );

		// Init Processes
		add_action( 'admin_init', array( $this, 'wpbaw_admin_init_process' ) );

		// Get Posts Filter
		add_filter( 'pre_get_posts', array( $this, 'wpbaw_blog_display_tags' ) );

		// Filter to add row action in category table
		add_filter( WPBAW_CAT.'_row_actions', array( $this, 'wpbaw_add_tax_row_data' ), 10, 2 );
	}

	/**
	 * Function to add menu
	 * 
	 * @since 1.3.2
	 */
	function wpbaw_register_menu() {

		// How it work page
		add_submenu_page( 'edit.php?post_type='.WPBAW_POST_TYPE, __('How it works, our plugins and offers', 'wp-blog-and-widgets'), __('How It Works', 'wp-blog-and-widgets'), 'manage_options', 'wpbawh-designs', array($this, 'wpbawh_designs_page') );

		// Setting page
		add_submenu_page( 'edit.php?post_type='.WPBAW_POST_TYPE, __('Overview - WP Blog and Widget', 'wp-blog-and-widgets'), __('Overview', 'wp-blog-and-widgets'), 'manage_options', 'wpbaw-solutions-features', array($this, 'wpbaw_solutions_features_page') );

		// Plugin features menu
		add_submenu_page( 'edit.php?post_type='.WPBAW_POST_TYPE, __('Upgrade To PRO - WP Blog and Widget', 'wp-blog-and-widgets'), '<span style="color:#2ECC71">'.__('Upgrade To PRO', 'wp-blog-and-widgets').'</span>', 'edit_posts', 'wpbawh-premium', array($this, 'wpbaw_premium_page') );
	}

	/**
	 * Function to display plugin design HTML
	 * 
	 * @since 2.0
	 */
	function wpbaw_solutions_features_page() {
		include_once( WPBAW_DIR . '/includes/admin/settings/solution-features/solutions-features.php' );
	}

	/**
	 * Function to display plugin design HTML
	 * 
	 * @since 2.0
	 */
	function wpbawh_designs_page() {
		include_once( WPBAW_DIR . '/includes/admin/wpbaw-how-it-work.php' );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @since 1.3.2
	 */
	function wpbaw_premium_page() {
		//include_once( WPBAW_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @since 2.1
	 */
	function wpbaw_post_sett_metabox() {
		add_meta_box( 'wpbaw-post-metabox-pro', __('More Premium - Settings', 'wp-slick-slider-and-image-carousel'), array($this, 'wpbaw_post_sett_box_callback_pro'), WPBAW_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Function to handle 'premium ' metabox HTML
	 * 
	 * @since 2.1
	 */
	function wpbaw_post_sett_box_callback_pro( $post ) {		
		include_once( WPBAW_DIR .'/includes/admin/metabox/wpbaw-post-setting-metabox-pro.php');
	}

	/**
	 * Function to notification transient
	 * 
	 * @since 1.3.2
	 */
	function wpbaw_admin_init_process() {

		global $typenow;

		$current_page = isset( $_REQUEST['page'] ) ? wpbaw_clean( $_REQUEST['page'] ) : '';

		// If plugin notice is dismissed
	    if( isset( $_GET['message'] ) && 'wpbawh-plugin-notice' == $_GET['message'] ) {
	    	set_transient( 'wpbawh_install_notice', true, 604800 );
	    }

	    // Redirect to external page for upgrade to menu
		if( $typenow == WPBAW_POST_TYPE ) {

			if( $current_page == 'wpbawh-premium' ) {

				$tab_url		= add_query_arg( array( 'post_type' => WPBAW_POST_TYPE, 'page' => 'wpbaw-solutions-features', 'tab' => 'wpbaw_basic_tabs' ), admin_url('edit.php') );

				wp_redirect( $tab_url );
				exit;
			}
		}
	}

	/**
	 * Function to blog display tags
	 * 
	 * @since 1.3.2
	 */
	function wpbaw_blog_display_tags( $query ) {

		if( is_tag() && $query->is_main_query() ) {
			$post_types = array( 'post', 'blog_post' );
			$query->set( 'post_type', $post_types );
		}
	}

	/**
	 * Function to add category row action
	 * 
	 * @since 1.0
	 */
	function wpbaw_add_tax_row_data( $actions, $tag ) {
		return array_merge( array( 'wpos_id' => esc_html__( 'ID:', 'wp-blog-and-widgets' ) .' '. esc_html( $tag->term_id ) ), $actions );
	}
}

$wpbaw_Admin = new Wpbaw_Admin();