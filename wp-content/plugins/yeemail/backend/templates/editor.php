<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class yeemail_builder_editor {
    function __construct(){
        add_action("yeemail_builder_tab__editor",array($this,"yeemail_builder_tab__editor"));  
    }
    public static function get_color_pick($text = "Color Pick",$name = "yeemail_name[]", $value = "",$class ="builder__editor_color"){
        ?>
        <div class="builder__editor--color">
            <label><?php echo esc_html( $text ) ?></label>
            <div class="">
                <input name="<?php echo esc_attr( $name ) ?>" type="text" value="<?php echo esc_attr( $value ) ?>" class="<?php echo esc_attr( $class ) ?>">
            </div>
        </div>
        <?php
    }
    public static function get_padding($text="",$class="") {
        ?>
        <div class="yeemail_setting_group <?php echo esc_attr( $class ) ?>">
            <?php if($text!= ""){
            ?>
            <div class="yeemail_setting_title">
                <?php echo esc_html( $text ) ?>
            </div>
            <?php
            } ?>
            <div class="yeemail_setting_row">
                <div class="yeemail_settings_group-wrapper">
                    <label class="yeemail_checkbox_label"><?php esc_html_e("Top","pdf-for-wpforms") ?></label>
                    <div class="yeemail_setting_input-wrapper">
                        <input name="yeemail_name[]" class="builder__editor--padding-top setting_input" step="1" type="number" data-after_value="px">
                    </div>
                </div>
                <div class="yeemail_settings_group-wrapper">
                    <label class="yeemail_checkbox_label"><?php esc_html_e("Right","pdf-for-wpforms") ?></label>
                    <div class="yeemail_setting_input-wrapper">
                        <input name="yeemail_name[]" class="builder__editor--padding-right setting_input" step="1" type="number" data-after_value="px">
                    </div>
                </div>
                <div class="yeemail_settings_group-wrapper">
                    <label class="yeemail_checkbox_label"><?php esc_html_e("Bottom","pdf-for-wpforms") ?></label>
                    <div class="yeemail_setting_input-wrapper">
                        <input name="yeemail_name[]" class="builder__editor--padding-bottom setting_input" step="1" type="number" data-after_value="px">
                    </div>
                </div>
                <div class="yeemail_settings_group-wrapper">
                    <label class="yeemail_checkbox_label"><?php esc_html_e("Left","pdf-for-wpforms") ?></label>
                    <div class="yeemail_setting_input-wrapper">
                        <input name="yeemail_name[]" class="builder__editor--padding-left setting_input" step="1" type="number" data-after_value="px">
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    function yeemail_builder_tab__editor($post){
        ?>
        <div class="yeemail-builder-goback">
            <span class="dashicons dashicons-arrow-left-alt"></span>
            <span class="yeemail-builder-goback_edit"><?php esc_attr_e( "Edit", "yeemail" ) ?></span>
            <span class="yeemail-builder-goback_block"></span>
        </div>
        <?php do_action( "yeemail_builder_tab__editor_before",$post ); ?>
        <div class="builder__editor--item builder__editor--item-setting_width">
            <?php
                if(isset($_GET["post"])){
                    $post_template_id = sanitize_text_field( $_GET["post"] );
                    $width = get_post_meta( $post_template_id,'_mail_width',true);
                    if($width < 480 || $width== "" ){
                        $width = "640";   
                    }
                }else{
                    $width = "640";
                }
            ?>
            <div class="yeemail_setting_group">
                <div class="yeemail_setting_row">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Email Width","pdf-for-wpforms") ?></label>
                        <input name="yeemail_settings_width" type="number" class="yeemail_setting_input text_width" data-after_value="px" value="<?php echo esc_attr( $width ) ?>" />
                        <p><?php esc_attr_e( "Email width must be 480px (min) - 900px (max) ", "yeemail") ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-html_editor">
            <div class="builder__editor--html_editor">
                <label><?php esc_html_e("HTML Code","yeemail") ?></label>
                <textarea class="builder__editor_html"></textarea>
            </div>
        </div> 
        <div class="builder__editor--item builder__editor--item-html">
            <textarea class="builder__editor--js"></textarea>
        </div>
        <div class="builder__editor--item builder__editor--item-video">
            <div class="builder__editor--video">
                <label><?php esc_html_e("Video URL","yeemail") ?></label>
                <input type="text" class="video_url">
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-image">
            <label><?php esc_html_e("Image","yeemail") ?></label>
            <div class="yeemail_setting_group">
                <div class="yeemail_setting_row builder__editor--button-url">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("URL","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input image_url"  />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Upload","pdf-for-wpforms") ?></label>
                        <input type="button" class="upload-editor--image button" value="Upload">
                    </div>
                </div>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-button">
            <label><?php esc_html_e("Button","yeemail") ?></label>
            <div class="yeemail_setting_group">
                <div class="yeemail_setting_row builder__editor--button">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Button text","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input button_text" value="Button text" />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Button url","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input button_url" />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Font size","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="number" class="yeemail_setting_input font_size" data-after_value="px" min="10" max="30" />
                    </div>
                </div>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-background">
        <label><?php esc_html_e("Background","pdf-for-wpforms") ?></label>
            <div class="yeemail_setting_group">
                <div class="yeemail_setting_row builder__editor--button-url">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label">Color</label>
                        <input name="yeemail_name[]" type="text" value="" class="builder__editor_color yeemail_setting_input">
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label">Image</label>
                        <input name="yeemail_name[]" type="text" class="image_url yeemail_setting_input" placeholder="Source url">
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label">Upload</label>
                        <input name="yeemail_name[]" type="button" class="upload-editor--image button button-primary" value="Upload">
                    </div>
                </div>
                <div class="yeemail_setting_row ">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_attr_e( "Background-repeat", "pdf-for-wpforms" ) ?></label>
                        <select name="yeemail_name[]" class="yeemail_setting_input builder__editor_background_repeat">
                            <option value="no-repeat">no-repeat</option>
                            <option value="repeat">repeat</option>
                            <option value="repeat-x">repeat-x</option>
                            <option value="repeat-y">repeat-y</option>
                        </select>
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_attr_e( "Background-size", "pdf-for-wpforms" ) ?></label>
                        <select name="yeemail_name[]" class="yeemail_setting_input builder__editor_background_size">
                            <option value="cover">cover</option>
                            <option value="auto">auto</option>
                            <option value="contain">contain</option>
                        </select>
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_attr_e( "Background-position", "pdf-for-wpforms" ) ?></label>
                        <select name="yeemail_name[]" class="yeemail_setting_input builder__editor_background_position">
                            <option value="0% %0">left top</option>
                            <option value="0% 100%">left bottom</option>
                            <option value="0% 50%">left center</option>
                            <option value="100% 0%">right top</option>
                            <option value="100% 100%">right bottom</option>
                            <option value="100% 50%">right center</option>
                            <option value="50% 50%">center center</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-background_full">
            <label><?php esc_html_e("Enable Background Full Width","yeemail") ?></label>
            <div>
                <label class="yeemail-switch">
                    <input type="checkbox" class="background_full_width" >
                    <span class="yeemail-slider yeemail-round"></span>
                </label>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-background_responsive">
            <label><?php esc_html_e("Enable Responsive","yeemail") ?></label>
            <div>
                <label class="yeemail-switch">
                    <input type="checkbox" class="background_responsive" >
                    <span class="yeemail-slider yeemail-round"></span>
                </label>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-color">
            <?php yeemail_builder_editor::get_color_pick(esc_html__("Color","yeemail")); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </div>
        <div class="builder__editor--item builder__editor--item-link_color">
            <?php 
            $post_id = 0;
            $link_color = "#7f54b3";
            if(isset($_GET["post"])){
                $post_id = sanitize_text_field( $_GET["post"] );
                $link_color = get_post_meta( $post_id,'_yeemail_link_color',true);
                if($link_color == ""){
                    $link_color = "#7f54b3";
                }
            }
            yeemail_builder_editor::get_color_pick(esc_html__("Link Color","yeemail"),"yeemail_link_color",$link_color,"builder__editor_color_link"); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </div>
        <div class="builder__editor--item builder__editor--item-menu">
        <label><?php esc_html_e("Menu","yeemail") ?></label>
            <div class="builder__editor--item-menu-hidden hidden">
                <div class="yeemail_setting_group">
                    <div class="yeemail_setting_row">
                        <div class="yeemail_settings_group-wrapper">
                            <label class="yeemail_checkbox_label"><?php esc_html_e("Text","pdf-for-wpforms") ?></label>
                            <input name="yeemail_name[]" type="text" class="yeemail_setting_input text"  />
                        </div>
                        <div class="yeemail_settings_group-wrapper">
                            <label class="yeemail_checkbox_label"><?php esc_html_e("URL","pdf-for-wpforms") ?></label>
                            <input name="yeemail_name[]" type="text" class="yeemail_setting_input text_url"  />
                        </div>
                    </div>
                    <div class="yeemail_setting_row">
                        <div class="yeemail_settings_group-wrapper">
                            <label class="yeemail_checkbox_label"><?php esc_html_e("Background","pdf-for-wpforms") ?></label>
                            <input name="yeemail_name[]" type="text" class="yeemail_setting_input text_background" value="transparent" />
                        </div>
                        <div class="yeemail_settings_group-wrapper">
                            <label class="yeemail_checkbox_label"><?php esc_html_e("Color","pdf-for-wpforms") ?></label>
                            <input name="yeemail_name[]" type="text" class="yeemail_setting_input text_color"  />
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-content-tool">
            </div>
            <a class="yeemail_builder_add_menu button" href="#"><?php esc_html_e("Add menu","yeemail") ?></a>
        </div>
        <div class="builder__editor--item builder__editor--item-social">
            <label><?php esc_html_e("Social","yeemail") ?></label>
            <div class="yeemail_setting_group">
                <div class="yeemail_setting_row builder__editor--social-facebook">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("URL Facebook","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input social_url"  />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Show/ hide","pdf-for-wpforms") ?></label>
                        <label class="yeemail-switch">
                            <input type="checkbox" class="social_show" name="yeemail_name[]" >
                            <span class="yeemail-slider yeemail-round"></span>
                        </label>
                    </div>
                </div>
                <div class="yeemail_setting_row builder__editor--social-twitter">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("URL Twitter","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input social_url"  />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Show/ hide","pdf-for-wpforms") ?></label>
                        <label class="yeemail-switch">
                            <input type="checkbox" class="social_show" name="yeemail_name[]" >
                            <span class="yeemail-slider yeemail-round"></span>
                        </label>
                    </div>
                </div>
                <div class="yeemail_setting_row builder__editor--social-instagram">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("URL Instagram","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input social_url"  />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Show/ hide","pdf-for-wpforms") ?></label>
                        <label class="yeemail-switch">
                            <input type="checkbox" class="social_show" name="yeemail_name[]" >
                            <span class="yeemail-slider yeemail-round"></span>
                        </label>
                    </div>
                </div>
                <div class="yeemail_setting_row builder__editor--social-linkedin">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("URL Linkedin","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input social_url"  />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Show/ hide","pdf-for-wpforms") ?></label>
                        <label class="yeemail-switch">
                            <input type="checkbox" class="social_show" name="yeemail_name[]" >
                            <span class="yeemail-slider yeemail-round"></span>
                        </label>
                    </div>
                </div>
                <div class="yeemail_setting_row builder__editor--social-whatsapp">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("URL Whatsapp","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input social_url"  />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Show/ hide","pdf-for-wpforms") ?></label>
                        <label class="yeemail-switch">
                            <input type="checkbox" class="social_show" name="yeemail_name[]" >
                            <span class="yeemail-slider yeemail-round"></span>
                        </label>
                    </div>
                </div>
                <div class="yeemail_setting_row builder__editor--social-youtube">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("URL Youtube","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input social_url"  />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Show/ hide","pdf-for-wpforms") ?></label>
                        <label class="yeemail-switch">
                            <input type="checkbox" class="social_show" name="yeemail_name[]" >
                            <span class="yeemail-slider yeemail-round"></span>
                        </label>
                    </div>
                </div>
                <div class="yeemail_setting_row builder__editor--social-skype">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("URL Skype","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input social_url"  />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Show/ hide","pdf-for-wpforms") ?></label>
                        <label class="yeemail-switch">
                            <input type="checkbox" class="social_show" name="yeemail_name[]" >
                            <span class="yeemail-slider yeemail-round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-text-align">
            <label><?php esc_html_e("Text align","yeemail") ?></label>
            <div class="builder__editor--align">
                <a class="button__align builder__editor--align-left" data-value="left"><i class="yeemail_builder-icon icon-align-left"></i></a>
                <a class="button__align builder__editor--align-center" data-value="center"><i class="yeemail_builder-icon icon-align-justify"></i></a>
                <a class="button__align builder__editor--align-right" data-value="right"><i class="yeemail_builder-icon icon-align-right"></i></a>
                <input type="text" value="left" class="text_align hidden">
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-width">
            <div class="yeemail_setting_group">
                <div class="yeemail_setting_row">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Width","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input text_width" data-after_value="px" />
                    </div>
                </div>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-height">
            <div class="yeemail_setting_group">
                <div class="yeemail_setting_row">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Height","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input text_height" data-after_value="px"  />
                    </div>
                </div>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-width_height">
            <label><?php esc_html_e("Size","pdf-for-wpforms") ?></label>
            <div class="yeemail_setting_group">
                <div class="yeemail_setting_row">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Width","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input text_width" data-after_value="px" />
                    </div>
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Height","pdf-for-wpforms") ?></label>
                        <input name="yeemail_name[]" type="text" class="yeemail_setting_input text_height" data-after_value="px" />
                    </div>
                </div>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-padding">
            <label><?php esc_html_e("Padding","yeemail") ?></label>
            <?php yeemail_builder_editor::get_padding() ;// phpcs:ignore WordPress.Security.EscapeOutput ?>
        </div>
        <div class="builder__editor--item builder__editor--item-margin">
            <label><?php esc_html_e("Margin","yeemail") ?></label>
            <?php  yeemail_builder_editor::get_padding() ;// phpcs:ignore WordPress.Security.EscapeOutput ?>
        </div>
        <div class="builder__editor--item builder__editor--item-border">
            <label><?php esc_html_e("Border","yeemail") ?></label>
            <label><?php esc_html_e("Border Width","yeemail") ?></label>
            <div class="builder__editor--item-border-width">
                <?php yeemail_builder_editor::get_padding() ;// phpcs:ignore WordPress.Security.EscapeOutput ?>
                <label class="hidden"><?php esc_html_e("Border Style","yeemail") ?></label>
                <input type="text" value="solid" class="border_style hidden">
                <?php yeemail_builder_editor::get_color_pick(esc_html__("Border color","yeemail")) ;// phpcs:ignore WordPress.Security.EscapeOutput ?> 
            </div>
            <label><?php esc_html_e("Border radius","yeemail") ?></label>
            <div class="builder__editor--item-border-radius">
                <?php yeemail_builder_editor::get_padding() ;// phpcs:ignore WordPress.Security.EscapeOutput ?>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-conditional_logic">
            <label><?php esc_html_e("Conditional Logic","yeemail") ?></label>
            <div class="builder__editor--item-border-conditional_logic">
                <?php
                $text = esc_attr__( "The feature need add-on: ","yeemail" ) .'<a href=" https://add-ons.org/plugin/yeemail-conditional-logic/" target="_blank">'. esc_html__("Buy Now","yeemail").'</a>';
                echo apply_filters( "yeemail_contional_logic_settings", $text ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                ?>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-custom_css">
            <label><?php esc_html_e("Custom CSS","yeemail") ?></label>
            <div class="builder__editor--button-custom_css builder__editor--button-settings">
                    <?php 
                    $custom_css ="";
                    if(isset($_GET["post"])){
                        $post_id = sanitize_text_field( $_GET['post'] );
			            $custom_css = get_post_meta( $post_id,'_custom_css',true); 
                    }
                    ?>
                    <textarea name="custom_css" class="custom_css"><?php echo esc_attr( $custom_css ) ?></textarea>
            </div>
        </div>
        <div class="builder__editor--item builder__editor--item-settings">
            <label><?php esc_html_e("Settings","yeemail") ?></label>
            <div class="builder__editor--button-settings">
                <div class="builder__editor--button-settings-inner">
                    <label><?php esc_html_e("Enable/Disable Templates","yeemail") ?></label>
                    <div class="builder__settings_templates_container">
                        <?php
                            $templates_post = get_posts(array("numberposts"=>-1,"post_type"=>"yeemail_template","meta_key"=>"_mail_template","meta_value"=>"","meta_compare"=>"!="));
                            $woo_active = Yeemail_Settings_Builder_Backend::is_plugin_active("woocommerce");
                            $edd_active = Yeemail_Settings_Builder_Backend::is_plugin_active("edd");
                            $pro_id= get_option( "yemail_pro_id", 0 );
                            foreach ( $templates_post as $post_template ) {
                                $post_template_id = $post_template->ID;
                                if( $pro_id == $post_template_id ){
                                    continue;
                                }
                                $status = get_post_meta( $post_template_id,'_status',true);
                                $mail_type = get_post_meta( $post_template_id,'_mail_type',true);
                                if( !$woo_active && $mail_type == "woocommerce" ){
                                    continue;
                                }
                                if( !$edd_active && $mail_type == "edd" ){
                                    continue;
                                }
                                $enable = false; 
                                if($status == "enable"){
                                    $enable = true;
                                }
                        ?>
                        <div class="builder__settings_templates_item">
                            <div class="builder__settings_templates_item_label">
                                <?php echo esc_html( $post_template->post_title) ?>
                            </div>
                            <div class="builder__settings_templates_item_input">
                                <label class="yeemail-switch">
                                    <input class="yeemail_settings_update yeemail_settings_update-<?php echo esc_attr( $post_template_id ) ?>" <?php checked( $enable ) ?> type="checkbox" value="<?php echo esc_attr( $post_template_id ) ?>">
                                    <span class="yeemail-slider yeemail-round"></span>
                                </label>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
                $custom_subject = get_post_meta( $post_id,'_yeemail_custom_subject',true); 
                $mail_type = get_post_meta( $post_id,'_mail_type',true);
                if($mail_type == "core"){
            ?>
            <label><?php esc_html_e("Custom subject","yeemail") ?></label>
            <div class="yeemail_setting_group">
                <div class="yeemail_setting_row">
                    <div class="yeemail_settings_group-wrapper">
                        <label class="yeemail_checkbox_label"><?php esc_html_e("Subject","pdf-for-wpforms") ?></label>
                        <input name="yeemail_custom_subject" type="text" class="yeemail_setting_input" value="<?php echo esc_attr( $custom_subject ) ?>"  />
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php
    }
}
new yeemail_builder_editor();