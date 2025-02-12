<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block","yeemail_builder_block_button",50);
function yeemail_builder_block_button(){
    ?>
    <li>
        <div class="momongaDraggable" data-type="button">
            <i class="yeemail_builder-icon icon-doc-landscape"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("Button","yeemail") ?></div>
        </div>
    </li>
    <?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_button_load" );
function yeemail_builder_block_button_load($type){
   $type["block"]["button"]["builder"] = '
   <div class="builder-elements">
        <div class="builder-elements-content" data-type="button" style="text-align: center;">
            <a style="display: inline-block; text-decoration: none;" class="yeemail_button" href="#">Click Here</a>
        </div>
    </div>';
    //Show editor
    $type["block"]["button"]["editor"]["container"]["show"]= ["text-align","padding","border","button","background","color","conditional_logic"];
    //Style container
    $type["block"]["button"]["editor"]["container"]["style"]= Yeemail_Builder_Global_Data::$text_align;
    //Style inner
    $padding = Yeemail_Builder_Global_Data::$padding;
    $border = Yeemail_Builder_Global_Data::$border;
    $a = array(
            ".builder__editor--item-button .font_size"=>"font-size",
            ".builder__editor--item-background .builder__editor_color"=>"background-color",
            ".builder__editor--item-color .builder__editor_color"=>"color",
            ".builder__editor--item-background .image_url"=>"background-image",
        );
    $type["block"]["button"]["editor"]["inner"]["style"]=["a" => array_merge($padding,$border,$a)];
    // Data Attr
    $type["block"]["button"]["editor"]["inner"]["attr"]=["a"=>[".builder__editor--item-button .button_text"=>"text",
        ".builder__editor--item-button .button_url"=>"href"]];
   return $type;
}
