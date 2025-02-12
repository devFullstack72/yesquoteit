<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Formcraft3 {
	function __construct(){
        add_filter( "formcraft_filter_yeemail_template", array($this,"formcraft_filter_yeemail_template"),10);
	}
    function formcraft_filter_yeemail_template($template ){
        $message = $template["Form Content"];
        $message = str_replace("600px","100%",$message);
        $template["Form Content"] = $message;
        return $template; 
    }
}
new Yeemail_Addons_Formcraft3;