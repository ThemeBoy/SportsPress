<?php
/**
 * Venue Map
 *
 * @author      ThemeBoy
 * @package     SportsPress/Templates
 * @version     2.6.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $meta ) )
    return;

$address = sp_array_value( $meta, 'sp_address', null );
$address = urlencode( $address );
$latitude = sp_array_value( $meta, 'sp_latitude', null );
$longitude = sp_array_value( $meta, 'sp_longitude', null );
$zoom = get_option( 'sportspress_map_zoom', 15 );
$maptype = get_option( 'sportspress_map_type', 'roadmap' );
$maptype = strtolower( $maptype );

if ( '' === $address ) $address = '+';
if ( 'satellite' !== $maptype ) $maptype = 'roadmap';

if ( $latitude != null && $longitude != null ):
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
endif;
