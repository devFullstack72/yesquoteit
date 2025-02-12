<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block","yeemail_builder_block_spacer",80);
function yeemail_builder_block_spacer(){
    ?>
    <li>
        <div class="momongaDraggable" data-type="spacer">
            <i class="yeemail_builder-icon icon-myspace"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("Spacer","yeemail") ?></div>
        </div>
    </li>
    <?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_spacer_load" );
function yeemail_builder_block_spacer_load($type){
   $type["block"]["spacer"]["builder"] = '
   <div class="builder-elements">
        <div class="builder-elements-content" data-type="spacer">
            <div class="builder-spacer" style="height:50px"></div>
        </div>
    </div>';
   //Show editor
    $type["block"]["spacer"]["editor"]["container"]["show"]= ["padding","background","height","conditional_logic"];
    $inner_style = array(
            ".builder__editor--item-background .builder__editor_color"=>"background-color",
            ".builder__editor--item-height .text_height"=>"height",
            ".builder__editor--item-background .image_url"=>"background-image",
        );
    $padding = Yeemail_Builder_Global_Data::$padding;
    $type["block"]["spacer"]["editor"]["container"]["style"]= array_merge($padding);
    $type["block"]["spacer"]["editor"]["inner"]["style"]=[".builder-spacer" => $inner_style];
   return $type;
}
