<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Eforms {
	function __construct(){
        add_filter( "ipt_fsqm_admin_email", array($this,"ipt_fsqm_admin_email"),10,2);
        add_filter( "ipt_fsqm_user_email", array($this,"ipt_fsqm_admin_email"),10,2);
        add_filter( "ipt_fsqm_user_payment_email", array($this,"ipt_fsqm_admin_email"),10,2);
        add_filter( "ipt_fsqm_admin_payment_email", array($this,"ipt_fsqm_admin_email"),10,2);
	}
    function ipt_fsqm_admin_email($email,$data_email){
        foreach($email as $key => $values){
            $message = $values["msgs"];
            $html_el = str_get_html($message);
            $datas = $html_el->find('.devicewidthinner');
            $new_html ="";
            foreach (  $datas as $data ){
                $new_html .= '<table style="width:100%;">'.$data->innertext.'</table>';
            }
            $values["msgs"] = $new_html;
            $email[$key] = $values;
        }
        return $email; 
    }
}
new Yeemail_Addons_Eforms;