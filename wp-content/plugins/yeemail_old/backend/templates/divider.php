<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block","yeemail_builder_block_divider",60);
function yeemail_builder_block_divider(){
    ?>
    <li>
        <div class="momongaDraggable" data-type="divider">
            <i class="yeemail_builder-icon icon-divide"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("Divider","yeemail") ?></div>
        </div>
    </li>
    <?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_divider_load" );
function yeemail_builder_block_divider_load($type){
   $type["block"]["divider"]["builder"] = '
   <div class="builder-elements">
        <div class="builder-elements-content" data-type="divider" style="padding: 15px 0;">
            <div class="builder-hr"></div>
        </div>
    </div>';
   //Show editor
    $type["block"]["divider"]["editor"]["container"]["show"]= ["padding","background","height","conditional_logic"];
    $inner_style = array(
            ".builder__editor--item-background .builder__editor_color"=>"background-color",
            ".builder__editor--item-height .text_height"=>"height",
            ".builder__editor--item-background .image_url"=>"background-image",
        );
    $padding = Yeemail_Builder_Global_Data::$padding;
    $type["block"]["divider"]["editor"]["container"]["style"]= array_merge($padding);
    $type["block"]["divider"]["editor"]["inner"]["style"]=[".builder-hr" => $inner_style];
   return $type;
}