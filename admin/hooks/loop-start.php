<?php
function sportspress_default_venue_content( $query ) {
    if ( ! is_tax( 'sp_venue' ) )
        return;

    $slug = sportspress_array_value( $query->query, 'sp_venue', null );

    if ( ! $slug )
        return;

    $venue = get_term_by( 'slug', $slug, 'sp_venue' );
    $t_id = $venue->term_id;
    $venue_meta = get_option( "taxonomy_$t_id" );
    $address = sportspress_array_value( $venue_meta, 'sp_address', null );
    $latitude = sportspress_array_value( $venue_meta, 'sp_latitude', null );
    $longitude = sportspress_array_value( $venue_meta, 'sp_longitude', null );

    if ( $latitude != null && $longitude != null )
        echo '<div class="sp-google-map" data-address="' . $address . '" data-latitude="' . $latitude . '" data-longitude="' . $longitude . '"></div>';
}
add_action( 'loop_start', 'sportspress_default_venue_content' );
