<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block_template","yeemail_builder_block_header",10);
function yeemail_builder_block_header(){
	?>
	<li data-type="header">
		<div class="momongaDraggable">
            <i class="dashicons dashicons-table-row-after"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("Header","yeemail") ?></div>
        </div>
    </li>
	<?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_header_load" );
function yeemail_builder_block_header_load($type){
    $content_element = '<h1 style="font-size: 30px; font-weight: 300; line-height: normal; margin: 0; color: inherit;"><span style="color: #ffffff;"><strong>[yeemail_site_name]</strong></span></h1>';
    $text_show = do_shortcode($content_element);
    $type["block"]["header"]["builder"] = '<div class="builder-row-container builder__item">
        <div style="background-color: rgb(127, 84, 179); background-image: none; background-position: center center; background-repeat: no-repeat; background-size: cover; text-align: start; padding: 30px;" background_full="not" data-type="row1" class="builder-row-container-row builder-row-container-row1">
            <div class="builder-row">
            <div class="builder-elements">
                <div class="builder-elements-content" data-type="text" style="padding: 0px; background-color: rgba(0, 0, 0, 0); background-image: none; background-position: center center; background-repeat: no-repeat; background-size: cover; text-align: start;">
                    <div class="text-content-data hidden">'.$content_element.'</div>
                    <div class="text-content">'.$text_show.'</div>
                </div>
            </div>
            </div>
        </div>
    </div>';
    return $type; 
}