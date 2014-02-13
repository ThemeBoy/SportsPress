<?php
function sportspress_admin_enqueue_scripts( $hook ) {
	// Add ThemeBoy icon font, used in the admin stylesheet.
	wp_enqueue_style( 'themeboy', SPORTSPRESS_PLUGIN_URL . 'assets/css/themeboy.css', array(), null );

	// Load our admin stylesheet.
	wp_enqueue_style( 'sportspress-admin', SPORTSPRESS_PLUGIN_URL . 'assets/css/admin.css', array(), time() );

	wp_enqueue_script( 'jquery' );

	if ( $hook == 'edit-tags.php' && isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'sp_venue' ):
		wp_enqueue_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places' );
		wp_enqueue_script( 'jquery-locationpicker', SPORTSPRESS_PLUGIN_URL .'assets/js/locationpicker.jquery.js', array( 'jquery' ), '0.1.6', true );
		wp_enqueue_script( 'sportspress-admin-locationpicker', SPORTSPRESS_PLUGIN_URL .'assets/js/admin-locationpicker.js', array( 'jquery', 'google-maps', 'jquery-locationpicker' ), time(), true );
	endif;
	
	wp_enqueue_script( 'sportspress-admin', SPORTSPRESS_PLUGIN_URL .'assets/js/admin.js', array( 'jquery' ), time(), true );

	// Localize scripts.
	wp_localize_script( 'sportspress-admin', 'localized_strings', array( 'remove_text' => __( '&mdash; Remove &mdash;', 'sportspress' ) ) );
}
add_action( 'admin_enqueue_scripts', 'sportspress_admin_enqueue_scripts' );
