<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Formidableforms {
	function __construct(){
        add_filter( "frm_email_message", array($this,"frm_email_message"),10,2);
	}
    function frm_email_message($emailBody, $notification){
        $html_el = str_get_html($emailBody);
        $datas = $html_el->find('table');
        $new_html ="";
        foreach (  $datas as $data ){
            $new_html .= '<table style="width:100%;">'.$data->innertext.'</table>';
        }
        if($new_html != ""){
            $emailBody = $new_html;
        }
        return $emailBody; 
    }
}
new Yeemail_Addons_Formidableforms;