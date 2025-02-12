<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block","yeemail_builder_block_social",90);
function yeemail_builder_block_social(){
    ?>
    <li>
        <div class="momongaDraggable" data-type="social">
            <i class="yeemail_builder-icon icon-share"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("Social","yeemail") ?></div>
        </div>
    </li>
    <?php
}
add_action( 'yeemail_builder_block_html', "yeemail_builder_block_social_load" );
function yeemail_builder_block_social_load($type){
    $type["block"]["social"]["builder"] = '
<div class="builder-elements" >
    <div class="builder-elements-content" data-type="social">
        <span class="social-facebook">
            <a href="" class="social_in">
                <img style="width: 32px; padding-right:5px;" src="'.YEEMAIL_PLUGIN_URL.'images/social/fb_1.png" alt="" />
            </a>
        </span>
        <span class="social-twitter">
            <a href="" class="social_int">
                <img style="width: 32px;padding-right:5px;" src="'.YEEMAIL_PLUGIN_URL.'images/social/tw_1.png" alt="" />
            </a>
        </span>
        <span class="social-instagram">
            <a href="" class="social_pt">
                <img style="width: 32px; padding-right:5px;" src="'.YEEMAIL_PLUGIN_URL.'images/social/int_1.png" alt="" />
            </a>
        </span>
        <span class="social-linkedin">
        <a href="" class="social_sk">
            <img style="width: 32px; padding-right:5px;" src="'.YEEMAIL_PLUGIN_URL.'images/social/in_1.png" alt="" />
        </a>
        </span>
        <span class="social-whatsapp">
            <a href="" class="social_tw">
                <img style="width: 32px; padding-right:5px;" src="'.YEEMAIL_PLUGIN_URL.'images/social/whatsapp.png" alt="" /> 
            </a>
        </span>
        <span class="social-youtube">
            <a href="" class="social_yt">
                <img style="width: 32px; padding-right:5px;" src="'.YEEMAIL_PLUGIN_URL.'images/social/yt_1.png" alt="" />
            </a>
        </span>
        <span class="social-skype">
            <a href="" class="social_fb">
                <img style="width: 32px;" src="'.YEEMAIL_PLUGIN_URL.'images/social/sk_1.png" alt="" />
            </a>
        </span>
    </div>
</div>';
//Show editor
    $type["block"]["social"]["editor"]["container"]["show"]= ["text-align","padding","background","social","width","conditional_logic"];
    $padding = Yeemail_Builder_Global_Data::$padding;
    $text_align = Yeemail_Builder_Global_Data::$text_align;
    $container_style = array(
            ".builder__editor--item-background .builder__editor_color"=>"background-color",
            ".builder__editor--item-background .image_url"=>"background-image",
        );
    $type["block"]["social"]["editor"]["container"]["style"]= array_merge($padding,$container_style,$text_align);
    $type["block"]["social"]["editor"]["inner"]["style"]= array(
        ".social-facebook img"=>array(
                                    ".builder__editor--social-facebook .social_show"=>"display",
                                    ".builder__editor--item-width .text_width"=>"width",
                                    ),
        ".social-twitter img"=>array(
                                    ".builder__editor--social-twitter .social_show"=>"display",
                                    ".builder__editor--item-width .text_width"=>"width",
                                    ),
        ".social-instagram img"=>array(
                                    ".builder__editor--social-instagram .social_show"=>"display",
                                    ".builder__editor--item-width .text_width"=>"width",
                                    ),
        ".social-linkedin img"=>array(
                                    ".builder__editor--social-linkedin .social_show"=>"display",
                                    ".builder__editor--item-width .text_width"=>"width",
                                    ),
        ".social-whatsapp img"=>array(
                                    ".builder__editor--social-whatsapp .social_show"=>"display",
                                    ".builder__editor--item-width .text_width"=>"width",
                                    ),
        ".social-youtube img"=>array(
                                    ".builder__editor--social-youtube .social_show"=>"display",
                                    ".builder__editor--item-width .text_width"=>"width",
                                    ),
        ".social-skype img"=>array(
                                    ".builder__editor--social-skype .social_show"=>"display",
                                    ".builder__editor--item-width .text_width"=>"width",
                                    ),
    );
    $type["block"]["social"]["editor"]["inner"]["attr"] = array(
    ".social-facebook a"=>array(".builder__editor--social-facebook .social_url"=>"href"),
    ".social-twitter a"=>array(".builder__editor--social-twitter .social_url"=>"href"),
    ".social-instagram a"=>array(".builder__editor--social-instagram .social_url"=>"href"),
    ".social-linkedin a"=>array(".builder__editor--social-linkedin .social_url"=>"href"),
    ".social-whatsapp a"=>array(".builder__editor--social-whatsapp .social_url"=>"href"),
    ".social-youtube a"=>array(".builder__editor--social-youtube .social_url"=>"href"),
    ".social-skype a"=>array(".builder__editor--social-skype .social_url"=>"href"),
    );
return $type;
}