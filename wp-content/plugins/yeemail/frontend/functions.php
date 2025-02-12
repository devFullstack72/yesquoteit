<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}
if (! function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool
    {
        $needle_len = strlen($needle);
        return ($needle_len === 0 || 0 === substr_compare($haystack, $needle, - $needle_len));
    }
}
class Yeemail_Builder_Frontend_Functions {
    public static function cover_array_to_css($array){
    	$result = implode(PHP_EOL, array_map(
				    function ($v, $k) { 
				    	if($k == "font-family"){
				    		$v = "'".$v."', sans-serif;";
				    	}
				    	return sprintf("%s: %s;", $k, $v); },
				    $array,
				    array_keys($array)
				));
		$result = str_replace('"', "'", $result);
    	return $result;
    }
	public static function get_settings_css($id_template){
		$link_color = get_post_meta( $id_template,'_yeemail_link_color',true);
		$css = get_post_meta( $id_template,'_custom_css',true);
		ob_start();
		?>
		<style type="text/css">
			<?php
			echo wp_kses_post($css);
			?>
			.container-row a {
				color: <?php echo esc_attr( $link_color ) ?>;
				font-weight: normal;
			}
			.container-row h1 {
				color: <?php echo esc_attr( $link_color ); ?>;
				font-size: 30px;
				font-weight: 300;
				line-height: 150%;
				margin: 0;
				text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
			}
			.container-row h2 {
				color: <?php echo esc_attr( $link_color ); ?>;
				display: block;
				font-size: 18px;
				font-weight: bold;
				line-height: 130%;
				margin: 0 0 18px;
				text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
			}
			.container-row h3 {
				color: <?php echo esc_attr( $link_color ); ?>;
				display: block;
				font-size: 16px;
				font-weight: bold;
				line-height: 130%;
				margin: 16px 0 8px;
				text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
			}
		</style>
		<?php
		$html= ob_get_clean();
		return $html;
	}
	public static function creator_template($attrs){
		$data_attrs = shortcode_atts(array(
			"id_template"    => "",
			"type"           => "preview",
			"html"           => "",
			"datas"          => array(),
			"params"         => array()
		),$attrs);
		if (!function_exists('str_get_html')) { 
			include YEEMAIL_PLUGIN_PATH."libs/simple_html_dom.php";
		}
		$id_template = $data_attrs["id_template"];
		$datas_check =$data_attrs["datas"];
		if ( is_user_logged_in() ) {
			$datas_check["[yeemail_is_user_logged_in]"] = "true";
		}else{
			$datas_check["[yeemail_is_user_logged_in]"] = false;
		}
		if($data_attrs["html"] != "") {
			$template_html = $data_attrs["html"];
		}else{
			$template_html = self::get_html($id_template,$datas_check);
		}
		switch($data_attrs["type"]){
			case "full":
				ob_start();
				include YEEMAIL_PLUGIN_PATH."email-templates/header.php";
				echo self::get_settings_css($id_template); // phpcs:ignore WordPress.Security.EscapeOutput
				echo do_shortcode($template_html); // phpcs:ignore WordPress.Security.EscapeOutput
				include YEEMAIL_PLUGIN_PATH."email-templates/footer.php";
				$html= ob_get_clean();
				return $html; 
			break;
			case "content_no_shortcode":
				return $template_html; 
			break;
			default:
				return do_shortcode($html); 
			break;
		}
	}
	public static function get_html( $id_template, $datas=""){
        $width = get_post_meta( $id_template,'_mail_width',true);
		if($width < 480 || $width== "" ){
			$width = "640";   
		}
		$html ="";
		$data_json = get_post_meta( $id_template,'data_email',true);
		$data_json = json_decode($data_json,true);
		if(!$data_json){
			return ;
		}
        $container = $data_json["container"];
        $container_css = self::cover_array_to_css($container);
		$data_contents = $data_json["rows"];
		$datas_builder = apply_filters("yeemail_builder_block_html",array());
		$class = "container";
		$html = "";
		ob_start();
		?>
		<div data-yeemail-id="<?php echo esc_attr( $id_template ) ?>" class="wap" width="100%" style="<?php echo $container_css;// phpcs:ignore WordPress.Security.EscapeOutput ?>">
            <?php
            foreach( $data_contents as $row){
                $row_columns = $row["columns"];
                if(isset($row["condition"])){
                    $row_condition = $row["condition"];
                }else{
                    $row_condition = '';
                }
                $show_row = apply_filters( "yeemail_conditional_logic_show", true, $row_condition,$datas );
                if( $show_row ) {
					$row_style_array = $row["style"];
					if(isset($row_style_array["background-image"]) && $row_style_array["background-image"] == "none"){
						unset($row_style_array["background-position"]);
						unset($row_style_array["background-repeat"]);
						unset($row_style_array["background-size"]);
						unset($row_style_array["background-image"]);
					}
                    $row_style = self::cover_array_to_css($row_style_array);
					$container_row_css = array();
					$container_row_class = "";
					if(isset($row["attr"]) && is_array($row["attr"])){
						foreach($row["attr"] as $row_key => $row_colunm){
							switch ($row_key){
								case "background_full":
									if($row_colunm == "ok" && isset($row_style_array["background-color"]) && $row_style_array["background-color"] != ""){
										$container_row_css["background-color"] = $row_style_array["background-color"];
									}
								break;
								case "responsive":
									$container_row_class = "yeemail-responsive";
								break;
							}
						}
					}
					
					$container_row_css = self::cover_array_to_css($container_row_css);
                    $i=0;
                    ?>
					<div class="container-row <?php echo esc_attr( $container_row_class ) ?>" style="<?php echo $container_row_css; // phpcs:ignore WordPress.Security.EscapeOutput ?>">
                    <table class="row" width="<?php echo esc_attr( $width ) ?>px" class="row" align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="<?php echo $row_style;// phpcs:ignore WordPress.Security.EscapeOutput ?>"; margin: auto;">
                        <tr>
                    <?php
                    foreach( $row_columns as $column ){
                        switch ($row["type"]){
                            case "row2":
                                $col_width = "50%";
                                break;
                            case "row3":
                                if( $i == 0 ){
                                    $col_width = "65%";
                                }else{
                                    $col_width = "35%";
                                }
                                break;
                            case "row4":
                                if( $i == 0 ){
                                    $col_width = "35%";
                                }else{
                                    $col_width = "35%";
                                }
                                break;
                            case "row5":
                                $col_width = "33.33%";
                                break;
                            case "row6":
                                $col_width = "25%";
                                break;
                            case "row7":
                                $col_width = "20%";
                                break;
                            case "row8":
                                $col_width = "16.66%";
                                break;
                            case "row9":
                                $col_width = "14.28%";
                                break;
                            default:
                                $col_width = "100%";
                                break;
                        }
                        ?>
                        <td class="col" width="<?php echo esc_attr( $col_width ); ?>">
                            <?php 
                            $elements = $column["elements"];
                            if(count($elements) < 1 ){
                                echo '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput
                            }else{
                                foreach( $elements as $element ){
                                    $element_html = self::cover_type_element_to_html($element,$datas_builder,$datas);
                                    echo $element_html; // phpcs:ignore WordPress.Security.EscapeOutput
                                }	
                            }
                            ?>
                        </td>
                        <?php
                        $i++;
                    } ?>
                    </tr>
                </table>
				</div>
                <?php
                }
            }
            ?>
		</div>
		<?php
		$html= ob_get_clean();
		return $html;
	}
	public static function cover_type_element_to_html($element,$datas_builder, $datas=array()){
    	$result = "";
    	$html = "";
    	$datas_builder = $datas_builder["block"];
    	$type = $element["type"];
    	if(!isset($datas_builder[ $type ]) ){
    		return "";
    	}
		if($type == "content"){
			return '<div class="builder-content">Content Email</div>';
		}
    	$inner_attr = $element["inner_attr"];
		$container_style_array = $element["container_style"];
		if(isset($container_style_array["background-image"]) && $container_style_array["background-image"] == "none"){
			unset($container_style_array["background-position"]);
			unset($container_style_array["background-repeat"]);
			unset($container_style_array["background-size"]);
			unset($container_style_array["background-image"]);
		}
    	$container_style = self::cover_array_to_css($container_style_array);
    	$inner_style = $element["inner_style"];
    	if(isset($element["condition"])){
			$element_condition = $element["condition"];
		}else{
			$element_condition = '';
		}
    	$html_el = str_get_html($datas_builder[ $type ]["builder"]);
    	$show = apply_filters( "yeemail_conditional_logic_show", true, $element_condition,$datas );
    	if( $show ){
			$html_el->find('.builder-elements-content',0)->setAttribute("style",$container_style);
			if($show == "yeemail_show_desktop" || $show == "yeemail_show_mobile"){
			$html_el->find('.builder-elements-content',0)->addClass($show);
			}
			foreach( $inner_attr as $key => $attrs ){
				foreach( $attrs as $k => $v ){
					if(!is_array($v)){
						$v = do_shortcode($v);
					}
					switch( $type ){
						case "qrcode":
							if( $k == "html_hide"){
								$html_el->find( $key ,0)->removeClass('hidden');
								$html_el->find( $key ,0)->innertext = '<div class="text-content"><img class="qrcode" src="[wp_builder_pdf_qrcode_new]'.strip_tags($v).'[/wp_builder_pdf_qrcode_new]" /></div>';
							}elseif( $k == "html"){
								$html_el->find( $key ,0)->remove();
							}else{
								$html_el->find( $key ,0)->setAttribute($k,$v);
							}
							break;
						case "barcode":
							if( $k == "html_hide"){
								$html_el->find( $key ,0)->removeClass('hidden');
								$html_el->find( $key ,0)->innertext = '<div class="text-content"><img src="[wp_builder_pdf_barcode_new]'.strip_tags($v).'[/wp_builder_pdf_barcode_new]" /></div>';
							}elseif( $k == "html"){
								$html_el->find( $key ,0)->remove();
							}else{
								$html_el->find( $key ,0)->setAttribute($k,$v);
							}
							break;
						case "image":
							if( isset($attrs["data-type"]) && $attrs["data-type"] == 1){
								$change_data = str_replace('"',"'",$attrs["data-field"]);
								$html_el->find( "img" ,0)->setAttribute("src",$change_data);
							}else{
								if( $v != ""){
									$html_el->find( $key ,0)->setAttribute($k,$v);
								}
							}
							break;
						case "signature":
							if( $attrs["data-field"] != ""){
								$change_data = str_replace('"',"'",$attrs["data-field"]);
								$html_el->find( "img" ,0)->setAttribute("src",$change_data);
							}else{
								if( $v != ""){
									$html_el->find( $key ,0)->setAttribute($k,$v);
								}
							}
							break;
						case "menu":
							if(is_array($v)){
								$table_menu ='<table cellpadding="0" cellspacing="0" width="100%" class="yeemail-menu"><tr>';
								foreach($v as $menu_key => $menu ){
									$table_menu .='<td style="background-color:'.$menu["background"].';" align="center" valign="top"><a style="color:'.$menu["color"].';text-decoration: none;" target="_blank" href="'.$menu["href"].'">'.$menu["text"].'</a></td>';
								}
								$table_menu .='</tr></table>';
								$html_el->find( $key ,0)->setAttribute("outertext",$table_menu);
							}
							break;
						case "order_detail":		
							if( $k == "html_hide"){
								$html_el->find( $key ,0)->removeClass('hidden');
								$table = $v;
								$table= preg_replace('/<td[^>]*\bhidden\b[^>]*>.*?<\/td>/is', '', $table);
								$table= preg_replace('/<th[^>]*\bhidden\b[^>]*>.*?<\/th>/is', '', $table);
								$html_el->find( $key ,0)->__set("innertext",$table);
							}
							elseif( $k == "html"){
								$html_el->find( $key ,0)->remove();
							}
							break;
						default:
	    	 				//text
							switch ($k){
								case "html_hide":
									$html_el->find( $key ,0)->removeClass('hidden');
									$html_el->find( $key ,0)->innertext = $v;
								break;
								case "html":
									$html_el->find( $key ,0)->remove();
								break;
								case "text":
									$html_el->find( $key ,0)->setAttribute("innertext",$v);
								break;
								default:
								$html_el->find( $key ,0)->setAttribute($k,$v);
								break;
							}
							if( $k == "html_hide"){
								$html_el->find( $key ,0)->removeClass('hidden');
								$html_el->find( $key ,0)->innertext = $v;
							}elseif( $k == "html"){
							}
							else{
								$html_el->find( $key ,0)->setAttribute($k,$v);
							}
							break;
					}
				}
			}
			$html_el = str_get_html($html_el);
			foreach( $inner_style as $key => $style ){
			$inner_style_array = $style;
			if(isset($inner_style_array["background-image"]) && $inner_style_array["background-image"] == "none"){
				unset($inner_style_array["background-position"]);
				unset($inner_style_array["background-repeat"]);
				unset($inner_style_array["background-size"]);
				unset($inner_style_array["background-image"]);
			}
			$in_style = self::cover_array_to_css($inner_style_array);
				foreach( $html_el->find( $key ) as $html_el_inner ){
				$old_style = $html_el_inner->getAttribute ("style");
				$html_el_inner->setAttribute("style",$old_style.$in_style);
				}
			}
	    	return $html_el;
		}
		return $html;
    }
    
}