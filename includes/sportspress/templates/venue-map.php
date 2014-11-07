<?php
/**
 * Venue Map
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $meta ) )
	return;

$address = sp_array_value( $meta, 'sp_address', null );
$latitude = sp_array_value( $meta, 'sp_latitude', null );
$longitude = sp_array_value( $meta, 'sp_longitude', null );

if ( $latitude != null && $longitude != null ):
	?>
	<div class="sp-google-map<?php if ( is_tax( 'sp_venue' ) ): ?> sp-venue-map<?php endif; ?>" data-address="<?php echo $address; ?>" data-latitude="<?php echo $latitude; ?>" data-longitude="<?php echo $longitude; ?>"></div>
	<?php
endif;
