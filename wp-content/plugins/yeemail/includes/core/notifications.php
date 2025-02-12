<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_New_User_Email{
    function __construct(){
		//New account
        add_filter( 'wp_new_user_notification_email', array($this,"wp_new_user_notification_email"),10,3 );
		//New account to admin
        add_filter( 'wp_new_user_notification_email_admin', array($this,"wp_new_user_notification_email_admin"),10,3 );
		// Password Changed
        add_filter( 'password_change_email', array($this,"password_change_email"),10,3 );
		// Password Changed to admin
		add_filter( 'wp_password_change_notification_email', array($this,"wp_password_change_notification_email"),10,3 );
		//Reset Password
        add_filter( 'retrieve_password_message', array($this,"retrieve_password_notification_email"),10,4 );
        add_filter( 'retrieve_password_title', array($this,"retrieve_password_title"),10,3 );
		//Reset Password admin
		//add_filter( 'new_admin_email_content', array($this,"new_admin_email_content"),10,3 );
		//comment
		add_filter( 'comment_notification_text', array($this,"comment_notification_text"),10,2 );
		add_filter( 'comment_notification_subject', array($this,"comment_notification_subject"),10,2 );
		add_filter( 'comment_notification_notify_author', array($this,"comment_notification_notify_author"),10,2 );
    }
	function comment_notification_notify_author($notify_message,$comment_id ){
		$template_id = Yeemail_Builder_Frontend::get_email_id_template_by_type("comment_notification");
		if( $template_id && $template_id > 0) {
			return true;
		}
		return $notify_message;
	}
	function comment_notification_text($notify_message,$comment_id ){
		$new_commnet_content = $notify_message;
		$comment = get_comment( $comment_id );
		switch ( $comment->comment_type ) {
			case 'trackback':
			case 'pingback':
				break;
			default:
				$template_id = Yeemail_Builder_Frontend::get_email_id_template_by_type("comment_notification");
				if( $template_id && $template_id > 0) {
					$new_content_email = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"content_no_shortcode"));
					$data_key = array(
						"[yeemail_post_title]",
						"[yeemail_comment_author]",
						"[yeemail_comment_author_ip]",
						"[yeemail_comment_author_domain]",
						"[yeemail_comment_author_email]",
						"[yeemail_comment_author_url]",
						"[yeemail_comment_content]",
						"[yeemail_comment_link_url]",
						"[yeemail_comment_link]"
					);
					$post   = get_post( $comment->comment_post_ID );
					$author = get_userdata( $post->post_author );
					$post_title = $post->post_title;
					$comment_author = $comment->comment_author;
					$comment_author_ip = $comment->comment_author_IP;
					$comment_author_domain = '';
					if ( WP_Http::is_ip_address( $comment->comment_author_IP ) ) {
						$comment_author_domain = gethostbyaddr( $comment->comment_author_IP );
					}
					$comment_author_email = $comment->comment_author_email;
					$comment_author_url = $comment->comment_author_email;
					$comment_content = wp_specialchars_decode( $comment->comment_content );
					$comment_link_url = get_permalink( $comment->comment_post_ID );;
					$comment_link = get_comment_link( $comment );
					$data_value = array($post_title,$comment_author,$comment_author_ip,$comment_author_domain,$comment_author_email,$comment_author_url,$comment_content,$comment_link_url,$comment_link);
					$new_content_email = str_replace($data_key,$data_value,$new_content_email);
					$new_commnet_content = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"full","html"=>$new_content_email));
				}
				break;	
		}
		return $new_commnet_content;
	}
	function comment_notification_subject($subject,$comment_id ){
		$template_id = Yeemail_Builder_Frontend::get_email_id_template_by_type("comment_notification");
		if( $template_id && $template_id > 0) {
			$custom_subject = get_post_meta( $template_id,'_yeemail_custom_subject',true); 
			if($custom_subject != ""){
				$title = $custom_subject;
			}
		}
		return $subject;
	}
	function user_notification_email($type,$wp_new_user_notification_email, $user_data, $blogname ){
		$template_id = Yeemail_Builder_Frontend::get_email_id_template_by_type($type);
		if( $template_id && $template_id > 0) {
			$user_login = $user_data->user_login;
			$user_email = $user_data->user_email;
			$user_name = $user_login;
			$display_name = $user_data->display_name;
			$user_url = $user_data->user_url;
			$key = get_password_reset_key( $user_data );
			$set_password_url = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_data->user_login ), 'login' );
			$data_key = array("[yeemail_user_name]","[yeemail_user_login]","[yeemail_user_email]","[yeemail_set_password_url]","[yeemail_user_display_name]","[yeemail_user_url]");
			$data_value = array($user_name,$user_login,$user_email,$set_password_url,$display_name,$user_url);
			$new_content_email = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"content_no_shortcode"));
			$new_content_email = str_replace($data_key,$data_value,$new_content_email);
			$notification_email = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"full","html"=>$new_content_email));
			$wp_new_user_notification_email['message'] = $notification_email;
			$custom_subject = get_post_meta( $template_id,'_yeemail_custom_subject',true); 
			if($custom_subject != ""){
				$wp_new_user_notification_email['subject'] = $custom_subject;
			}
		}
    	return $wp_new_user_notification_email;
    }
	function new_admin_email_content($email_text, $new_admin_email ){
		return $email_text;
	}
	function retrieve_password_notification_email($notification_email, $key, $user_login, $user_data ){
		$template_id = Yeemail_Builder_Frontend::get_email_id_template_by_type("password_reset");
		if( $template_id && $template_id > 0) {
			$user_email = $user_data->user_email;
			$user_name = $user_login;
			$display_name = $user_data->display_name;
			$user_url = $user_data->user_url;
			$locale = get_user_locale( $user_data );
			$set_password_url = network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '&wp_lang=' . $locale ;
			$data_key = array("[yeemail_user_name]","[yeemail_user_login]","[yeemail_user_email]","[yeemail_set_password_url]","[yeemail_user_display_name]","[yeemail_user_url]");
			$data_value = array($user_name,$user_login,$user_email,$set_password_url,$display_name,$user_url);
			$new_content_email = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"content_no_shortcode"));
			$new_content_email = str_replace($data_key,$data_value,$new_content_email);
			$notification_email = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"full","html"=>$new_content_email));
		}
    	return $notification_email;
    }
	function retrieve_password_title($title, $user_login, $user_data){
		$template_id = Yeemail_Builder_Frontend::get_email_id_template_by_type("password_reset");
		if( $template_id && $template_id > 0) {
			$custom_subject = get_post_meta( $template_id,'_yeemail_custom_subject',true); 
			if($custom_subject != ""){
				$title = $custom_subject;
			}
		}
		return $title;
	}
	function password_change_email($pass_change_email, $user, $userdata){
		$template_id = Yeemail_Builder_Frontend::get_email_id_template_by_type("password_change");
		if( $template_id && $template_id > 0) {
			$user_login = $user['user_login'];
			$user_email = $user['user_email'];
			$user_name = $user['user_login'];
			$display_name = $userdata->display_name;
			$user_url = $userdata->user_url;
			$data_key = array("[yeemail_user_name]","[yeemail_user_login]","[yeemail_user_email]","[yeemail_set_password_url]","[yeemail_user_display_name]","[yeemail_user_url]",);
			$data_value = array($user_name,$user_login,$user_email,$set_password_url,$display_name,$user_url);
			$new_content_email = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"content_no_shortcode"));
			$new_content_email = str_replace($data_key,$data_value,$new_content_email);
			$notification_email = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"full","html"=>$new_content_email));
			$pass_change_email['message'] = $notification_email;
			$custom_subject = get_post_meta( $template_id,'_yeemail_custom_subject',true); 
			if($custom_subject != ""){
				$wp_new_user_notification_email['subject'] = $custom_subject;
			}
		}
    	return $pass_change_email;
    }
	function wp_password_change_notification_email($wp_new_user_notification_email, $user, $blogname ){
		$wp_new_user_notification_email = $this->user_notification_email("password_change_admin",$wp_new_user_notification_email, $user, $blogname);
    	return $wp_new_user_notification_email;
    }
	function wp_new_user_notification_email_admin($wp_new_user_notification_email, $user, $blogname ){
		$wp_new_user_notification_email = $this->user_notification_email("new_user_notification_admin",$wp_new_user_notification_email, $user, $blogname);
    	return $wp_new_user_notification_email;
    }
    function wp_new_user_notification_email($wp_new_user_notification_email, $user, $blogname ){
		$wp_new_user_notification_email = $this->user_notification_email("new_user_notification",$wp_new_user_notification_email, $user, $blogname);
    	return $wp_new_user_notification_email;
    }
}
new Yeemail_New_User_Email;