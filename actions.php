<?php
function sp_load_textdomain() {
    load_plugin_textdomain ( 'sportspress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	add_image_size( 'sp_icon',  32, 32, false );
}
add_action( 'plugins_loaded', 'sp_load_textdomain' );

function sp_add_theme_support() {
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_theme_setup', 'sp_add_theme_support' );
?>