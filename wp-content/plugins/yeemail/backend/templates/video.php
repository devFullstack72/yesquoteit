<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block","yeemail_builder_block_video",40);
function yeemail_builder_block_video(){
    ?>
    <li>
        <div class="momongaDraggable" data-type="video">
            <i class="yeemail_builder-icon icon-youtube-play"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("Video","yeemail") ?></div>
        </div>
    </li>
    <?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_video_load" );
function yeemail_builder_block_video_load($type){
    $type["block"]["video"]["builder"] = '
   <div class="builder-elements">
        <div class="builder-elements-content" data-type="video">
            <a href="#" target="_blank">
                <div class="yeemail-builder-container-video" style="text-align: center;width: 100%;height: 360px;line-height: 360px;">  
                    <img src="'.YEEMAIL_PLUGIN_URL.'images/youtube_play.png" />
                </div>
            </a>
        </div>
    </div>';
    $padding = Yeemail_Builder_Global_Data::$padding;
    $type["block"]["video"]["editor"]["container"]["show"]= ["padding","video","conditional_logic"];
    $type["block"]["video"]["editor"]["container"]["style"]= $padding;
    $type["block"]["video"]["editor"]["inner"]["style"]= array();
    $type["block"]["video"]["editor"]["inner"]["attr"]= array("a"=>array(".builder__editor--item-video .video_url"=>"href") );
 return $type;
}