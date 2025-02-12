<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Woocommerce {
	private $plugin_woocommerce = "email-templates-customizer-for-woocommerce/email-templates-customizer-for-woocommerce.php";
	function __construct(){
        add_filter( 'woocommerce_email_setting_columns', array( $this, 'email_setting_columns' ) );
		add_action( 'woocommerce_email_setting_column_yeemail_template', array( $this, 'column_template' ) );
		add_filter( 'wc_get_template', array( $this, 'replace_template_path' ), 888, 5 );
	}
	public function replace_template_path( $located, $template_name, $args, $template_path, $default_path) {
		$default_id = Yeemail_Builder_Frontend::get_email_id_template_by_type("default");
		if($default_id) {
			if($template_name == "emails/email-header.php"){
				$located        = YEEMAIL_PLUGIN_PATH . 'templates/woocommerce/emails/email-header.php';
			}
			if($template_name == "emails/email-footer.php"){
				$located        = YEEMAIL_PLUGIN_PATH . 'templates/woocommerce/emails/email-footer.php';
			}
		}
		return $located;
	}
    public function email_setting_columns( $array ) {
		if ( isset( $array['actions'] ) ) {
			unset( $array['actions'] );
			return array_merge(
				$array,
				array(
					'yeemail_template' => '',
					'actions'  => '',
				)
			);
		}
		return $array;
	}
	function yeemail_template($args) {
	    $message =  $args["message"];
	    return $args;
	}
    public function column_template( $email ) {
		$email_id = $email->id;
        $template_id = Yeemail_Builder_Frontend::get_email_id_template_by_type($email_id,"enable",true);
        if($template_id && $template_id > 0){
            $link = get_edit_post_link( $template_id);
        }else{
            $link = get_edit_post_link( get_option( "yemail_pro_id" ));
			if ( $this->is_yeemail_woocommerce_installed() ) {
				if ( current_user_can( 'activate_plugins' ) ) {
					$link = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $this->plugin_woocommerce . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $this->plugin_woocommerce );
				}
			}else{
				if ( current_user_can( 'install_plugins' ) ) {
					$link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=email-templates-customizer-for-woocommerce' ), 'install-plugin_email-templates-customizer-for-woocommerce' );
				}
			}
        }
		?>
        <td class="wc-email-settings-table-template">
			<a class="button alignright" target="_blank" href="<?php echo  esc_url( $link ) ?>"><?php  esc_html_e( 'Customize with YeeMail', 'yeemail' ) ?></a>
        </td>
        <?php
	}
	function is_yeemail_woocommerce_installed() {
		$file_path = $this->plugin_woocommerce;
		$installed_plugins = get_plugins();
		return isset( $installed_plugins[ $file_path ] );
	}
}
new Yeemail_Addons_Woocommerce;