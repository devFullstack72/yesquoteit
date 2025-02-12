<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Builder_Email_Shortcode {
	function __construct() {
		$lists = self::list_shortcodes(false);
		foreach($lists as $key=>$values){
			foreach($values as $k=>$v){
				if(!is_array($v)){
					add_shortcode( $k, array($this,'shortcode_main') );
				}else{
					foreach($v as $kc=>$vc){
						add_shortcode( $kc, array($this,'shortcode_main') );
					}
				}
			}
		}
		add_filter( 'yeemail_builder_shortcode', array($this,'builder_shortcode') );
	}
	public static function list_shortcodes($filter = true){
		$shortcodes = array(
			"Genaral" => array(
				"yeemail_site_name" => "Site Name",
				"yeemail_site_url" => "Site URL",
				"yeemail_admin_email" => "Admin Email",
				"yeemail_current_date" => "Current Date",
				"yeemail_current_time" => "Current Time",
				"yeemail_current_ip" => "User IP",
			),
			"User" => array(
				"yeemail_user_login_url" => "User Login URL",
				"yeemail_user_logout_url" => "User Logout URL",
				"yeemail_user_id" => "User ID",
				"yeemail_user_login" => "User Login",
				"yeemail_user_name" => "User Name",
				"yeemail_user_email" => "User Email",
				"yeemail_user_url" => "User URL",
				"yeemail_user_display_name" => "User Display Name",
				"yeemail_set_password_url" => "Password Reset URL",
			),
			"Comment" => array(
				"yeemail_post_title" => "Post Title",
				"yeemail_comment_link" => "Comment Link",
				"yeemail_comment_link_url" => "Comment Link URL",
				"yeemail_comment_content" => "Comment Content",
				"yeemail_comment_author" => "Comment Author",
				"yeemail_comment_author_ip" => "Comment Author IP",
				"yeemail_comment_author_email" => "Comment Author Email",
				"yeemail_comment_author_url" => "Comment Author URL",
				"yeemail_comment_author_domain" => "Comment Author Domain",
			)
		);
		if($filter){
			return apply_filters( "yeemail_shortcodes", $shortcodes );
		}else{
			return $shortcodes;
		}
		
	}
	function builder_shortcode($shortcodes){
		$lists = self::list_shortcodes();
		foreach($lists as $key=>$values){
			foreach($values as $k=>$v){
				if(!is_array($v)){
					$shortcodes[$k] = do_shortcode( "[".$k."]");
				}else{
					foreach($v as $kc=>$vc){
						$shortcodes[$kc] = do_shortcode( "[".$kc."]");
					}
				}
			}
		}
		return $shortcodes;
	}
	function shortcode_main($atts, $content, $tag){
		switch ($tag) {
			case "yeemail_site_url":
				return site_url();
				break;
			case "yeemail_site_name":
				if ( is_multisite() ) {
					$site_name = get_network()->site_name;
				} else {
					$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
				}
				return $site_name;
				break;
			case "yeemail_current_date":
				return date(get_option('date_format'));
				break;
			case "yeemail_current_time":
				return date(get_option('time_format'));
				break;
			case "yeemail_admin_email":
				return get_option('admin_email');
				break;
			case "yeemail_user_login":
			case "yeemail_user_name":	
				$current_user = wp_get_current_user();
				return $current_user->user_login;
				break;
			case "yeemail_user_email":
				$current_user = wp_get_current_user();
				return $current_user->user_email;
				break;
			case "yeemail_user_url":
				return site_url();
				break;
			case "yeemail_user_display_name":
				$current_user = wp_get_current_user();
				return $current_user->display_name;
				break;
			case "yeemail_user_login_url":
				return '<a href="' . wp_login_url() . '"> '.esc_html__('Log in', 'woocommerce').' </a>';
				break;
			case "yeemail_user_logout_url":
				return '<a href="' . wp_logout_url( home_url()) . '"> '.esc_html__('Log in', 'woocommerce').' </a>';
				break;
			case "yeemail_post_title":
				return 'Yeemail Post Name';
				break;
			case "yeemail_comment_author_email":
				$current_user = wp_get_current_user();
				return $current_user->user_email;
				break;
			case "yeemail_comment_content":
				return '<p>Comment content Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>';
				break;
			case "yeemail_comment_author_domain":
			case "yeemail_comment_author_url":
				return site_url();
				break;
			case "yeemail_comment_author_ip":
				return "192.168.1.1";
				break;
			case "yeemail_comment_link":
				return '<a href="#">'.site_url().'</a>';
				break;
			case "yeemail_comment_link_url":
				return site_url();
				break;
			default:
		}
	}
}
new Yeemail_Builder_Email_Shortcode;