<?php
/**
 * Plugin Name: Email Customizer for Contact Form 7
 * Description: Customize Contact Form 7 emails Advanced with YeeMail - Email Customizer for WordPress.
 * Version: 1.0.3
 * Requires Plugins: contact-form-7
 * Author: add-ons.org
 * Author URI: https://add-ons.org/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define( 'YEEMAIL_CF7_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'YEEMAIL_CF7_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
class Yeemail_Contact_Form_7_Template_Customizer_Init{
    function __construct(){
        register_activation_hook( __FILE__, array($this,'activation') );
        add_action( 'plugins_loaded', array($this,"yeemail_load_plugin") );
        include_once YEEMAIL_CF7_PLUGIN_PATH."backend/processing.php";
        include YEEMAIL_CF7_PLUGIN_PATH."superaddons/check_purchase_code.php";
        new Superaddons_Check_Purchase_Code( 
            array(
                "plugin" => "email-customizer-for-contact-form-7/email-customizer-for-contact-form-7.php",
                "id"=>"3935",
                "pro"=>"https://add-ons.org/plugin/yeemail-for-contact-form-7/",
                "plugin_name"=> "Email Customizer for Contact Form 7",
                "document"=>"https://add-ons.org/document-email-customizer-for-contact-form-7/"
            )
        );
    }
    function activation() {
        $check = get_option( "yeemail_contact_form_7_setup" );
        if( !$check ){           
            $data = file_get_contents(YEEMAIL_CF7_PLUGIN_PATH."backend/form-import.json");
            $my_template = array(
            'post_title'    => "Contact Form 7 Template Default",
            'post_content'  => "",
            'post_status'   => 'publish',
            'post_type'     => 'yeemail_template'
            );
            $id_template = wp_insert_post( $my_template );
            add_post_meta($id_template,"data_email",$data);      
            add_post_meta($id_template,"_mail_type","other");      
            update_option( "yeemail_contact_form_7_setup",$id_template );     
        } 
    }
    function yeemail_load_plugin(){
        if ( ! did_action( 'yeemail/loaded' ) ) {
            add_action( 'admin_notices', array($this,"yeemail_fail_load") );
            return;
        } 
    }
    function yeemail_fail_load() {
        $screen = get_current_screen();
        if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
            return;
        }
        $plugin = 'yeemail/yeemail.php';
        if ( $this->is_yeemail_installed() ) {
            if ( ! current_user_can( 'activate_plugins' ) ) {
                return;
            }
            $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
            $message = '<h3>' . esc_html__( 'You\'re not using YeeMail for Contact Form 7 yet!', 'yeemail-for-contact-form-7' ) . '</h3>';
            $message .= '<p>' . esc_html__( 'Activate the YeeMail plugin to start using all of YeeMail for Contact Form 7 plugin’s features.', 'yeemail-for-contact-form-7' ) . '</p>';
            $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Now', 'yeemail-for-contact-form-7' ) ) . '</p>';
        } else {
            if ( ! current_user_can( 'install_plugins' ) ) {
                return;
            }
            $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=yeemail' ), 'install-plugin_yeemail' );
            $message = '<h3>' . esc_html__( 'YeeMail for Contact Form 7 plugin requires installing the YeeMail plugin', 'yeemail-for-contact-form-7' ) . '</h3>';
            $message .= '<p>' . esc_html__( 'Install and activate the YeeMail plugin to access all the Pro features.', 'yeemail-for-contact-form-7' ) . '</p>';
            $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Now', 'yeemail-for-contact-form-7' ) ) . '</p>';
        }
        $this->print_error( $message );
    }
    function print_error( $message ) {
        if ( ! $message ) {
            return;
        }
        // PHPCS - $message should not be escaped
        echo '<div class="error">' . $message . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    function is_yeemail_installed() {
		$file_path = 'yeemail/yeemail.php';
		$installed_plugins = get_plugins();
		return isset( $installed_plugins[ $file_path ] );
	}
}
new Yeemail_Contact_Form_7_Template_Customizer_Init;