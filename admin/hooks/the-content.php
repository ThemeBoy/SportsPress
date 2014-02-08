<?php
function sportspress_the_content( $content ) {
    if ( is_single() || is_page() )
        sportspress_set_post_views( get_the_ID() );
    return $content;
}
add_filter( 'the_content', 'sportspress_the_content' );
add_filter( 'get_the_content', 'sportspress_the_content' );

function sportspress_default_event_content( $content ) {
    if ( is_singular( 'sp_event' ) && in_the_loop() ):
        $details = sportspress_event_details();
        $results = sportspress_event_results();
        $players = sportspress_event_players();
        $staff = sportspress_event_staff();
        if ( is_array( $results ) && array_filter( $results, 'array_filter' ) )
            return $results . $details . $players . $staff . $content;
        $venue = sportspress_event_venue();
        $content = $details . $venue . $players . $staff . $content;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_event_content' );

function sportspress_default_team_content( $content ) {
    if ( is_singular( 'sp_team' ) && in_the_loop() ):
        $columns = sportspress_team_columns();
        $content = $content . $columns;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_team_content' );

function sportspress_default_table_content( $content ) {
    if ( is_singular( 'sp_table' ) && in_the_loop() ):
        $table = sportspress_league_table();
        $excerpt = has_excerpt() ? wpautop( get_the_excerpt() ) : '';
        $content = $table . $content . $excerpt;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_table_content' );

function sportspress_default_player_content( $content ) {
    if ( is_singular( 'sp_player' ) && in_the_loop() ):
        $metrics = sportspress_player_metrics();
        $statistics = sportspress_player_statistics();
        $content = $metrics . $statistics . $content;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_player_content' );

function sportspress_default_list_content( $content ) {
    if ( is_singular( 'sp_list' ) && in_the_loop() ):
        $list = sportspress_player_list( get_the_ID() );
        $content = $list . $content;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_list_content' );


/* 
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
        */