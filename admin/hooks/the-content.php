<?php
function sportspress_the_content( $content ) {
    sportspress_set_post_views( get_the_ID() );

    if ( is_singular( 'sp_event' ) && in_the_loop() ):
        $content = apply_filters( 'sportspress_event_content', $content );
    elseif ( is_singular( 'sp_calendar' ) && in_the_loop() ):
        $content = apply_filters( 'sportspress_calendar_content', $content );
    elseif ( is_singular( 'sp_team' ) && in_the_loop() ):
        $content = apply_filters( 'sportspress_team_content', $content );
    elseif ( is_singular( 'sp_table' ) && in_the_loop() ):
        $content = apply_filters( 'sportspress_table_content', $content );
    elseif ( is_singular( 'sp_list' ) && in_the_loop() ):
        $content = apply_filters( 'sportspress_list_content', $content );
    elseif ( is_singular( 'sp_player' ) && in_the_loop() ):
        $content = apply_filters( 'sportspress_player_content', $content );
    endif;

    return $content;
}
add_filter( 'the_content', 'sportspress_the_content' );
add_filter( 'get_the_content', 'sportspress_the_content' );

function sportspress_default_event_content( $content ) {
    $details = sportspress_event_details();
    $results = sportspress_event_results();
    $players = sportspress_event_players();
    $staff = sportspress_event_staff();
    if ( ! empty( $results ) )
        return $results . $details . $players . $staff . $content;
    $venue = sportspress_event_venue();
    return $details . $venue . $players . $staff . $content;
}
add_filter( 'sportspress_event_content', 'sportspress_default_event_content' );

function sportspress_default_calendar_content( $content ) {
        $calendar = sportspress_events_calendar();
        return $calendar . $content;
}
add_filter( 'sportspress_calendar_content', 'sportspress_default_calendar_content' );

function sportspress_default_team_content( $content ) {
    $columns = sportspress_team_columns();
    return $content . $columns;
}
add_filter( 'sportspress_team_content', 'sportspress_default_team_content' );

function sportspress_default_table_content( $content ) {
    $table = sportspress_league_table();
    $excerpt = has_excerpt() ? wpautop( get_the_excerpt() ) : '';
    return $table . $content . $excerpt;
}
add_filter( 'sportspress_table_content', 'sportspress_default_table_content' );

function sportspress_default_player_content( $content ) {
    $metrics = sportspress_player_metrics();
    $statistics = sportspress_player_statistics();
    return $metrics . $statistics . $content;
}
add_filter( 'sportspress_player_content', 'sportspress_default_player_content' );

function sportspress_default_list_content( $content ) {
    $list = sportspress_player_list( get_the_ID() );
    return $list . $content;
}
add_filter( 'sportspress_list_content', 'sportspress_default_list_content' );
