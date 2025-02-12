<?php 
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}
if( isset($_GET['id'])) {
	if ( isset($_GET[ '_wpnonce' ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET[ '_wpnonce' ] ) ), 'yeemail' ) ) {
		$id = sanitize_text_field($_GET['id']);
		$html = Yeemail_Builder_Frontend_Functions::creator_template(array("id_template"=>$id,"type"=>"full"));
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput
	}else{
		wp_die("Check nonce");
	}
}