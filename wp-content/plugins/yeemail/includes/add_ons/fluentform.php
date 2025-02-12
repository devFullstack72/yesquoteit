<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Fluentform {
	function __construct(){
        add_filter( "fluentform/email_body", array($this,"ipt_fsqm_admin_email"),10,2);
	}
    function ipt_fsqm_admin_email($emailBody, $notification){
        $html_el = str_get_html($emailBody);
        $datas = $html_el->find('.ff_all_data');
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
new Yeemail_Addons_Fluentform;