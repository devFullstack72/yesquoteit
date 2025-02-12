<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action( 'yeemail_builder_block_html', "yeemail_builder_block_main_load" );
function yeemail_builder_block_main_load($type) {
	$type["block"]["main"]["editor"]["container"]["show"]= ["background","color","link_color","padding","setting_width","custom_css","settings","addons"];
    $padding = Yeemail_Builder_Global_Data::$padding;
    $boder = Yeemail_Builder_Global_Data::$border;
    $settings = array_merge($padding,$boder);
    $type["block"]["main"]["editor"]["container"]["style"]= array_merge($padding,array(
                ".builder__editor--item-background .builder__editor_color"=>"background-color",
                ".builder__editor--item-color .builder__editor_color"=>"color",
                ".builder__editor--item-background .image_url"=>"background-image",
                ".builder__editor--item-background .builder__editor_background_repeat"=>"background-repeat",
                ".builder__editor--item-background .builder__editor_background_size"=>"background-size",
                ".builder__editor--item-background .builder__editor_background_position"=>"background-position",
            ));
	return $type;
}
class Yeemail_Builder_Global_Data {
    public static $padding = array(
        ".builder__editor--item-padding .builder__editor--padding-top"=>"padding-top",
        ".builder__editor--item-padding .builder__editor--padding-bottom"=>"padding-bottom",
        ".builder__editor--item-padding .builder__editor--padding-left"=>"padding-left",
        ".builder__editor--item-padding .builder__editor--padding-right"=>"padding-right",
    );
    public static $margin = array(
        ".builder__editor--item-margin .builder__editor--padding-top"=>"margin-top",
        ".builder__editor--item-margin .builder__editor--padding-bottom"=>"margin-bottom",
        ".builder__editor--item-margin .builder__editor--padding-left"=>"margin-left",
        ".builder__editor--item-margin .builder__editor--padding-right"=>"margin-right",
    );
    public static $text_align = array(
        ".builder__editor--item-text-align .text_align"=>"text-align"
    );
    public static $border = array(
        ".builder__editor--item-border-width .builder__editor--padding-top"=>"border-top-width",
        ".builder__editor--item-border-width .builder__editor--padding-bottom"=>"border-bottom-width",
        ".builder__editor--item-border-width .builder__editor--padding-left"=>"border-left-width",
        ".builder__editor--item-border-width .builder__editor--padding-right"=>"border-right-width",
        ".builder__editor--item-border-width .border_style"=>"border-style",
        ".builder__editor--item-border-width .builder__editor_color"=>"border-color",
        ".builder__editor--item-border-radius .builder__editor--padding-top"=>"border-top-left-radius",
        ".builder__editor--item-border-radius .builder__editor--padding-bottom"=>"border-bottom-right-radius",
        ".builder__editor--item-border-radius .builder__editor--padding-left"=>"border-bottom-left-radius",
        ".builder__editor--item-border-radius .builder__editor--padding-right"=>"border-top-right-radius",
    );
    public static $color = array(
        ".builder__editor--item-text-color .builder__editor_color"=>"color"
    );
    public static $border_color = array(
        ".builder__editor--item-border_color .builder__editor_color"=>"color"
    );
    public static $width_height = array(
        ".builder__editor--item-width_height .text_width"=>"width",
        ".builder__editor--item-width_height .text_height"=>"height"
    );
    public static $width = array(
        ".builder__editor--item-width .text_width"=>"width"
    );
    public static $background = array(
        ".builder__editor--item-background .builder__editor_color"=>"background-color",
        ".builder__editor--item-background .image_url"=>"background-image",
        ".builder__editor--item-background .builder__editor_background_repeat"=>"background-repeat",
        ".builder__editor--item-background .builder__editor_background_size"=>"background-size",
        ".builder__editor--item-background .builder__editor_background_position"=>"background-position",
    );
}