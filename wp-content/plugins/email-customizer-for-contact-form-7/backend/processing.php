<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Yeemail_Addons_Contactform7_Advanced {
	function __construct(){
		add_action("yeemail_header_builder",array($this,"builder_email_tool_header"));
		add_action("save_post_yeemail_template",array( $this, 'yeemail_template' ) );
		add_action("save_post_wpcf7_contact_form",array( $this, 'save_metabox' ) );
		add_filter("yeemail_contact_form_7_settings",array($this,"setting_form"),10,2);
		add_filter( "wpcf7_mail_components", array($this,"wpcf7_mail_components"),10,3);
		add_filter("yeemail_shortcodes",array($this,"add_shortcode"),200);
	}
    function add_shortcode($shortcode) {
		if( isset($_GET["post"]) ){
            $post_id = sanitize_text_field($_GET["post"]);
			$form_id = get_post_meta( $post_id,'_yeemail_contact_form_7',true);	
			$fields = array();
			$inner_shortcode = array();
			$inner_shortcode["all-fields"] = "[all-fields]";
			$inner_shortcode["form_name"] = "[form_name]";
			$inner_shortcode["form_id"] = "[form_id]";
			if($form_id){
				$ContactForm = WPCF7_ContactForm::get_instance( $form_id );
				$tags = $ContactForm->scan_form_tags();
				foreach ($tags as $tag_inner):
					if ($tag_inner['type'] == 'group' || $tag_inner['name'] == '') continue;
					$inner_shortcode[$tag_inner['name']] = "[".$tag_inner['name']."]";
				endforeach;  
			}
			$shortcode["Contact Form 7"] = $inner_shortcode;  
		}
		return $shortcode;
	}
    function wpcf7_mail_components($components,$form,$current){
        $type = $current->get_template_name();
		if($type == "mail_2"){
			$template_id = get_post_meta($form->id(),'_email2_template',true);
		}else{
			$template_id = get_post_meta($form->id(),'_yee'.$type.'_template',true);
		}
        if($template_id > 0 && $template_id != "" && $template_id !="default"){
			$form_data = array();
			$form = WPCF7_Submission::get_instance();
			$all_field = $this->get_all_fields($form);
			$form_title = $form->get_contact_form()->title;
			$form_id = $form->get_contact_form()->id;
			foreach( $form->get_posted_data() as $n=> $value){
				$form_data[$n] = $value;
			}
			$form_data["[form_name]"] = $form_title;
			$form_data["[form_id]"] = $form_id;
			$template_content = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"content_no_shortcode","datas"=>$form_data));
			$template_content = str_replace(array("[all-fields]","[form_name]","[form_id]"),array($all_field,$form_title,$form_id),$template_content);
            $new_content_email = wpcf7_mail_replace_tags($template_content);
			$notification_email = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$template_id,"type"=>"full","html"=>$new_content_email));
            $components["body"] = $notification_email;
        }
        return $components;
    }
	function yeemail_template($post_id){
		if( isset( $_POST["yeemail_contact_form_7"] )){
			$content = sanitize_text_field( $_POST["yeemail_contact_form_7"] );
            update_post_meta($post_id,'_yeemail_contact_form_7',$content);
		}
	}
	function get_all_fields($form){
		$all_field = '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
		$style = 'padding-top: 25px;padding-bottom: 25px;border-top: 1px solid #e2e2e2;min-width: 113px;padding-right: 10px;line-height: 22px;';
		$style_first = 'padding-top: 25px;padding-bottom: 25px;min-width: 113px;padding-right: 10px;line-height: 22px;';
		$i = 0;
		foreach( $form->get_posted_data() as $n=> $value){
			if($i == 0){
				$all_field .= '<tr>
				<td style="'.$style_first.'"><strong>'.$n.'</strong></td>
				<td style="'.$style_first.'">'.$value.'</td>
				</tr>';
			}else{
				$all_field .= '<tr>
				<td style="'.$style.'"><strong>'.$n.'</strong></td>
				<td style="'.$style.'">'.$value.'</td>
				</tr>';
			}
			$i++;
		}
		foreach( $uploaded_files as $key =>$value ){
			$all_field .= '<tr>
				<td style="'.$style.'"><strong>'.$key.'</strong></td>
				<td style="'.$style.'">'.$value.'</td>
				</tr>';
		}
		$all_field .="</table>";
		return $all_field;
	}
    function save_metabox($post_id){
        if( isset( $_POST["yeemail_template"] )) {
    		$template2 = sanitize_text_field($_POST["yeemail_template"]);
    		update_post_meta($post_id,'_yeemail_template',$template2);
    	}
    	if( isset( $_POST["email2_template"] )) {
    		$template2 = sanitize_text_field($_POST["email2_template"]);
    		update_post_meta($post_id,'_email2_template',$template2);
    	}
	}
    function setting_form($text, $post_id){
    	$template = get_post_meta($post_id,'_yeemail_template',true);
    	$template2 = get_post_meta($post_id,'_email2_template',true);
		$new_link = get_admin_url()."post-new.php?post_type=yeemail_template";
		ob_start();
    	?>
    	<h3><?php esc_html_e("Choosse Email Template","email-customizer-for-contact-form-7") ?></h3>
        <ul>
            <li>
				<?php esc_html_e("Email Template","email-customizer-for-contact-form-7") ?>: <?php $this->get_option_select("yeemail_template",$template) ?>
				<?php if($template != "" && $template > 0 && $template !="default"){
					?>
					<a class="button" target="_blank" href="<?php echo esc_url(get_edit_post_link($template)) ?>"><?php esc_attr_e( "Customize with YeeMail", "email-customizer-for-contact-form-7") ?></a>
					<?php
				}else{
					?>
					<a class="button" target="_blank" href="<?php echo esc_url($new_link) ?>"><?php esc_attr_e( "New Template", "email-customizer-for-contact-form-7") ?></a>
					<?php
				} ?>
			</li>
            <li><?php esc_html_e("Email(2) Template","email-customizer-for-contact-form-7") ?> : <?php $this->get_option_select("email2_template",$template2) ?>
			<?php if($template2 != "" && $template2 > 0 && $template2 !="default"){
					?>
					<a class="button" target="_blank" href="<?php echo esc_url(get_edit_post_link($template2)) ?>"><?php esc_attr_e( "Customize with YeeMail", "email-customizer-for-contact-form-7") ?></a>
					<?php
				}else{
					?>
					<a class="button" target="_blank" href="<?php echo esc_url($new_link) ?>"><?php esc_attr_e( "New Template", "email-customizer-for-contact-form-7") ?></a>
					<?php
				} ?>
			</li>
        </ul>
        <?php
		$html= ob_get_clean();
		return $html;
    }
	function get_option_select($name,$selected=""){
        ?>
		<select name="<?php echo esc_attr( $name ) ?>" class="wp-builder-email-choose-cf7">
		    <option value="default"><?php esc_html_e("Default","email-customizer-for-contact-form-7") ?></option>
        <?php
		$orders = new WP_Query( array( 'post_type' => 'yeemail_template','post_status' => 'publish','posts_per_page'=>-1,"meta_key"=>"_mail_type","meta_value"=>"other") );
        if( $orders->have_posts() ):
            while ( $orders->have_posts() ) : $orders->the_post();
            	$id = get_the_id();
            ?>
                <option <?php selected($selected,$id) ?> value="<?php echo esc_attr($id) ?>"> ( <?php echo esc_attr($id)  ?> ) <?php the_title() ?></option>
            <?php
            endwhile;
        else:
            ?>
            <option ><?php esc_html_e("No Template Email","email-customizer-for-contact-form-7") ?></option>
            <?php
        endif;
		wp_reset_postdata();
        ?>
        <select>
        <?php
	}
	function builder_email_tool_header($post_id){
		$data = get_post_meta( $post_id,'_yeemail_contact_form_7',true);
		$type = get_post_meta( $post_id,'_mail_template',true);
		if($type == ""){
		?>
		<div class="yeemail_builder_addons_header">
			<select name="yeemail_contact_form_7">
				<option value="">---<?php esc_html_e("Contact Form 7","email-customizer-for-contact-form-7") ?>---</option>
			<?php
				$forms = new WP_Query( array("post_type"=>"wpcf7_contact_form","posts_per_page"=>-1) );
				if ( $forms->have_posts() ){
					while ( $forms->have_posts() ) : 
						$forms->the_post();
						$form_id = get_the_id();
						$form_title = get_the_title();
						?>
							<option <?php selected($data,$form_id) ?> value="<?php echo esc_attr($form_id) ?>"><?php echo esc_html($form_title) ?></option>
						<?php
					endwhile;
				}else{
					printf( "<option value='0'>%s</option>",esc_html__("No Form","email-customizer-for-contact-form-7"));
				}
				wp_reset_postdata();
                ?>
            </select>
		</div>
		<?php
		}
	}
}
new Yeemail_Addons_Contactform7_Advanced;