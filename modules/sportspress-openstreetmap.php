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

if ( ! class_exists( 'SportsPress_OpenStreetMap' ) && get_option( 'sportspress_load_googlemaps_module', 'no' ) == 'no' ) :

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
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'sp_venue_show_openstreetmap', array( $this, 'show_venue_openstreetmap' ), 10, 4 );

		// Filters
		//add_filter( 'sportspress_openstreetmap', array( $this, 'add_options' ) );

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
	 * Enqueue admin styles
	 */
	public function admin_styles( $hook ) {
		$screen = get_current_screen();
		if ( in_array( $screen->id, sp_get_screen_ids() ) ) {
			wp_enqueue_style( 'leaflet_stylesheet', SP()->plugin_url() . '/assets/css/leaflet.css', array(), '1.4.0' );
			wp_enqueue_style( 'control-geocoder', SP()->plugin_url() . '/assets/css/Control.Geocoder.css', array() );
		}
	}
	
	/**
	 * Enqueue admin scripts
	 */
	public function admin_scripts( $hook ) {
		$screen = get_current_screen();
		if ( in_array( $screen->id, sp_get_screen_ids() ) ) {
			wp_register_script( 'leaflet_js', SP()->plugin_url() . '/assets/js/leaflet.js', array(), '1.4.0' );
			wp_register_script( 'control-geocoder', SP()->plugin_url() . '/assets/js/Control.Geocoder.js', array( 'leaflet_js' ) );
			wp_register_script( 'sportspress-admin-geocoder', SP()->plugin_url() . '/assets/js/admin/sp-geocoder.js', array( 'leaflet_js', 'control-geocoder' ), SP_VERSION, true );
		}
		// Edit venue pages
	    if ( in_array( $screen->id, array( 'edit-sp_venue' ) ) ) {
	    	wp_enqueue_script( 'leaflet_js' );
	    	wp_enqueue_script( 'control-geocoder' );
		}
	}
	
	/**
	 * Enqueue frontend scripts
	 */
	public function frontend_scripts() {
		if( ( is_single() || is_tax() ) && get_post_type()=='sp_event' ){
			wp_enqueue_style( 'leaflet_stylesheet', SP()->plugin_url() . '/assets/css/leaflet.css', array(), '1.4.0' );
			wp_enqueue_script( 'leaflet_js', SP()->plugin_url() . '/assets/js/leaflet.js', array(), '1.4.0' );
		}
	}
	
	/**
	 * Integrate OpenStreetMap (Show Venue)
	 *
	 * @return mix
	 */
	public function show_venue_openstreetmap( $latitude, $longitude, $zoom, $maptype ) {
		?>
		<a href="https://www.openstreetmap.org/?mlat=<?php echo $latitude; ?>&amp;mlon=<?php echo $longitude; ?>#map=<?php echo $zoom; ?>/<?php echo $latitude; ?>/<?php echo $longitude; ?>" target="_blank"><div id="sp_openstreetmaps_container" style="width: 100%; height: 320px"></div></a>
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
			
}

endif;
if ( get_option( 'sportspress_load_googlemaps_module', 'no' ) == 'no' ) {
	new SportsPress_OpenStreetMap();
}
