<?php
function sportspress_plugins_loaded() {

    // Load plugin textdomain
	if ( function_exists( 'load_plugin_textdomain' ) ) {

		// SportsPress
    	load_plugin_textdomain ( 'sportspress', false, dirname( SPORTSPRESS_PLUGIN_BASENAME ) . '/languages/' );

    	// Countries
    	load_plugin_textdomain ( 'countries', false, dirname( SPORTSPRESS_PLUGIN_BASENAME ) . '/languages/' );

    }
	
    // Add image sizes
	if ( function_exists( 'add_image_size' ) ) {

		// Header (1680 width minus 15 for scrollbar)
		add_image_size( 'sportspress-header', 1665, 705, true );

		// Standard (4:3)
		add_image_size( 'sportspress-standard', 800, 600, true );
		add_image_size( 'sportspress-standard-thumbnail', 400, 300, true );

		// Wide (16:9)
		add_image_size( 'sportspress-wide', 800, 450, true );
		add_image_size( 'sportspress-wide-thumbnail', 400, 225, true );

		// Square (1:1)
		add_image_size( 'sportspress-square', 612, 612, true );
		add_image_size( 'sportspress-square-thumbnail', 200, 200, true );

		// Fit (Proportional)
		add_image_size( 'sportspress-fit',  300, 300, false );
		add_image_size( 'sportspress-fit-thumbnail',  150, 150, false );

		// Icon (Proportional)
		add_image_size( 'sportspress-icon',  32, 32, false );

	}

}
add_action( 'plugins_loaded', 'sportspress_plugins_loaded' );
