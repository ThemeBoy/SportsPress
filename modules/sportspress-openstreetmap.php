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

if ( ! class_exists( 'SportsPress_OpenStreetMap' ) ) :

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
		add_action( 'sp_venue_add_openstreetmap', array( $this, 'add_venue_openstreetmap' ), 10, 3 );
		add_action( 'sp_venue_edit_openstreetmap', array( $this, 'edit_venue_openstreetmap' ), 10, 3 );
		add_action( 'sp_venue_show_openstreetmap', array( $this, 'show_venue_openstreetmap' ), 10, 4 );

		// Filters
		//add_filter( 'sportspress_openstreetmap', array( $this, 'add_options' ) );
		//add_filter( 'sportspress_equation_alter', array( $this, 'alter_equation' ), 10, 2 );

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
	 * Integrate OpenStreetMap (Add Venue)
	 *
	 * @return mix
	 */
	public function add_venue_openstreetmap( $latitude, $longitude, $address ) {
		?>
		<div class="form-field">
			<p><div id="mapDiv" style="width: 95%; height: 320px"></div></p>
			<p><?php _e( "Drag the marker to the venue's location.", 'sportspress' ); ?></p>
		</div>
		<script>
		//Initialize the map and add the Search control box
			var map = L.map('mapDiv').setView([<?php echo $latitude;?>, <?php echo $longitude;?>], 15),
				geocoder = L.Control.Geocoder.nominatim(),
				control = L.Control.geocoder({
					geocoder: geocoder,
					collapsed: false,
					defaultMarkGeocode: false
				}).addTo(map),
				//Add a marker to use from the begining
				marker = L.marker([<?php echo $latitude;?>, <?php echo $longitude;?>],{draggable: true, autoPan: true}).addTo(map);

			L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);
			
			//Pass the values to the fields after dragging
			marker.on('dragend', function (e) {
					document.getElementById('term_meta[sp_latitude]').value = marker.getLatLng().lat;
					document.getElementById('term_meta[sp_longitude]').value = marker.getLatLng().lng;
					geocoder.reverse(marker.getLatLng(), map.options.crs.scale(map.getZoom()), function(results) {
						var r = results[0];
						if (r) {
							document.getElementById('term_meta[sp_address]').value = r.name;
						}
					})
				});
			
			//After searching
			control.on('markgeocode', function(e) {
				var center = e.geocode.center;
				var address = e.geocode.name;
				map.setView([center.lat, center.lng], 15); //Center map to the new place
				map.removeLayer(marker); //Remove previous marker
				marker = L.marker([center.lat, center.lng],{draggable: true, autoPan: true}).addTo(map); //Add new marker to use
				//Pass the values to the fields after searching
				document.getElementById('term_meta[sp_latitude]').value = center.lat;
				document.getElementById('term_meta[sp_longitude]').value = center.lng;
				document.getElementById('term_meta[sp_address]').value = address;
				//Pass the values to the fields after dragging
				marker.on('dragend', function (e) {
					document.getElementById('term_meta[sp_latitude]').value = marker.getLatLng().lat;
					document.getElementById('term_meta[sp_longitude]').value = marker.getLatLng().lng;
					geocoder.reverse(marker.getLatLng(), map.options.crs.scale(map.getZoom()), function(results) {
						var r = results[0];
						if (r) {
							document.getElementById('term_meta[sp_address]').value = r.name;
						}
					})
				});
			}).addTo(map);
		</script>
		<?php
	}
	
	/**
	 * Integrate OpenStreetMap (Edit Venue)
	 *
	 * @return mix
	 */
	public function edit_venue_openstreetmap( $latitude, $longitude, $address ) {
		?>
		<tr class="form-field">
			<td colspan="2">
				<p><div id="mapDiv" style="width: 95%; height: 320px"></div></p>
				<p class="description"><?php _e( "Drag the marker to the venue's location.", 'sportspress' ); ?></p>
			</td>
		</tr>
		<?php if ( $latitude === '' || $longitude === '' ) { 
			$latitude = 40.866667;
			$longitude = 34.566667;
			$zoom = 1;
		}else{
			$zoom = 15;
		}
		?>
		<script>
		//Initialize the map and add the Search control box
			var map = L.map('mapDiv').setView([<?php echo $latitude;?>, <?php echo $longitude;?>], <?php echo $zoom; ?>),
				geocoder = L.Control.Geocoder.nominatim(),
				control = L.Control.geocoder({
					geocoder: geocoder,
					collapsed: false,
					defaultMarkGeocode: false
				}).addTo(map),
				//Add a marker to use from the begining
				marker = L.marker([<?php echo $latitude;?>, <?php echo $longitude;?>],{draggable: true, autoPan: true}).addTo(map);

			L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);
			
			//Pass the values to the fields after dragging
			marker.on('dragend', function (e) {
					document.getElementById('term_meta[sp_latitude]').value = marker.getLatLng().lat;
					document.getElementById('term_meta[sp_longitude]').value = marker.getLatLng().lng;
					geocoder.reverse(marker.getLatLng(), map.options.crs.scale(map.getZoom()), function(results) {
						var r = results[0];
						if (r) {
							document.getElementById('term_meta[sp_address]').value = r.name;
						}
					})
				});
			
			//After searching
			control.on('markgeocode', function(e) {
				var center = e.geocode.center;
				var address = e.geocode.name;
				map.setView([center.lat, center.lng], 15); //Center map to the new place
				map.removeLayer(marker); //Remove previous marker
				marker = L.marker([center.lat, center.lng],{draggable: true, autoPan: true}).addTo(map); //Add new marker to use
				//Pass the values to the fields after searching
				document.getElementById('term_meta[sp_latitude]').value = center.lat;
				document.getElementById('term_meta[sp_longitude]').value = center.lng;
				document.getElementById('term_meta[sp_address]').value = address;
				//Pass the values to the fields after dragging
				marker.on('dragend', function (e) {
					document.getElementById('term_meta[sp_latitude]').value = marker.getLatLng().lat;
					document.getElementById('term_meta[sp_longitude]').value = marker.getLatLng().lng;
					geocoder.reverse(marker.getLatLng(), map.options.crs.scale(map.getZoom()), function(results) {
						var r = results[0];
						if (r) {
							document.getElementById('term_meta[sp_address]').value = r.name;
						}
					})
				});
			}).addTo(map);
		</script>
		<?php
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

new SportsPress_OpenStreetMap();
