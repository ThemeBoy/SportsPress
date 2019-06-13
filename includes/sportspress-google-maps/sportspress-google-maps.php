<?php
/*
Plugin Name: SportsPress Google Maps Integration
Plugin URI: http://tboy.co/pro
Description: Integrate Google Maps to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.18
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Google_Maps' ) ) :

/**
 * Main SportsPress Google Maps Class
 *
 * @class SportsPress_Google_Maps
 * @version	2.6.18
 */
class SportsPress_Google_Maps {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		remove_all_actions( 'sp_venue_show_map', 10 );
		remove_all_actions( 'sp_admin_geocoder_scripts', 10 );
		remove_all_actions( 'sp_setup_geocoder_scripts', 10 );
		remove_all_actions( 'sp_setup_venue_geocoder_scripts', 10 );
		remove_all_actions( 'sp_admin_venue_scripts', 10 );
		remove_all_actions( 'sp_frontend_venue_scripts', 10 );
		add_action( 'sp_venue_show_map', array( $this, 'show_venue_map' ), 10, 5 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'sp_admin_geocoder_scripts', array( $this, 'admin_geocoder_scripts' ), 10 );
		add_action( 'sp_setup_geocoder_scripts', array( $this, 'setup_geocoder_scripts' ), 10 );
		add_action( 'sp_setup_venue_geocoder_scripts', array( $this, 'setup_venue_geocoder_scripts' ), 10 );
	}

	/**
	 * Define constants.
	 */
	private function define_constants() {
		if ( !defined( 'SP_GOOGLE_MAPS_VERSION' ) )
			define( 'SP_GOOGLE_MAPS_VERSION', '2.6.18' );

		if ( !defined( 'SP_GOOGLE_MAPS_URL' ) )
			define( 'SP_GOOGLE_MAPS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_GOOGLE_MAPS_DIR' ) )
			define( 'SP_GOOGLE_MAPS_DIR', plugin_dir_path( __FILE__ ) );
	}


	/**
	 * Integrate Google Maps (View Venue)
	 *
	 * @return mix
	 */
	public function show_venue_map( $latitude, $longitude, $address, $zoom, $maptype ) { ?>
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
		wp_register_script( 'jquery-locationpicker', SP_GOOGLE_MAPS_URL . 'js/locationpicker.jquery.js', array( 'jquery', 'google-maps' ), '0.1.6', true );
		wp_register_script( 'sportspress-admin-locationpicker', SP_GOOGLE_MAPS_URL . 'js/admin/locationpicker.js', array( 'jquery', 'google-maps', 'jquery-locationpicker' ), SP_GOOGLE_MAPS_VERSION, true );
		
		// Edit venue pages
		if ( in_array( $screen->id, array( 'edit-sp_venue' ) ) ) {
			wp_enqueue_script( 'google-maps' );
	    	wp_enqueue_script( 'jquery-locationpicker' );
			wp_enqueue_script( 'sportspress-admin-locationpicker' );
		}
	}
	
	/**
	 * Print geocoder script in admin
	 */
	public function admin_geocoder_scripts() {
		wp_print_scripts( 'sportspress-admin-locationpicker' ); 
	}
	
	/**
	 * Print geocoder script in setup
	 */
	public function setup_geocoder_scripts() {
    wp_register_script( 'google-maps', '//tboy.co/maps_js' );
    wp_register_script( 'jquery-locationpicker', SP_GOOGLE_MAPS_URL . 'js/locationpicker.jquery.js', array( 'jquery', 'google-maps' ), '0.1.6', true );
    wp_register_script( 'sportspress-admin-locationpicker', SP_GOOGLE_MAPS_URL . 'js/admin/locationpicker.js', array( 'jquery', 'google-maps', 'jquery-locationpicker' ), SP_GOOGLE_MAPS_VERSION, true );
	}
	
	/**
	 * Print geocoder script in setup venue step
	 */
	public function setup_venue_geocoder_scripts() {
		wp_print_scripts( 'google-maps' );
	}
}

endif;

if ( get_option( 'sportspress_load_google_maps_module', 'yes' ) == 'yes' ) {
	new SportsPress_Google_Maps();
}