<?php
function sportspress_the_content( $content ) {
    
    if ( is_singular( 'sp_event' ) && in_the_loop() ):

        global $post;

        // Display event details
        $content = sportspress_event_details( $post->ID ) . $content;

    elseif ( is_singular( 'sp_calendar' ) && in_the_loop() ):

        global $post;

        // Display events calendar
        $content = sportspress_events_calendar( $post->ID ) . $content;

    elseif ( is_singular( 'sp_team' ) && in_the_loop() ):

        global $post;

        // Display team columns
        $content = sportspress_team_columns( $post->ID ) . $content;

    elseif ( is_singular( 'sp_table' ) && in_the_loop() ):

        global $post;

        // Display league table
        $content = sportspress_league_table( $post->ID ) . $content;
    
    elseif ( is_singular( 'sp_list' ) && in_the_loop() ):

        global $post;

        // Display player list
        $content = sportspress_player_list( $post->ID ) . $content;

    
    elseif ( is_singular( 'sp_player' ) && in_the_loop() ):

        global $post;

        // Display player metrics and statistics
        $content = sportspress_player_metrics( $post->ID ) . sportspress_player_statistics( $post->ID ) . $content;

    endif;

    return $content;
}
add_filter( 'the_content', 'sportspress_the_content' );
add_filter( 'get_the_content', 'sportspress_the_content' );