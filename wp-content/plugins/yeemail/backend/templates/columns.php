<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_action("yeemail_builder_tab_block_row","yeemail_builder_block_row");
function yeemail_builder_block_row(){
    ?>
    <li class="builder-row-inner"  data-type="row1" >
        <span></span>
    </li>
    <li class="builder-row-inner" data-type="row2">
        <span></span>
        <span></span>
    </li>
    <li class="builder-row-inner" data-type="row3">
        <span class="bd-row-2"></span>
        <span></span>
    </li>
    <li class="builder-row-inner" data-type="row4">
        <span></span>
        <span class="bd-row-2"></span>
    </li>
    <li class="builder-row-inner" data-type="row5">
        <span></span>
        <span></span>
        <span></span>
    </li>
    <li class="builder-row-inner" data-type="row6">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </li>
    <?php
}
add_filter( 'yeemail_builder_block_html', "yeemail_builder_block_row_load" );
function yeemail_builder_block_row_load($type){
    $col = array("row1","row2","row3","row4","row5","row6");
    $padding = Yeemail_Builder_Global_Data::$padding;
    foreach( $col as $value ){
        switch($value){
            case "row1":
                $type_text = "1 Colunm";
            break;
            case "row2":
                $type_text = "2 Colunms";
            break;
            case "row3":
                $type_text = "2 Colunms 8-4";
            break;
            case "row4":
                $type_text = "2 Colunms 4-8";
            break;
            case "row5":
                $type_text = "3 Colunms";
            break;
            case "row6":
                $type_text = "4 Colunms";
            break;
            default:
                $type_text = $value;
            break;
        }
        $type["block"][$value]["type_text"] = $type_text;
        $background = Yeemail_Builder_Global_Data::$background;
        $type["block"][$value]["editor"]["container"]["style"]= array_merge($padding,$background);
        if($value != "row1"){
            $type["block"][$value]["editor"]["container"]["show"]= ["padding","background","background_full","background_responsive","conditional_logic"];
            $type["block"][$value]["editor"]["container"]["attr"]= array(".builder__editor--item-background_full .background_full_width"=>"background_full",".builder__editor--item-background_responsive .background_responsive"=>"responsive");
        }else{
            $type["block"][$value]["editor"]["container"]["show"]= ["padding","background","background_full","conditional_logic"];
            $type["block"][$value]["editor"]["container"]["attr"]= array(".builder__editor--item-background_full .background_full_width"=>"background_full");
        }
    }
    $type["block"]["row1"]["builder"] = '
    <div class="builder-row-container builder__item">
        <div style="background-color: #ffffff" background_full="not" data-type="row1" class="builder-row-container-row builder-row-container-row1">
            <div class="builder-row">
            </div>
        </div>
    </div>';
    $type["block"]["row2"]["builder"]  = '
    <div class="builder-row-container builder__item">
        <div style="background-color: #ffffff" background_full="not" data-type="row2" class="builder-row-container-row builder-row-container-row2">
            <div class="builder-row">
            </div>
            <div class="builder-row">
            </div>
        </div>
    </div>';
    $type["block"]["row3"]["builder"]  = '
    <div class="builder-row-container builder__item">
        <div style="background-color: #ffffff" background_full="not" data-type="row3" class="builder-row-container-row builder-row-container-row3">
            <div class="builder-row bd-row-2">
            </div>
            <div class="builder-row">
            </div>
        </div>
    </div>';
    $type["block"]["row4"]["builder"]  = '
    <div class="builder-row-container builder__item">
        <div style="background-color: #ffffff" background_full="not" data-type="row4" class="builder-row-container-row builder-row-container-row4">
            <div class="builder-row">
            </div>
            <div class="builder-row bd-row-2">
            </div>
        </div>
    </div>';
    $type["block"]["row5"]["builder"]  = '
    <div class="builder-row-container builder__item">
        <div style="background-color: #ffffff" background_full="not" data-type="row5" class="builder-row-container-row builder-row-container-row5">
            <div class="builder-row">
            </div>
            <div class="builder-row">
            </div>
            <div class="builder-row">
            </div>
        </div>
    </div>';
    $type["block"]["row6"]["builder"]  = '
    <div style="background-color: #ffffff" background_full="not" class="builder-row-container builder__item">
        <div data-type="row6" class="builder-row-container-row builder-row-container-row6">
            <div class="builder-row">
            </div>
            <div class="builder-row">
            </div>
            <div class="builder-row">
            </div>
            <div class="builder-row">
            </div>
        </div>
    </div>';
    return $type;
}
