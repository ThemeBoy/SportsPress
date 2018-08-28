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
    <div class="sp-google-map-container">
      <iframe
        class="sp-google-map<?php if ( is_tax( 'sp_venue' ) ): ?> sp-venue-map<?php endif; ?>"
        width="600"
        height="320"
        frameborder="0" style="border:0"
        src="//tboy.co/maps_embed?q=<?php echo $address; ?>&amp;center=<?php echo $latitude; ?>,<?php echo $longitude; ?>&amp;zoom=<?php echo $zoom; ?>&amp;maptype=<?php echo $maptype; ?>" allowfullscreen>
      </iframe>
      <a href="https://www.google.com.au/maps/place/<?php echo $address; ?>/@<?php echo $latitude; ?>,<?php echo $longitude; ?>,<?php echo $zoom; ?>z" target="_blank" class="sp-google-map-link"></a>
    </div>
    <?php
endif;
