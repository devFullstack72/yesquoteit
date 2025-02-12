<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Gravity_Forms{
    public $form;
    public $lead;
    function __construct(){
		add_filter("gform_notification_settings_fields",array($this,"add_settings"),10,3);
		add_filter("gform_pre_send_email",array($this,"message"),10,4);
		add_filter("gform_notification",array($this,"gform_notification_email"),10,3);
    }
    
    function gform_notification_email($notification, $form, $lead ){
        $this->lead = $lead;
        $this->form = $form;
        return $notification;
    }
    function get_shortcode_form_id($form_id){
		$shortcode= array();
		$fields = array();
		if($form_id){
			$form = RGFormsModel::get_form_meta($form_id);
			if(is_array($form["fields"])){
	            foreach($form["fields"] as $field){
	                if(isset($field["inputs"]) && is_array($field["inputs"])){
	                    foreach($field["inputs"] as $input){
	                    	$lable = GFCommon::get_label($field, $input["id"]);
	                    	$value = '{'.$lable.':'.$input["id"].'}';
	                    	$shortcode[$value] = $lable;
	                    }
	                }
	                else if(!rgar($field, 'displayOnly')){
	                    	$fields[] =  array($field["id"], GFCommon::get_label($field));
	                    	$lable = GFCommon::get_label($field);
	                    	$value = '{'.$lable.':'.$field["id"].'}';
	                    	$shortcode[$value] = $lable;
	                }
	            }
	        }
		}
		return $shortcode;
	}
    function message($email, $message_format, $notification, $entry ){
		require_once( YEEMAIL_PLUGIN_PATH . 'includes-overwrite/gravityforms-common.php' );
        $message = $notification["message"];
        $message_content_email        = Yeemail_GFCommon::replace_variables( $message, $this->form, $this->lead, false, false, false, "html" );
        $message_content_email = apply_filters( "yeemail_gravityforms_message",$message_content_email,$notification,$entry,$this->form, $this->lead,$this );
        $email["message"] = $message_content_email;
        return $email;
    }
    //gravity 2.5
	function add_settings($fields, $notification, $form){
        $link = get_option( "yemail_pro_id");
        $from_email_warning = '<div class="alert warning" role="alert" style=""><a class="button" target="_blank" href="'.esc_url(get_edit_post_link($link)."&add-ons=yeemail-for-gravity-forms").'">'.esc_attr__( "Customize with YeeMail", "yeemail").'</a></div>';
        $field_yeemail = array( 'type' => 'text', 'name' => 'email_template','class'=>'hidden',
            'label'         => esc_html__( 'Yeemail Template Advanced',"yeemail"  ),
            'after_input'         => $from_email_warning,
        );
        $field_yeemail = apply_filters( "yeemail_gravity_forms_settings",$field_yeemail,$notification, $form );
        $fields[0]['fields'][] = $field_yeemail;
	    return $fields;
	}
}
new Yeemail_Addons_Gravity_Forms;