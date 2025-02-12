<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Easy_Digital_Downloads {
    public $order;
	function __construct(){
        add_action( 'edd_purchase_receipt_email_settings', array($this,"edd_email_template_preview"),11 );
        add_action( 'edd_email_settings', array($this,"email_settings"),11 );
        add_filter( "edd_email_templates", array($this,"edd_yeemail_templates"),10);
	}
    function edd_email_template_preview(){
        if( ! current_user_can( 'manage_shop_settings' ) ) {
            return;
        }
        
        $link_pro = get_option( "yemail_pro_id");
        $templates_edd = get_option( "yeemail_edd_setup", array());
        if(count($templates_edd) < 1 ){
          $link=  get_edit_post_link($link_pro)."&add-ons=edd";
        }else{
          $link=  get_edit_post_link($templates_edd["edd_receipt"]); 
        }
        ob_start();
        ?>
        <a class="button" target="_blank" href="<?php echo esc_url($link) ?>"><?php esc_attr_e( "Customize with YeeMail", "yeemail") ?></a>
        <?php
        echo ob_get_clean();
    }
    function email_settings(){
        if( ! current_user_can( 'manage_shop_settings' ) ) {
            return;
        }
        $link = get_admin_url()."edit.php?post_type=yeemail_template&mail_type=edd";
        $link_pro = get_option( "yemail_pro_id");
        $templates_edd = get_option( "yeemail_edd_setup", array());
        if(count($templates_edd) < 1 ){
          $link=  get_edit_post_link($link_pro)."&add-ons=yeemail-for-easy-digital-downloads";
        }
        ob_start();
        ?>
        <a class="button" target="_blank" href="<?php echo esc_url($link) ?>"><?php esc_attr_e( "Customize with YeeMail", "yeemail") ?></a>
        <?php
        echo ob_get_clean(); 
    }
    function edd_yeemail_templates($templates){
        $templates["yeemail"] = "YeeMail";
        return $templates;
    }
}
new Yeemail_Addons_Easy_Digital_Downloads;