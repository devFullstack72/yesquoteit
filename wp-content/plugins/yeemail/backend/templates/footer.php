<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block_template","yeemail_builder_block_footer",20);
function yeemail_builder_block_footer(){
	?>
	<li data-type="footer">
		<div class="momongaDraggable">
            <i class="dashicons dashicons-table-row-before"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("Footer","yeemail") ?></div>
        </div>
    </li>
	<?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_footer_load" );
function yeemail_builder_block_footer_load($type){
    $content_element = '[yeemail_site_name] - Built with <a style="color: #7f54b3;" href="https://add-ons.org/plugin/yeemail" target="_blank" rel="noopener">YeeMail</a>';
    $text_show = do_shortcode($content_element);
    $type["block"]["footer"]["builder"] = '<div class="builder-row-container builder__item">
        <div style="background-color: transparent; background-image: none; background-position: center center; background-repeat: no-repeat; background-size: cover; text-align: start; padding: 15px 30px;" background_full="not" data-type="row1" class="builder-row-container-row builder-row-container-row1">
            <div class="builder-row">
            <div class="builder-elements">
                <div class="builder-elements-content" data-type="text" style="padding: 0px; background-color: rgba(0, 0, 0, 0); background-image: none; background-position: center center; background-repeat: no-repeat; background-size: cover; text-align: center;">
                    <div class="text-content-data hidden">'.$content_element.'</div>
                    <div class="text-content">'.$text_show.'</div>
                </div>
            </div>
            </div>
        </div>
    </div>';
    return $type; 
}