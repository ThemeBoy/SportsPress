<?php
/*
Plugin Name: SportsPress OpenStreetMap Integration
Plugin URI: http://themeboy.com/
Description: Integrate OpenStreetMap to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_OpenStreetMap' ) ):

/**
 * Main SportsPress OpenStreetMap Class
 *
 * @class SportsPress_OpenStreetMap
 * @version	2.7
 */
 
 class SportsPress_OpenStreetMap {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'sp_admin_venue_scripts', array( $this, 'admin_venue_scripts' ) );
		add_action( 'sp_frontend_venue_scripts', array( $this, 'frontend_venue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'sp_venue_show_map', array( $this, 'show_venue_map' ), 10, 5 );
		add_action( 'sp_admin_geocoder_scripts', array( $this, 'admin_geocoder_scripts' ), 10 );
		add_action( 'sp_setup_geocoder_scripts', array( $this, 'setup_geocoder_scripts' ), 10 );
		add_action( 'sp_setup_venue_geocoder_scripts', array( $this, 'setup_venue_geocoder_scripts' ), 10 );
	}
	
	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_OPENSTREETMAP_VERSION' ) )
			define( 'SP_OPENSTREETMAP_VERSION', '2.7' );

		if ( !defined( 'SP_OPENSTREETMAP_URL' ) )
			define( 'SP_OPENSTREETMAP_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_OPENSTREETMAP_DIR' ) )
			define( 'SP_OPENSTREETMAP_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Enqueue admin scripts
	 */
	public function admin_scripts() {
		do_action( 'sp_admin_venue_scripts' );
	}
	
	/**
	 * Enqueue admin venue scripts
	 */
	public function admin_venue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, sp_get_screen_ids() ) ) {
			wp_enqueue_style( 'leaflet_stylesheet', SP()->plugin_url() . '/assets/css/leaflet.css', array(), '1.4.0' );
			wp_enqueue_style( 'control-geocoder', SP()->plugin_url() . '/assets/css/Control.Geocoder.css', array() );
		}

		if ( in_array( $screen->id, sp_get_screen_ids() ) ) {
			wp_register_script( 'leaflet_js', SP()->plugin_url() . '/assets/js/leaflet.js', array(), '1.4.0' );
			wp_register_script( 'control-geocoder', SP()->plugin_url() . '/assets/js/Control.Geocoder.js', array( 'leaflet_js' ) );
			wp_register_script( 'sportspress-admin-geocoder', SP()->plugin_url() . '/assets/js/admin/sp-geocoder.js', array( 'leaflet_js', 'control-geocoder' ), SP_VERSION, true );
		}

		if ( in_array( $screen->id, array( 'edit-sp_venue' ) ) ) {
			wp_enqueue_script( 'leaflet_js' );
			wp_enqueue_script( 'control-geocoder' );
			wp_enqueue_script( 'sportspress-admin-geocoder' );
		}
	}
	
	/**
	 * Enqueue frontend scripts
	 */
	public function frontend_scripts() {
		do_action( 'sp_frontend_venue_scripts' );
	}
	
	/**
	 * Enqueue frontend venue scripts
	 */
	public function frontend_venue_scripts() {
		global $post;
		if( ( ( is_single() || is_tax() ) && get_post_type()=='sp_event' ) || sp_has_shortcodes( $post->post_content, array('event_full', 'event_venue') ) ) {
			wp_enqueue_style( 'leaflet_stylesheet', SP()->plugin_url() . '/assets/css/leaflet.css', array(), '1.4.0' );
			wp_enqueue_script( 'leaflet_js', SP()->plugin_url() . '/assets/js/leaflet.js', array(), '1.4.0' );
		}
	}
	
	/**
	 * Integrate OpenStreetMap (Show Venue)
	 *
	 * @return mix
	 */
	public function show_venue_map( $latitude, $longitude, $address, $zoom, $maptype ) {
		$lat = abs($latitude);
		$lat_deg = floor($lat);
		$lat_sec = ($lat - $lat_deg) * 3600;
		$lat_min = floor($lat_sec / 60);
		$lat_sec = floor($lat_sec - ($lat_min * 60));
		$lat_dir = $latitude > 0 ? 'N' : 'S';

		$lon = abs($longitude);
		$lon_deg = floor($lon);
		$lon_sec = ($lon - $lon_deg) * 3600;
		$lon_min = floor($lon_sec / 60);
		$lon_sec = floor($lon_sec - ($lon_min * 60));
		$lon_dir = $longitude > 0 ? 'E' : 'W';
		?>
		<a href="https://www.google.com/maps/place/<?php echo urlencode("{$lat_deg}°{$lat_min}'{$lat_sec}\"{$lat_dir}").'+'.urlencode("{$lon_deg}°{$lon_min}'{$lon_sec}\"{$lon_dir}"); ?>/@<?php echo $latitude; ?>,<?php echo $longitude; ?>,<?php echo $zoom; ?>z" target="_blank"><div id="sp_openstreetmaps_container" style="width: 100%; height: 320px"></div></a>
	<script>
    // position we will use later
    var lat = <?php echo $latitude; ?>;
    var lon = <?php echo $longitude; ?>;
    // initialize map
    map = L.map('sp_openstreetmaps_container', { zoomControl:false }).setView([lat, lon], <?php echo $zoom; ?>);
    // set map tiles source
    <?php if ( 'satellite' === $maptype ) { ?>
		L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
		  attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
		  maxZoom: 18,
		}).addTo(map);
	<?php }else{ ?>
		L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		  attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
		  maxZoom: 18,
		}).addTo(map);
	<?php } ?>
    // add marker to the map
    marker = L.marker([lat, lon]).addTo(map);
	map.dragging.disable();
	map.touchZoom.disable();
	map.doubleClickZoom.disable();
	map.scrollWheelZoom.disable();
  </script>
		<?php
	}
	
	/**
	 * Print geocoder script in admin
	 */
	public function admin_geocoder_scripts() {
    wp_print_scripts( 'sportspress-admin-setup-geocoder' );
	}
	
	/**
	 * Print geocoder script in setup
	 */
	public function setup_geocoder_scripts() {
		wp_register_script( 'leaflet_js', SP()->plugin_url() . '/assets/js/leaflet.js', array(), '1.4.0' );
		wp_register_script( 'control-geocoder', SP()->plugin_url() . '/assets/js/Control.Geocoder.js', array( 'leaflet_js' ) );
		wp_register_script( 'sportspress-admin-setup-geocoder', SP()->plugin_url() . '/assets/js/admin/sp-setup-geocoder.js', array( 'leaflet_js', 'control-geocoder' ), SP_VERSION, true );
		wp_enqueue_style( 'control-geocoder', SP()->plugin_url() . '/assets/css/Control.Geocoder.css', array() );
		wp_enqueue_style( 'leaflet_stylesheet', SP()->plugin_url() . '/assets/css/leaflet.css', array(), '1.4.0' );
	}
	
	/**
	 * Print geocoder script in setup venue step
	 */
	public function setup_venue_geocoder_scripts() {
		wp_print_scripts( 'leaflet_js' );
		wp_print_scripts( 'control-geocoder' );
	}
}

endif;

new SportsPress_OpenStreetMap();
