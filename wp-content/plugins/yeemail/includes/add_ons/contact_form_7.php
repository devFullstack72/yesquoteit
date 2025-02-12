<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Contactform7{
	function __construct(){;
		add_filter("wpcf7_editor_panels",array($this,"custom_form"));
	}
	function custom_form($panels){
        $panels["form-panel-email-template-setting"] = array(
                'title' => __( 'Yeemail Template', "yeemail" ),
                'callback' => array($this,"setting_form") );
        return $panels;
    }
    function setting_form($post){
    	$post_id = $post->id();
        $link = get_option( "yemail_pro_id");
        ob_start();
        ?>
        <div class="yeemail_addon_3">
            <label><?php esc_html_e( "YeeEmail Template Advanced", "yeemail" ) ?></label>
            <a class="button" target="_blank" href="<?php echo esc_url(get_edit_post_link($link)."&add-ons=yeemail-for-contact-form-7") ?>"><?php esc_attr_e( "Customize with YeeMail", "yeemail") ?></a>
        </div>
        <?php
        $text= ob_get_clean();
        $html_text = apply_filters( "yeemail_contact_form_7_settings", $text, $post_id,$post,$this);
    	echo $html_text; // phpcs:ignore WordPress.Security.EscapeOutput
    }
	
}
new Yeemail_Addons_Contactform7;