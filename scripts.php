<?php
function sp_admin_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'sportspress-admin', plugin_dir_url( __FILE__ ) .'/sportspress-admin.js', array( 'jquery' ), time(), true );
}
add_action( 'admin_enqueue_scripts', 'sp_admin_enqueue_scripts' );
?>