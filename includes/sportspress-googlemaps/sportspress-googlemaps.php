<?php
/*
Plugin Name: SportsPress GoogleMaps Integration
Plugin URI: http://tboy.co/pro
Description: Integrate GoogleMaps to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_GoogleMaps' ) ) :

/**
 * Main SportsPress GoogleMaps Class
 *
 * @class SportsPress_GoogleMaps
 * @version	2.7
 */
class SportsPress_GoogleMaps {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'sp_venue_show_googlemaps', array( $this, 'show_venue_googlemaps' ), 10, 5 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Define constants.
	 */
	private function define_constants() {
		if ( !defined( 'SP_GOOGLEMAPS_VERSION' ) )
			define( 'SP_GOOGLEMAPS_VERSION', '2.7.0' );

		if ( !defined( 'SP_GOOGLEMAPS_URL' ) )
			define( 'SP_GOOGLEMAPS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_GOOGLEMAPS_DIR' ) )
			define( 'SP_GOOGLEMAPS_DIR', plugin_dir_path( __FILE__ ) );
	}


	/**
	 * Integrate GoogleMaps (View Venue)
	 *
	 * @return mix
	 */
	public function show_venue_googlemaps( $latitude, $longitude, $address, $zoom, $maptype ) { ?>
		<div class="sp-google-map-container">
		  <iframe
			class="sp-google-map<?php if ( is_tax( 'sp_venue' ) ): ?> sp-venue-map<?php endif; ?>"
			width="600"
			height="320"
			frameborder="0" style="border:0"
			src="//tboy.co/maps_embed?q=<?php echo $address; ?>&amp;center=<?php echo $latitude; ?>,<?php echo $longitude; ?>&amp;zoom=<?php echo $zoom; ?>&amp;maptype=<?php echo $maptype; ?>" allowfullscreen>
		  </iframe>
		  <a href="https://www.google.com/maps/place/<?php echo $address; ?>/@<?php echo $latitude; ?>,<?php echo $longitude; ?>,<?php echo $zoom; ?>z" target="_blank" class="sp-google-map-link"></a>
		</div>
	<?php
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		wp_register_script( 'google-maps', '//tboy.co/maps_js' );
		wp_register_script( 'jquery-locationpicker', SP_GOOGLEMAPS_URL . 'js/locationpicker.jquery.js', array( 'jquery', 'google-maps' ), '0.1.6', true );
		wp_register_script( 'sportspress-admin-locationpicker', SP_GOOGLEMAPS_URL . 'js/admin/locationpicker.js', array( 'jquery', 'google-maps', 'jquery-locationpicker' ), SP_GOOGLEMAPS_VERSION, true );
		
		// Edit venue pages
		if ( in_array( $screen->id, array( 'edit-sp_venue' ) ) ) {
			wp_enqueue_script( 'google-maps' );
	    	wp_enqueue_script( 'jquery-locationpicker' );
			wp_enqueue_script( 'sportspress-admin-locationpicker' );
		}
	}

}

endif;

if ( get_option( 'sportspress_load_googlemaps_module', 'no' ) == 'yes' ) {
	new SportsPress_GoogleMaps();
}