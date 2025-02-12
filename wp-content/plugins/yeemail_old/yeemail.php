<?php
/**
 * Plugin Name: YeeMail - Email Customizer for WordPress
 * Description: Customizing the design and content of your email
 * Version: 2.1.6
 * Author: add-ons.org
 * Plugin URI: https://wordpress.org/plugins/yeemail/
 * Author URI: https://add-ons.org/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define( 'YEEMAIL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'YEEMAIL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'YEEMAIL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if (function_exists('is_plugin_active')) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}
class Yeemail_Template_Customizer_Init{
    function __construct(){
        register_activation_hook( __FILE__, array($this,'activation') );
        $dir = new RecursiveDirectoryIterator(YEEMAIL_PLUGIN_PATH."backend");
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, "/\.php/", RegexIterator::MATCH);
        foreach ($files as $file) {
            if (!$file->isDir()){
                require_once $file->getPathname();
            }
        }
        $dir = new RecursiveDirectoryIterator(YEEMAIL_PLUGIN_PATH."includes");
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, "/\.php/", RegexIterator::MATCH);
        if (!function_exists('str_get_html')) { 
			include YEEMAIL_PLUGIN_PATH."libs/simple_html_dom.php";
		}
        foreach ($files as $file) {
            if (!$file->isDir()){
                require_once $file->getPathname();
            }
        }
        include_once YEEMAIL_PLUGIN_PATH."frontend/index.php";
        include_once YEEMAIL_PLUGIN_PATH."frontend/functions.php";
        do_action( "yeemail/loaded");
    }
    function activation() {
        global $wpdb;
        $check = get_option( "yeemail_setup",array() );
        if( count($check) < 1){            
            $string = file_get_contents(YEEMAIL_PLUGIN_PATH."backend/demo/default.json");
            $id_map_email_id = array();
            $datas_templates = explode("\n", $string);
            foreach( $datas_templates as $datas_template ){
                $settings_datas = explode("|||yeemail_data|||",$datas_template);
                if(count($settings_datas) > 1){
                    $template_content = $settings_datas[1];
                    $settings_data = explode(",",$settings_datas[0]);
                    foreach ($settings_data as $setting){
                        $main_settings = explode(":",$setting);
                        switch($main_settings[0]){
                            case "type":
                                $type = $main_settings[1];
                            break;
                            case "title":
                                $title = $main_settings[1];
                            break;
                            case "email":
                                $email = $main_settings[1];
                            break;
                            case "status":
                                $status = $main_settings[1];
                            break;
                        }
                    }
                    $my_template = array(
                        'post_title'    => $title,
                        'post_content'  => "",
                        'post_status'   => 'publish',
                        'post_type'     => 'yeemail_template'
                    );
                    $id_template = wp_insert_post( $my_template );
                    $id_map_email_id[$email] = $id_template;
                    add_post_meta($id_template,"data_email",$settings_datas[1]);
                    add_post_meta($id_template,"_mail_type",$type);
                    add_post_meta($id_template,"_mail_template",$email);
                    add_post_meta($id_template,"_status",$status);
                    if($email == "yeemail_pro"){
                        update_option( "yemail_pro_id", $id_template );
                    }
                }
            }
            update_option( "yeemail_setup",$id_map_email_id );
        } 
    }
}
new Yeemail_Template_Customizer_Init;
if(!class_exists('Superaddons_List_Addons')) {  
    include YEEMAIL_PLUGIN_PATH."add-ons.php"; 
}