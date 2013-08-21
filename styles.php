<?php
function sp_enqueue_styles() {
	wp_register_style( 'stylesheet', get_bloginfo('stylesheet_url' ) );
	wp_enqueue_style( 'stylesheet' );
	wp_register_style( 'jquery-fancybox', get_template_directory_uri() . '/js/fancybox/jquery.fancybox-1.3.4.css' );
	wp_enqueue_style( 'jquery-fancybox' );
}
//add_action( 'wp_print_styles', 'sp_enqueue_styles' );

function sp_admin_styles() {
	wp_register_style( 'sportspress-admin.css', SPORTSPRESS_PLUGIN_URL . 'sportspress-admin.css', array(), '1.0' );
	wp_enqueue_style( 'sportspress-admin.css');
}
add_action( 'admin_init', 'sp_admin_styles' );

function sp_adminbar_enqueue_styles() {
	wp_register_style( 'adminbar-stylesheet',  get_template_directory_uri() . '/css/adminbar.css' );
	wp_enqueue_style( 'adminbar-stylesheet' );
}
//add_action( 'wp_head', 'sp_adminbar_enqueue_styles' );
?>