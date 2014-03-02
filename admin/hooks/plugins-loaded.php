<?php
function sportspress_plugins_loaded() {

    // Load plugin textdomain
	if ( function_exists( 'load_plugin_textdomain' ) ) {
    	load_plugin_textdomain ( 'sportspress', false, dirname( SPORTSPRESS_PLUGIN_BASENAME ) . '/languages/' );
    }
	
    // Add image sizes
	if ( function_exists( 'add_image_size' ) ) {

		// Standard (3:2)
		add_image_size( 'sportspress-standard', 637, 425, true );
		add_image_size( 'sportspress-standard-thumbnail', 303, 202, true );

		// Wide (16:9)
		add_image_size( 'sportspress-wide-header', 1600, 900, true );
		add_image_size( 'sportspress-wide', 637, 358, true );
		add_image_size( 'sportspress-wide-thumbnail', 303, 170, true );

		// Square (1:1)
		add_image_size( 'sportspress-square', 637, 637, true );
		add_image_size( 'sportspress-square-thumbnail', 303, 303, true );

		// Fit (Proportional)
		add_image_size( 'sportspress-fit',  637, 637, false );
		add_image_size( 'sportspress-fit-thumbnail',  303, 303, false );
		add_image_size( 'sportspress-fit-icon',  32, 32, false );

	}

}
add_action( 'plugins_loaded', 'sportspress_plugins_loaded' );
