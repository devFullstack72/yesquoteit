<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block","yeemail_builder_block_content",1000);
function yeemail_builder_block_content(){
    ?>
    <li>
        <div class="momongaDraggable" data-type="content">
            <i class="yeemail_builder-icon icon-wpforms"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("Content","yeemail") ?></div>
        </div>
    </li>
    <?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_content_load" );
function yeemail_builder_block_content_load($type){
   $type["block"]["content"]["builder"] = '
   <div class="builder-elements">
        <div class="builder-elements-content builder-elements-content-hold" data-type="content" style="padding: 15px 0;">
            <div class="builder-content">Content Email</div>
        </div>
    </div>';
   //Show editor
    $type["block"]["content"]["editor"]["container"]["show"]= ["padding","background","conditional_logic"];
    $inner_style = array(
            ".builder__editor--item-background .builder__editor_color"=>"background-color",
            ".builder__editor--item-background .image_url"=>"background-image",
        );
    $padding = Yeemail_Builder_Global_Data::$padding;
    $type["block"]["content"]["editor"]["container"]["style"]= array_merge($padding);
    $type["block"]["content"]["editor"]["inner"]["style"]=[".builder-hr" => $inner_style];
   return $type;
}