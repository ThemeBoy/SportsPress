<?php
function sportspress_admin_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'sportspress-admin', SPORTSPRESS_PLUGIN_URL .'/assets/js/admin.js', array( 'jquery' ), time(), true );
}
add_action( 'admin_enqueue_scripts', 'sportspress_admin_enqueue_scripts' );
