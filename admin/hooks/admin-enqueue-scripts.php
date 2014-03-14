<?php
function sportspress_admin_enqueue_scripts( $hook ) {
	wp_enqueue_style( 'jquery-chosen', SPORTSPRESS_PLUGIN_URL . 'assets/css/chosen.css', array(), null );

	// Add ThemeBoy icon font, used in the admin stylesheet.
	wp_enqueue_style( 'themeboy', SPORTSPRESS_PLUGIN_URL . 'assets/css/themeboy.css', array(), null );

	// Load our admin stylesheet.
	wp_enqueue_style( 'sportspress-admin', SPORTSPRESS_PLUGIN_URL . 'assets/css/admin.css', array(), time() );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-chosen', SPORTSPRESS_PLUGIN_URL .'assets/js/chosen.jquery.min.js', array( 'jquery' ), '1.1.0', true );
	wp_enqueue_script( 'jquery-tiptip', SPORTSPRESS_PLUGIN_URL .'assets/js/jquery.tipTip.min.js', array( 'jquery' ), '1.3', true );
	wp_enqueue_script( 'jquery-caret', SPORTSPRESS_PLUGIN_URL .'assets/js/jquery.caret.min.js', array( 'jquery' ), '1.02', true );
	wp_enqueue_script( 'jquery-countdown', SPORTSPRESS_PLUGIN_URL .'assets/js/jquery.countdown.min.js', array( 'jquery' ), '2.0.2', true );

	if ( $hook == 'edit-tags.php' && isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'sp_venue' ):
		wp_enqueue_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places' );
		wp_enqueue_script( 'jquery-locationpicker', SPORTSPRESS_PLUGIN_URL .'assets/js/locationpicker.jquery.js', array( 'jquery' ), '0.1.6', true );
		wp_enqueue_script( 'sportspress-admin-locationpicker', SPORTSPRESS_PLUGIN_URL .'assets/js/admin-locationpicker.js', array( 'jquery', 'google-maps', 'jquery-locationpicker' ), time(), true );
	endif;
	
	wp_enqueue_script( 'sportspress-admin', SPORTSPRESS_PLUGIN_URL .'assets/js/admin.js', array( 'jquery' ), time(), true );

	// Localize scripts.
	wp_localize_script( 'sportspress-admin', 'localized_strings', array( 'none' => __( 'None', 'sportspress' ), 'remove_text' => __( '&mdash; Remove &mdash;', 'sportspress' ), 'days' => __( 'days', 'sportspress' ), 'hrs' => __( 'hrs', 'sportspress' ), 'mins' => __( 'mins', 'sportspress' ), 'secs' => __( 'secs', 'sportspress' ) ) );
}
add_action( 'admin_enqueue_scripts', 'sportspress_admin_enqueue_scripts' );
