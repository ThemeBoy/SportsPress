<?php
/**
 * Venue Map
 *
 * @author      ThemeBoy
 * @package     SportsPress/Templates
 * @version     2.7.18
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $meta ) ) {
	return;
}

if ( is_tax( 'sp_venue' ) ) {
	do_action( 'sportspress_before_venue_map' );
}

$address   = sp_array_value( $meta, 'sp_address', null );
if ( !is_null( $address ) ) {
	$address = urlencode( $address );
}
$latitude  = sp_array_value( $meta, 'sp_latitude', null );
$longitude = sp_array_value( $meta, 'sp_longitude', null );
$zoom      = get_option( 'sportspress_map_zoom', 15 );
$maptype   = get_option( 'sportspress_map_type', 'roadmap' );
$maptype   = strtolower( $maptype );
$osm_tile  = get_option( 'sportspress_osm_tile_server', '' );
$osm_tile  = strtolower( $osm_tile );
$osm_tile  = empty( $osm_tile ) ? 'https://tile.openstreetmap.org/{z}/{x}/{y}.png' : $osm_tile;
$osm_attribution  = get_option( 'sportspress_osm_attribution', '' );
$osm_attribution  = empty( $osm_attribution ) ? 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors' : $osm_attribution;

if ( '' === $address ) {
	$address = '+';
}
if ( 'satellite' === $maptype ) {
	$osm_tile = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';
	$osm_attribution = 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';
}


if ( $latitude != null && $longitude != null ) {
	do_action( 'sp_venue_show_map', $latitude, $longitude, $address, $zoom, $osm_tile, $osm_attribution );
}
if ( is_tax( 'sp_venue' ) ) {
	do_action( 'sportspress_after_venue_map' );
}
