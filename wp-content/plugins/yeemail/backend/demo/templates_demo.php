<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Templates_Demo {
	function __construct() { 
		add_action("yeemail_builder_templates",array($this,"yeemail_builder_templates"));
	}
	function yeemail_builder_templates(){
        $args = array(
            array(
            "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template0.json",
            "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/0.png",
            "title" => "Blank",
            "id"=> 118,
            ),
            array(
            "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template1.json",
            "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/1.png",
            "title" => "Template 1",
            "id"=> 26,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template2.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/2.png",
                "title" => "Template 2",
                "id"=> 9,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template3.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/3.png",
                "title" => "Template 3",
                "id"=> 33,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template15.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/15.png",
                "title" => "Template 15",
                "id"=> 239,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template16.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/16.png",
                "title" => "Template 16",
                "id"=> 256,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template4.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/4.png",
                "title" => "Template 4",
                "id"=> 40,
            ),  
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template5.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/5.png",
                "title" => "Template 5",
                "id"=> 54,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template6.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/6.png",
                "title" => "Template 6",
                "id"=> 170,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template7.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/7.png",
                "title" => "Template 7",
                "id"=> 195,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template9.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/9.png",
                "title" => "Template 9",
                "id"=> 189,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template10.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/10.png",
                "title" => "Template 10",
                "id"=> 194,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template11.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/11.png",
                "title" => "Template 11",
                "id"=> 201,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template12.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/12.png",
                "title" => "Template 12",
                "id"=> 205,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template13.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/13.png",
                "title" => "Template 13",
                "id"=> 218,
            ),
            array(
                "json"=>YEEMAIL_PLUGIN_URL."backend/demo/yeemail_template14.json",
                "img"=>YEEMAIL_PLUGIN_URL."backend/demo/images/14.png",
                "title" => "Template 14",
                "id"=> 223,
            ),    
        );
        foreach ($args as $value) {
            Yeemail_Settings_Builder_Backend::item_demo($value);
        }
	}
}
new Yeemail_Templates_Demo;