<?php
function sportspress_enqueue_scripts() {
	// Styles
	wp_enqueue_style( 'sportspress', plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/css/sportspress.css', array( 'dashicons' ), time() );

	// Scripts
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false', array(), '3.exp', true );
	wp_enqueue_script( 'jquery-datatables', plugin_dir_url( SP_PLUGIN_FILE ) .'assets/js/jquery.dataTables.min.js', array( 'jquery' ), '1.9.4', true );
	wp_enqueue_script( 'jquery-countdown', plugin_dir_url( SP_PLUGIN_FILE ) .'assets/js/jquery.countdown.min.js', array( 'jquery' ), '2.0.2', true );
	wp_enqueue_script( 'sportspress', plugin_dir_url( SP_PLUGIN_FILE ) .'assets/js/sportspress.js', array( 'jquery' ), time(), true );

	// Localize scripts.
	wp_localize_script( 'sportspress', 'localized_strings', array( 'days' => __( 'days', 'sportspress' ), 'hrs' => __( 'hrs', 'sportspress' ), 'mins' => __( 'mins', 'sportspress' ), 'secs' => __( 'secs', 'sportspress' ) ) );
}
add_action( 'wp_enqueue_scripts', 'sportspress_enqueue_scripts' );