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

if ( $latitude != null && $longitude != null ){
  do_action ( 'sp_venue_show_map', $latitude, $longitude, $address, $zoom, $maptype );
}
