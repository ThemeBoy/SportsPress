<?php
function sportspress_the_content( $content ) {
    
    if ( is_singular( 'sp_event' ) && in_the_loop() ):

        global $post;

        $details = sportspress_event_details( $post->ID );
        $results = sportspress_event_results( $post->ID );
        $players = sportspress_event_players( $post->ID );
        $staff = sportspress_event_staff( $post->ID );

        if ( ! empty( $results ) ):
            $content = $results . $details . $players . $staff . $content;
        else:
            $venue = sportspress_event_venue( $post->ID );
            $content = $details . $venue . $players . $staff . $content;
        endif;

    elseif ( is_singular( 'sp_calendar' ) && in_the_loop() ):

        global $post;

        $calendar = sportspress_events_calendar( $post->ID );

        $content = $calendar . $content;

    elseif ( is_singular( 'sp_team' ) && in_the_loop() ):

        global $post;

        $columns = sportspress_team_columns( $post->ID );

        $content = $columns . $content;

    elseif ( is_singular( 'sp_table' ) && in_the_loop() ):

        global $post;

        $table = sportspress_league_table( $post->ID );

        $content = $table . $content;
    
    elseif ( is_singular( 'sp_list' ) && in_the_loop() ):

        global $post;

        $list = sportspress_player_list( $post->ID );

        $content = $list . $content;

    
    elseif ( is_singular( 'sp_player' ) && in_the_loop() ):

        global $post;

        $metrics = sportspress_player_metrics( $post->ID );
        $statistics = sportspress_player_statistics( $post->ID );

        $content = $metrics . $statistics . $content;

    endif;

    return $content;
}
add_filter( 'the_content', 'sportspress_the_content' );
add_filter( 'get_the_content', 'sportspress_the_content' );