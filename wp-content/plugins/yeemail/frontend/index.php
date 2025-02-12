<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Builder_Frontend {
	function __construct(){
		add_filter( 'template_include',array($this,'template_include'),99);
		add_filter('wp_mail',array($this,"yeemail_template"));
		add_filter( 'wp_mail_content_type',array($this,'set_content_type') );
		add_filter('upload_mimes', array($this,'mime_types'));
		add_filter("yeemail_email_id",array($this,"set_id_send_email"),10);
    }
    function set_id_send_email($emails){
		preg_match_all('/data-yeemail-id="([^"]*)"/',$emails["message"],$matches);
		if( isset($matches[1][0]) && is_numeric($matches[1][0]) ){
			$emails["apply"] = false;
		}
		if(strpos($emails["message"], "yeemail_disable") !== false) {
			$emails["apply"] = false;
		}
		return $emails;
	}
	function get_nl2br($string){
		if(!preg_match("/<[^<]+>/",$string)){
			return nl2br($string);
		}
		return $string;
	}
    function template_include($template) {
	    if( isset($_GET['email_preview']) ){
	        if( $_GET['email_preview'] == "preview") {
	            $template = YEEMAIL_PLUGIN_PATH."preview.php";   
	        }else{
	            $template = YEEMAIL_PLUGIN_PATH."preview-inner.php";   
	        }
	        if ( file_exists( $template ) ) { 
	            return $template;
	        }
	    }else{
	    	return $template;
	    }
	}
	public static function get_email_id_template_by_type($id_email ="default",$status = "enable", $show_all = false){
		if($show_all){
			$templates_post = get_posts(array("numberposts"=>1,"post_type"=>"yeemail_template","meta_query"=>array(array('key'=>"_mail_template",'value' => $id_email))));
		}else{
			$templates_post = get_posts(array("numberposts"=>1,"post_type"=>"yeemail_template","meta_query"=>array(array('key'=>"_status",'value' => $status),array('key'=>"_mail_template",'value' => $id_email))));
		}
		foreach ( $templates_post as $post ) {
			return $post->ID;
			break;
		}
		return false;
	}
	function yeemail_template($args) {
	    $message =  $args["message"];
		$mails = apply_filters( "yeemail_email_id", array("apply"=>true,"message"=>$message));
		if( $mails["apply"]  ) {
			//check mail template
			$template_id = self::get_email_id_template_by_type("default");
			if( is_numeric($template_id) && $template_id > 0 ) { 
				//remove header and
				preg_match("/<body[^>]*>(.*?)<\/body>/is", $message, $matches);
				if(isset($matches[1])){
					$message = $matches[1];
				}
				$content = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"full"));
				//check no use html
				$message = $this->get_nl2br($message);
				$args["message"] = str_replace('<div class="builder-content">Content Email</div>','<div class="builder-content-email">'.$message.'</div>',$content);
			}
		}else{
			//Another template has already used this email template.
	    }
	    return $args;
	}
	function set_content_type(){
	    return "text/html";
	}
	function mime_types($mimes) {
	    $mimes['json'] = 'text/plain';
	    return $mimes;
	}
}
new Yeemail_Builder_Frontend;