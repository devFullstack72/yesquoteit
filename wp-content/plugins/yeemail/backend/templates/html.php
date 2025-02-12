<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block","yeemail_builder_block_html",20);
function yeemail_builder_block_html(){
	?>
	<li>
		<div class="momongaDraggable" data-type="html">
        <i class="dashicons dashicons-editor-code"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("HTML","yeemail") ?></div>
        </div>
    </li>
	<?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_html_load" );
function yeemail_builder_block_html_load($type){
    $type["block"]["html"]["builder"] = '
<div class="builder-elements">
    <div class="builder-elements-content" data-type="html">
        <div class="text-content-data hidden">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
        <div class="text-content">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</div>
    </div>
</div>';
    $type["block"]["html"]["editor"]["container"]["show"]= ["html_editor","conditional_logic"];
    $type["block"]["html"]["editor"]["container"]["style"]= array();
    $type["block"]["html"]["editor"]["inner"]["style"]= array();
    $type["block"]["html"]["editor"]["inner"]["attr"] = array(".text-content"=>array(".builder__editor--item-html_editor .builder__editor_html"=>"html"),
                                                              ".text-content-data"=>array(".builder__editor--item-html_editor .builder__editor_html"=>"html_hide"));
    return $type; 
}