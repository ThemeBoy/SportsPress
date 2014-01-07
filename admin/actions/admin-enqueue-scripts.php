<?php
function sportspress_admin_enqueue_scripts() {
	wp_register_style( 'sportspress-admin', SPORTSPRESS_PLUGIN_URL . 'assets/css/admin.css', array(), time() );
	wp_enqueue_style( 'sportspress-admin');

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'sportspress-admin', SPORTSPRESS_PLUGIN_URL .'/assets/js/admin.js', array( 'jquery' ), time(), true );
}
add_action( 'admin_enqueue_scripts', 'sportspress_admin_enqueue_scripts' );
