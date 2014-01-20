<?php
function sportspress_enqueue_scripts() {
	// Styles
	wp_enqueue_style( 'sportspress', SPORTSPRESS_PLUGIN_URL . 'assets/css/sportspress.css', array( 'dashicons' ), time() );

	// Scripts
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array(), '3.exp', true );
	wp_enqueue_script( 'jquery-datatables', SPORTSPRESS_PLUGIN_URL .'/assets/js/jquery.dataTables.min.js', array( 'jquery' ), '1.9.4', true );
	wp_enqueue_script( 'sportspress', SPORTSPRESS_PLUGIN_URL .'/assets/js/sportspress.js', array( 'jquery' ), time(), true );
}
add_action( 'wp_enqueue_scripts', 'sportspress_enqueue_scripts' );