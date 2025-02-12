<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block","yeemail_builder_block_menu",70);
function yeemail_builder_block_menu(){
    ?>
    <li>
        <div class="momongaDraggable" data-type="menu">
            <i class="dashicons dashicons-welcome-widgets-menus"></i>
            <div class="yeemail-tool-text"><?php esc_html_e("Menu","yeemail") ?></div>
        </div>
    </li>
    <?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_menu_load" );
function yeemail_builder_block_menu_load($type){
   $type["block"]["menu"]["builder"] = '
   <div class="builder-elements">
        <div class="builder-elements-content" data-type="menu">
            <table cellpadding="0" cellspacing="0" width="100%" class="yeemail-menu">
                <tr class="links">
                    <td align="center" valign="top" style="padding:10px 0;boder:none;" >
                        <a style="text-decoration: none;" target="_blank" href="#">Item1</a>
                    </td>
                   <td align="center" valign="top" style="padding:10px 0;">
                        <a style="text-decoration: none;" target="_blank" href="#">Item2</a>
                    </td>
                    <td align="center" valign="top" style="padding:10px 0;">
                        <a style="text-decoration: none;" target="_blank" href="#">Item3</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>';
   //Show editor
    $type["block"]["menu"]["editor"]["container"]["show"]= ["padding","border","menu","conditional_logic"];
    $inner_style = array(
          ".builder__editor--item-background .builder__editor_color"=>"background-color", 
        );
    $padding = Yeemail_Builder_Global_Data::$padding;
    $border = Yeemail_Builder_Global_Data::$border;
    $type["block"]["menu"]["editor"]["container"]["style"]= array();
    $type["block"]["menu"]["editor"]["inner"]["style"]=array("td"=>$padding);
    $type["block"]["menu"]["editor"]["inner"]["attr"]=[".yeemail-menu" => array("a"=>"menu")];
   return $type;
}