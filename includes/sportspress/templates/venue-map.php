<?php
/**
 * Venue Map
 *
 * @author      ThemeBoy
 * @package     SportsPress/Templates
 * @version     2.0.11
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
if ( 'satellite' !== $maptype ) $maptype = 'roadmap';

if ( $latitude != null && $longitude != null ):
    ?>
    <iframe
      class="sp-google-map<?php if ( is_tax( 'sp_venue' ) ): ?> sp-venue-map<?php endif; ?>"
      width="600"
      height="320"
      frameborder="0" style="border:0"
      src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAWyt_AG0k_Pgz4LuegtHwesA_OMRnSSAE&amp;q=<?php echo $address; ?>&amp;center=<?php echo $latitude; ?>,<?php echo $longitude; ?>&amp;zoom=<?php echo $zoom; ?>" allowfullscreen>
    </iframe>
    <?php
endif;
