<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
* Create Tab settings
*/
class Yeemail_Settings_Builder_Backend {
    function __construct() {
        add_action('admin_enqueue_scripts', array($this,'style'));
        add_action( 'init', array($this,'create_posttype') );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_filter( 'get_sample_permalink_html', array( $this, 'remove_permalink' ) );
        add_action( 'save_post_yeemail_template',array( $this, 'save_metabox' ), 10, 2 );
        add_filter( 'admin_body_class', array($this,'body_class' ));
        add_action( 'admin_footer', array($this,"add_page_templates"));
        add_action( 'admin_action_rednumber_duplicate', array($this,"rednumber_duplicate") );
        add_filter( 'parse_query', array($this,"query_remove_corder_template"),15 );
        add_filter('views_edit-yeemail_template',array($this,'add_yeemail_template_to_subsubsub'));
        add_action( 'wp_ajax_nopriv_booknow_load_time', array($this,"load_time") );
        add_filter( 'manage_yeemail_template_posts_columns', array($this,"columns") );
        add_filter( 'manage_yeemail_template_posts_custom_column', array($this,"custom_column"), 10, 2 );
        add_action( 'admin_menu', array($this,"remove_submenu"), 999 );
        add_action( 'yeemail_builder_before', array($this,"add_pro_notification") );
        add_filter( 'plugin_action_links_' . YEEMAIL_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );
    }
    function plugin_action_links($links){
        $action_links = array(
            'settings' => '<a href="' . admin_url( 'edit.php?post_type=yeemail_template' ) . '" aria-label="' . esc_attr__( 'View YeeMail', 'yeemail' ) . '">' . esc_html__( 'Start Customizing', 'yeemail' ) . '</a>',
        );
        $document = array(
            'addons' => '<a target="_blank" href="https://add-ons.org/add-ons/yeemail/" aria-label="' . esc_attr__( 'Add-ons', 'yeemail' ) . '"style="color: rgb(0, 163, 42); font-weight: 700;">' . esc_html__( 'Add-ons', 'yeemail' ) . '</a>',
            'document' => '<a target="_blank" href="https://add-ons.org/yeemail-email-customizer-for-wordpress-documents/" aria-label="' . esc_attr__( 'Document', 'yeemail' ) . '">' . esc_html__( 'Document', 'yeemail' ) . '</a>',
        );
        $links = array_merge( $links, $document );
        return array_merge( $action_links, $links );
    }
    function add_pro_notification($post_id){
        $yemail_pro_id = get_option( "yemail_pro_id");
        if($post_id == $yemail_pro_id){
            $link_addon = "";
            if(isset($_GET["add-ons"])){
                $link_addon = sanitize_text_field($_GET["add-ons"]);
            }
            $link_buy = 'https://add-ons.org/plugin/'.$link_addon;
        ?>
        <div class="yeemail-message-notice-content">
            <div class="yeemail-message-notice-content-inner" style="text-align: center;"> 
                <span class="dashicons dashicons-warning"></span> <?php esc_html_e( "This email template can be fully customized with YeeMail Premium Addon.", "yeemail" ) ?> <strong><a href="<?php echo esc_url( $link_buy) ?>" target="_blank" style="color: rgb(127, 84, 179);"><?php esc_html_e("Buy Now","yeemail") ?></a></strong>
            </div>
        </div>
        <?php
        }
    }
    function remove_submenu() {
        $page = remove_submenu_page( 'edit.php?post_type=yeemail_template', 'post-new.php?post_type=yeemail_template' );
    }
    function columns($columns){
        unset($columns['date']);
        $columns['status']     = esc_html__("Status","yeemail");
        return $columns;
    }
    function custom_column( $column, $post_id ) {
        $post_sst = (isset($_GET["post_status"]))?sanitize_text_field( $_GET["post_status"]):"";
        switch ( $column ) {
            case 'status' :
                if($post_sst == "trash"){
                    ?>
                    <style>
                        .post-type-yeemail_template .tablenav, .post-type-yeemail_template .row-actions {
                                display: block;
                            }
                    </style>   
                    <?php
                }else{
                    $post_status = get_post_status($post_id);
                    $type = get_post_meta( $post_id,'_mail_type',true);
                    $status = get_post_meta( $post_id,'_status',true);
                    $mail_id = get_post_meta( $post_id,'_mail_template',true);
                    if($mail_id != "" ){
                        $status_c = false;
                        if($status == "enable"){
                            $status_c = true;
                        } 
                        ?>
                        <label class="yeemail-switch">
                            <input class="yeemail_settings_update" type="checkbox" value="<?php echo esc_attr( $post_id ) ?>" <?php checked( $status_c ) ?>>
                            <span class="yeemail-slider yeemail-round"></span>
                        </label>
                        <?php
                    }else{
                        $remove_link = get_delete_post_link($post_id);
                        ?>
                        <span class="trash"><a class="" href="<?php echo esc_url($remove_link) ?>"><span class="dashicons dashicons-trash"></span> <?php esc_html_e("Trash","yeemail")  ?></a></span>
                        <?php
                    }
                }
            break;
        }
    }
    public static function is_plugin_active($plugin = "woocommerce"){
        if (!function_exists('is_plugin_active')) {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }
        switch($plugin){
            case "woocommerce":
                if(is_plugin_active( 'woocommerce/woocommerce.php' )){
                    return true;
                }
                break;
            case "edd":
                if(is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' )){
                    return true;
                }
                break;
        }
        return false;
    }
    function add_yeemail_template_to_subsubsub($views){
        $class_default = "";
        $class_all = "";
        $class_core = "";
        $class_woo = "";
        $class_edd = "";
        $class_other = "";
        $trash = '';
        $mail_type = (isset($_GET["mail_type"]))?sanitize_text_field( $_GET["mail_type"]):"default";
        $post_sst = (isset($_GET["post_status"]))?sanitize_text_field( $_GET["post_status"]):"";
        switch ($mail_type){
            case "default":
                $class_default = 'class="current"';
                break;
            case "all":
                if($post_sst == "trash"){
                    $trash = 'class="current"';
                }else{
                    $class_all = 'class="current"';
                }
                break;
            case "core":
                $class_core = 'class="current"';
                break;
            case "other":
                $class_other = 'class="current"';
                break;
            default:
                //$trash = 'class="current"';
                break;
        }
        $total_post = wp_count_posts("yeemail_template")->publish;
        $total_trash = wp_count_posts("yeemail_template")->trash;
        $core_post = new WP_Query(array("posts_per_page"=>-1,"post_type"=>"yeemail_template","meta_key"=>"_mail_type","meta_value"=>"core"));
        $core_other = new WP_Query(array("posts_per_page"=>-1,"post_type"=>"yeemail_template","meta_key"=>"_mail_type","meta_value"=>"other"));
        $default_views = array();
        $default_views["default"] =  sprintf(__('<a href="%s" '.$class_default.'>Default<span class="count">(%d)</span></a>', 'yeemail' ), admin_url('edit.php?post_type=yeemail_template'),1);
        $default_views["all"] =  sprintf(__('<a href="%s" '.$class_all.'>ALL<span class="count">(%d)</span></a>', 'yeemail' ), admin_url('edit.php?post_type=yeemail_template&mail_type=all'),$total_post - 1);
        $default_views["core"] = sprintf(__('<a href="%s" '.$class_core.'>Core WordPress<span class="count">(%d)</span></a>', 'yeemail' ), admin_url('edit.php?post_type=yeemail_template&mail_type=core'), $core_post->post_count);
        $default_views = apply_filters( "views_edit-yeemail_template_addons",$default_views);
        $default_views["other"] = sprintf(__('<a href="%s" '.$class_other.'>Other<span class="count">(%d)</span></a>', 'yeemail' ), admin_url('edit.php?post_type=yeemail_template&mail_type=other'), $core_other->post_count);
        if($total_trash> 0){
            $default_views["trash"] = sprintf(__('<a href="%s" '.$trash.'>Trash<span class="count">(%d)</span></a>', 'yeemail' ), admin_url('edit.php?post_type=yeemail_template&mail_type=all&post_status=trash'), $total_trash);
        }
        return $default_views;
    }
    function query_remove_corder_template($query){
        global $pagenow;
        if ( is_admin() && $query->is_main_query() && $pagenow == "edit.php" && (isset($_GET['post_type']) && $_GET['post_type'] == 'yeemail_template') ) {
            $mail_type = (isset($_GET["mail_type"]))?sanitize_text_field( $_GET["mail_type"]):"default";
            if($mail_type != "all"){
                $query->set( 'meta_query', array(
                    array(
                        'key'     => '_mail_type',
                        'value'   => $mail_type,
                    )
                ) );
            }else{
                $woo_active = self::is_plugin_active("woocommerce");
                $edd_active = self::is_plugin_active("edd");
                $default = array(
                    'key'     => '_mail_type',
                    'value'   => 'default',
                );
                $core = array(
                    'key'     => '_mail_type',
                    'value'   => 'core',
                );
                $other = array(
                    'key'     => '_mail_type',
                    'value'   => 'other',
                );
                $meta_edd = array();
                $meta_woo = array();
                if($edd_active){
                    $meta_edd = array(
                        'key'     => '_mail_type',
                        'value'   => 'edd',
                    );
                }
                if($woo_active){
                    $meta_woo = array(
                        'key'     => '_mail_type',
                        'value'   => 'woocommerce',
                    );
                }
                $query->set( 'meta_query', array(
                    'relation' => 'OR',
                    $default,
                    $other,
                    $core,
                    $meta_edd,
                    $meta_woo
                ) ); 
            }
        }
        return $query;
    }
    function email_builder_main($post ) {
        $post_id= $post->ID;
        $template_id_disable = false;
        $main_width = get_post_meta( $post_id,'_mail_width',true);
        if($main_width < 480 || $main_width== "" ){
            $main_width = "640";   
        }
        $link_color = get_post_meta( $post_id,'_yeemail_link_color',true);
        if($link_color == ""){
            $link_color = "#7f54b3";
        }
        ?>
        <style>
            #poststuff .yeemail-builder-main .builder-row-container-row {
                width: <?php echo esc_attr( $main_width ) ?>px;
            }
            #poststuff .yeemail-builder-main a {
                color: <?php echo esc_attr( $link_color ) ?>;
                font-weight: normal;
            }
            #poststuff .yeemail-builder-main h1 {
                color: <?php echo esc_attr( $link_color ); ?>;
                font-size: 30px;
                font-weight: bold;
                line-height: 150%;
                margin: 0;
                text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
            }
            #poststuff .yeemail-builder-main h2 {
                color: <?php echo esc_attr( $link_color ); ?>;
                display: block;
                font-size: 18px;
                font-weight: bold;
                line-height: 130%;
                margin: 0 0 18px;
                text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
            }
            #poststuff .yeemail-builder-main h3 {
                color: <?php echo esc_attr( $link_color ); ?>;
                display: block;
                font-size: 16px;
                font-weight: bold;
                line-height: 130%;
                margin: 16px 0 8px;
                text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
            }
        </style>
        <div id="builder-header">
            <div id="header-right">
                <div class="header-right-l">
                    <div class="button-select yeemail-tooltip-show" title="Choose Template">
                        <?php
                        $templates_default = get_posts(array("numberposts"=>1,"post_type"=>"yeemail_template","meta_key"=>"_mail_type","meta_value"=>"default"));
                        $templates_core = get_posts(array("numberposts"=>-1,"post_type"=>"yeemail_template","meta_key"=>"_mail_type","meta_value"=>"core"));
                        $templates_other = get_posts(array("numberposts"=>-1,"post_type"=>"yeemail_template","meta_key"=>"_mail_type","meta_value"=>"other"));
                        $templates = array();
                        $woo_active =self::is_plugin_active("woocommerce");
                        $edd_active =self::is_plugin_active("edd");
                        $templates["optgroup_core"] = array("key"=>"optgroup","value"=>"Core WordPress");
                        foreach ( $templates_default as $post_template ) {
                            $post_template_id = $post_template->ID;
                            $type = get_post_meta( $post_template_id,'_mail_type',true); 
                            $status = get_post_meta( $post_template_id,'_status',true); 
                            $custom_css = get_post_meta( $post_template_id,'_mail_template',true);
                            $enable_select = false;
                            $enable = "select_icon_data";
                            if( $status == "enable"){
                                $enable = "select_icon_data select_icon_enable";
                            }
                            if($post_template_id == $post_id ){
                                $enable_select = true;
                            }
                            $templates[$post_template_id] = array("enable"=>$enable,"enable_select"=>$enable_select,"title"=>$post_template->post_title);
                         }
                         foreach ( $templates_core as $post_template ) {
                            $post_template_id = $post_template->ID;
                            $type = get_post_meta( $post_template_id,'_mail_type',true); 
                            $status = get_post_meta( $post_template_id,'_status',true); 
                            $email_id = get_post_meta( $post_template_id,'_mail_template',true);
                            $enable_select = false;
                            $enable = "select_icon_data";
                            if( $status == "enable"){
                                $enable = "select_icon_data select_icon_enable";
                            }
                            if($post_template_id == $post_id ){
                                $enable_select = true;
                            }
                            $templates[$post_template_id] = array("enable"=>$enable,"enable_select"=>$enable_select,"title"=>$post_template->post_title);
                         }
                         $templates["optgroup_core_end"] = array("key"=>"optgroup_end","value"=>"Core WordPress");
                         if($woo_active){
                            $templates_woo = get_posts(array("numberposts"=>-1,"post_type"=>"yeemail_template","meta_key"=>"_mail_type","meta_value"=>"woocommerce"));
                            $templates["optgroup_woo"] = array("key"=>"optgroup","value"=>"WooCommerce");
                            foreach ( $templates_woo as $post_template ) {
                                $post_template_id = $post_template->ID;
                                $type = get_post_meta( $post_template_id,'_mail_type',true); 
                                $status = get_post_meta( $post_template_id,'_status',true); 
                                $email_id = get_post_meta( $post_template_id,'_mail_template',true);
                                $enable_select = false;
                                $enable = "select_icon_data";
                                if( $status == "enable"){
                                    $enable = "select_icon_data select_icon_enable";
                                }
                                if($post_template_id == $post_id ){
                                    $enable_select = true;
                                }
                                $templates[$post_template_id] = array("enable"=>$enable,"enable_select"=>$enable_select,"title"=>$post_template->post_title);
                             }
                             $templates["optgroup_woo_end"] = array("key"=>"optgroup_end","value"=>"WooCommerce");
                         }
                         if($edd_active){
                            $templates_woo = get_posts(array("numberposts"=>-1,"post_type"=>"yeemail_template","meta_key"=>"_mail_type","meta_value"=>"edd"));
                            $templates["optgroup_edd"] = array("key"=>"optgroup","value"=>"Easy Digital Downloads");
                            foreach ( $templates_woo as $post_template ) {
                                $post_template_id = $post_template->ID;
                                $type = get_post_meta( $post_template_id,'_mail_type',true); 
                                $status = get_post_meta( $post_template_id,'_status',true); 
                                $email_id = get_post_meta( $post_template_id,'_mail_template',true);
                                $enable_select = false;
                                $enable = "select_icon_data";
                                if( $status == "enable"){
                                    $enable = "select_icon_data select_icon_enable";
                                }
                                if($post_template_id == $post_id ){
                                    $enable_select = true;
                                }
                                $templates[$post_template_id] = array("enable"=>$enable,"enable_select"=>$enable_select,"title"=>$post_template->post_title);
                             }
                             $templates["optgroup_edd_end"] = array("key"=>"optgroup_end","value"=>"WooCommerce");
                         }
                         $templates = apply_filters( "yeemail_slect_choose_templates_addons", $templates,$post_id );
                         $templates["optgroup_other"] = array("key"=>"optgroup","value"=>"Other");
                         foreach ( $templates_other as $post_template ) {
                            $post_template_id = $post_template->ID;
                            $type = get_post_meta( $post_template_id,'_mail_type',true); 
                            $status = get_post_meta( $post_template_id,'_status',true); 
                            $email_id = get_post_meta( $post_template_id,'_mail_template',true);
                            $enable_select = false;
                            $enable = "";
                            if($email_id != ""){
                                $enable = "select_icon_data";
                            }
                            if( $status == "enable"){
                                $enable = "select_icon_data select_icon_enable";
                            }
                            if($post_template_id == $post_id ){
                                $enable_select = true;
                                if($email_id == ""){
                                    $template_id_disable  = true;
                                }
                            }
                            $templates[$post_template_id] = array("enable"=>$enable,"enable_select"=>$enable_select,"title"=>$post_template->post_title);
                         }
                         $templates["optgroup_other_end"] = array("key"=>"optgroup_end","value"=>"Other");
                         ?>
                        <select class="yeemail_choose_template_status">
                        <?php
                            foreach($templates as $key => $template){
                                if(isset($template["key"]) && $template["key"] == "optgroup"){
                                    ?>
                                    <optgroup class="select2-result-selectable" label="<?php echo esc_html( $template["value"]) ?>"   >  
                                    <?php
                                    continue;
                                }
                                if(isset($template["key"]) && $template["key"] == "optgroup_end"){
                                    ?>
                                    </optgroup>
                                    <?php
                                     continue;
                                }
                            ?>
                                <option data-link="<?php echo esc_url(get_edit_post_link($key)) ?>" data-class="<?php echo esc_attr( $template['enable'] ) ?>" <?php selected($template["enable_select"]) ?> value="<?php echo esc_attr( $key ) ?>"><?php echo esc_html($template['title'] ) ?></option>
                            <?php
                            }
                        ?>
                        </select>
                    </div>
                    <?php do_action( "yeemail_header_builder", $post_id ) ?>
                    <?php if(!$template_id_disable){ 
                        $status = get_post_meta( $post_id,'_status',true);
                        $template_id_enable = false;
                        if($status == "enable"){
                            $template_id_enable = true;
                        } 
                        ?>
                    <div class="yeemail-container-switch yeemail-tooltip-show" title="Enable This Template">
                        <label class="yeemail-switch">
                            <input class="yeemail_settings_update yeemail_settings_update-<?php echo esc_attr( $post_id ) ?>" type="checkbox" value="<?php echo esc_attr( $post_id ) ?>" <?php checked($template_id_enable) ?>>
                            <span class="yeemail-slider yeemail-round"></span>
                        </label>
                    </div>
                    <?php } ?>
                    <div class="button-icon yeemail-builder-choose-shortcodes" title="Shortcodes">
                        <span class="dashicons dashicons-shortcode"></span>
                    </div>
                    <div class="button-icon yeemail-builder-choose-test-email" title="Send Test Email">
                        <span class="dashicons dashicons-email"></span>
                    </div>
                    <div class="button-icon yeemail-builder-choose-blank" title="Blank The Template">
                        <span class="dashicons dashicons-media-default"></span>
                    </div>
                    <div class="button-icon yeemail-builder-choose-reset" title="Reset The Template">
                        <span class="dashicons dashicons-image-rotate"></span>
                    </div>
                </div>
                <div class="header-right-r">
                    <div class="" title="Templates">
                        <a href="#" class="button yeemail-builder-choose-template"><span class="dashicons dashicons-welcome-add-page"></span></a>
                    </div>
                    <div class=""  title="Import Template">
                        <a href="#" class="button yeemail-builder-import"><span class="dashicons dashicons-upload"></span></a>
                    </div>
                    <div class=""  title="Export Template">
                        <a href="#"class="button yeemail-builder-export"><span class="dashicons dashicons-download"></span></a>
                    </div> 
                    <div class="">
                        <?php $id_show_demo = apply_filters( "yeemail_id_show_demo","",$post_id);
                        if($id_show_demo != ""){
                            $link = wp_nonce_url( get_home_url() ."/?email_preview=preview&id=". $post_id."&id_show=".$id_show_demo ,"yeemail");
                        }else{
                            $link = wp_nonce_url( get_home_url() ."/?email_preview=preview&id=". $post_id ,"yeemail");
                        }
                        ?>
                        <a class="button" target="_blank" href="<?php echo esc_url($link) ?>"><span class="dashicons dashicons-visibility"></span> <?php esc_html_e("Preview","yeemail")  ?></a>
                    </div>
                    <div class="">
                        <a href="#" class="button yeemail-builder-save button-primary-ok"><span class="dashicons dashicons-saved"></span> <?php esc_html_e("Save","yeemail")  ?></a>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <?php
        do_action( "yeemail_builder_before", $post_id);
        ?>
        <div class="yeemail-builder-container">
            <div class="yeemail-builder-side">
                <div class="builder__right">
                        <div class="builder__widget">
                            <div class="builder_main_info">
                                <div class="builder_main_info_text">
                                <?php esc_attr_e( "YeeMail - Email Customizer", "yeemail") ?>
                                </div>
                                <div class="builder_main_info_icon"  title="Go To Dashboard">
                                    <a href="<?php echo esc_url( get_admin_url()."edit.php?post_type=yeemail_template") ?>"><span class="dashicons dashicons-wordpress"></span></a>
                                </div>
                            </div>
                            <ul class="builder__tab">
                                <li><a class="active" id="#tab__block"><i class="dashicons dashicons-table-col-after"></i><span><?php esc_html_e("Elements","yeemail")  ?></span> </a></li>
                                <li><a class="" id="#tab__editor"><i class="yeemail_builder-icon icon-pencil"></i><span><?php esc_html_e("Editor","yeemail")  ?></span></a></li>
                            </ul>
                            <div class="tab__inner">
                                <div class="yeemail-builder-expand">
                                    <div class="yeemail-builder-expand-title"></div>
                                    <div class="yeemail-builder-expand-shrink">
                                        <a data-type="left" class="yeemail-builder-expand" href="#"><i class="yeemail_builder-icon icon-right-big"></i></a>
                                        <a data-type="left" class="yeemail-builder-shrink hidden" href="#"><i class="yeemail_builder-icon icon-left-big"></i></a>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="tab__content active" id="tab__block">
                                    <div class="builder__widget--inner">
                                        <div class="builder__widget_tab builder__widget_genaral">
                                            <div class="builder__widget_tab_title"><span class="builder__widget_tab_title_t"><?php esc_attr_e( "Genaral", "yeemail") ?></span><span class="builder__widget_tab_title_icon dashicons dashicons-arrow-down-alt2"></span><span class="builder__widget_tab_title_icon dashicons dashicons-arrow-up-alt2"></span></div>
                                            <ul class="momongaPresets momongaPresets_data">
                                                <?php do_action( "yeemail_builder_tab_block" )?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="builder__widget--inner">
                                        <div class="builder__widget_tab builder__widget_columns">
                                            <div class="builder__widget_tab_title"><span class="builder__widget_tab_title_t"><?php esc_attr_e( "Columns", "yeemail") ?></span><span class="builder__widget_tab_title_icon dashicons dashicons-arrow-down-alt2"></span><span class="builder__widget_tab_title_icon dashicons dashicons-arrow-up-alt2"></span><span class="builder__widget_tab_title_icon dashicons dashicons-arrow-up-alt2"></span></div>
                                            <ul class="builder-row-tool momongaPresets_data">
                                                <?php do_action( "yeemail_builder_tab_block_row" ) ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php do_action( "yeemail_builder_tab_block_addons", $post )?>
                                </div>
                                <div class="tab__content" id="tab__editor">
                                    <div class="builder__editor">
                                        <?php do_action( "yeemail_builder_tab__editor",$post )  ?>
                                    </div>
                                </div>
                            </div>
                            <div class="builder_main_footer">
                                <div class="builder_main_footer_text">
                                    <a href="<?php echo esc_url(get_dashboard_url()) ?>"><span class="dashicons dashicons-arrow-left-alt"></span> <?php esc_attr_e( "BACK TO DASHBOARD", "yeemail" ) ?></a>
                                </div>
                                <div class="builder_main_footer_icon">
                                    <a href="#" class="button button-primary yeemail_button_settings"><?php esc_attr_e( "SETTINGS", "yeemail" ) ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="yeemail-builder-main" data-type="main">
                    <div class="yeemail-builder-main-change_backgroud" data-type="main"><i class="fa fa-pencil"></i> <?php esc_html_e("Background","yeemail") ?></div>
                    <div class="builder__list builder__list--js"> 
                        <div class="builder-row-container builder__item" style="background-color: transparent" >
                            <div style="background-color: #ffffff" background_full="not" data-type="row1" class="builder-row-container-row builder-row-container-row1">
                                <div class="builder-row builder-row-empty">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="yeemail_builder-expand-right hidden">
                <a data-type="right" class="yeemail-builder-expand" href="#"><?php esc_html_e("Expand","yeemail") ?> <i class="yeemail_builder-icon icon-left-big"></i></a>
            </div>
            <?php 
                $data_js = get_post_meta( $post_id,'data_email',true);
            ?>
            <textarea name="data_email" class="yeemail_data_email hidden"><?php echo esc_attr($data_js) ?></textarea>
            <script type="text/javascript">
                <?php
                    $data =array(); 
                    $datas = apply_filters("yeemail_builder_block_html",$data);
                ?>
                var yeemail_builder = <?php echo wp_json_encode($datas) ?>
            </script>
            <?php 
            $mail_type = get_post_meta( $post_id,'_mail_type',true);
            if($mail_type == ""){
                $mail_type ="other"; 
            } 
            ?>
            <input type="hidden" name="yeemail_mail_type" value="<?php echo esc_attr( $mail_type ) ?>" >
            <?php
        wp_enqueue_media();
    }
    function style() {
        global $post;
        if(isset($post->post_type) && $post->post_type == "yeemail_template"){
            $disable_builder = false;
            $ver = time();
            if(isset($_GET["post"])){
                $post_template_id = sanitize_text_field( $_GET["post"] );
                $main_width = get_post_meta( $post_template_id,'_mail_width',true);
                if($main_width < 480 || $main_width== "" ){
                    $main_width = "640";   
                }
                $yemail_pro_id = get_option( "yemail_pro_id");
                if($yemail_pro_id == $post_template_id){
                    $disable_builder = true;
                }
            }else{
                $main_width = "640";
            }
            wp_enqueue_style('selectr', YEEMAIL_PLUGIN_URL. "backend/libs/selectr/selectr.css");
            wp_enqueue_style('yeemail-font', YEEMAIL_PLUGIN_URL."backend/css/yeemail-builder.css",array(),$ver);
            wp_enqueue_style('yeemail-momonga', YEEMAIL_PLUGIN_URL. "backend/css/momonga.css",array("wp-color-picker","thickbox","wp-jquery-ui-dialog"),$ver);
            wp_enqueue_style('yeemail-main', YEEMAIL_PLUGIN_URL."backend/css/main.css",array(),$ver);
            wp_enqueue_script('selectr', YEEMAIL_PLUGIN_URL ."backend/libs/selectr/selectr.js",array());
            wp_register_script('yeemail_code_toggle', YEEMAIL_PLUGIN_URL ."backend/src/tinymce-ace.js",array(),$ver);
            wp_register_script('yeemail-builder-main', YEEMAIL_PLUGIN_URL."backend/src/main.js",array("yeemail_code_toggle"), $ver);
            wp_register_script("yeemail", YEEMAIL_PLUGIN_URL."backend/src/builder.js",array("yeemail-builder-main"), $ver);
            wp_register_script('yeemail-editor', YEEMAIL_PLUGIN_URL."backend/src/set_editor.js",array("yeemail"), $ver);
            wp_enqueue_script('yeemail_script', YEEMAIL_PLUGIN_URL."backend/src/script.js",array("jquery","jquery-ui-core","jquery-ui-dialog","jquery-ui-sortable","jquery-ui-draggable","jquery-ui-droppable","wp-color-picker","wp-tinymce","yeemail-editor","thickbox","jquery-effects-core","jquery-effects-scale"),$ver);
            $builder_shorcode = apply_filters("yeemail_builder_shortcode",array());
            $shortcodes = Yeemail_Builder_Email_Shortcode::list_shortcodes();
            $builder_shorcode_re ="";
            $i= 0;
            foreach( $builder_shorcode as $k=>$v){
                $k = str_replace(array("[","]"), "", $k);
                if($i == 0){
                    $builder_shorcode_re .="\[".$k."\]";
                }else{
                    $builder_shorcode_re .="|\[".$k."\]";
                }
                $i++;
            }
            wp_localize_script( 'yeemail_script', 'yeemail_script',
                array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 
                    'youtube_play_src' => YEEMAIL_PLUGIN_URL."images/youtube_play.png",
                    'yeemail_url_plugin' => YEEMAIL_PLUGIN_URL,
                    'nonce' => wp_create_nonce('yeemail'),
                    'email_width'=> $main_width,
                    'builder_shorcode' => $builder_shorcode,
                    'builder_shorcode_re' => $builder_shorcode_re,
                    'nonce' => wp_create_nonce( 'yeemail_editor' ),
                    'shortcodes'=>$shortcodes,
                    'disable_builder'=>$disable_builder,
                    'google_font_font_formats' => Yeemail_Settings_Builder_Backend::google_font("font_formats"),
                ) );
        }
    }
    function create_posttype() {
        register_post_type( 'yeemail_template',
            array(
                'labels' => array(
                    'name' => __( 'Email Templates',"yeemail" ),
                    'singular_name' => __( 'yeemail_templates',"yeemail" ),
                    'add_new' => __( 'New Template',"yeemail" ),
                    'search_items' => __( 'Seach Templates',"yeemail" ),
                    'not_found' => __( 'No template found',"yeemail" ),
                    'menu_name' => 'YeeMail',
                ),
                'public' => true,
                'has_archive' => true,
                'supports'    => array( 'title' ),
                'show_in_menu' => true,
                'rewrite' => array('slug' => 'yeemail_template'),
                'show_in_rest' => true,
                'menu_icon'           => self::get_logo_url(),
                'menu_position'=>79,
                'exclude_from_search' => true,
                'publicly_queryable' => false,
                'query_var'=>false,
                'capabilities' => array(
                    'edit_post'          => 'manage_options',
                    'read_post'          => 'manage_options',
                    'delete_post'        => 'manage_options',
                    'edit_posts'         => 'manage_options',
                    'edit_others_posts'  => 'manage_options',
                    'delete_posts'       => 'manage_options',
                    'publish_posts'      => 'manage_options',
                    'read_private_posts' => 'manage_options'
                ),
            )
        );
    }
    public static function get_logo_url() {
        return 'data:image/svg+xml;base64,PHN2ZyBjbGFzcz0ic3ZnLWljb24iIHN0eWxlPSJ3aWR0aDogMWVtOyBoZWlnaHQ6IDFlbTt2ZXJ0aWNhbC1hbGlnbjogbWlkZGxlO2ZpbGw6IGN1cnJlbnRDb2xvcjtvdmVyZmxvdzogaGlkZGVuOyIgdmlld0JveD0iMCAwIDEwMjQgMTAyNCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxwYXRoIGQ9Ik05MzguNjY2NjY3IDU2OS4yMTZBMjU2IDI1NiAwIDAgMCA2MTEuODgyNjY3IDg5NkgxMjhhNDIuNjY2NjY3IDQyLjY2NjY2NyAwIDAgMS00Mi42NjY2NjctNDIuNjY2NjY3VjE3MC42NjY2NjdhNDIuNjY2NjY3IDQyLjY2NjY2NyAwIDAgMSA0Mi42NjY2NjctNDIuNjY2NjY3aDc2OGE0Mi42NjY2NjcgNDIuNjY2NjY3IDAgMCAxIDQyLjY2NjY2NyA0Mi42NjY2Njd2Mzk4LjU0OTMzM3ogbS00MjQuMTA2NjY3LTcwLjc0MTMzM0wyNDAuOTgxMzMzIDI2Ni4xNTQ2NjcgMTg1LjcyOCAzMzEuMTc4NjY3bDMyOS4zODY2NjcgMjc5LjY4IDMyMy40NTYtMjc5Ljg5MzMzNC01NS44MDgtNjQuNTU0NjY2LTI2OC4xNiAyMzIuMDY0eiBtMjEyLjkwNjY2NyAzMzUuNTczMzMzYTEyOC43MjUzMzMgMTI4LjcyNTMzMyAwIDAgMSAwLTQ2Ljc2MjY2N2wtNDMuMjY0LTI1LjAwMjY2NiA0Mi42NjY2NjYtNzMuODk4NjY3IDQzLjI2NCAyNS4wMDI2NjdjMTEuODYxMzMzLTEwLjE1NDY2NyAyNS41NTczMzMtMTguMTMzMzMzIDQwLjUzMzMzNC0yMy40NjY2NjdWNjQwaDg1LjMzMzMzM3Y0OS45MmMxNC45NzYgNS4zMzMzMzMgMjguNjcyIDEzLjMxMiA0MC41MzMzMzMgMjMuNDY2NjY3bDQzLjI2NC0yNS4wMDI2NjcgNDIuNjY2NjY3IDczLjg5ODY2Ny00My4yNjQgMjUuMDAyNjY2YTEyOC43MjUzMzMgMTI4LjcyNTMzMyAwIDAgMSAwIDQ2Ljc2MjY2N2w0My4yNjQgMjUuMDAyNjY3LTQyLjY2NjY2NyA3My44OTg2NjYtNDMuMjY0LTI1LjAwMjY2NmExMjcuODcyIDEyNy44NzIgMCAwIDEtNDAuNTMzMzMzIDIzLjQ2NjY2NlY5ODEuMzMzMzMzaC04NS4zMzMzMzN2LTQ5LjkyYTEyNy44NzIgMTI3Ljg3MiAwIDAgMS00MC41MzMzMzQtMjMuNDY2NjY2bC00My4yNjQgMjUuMDAyNjY2LTQyLjY2NjY2Ni03My44OTg2NjYgNDMuMjY0LTI1LjAwMjY2N3pNODUzLjMzMzMzMyA4NTMuMzMzMzMzYTQyLjY2NjY2NyA0Mi42NjY2NjcgMCAxIDAgMC04NS4zMzMzMzMgNDIuNjY2NjY3IDQyLjY2NjY2NyAwIDAgMCAwIDg1LjMzMzMzM3oiICAvPjwvc3ZnPg==';
    }
    function save_metabox($post_id, $post) {
        if( isset($_POST['data_email'])) {
            $datas =  $_POST['data_email'] ;//phpcs:ignore WordPress.Security.NonceVerification
            update_post_meta($post_id,'data_email',$datas); 
        }
        if( isset($_POST['custom_css'])) {
            update_post_meta($post_id,'_custom_css',sanitize_textarea_field( $_POST['custom_css'] ));
        }
        if( isset($_POST['yeemail_settings_width'])) {
            update_post_meta($post_id,'_mail_width',sanitize_textarea_field( $_POST['yeemail_settings_width'] ));
        }
        if( isset($_POST['yeemail_mail_type'])) {
            update_post_meta($post_id,'_mail_type',sanitize_textarea_field( $_POST['yeemail_mail_type'] ));
        }
        if( isset($_POST['yeemail_mail_template'])) {
            update_post_meta($post_id,'_mail_template',sanitize_textarea_field( $_POST['_mail_template'] ));
        }
        if( isset($_POST['yeemail_link_color'])) {
            update_post_meta($post_id,'_yeemail_link_color',sanitize_textarea_field( $_POST['yeemail_link_color'] ));
        }
        if( isset($_POST['yeemail_custom_subject'])) {
            update_post_meta($post_id,'_yeemail_custom_subject',sanitize_textarea_field( $_POST['yeemail_custom_subject'] ));
        }
    }
    function remove_view_action(){
        global $post_type;
        if ( 'yeemail_template' === $post_type ) {
            unset( $actions['view'] );
        }
        return $actions;
    }
    function remove_permalink($link){
        global $post_type;
        if ( 'yeemail_template' === $post_type ) {
            return "";
        }else{
            return $link;
        }
    }
    function add_meta_boxes() {
        add_meta_box(
            'yeemail-builder-main',
            esc_html__( 'Builder Email', "yeemail" ),
            array( $this, 'email_builder_main' ),
            'yeemail_template',
            'normal',
            'default'
        );
    }
    function body_class( $classes ) {
        global $post_type, $post;
        $screen = get_current_screen();
        if ( 'yeemail_template' == $post_type && $screen->id == 'yeemail_template' ) {
            if(isset($post->ID)){
                $mail_id = get_post_meta( $post->ID, "_mail_template", true );
                if($mail_id != ""){
                    $classes .= " yeemail_type_main_builder";
                }
            }
            return  $classes . " post-php";
        }else{
            return  $classes;
        }
    }
    function add_page_templates(){
        add_thickbox(); 
        ?>
        <div id="yeemail-builder-templates" style="display:none">
            <div class="list-view-templates">
                <?php 
                $args = array(
                    "json"=>"",
                    "img"=>YEEMAIL_PLUGIN_URL."backend/demo/template1/1.png",
                    "title"=>"Email templates",
                    "cat" => array(),
                    "id"=>0,
                );
                do_action( "yeemail_builder_templates" );
                ?>
            </div>       
        </div>
        <div id="yeemail-builder-shortcodes-templates" style="display:none">
            <div class="list-view-short-templates">
                <?php 
                $shortcodes = Yeemail_Builder_Email_Shortcode::list_shortcodes();
                foreach( $shortcodes as $shortcode_k =>$shortcode_v){
                ?>
                <h3><?php echo esc_html( $shortcode_k ) ?></h3>
                <?php 
                foreach( $shortcode_v as $k =>$v){
                    if(is_array($v)){
                        ?>
                        <h4><?php echo esc_html( $k ) ?></h4>
                        <?php
                        foreach( $v as $k_i =>$v_i){
                            ?>
                            <div class="list-view-short-templates-r">
                                <div class="list-view-short-templates-k">
                                    <?php 
                                    if (strpos($k_i, "{") === false) { 
                                        echo esc_html( "[".$k_i."]" );
                                    }else{
                                        echo esc_html( $k_i);
                                    }
                                    ?>
                                </div>
                                <div class="list-view-short-templates-v">
                                    <?php echo esc_html( $v_i ) ?>
                                </div>
                            </div>
                            <?php
                        }
                    }else{
                        ?>
                        <div class="list-view-short-templates-r">
                            <div class="list-view-short-templates-k">
                                <?php 
                                if (strpos($k, "{") === false) { 
                                    echo esc_html( "[".$k."]" );
                                }else{
                                    echo esc_html( $k);
                                }
                                ?>
                            </div>
                            <div class="list-view-short-templates-v">
                                <?php echo esc_html( $v ) ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                } 
                ?>
            </div>       
        </div>
        <div id="yeemail-builder-templates-test-email" style="display:none">
            <div class="list-view-templates-test-email">
                <div class="yeemail-builder-row-tool">
                    <h3><?php esc_html_e("Testing","yeemail") ?></h3>
                    <ul>
                    <li><input type="email" id="yeemail-builder-testting" placeholder="Email"></li>
                    <li><a href="#" class="button button-primary yeemail-builder-testting-send"><?php esc_html_e("Send Email","yeemail") ?></a></li>
                    </ul>
                </div>
            </div>       
        </div>
        <?php
    }
    public static function item_demo($args1){
        $defaults = array(
            "json"=>"",
            "img"=>YEEMAIL_PLUGIN_URL."backend/demo/template1/1.png",
            "title"=>"Email templates",
            "url" => "#",
            "id"=>0,
            "cat" => array(),
        );
        $args = wp_parse_args( $args1, $defaults );
        $domain = "https://demo.add-ons.org/yeemail-demo/";
        $url_view = $domain."?email_preview=preview&id=".$args["id"];
        $url_design = $domain."?templates_id=".$args["id"];
        ?>
        <div class="grid-item" data-file="<?php echo esc_url($args["json"]) ?>">
            <img src="<?php echo esc_url($args["img"]) ?>">
            <div class="demo_content">
                <div class="demo-title"><?php echo esc_html($args["title"]) ?></div>
                <div class="demo-tags"><?php echo implode(", ",$args["cat"]) ?></div>
                <div class="yeemail-builder-actions">
                        <div class="demo-fl">
                            <a class="button yeemail-builder-actions-import" href="#"><?php esc_html_e("Import","yeemail") ?></a>
                            <a target="_blank" class="button yeemail-builder-actions-design" href="<?php echo esc_url($url_design) ?>"><?php esc_html_e("Design","yeemail") ?></a>
                        </div>
                        <div class="demo-fr">
                            <a target="_blank" class="button yeemail-builder-actions-view" href="<?php echo esc_url($url_view) ?>"><?php esc_html_e("Preview","yeemail") ?></a>
                        </div>
                        <div class="clear"></div>
                </div>
            </div>
        </div>
        <?php
    }
    public static function google_font($type){
        switch ($type) {
            case "font_formats":
                return 'Helvetica Neue, Helvetica, Roboto, Arial, sans-serif=Helvetica Neue, Helvetica, Roboto, Arial, sans-serif; Arial Black=Arial Black; Arial, Helvetica, sans-serif=Arial, Helvetica, sans-serif; Courier=Courier; Courier New=Courier New; Lucida=Lucida; Tahoma=Tahoma; Times New Roman=Times New Roman; Times=Times; Tahoma=Tahoma; Lucida=Lucida; Trebuchet MS=Trebuchet MS; Verdana=Verdana;';
                break;
            case "add_css":
                return array(
                    "Lato","Roboto","Oswald","Raleway","Open+Sans"
                );
                break;
            default:
                return 'https://fonts.googleapis.com/css2?family=Lato&family=Roboto&family=Oswald&family=Raleway&family=Open+Sans&display=swap';
                break;
        }
    }
}
new Yeemail_Settings_Builder_Backend;