<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Builder_Ajax {
	function __construct(){
		add_action( 'wp_ajax_yeemail_builder_text', array($this,'yeemail_builder_text') );
		add_action( 'wp_ajax_yeemail_builder_save_video', array($this,"yeemail_builder_save_video" ));
		add_action( 'wp_ajax_yeemail_builder_send_email_testing', array($this,'yeemail_builder_send_email_testing') );
		add_action( 'wp_ajax_yeemail_update_settings_template', array($this,'yeemail_update_settings_template') );
		add_action( 'wp_ajax_yeemail_builder_reset_template', array($this,'yeemail_builder_reset_template') );
	}
	function yeemail_builder_reset_template(){
		if ( isset($_POST[ 'nonce' ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ), 'yeemail_editor' ) ) {
			$id = sanitize_text_field( $_POST['id'] );
			$email_type_id = get_post_meta($id,"_mail_template",true);
			$reset = apply_filters( "yeemail_reset_template",true,$email_type_id );
			if($reset){
				$string = file_get_contents(YEEMAIL_PLUGIN_PATH."backend/demo/default.json");
				$datas_templates = explode("\n", $string);
				foreach( $datas_templates as $datas_template ){
					$settings_datas = explode("|||yeemail_data|||",$datas_template);
					$settings_data = explode(",",$settings_datas[0]);
					foreach ($settings_data as $setting){
						if($setting == "email:".$email_type_id ){
							update_post_meta($id,"data_email",$settings_datas[1]);
							wp_send_json( array("status"=>true));
							break 2;
						}
					}
				}
			}
			
		}
	}
	function yeemail_update_settings_template(){
		if ( isset($_POST[ 'nonce' ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ), 'yeemail_editor' ) ) {
			$id = sanitize_text_field( $_POST['id'] );
			$status = sanitize_text_field( $_POST['status'] );
			update_post_meta($id,"_status",$status);
			wp_send_json( array("status"=>true));
		}
	}
	function yeemail_builder_text(){
		if ( isset($_POST[ 'nonce' ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ), 'yeemail_editor' ) ) {
			if( isset($_POST["text"])){
				$string_with_shortcodes = sanitize_textarea_field( $_POST["text"] );
				$string_with_shortcodes = do_shortcode($string_with_shortcodes);
				$string_with_shortcodes = str_replace("\\","",$string_with_shortcodes);
				echo $string_with_shortcodes; // phpcs:ignore WordPress.Security.EscapeOutput
				die();
			}
		}
	}
	function yeemail_builder_save_video(){
		if ( isset($_POST[ 'nonce' ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ), 'yeemail_editor' ) ) {
			WP_Filesystem();
			global $wp_filesystem;
			if( isset($_POST["img"])){
				$img = sanitize_text_field($_POST["img"]);
				$id = sanitize_text_field($_POST["id"]);
				$img = str_replace('data:image/png;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$img          = base64_decode($img) ;
				$filename  = $id.".png";
				$upload = wp_upload_dir();
				$upload_dir = $upload['basedir'];
				$upload_dir = $upload_dir . '/wpbuider-email-uploads';
				if ( ! file_exists( $upload_dir ) ) {
					wp_mkdir_p( $upload_dir );
				}
				$upload_path      = $upload_dir."/".$filename;
				$success = $wp_filesystem->put_contents($upload_path, $img);
				echo esc_url($upload['baseurl'].'/wpbuider-email-uploads/'.$filename);
			}
		}
	    die();
	}
	function yeemail_builder_send_email_testing(){
		if ( isset($_POST[ 'nonce' ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ 'nonce' ] ) ), 'yeemail_editor' ) ) {
			$post_id = sanitize_text_field($_POST["id"]);
			$email =  sanitize_email($_POST["email"]);
			if(is_email($email)){
				$content = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$post_id,"type"=>"full"));
				$data = wp_mail( $email, "YeeMail Testings", $content );
				if($data) {
						esc_html_e("Sent email","yeemail");
				}else{
					esc_html_e("Can't send email","yeemail");
				}
			}
		}
		die();	
	}
}
new Yeemail_Builder_Ajax;