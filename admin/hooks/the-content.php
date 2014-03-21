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
        $id = get_the_ID();
        $video_url = get_post_meta( $id, 'sp_video', true );
        if ( $video_url ):
            global $wp_embed;
            $video = $wp_embed->autoembed( $video_url );
        else:
            $video = '';
        endif;
        if ( $results ):
            $content = $video . $results . $details . $players . $staff . $content;
        else:
            $venue = sportspress_event_venue();
            $content = $video . $details . $venue . $players . $staff . $content;
        endif;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_event_content', 7 );

function sportspress_default_calendar_content( $content ) {
    if ( is_singular( 'sp_calendar' ) && in_the_loop() ):
        $id = get_the_ID();
        $format = get_post_meta( $id, 'sp_format', true );
        switch ( $format ):
            case 'list':
                $calendar = sportspress_events_list( $id );
                break;
            default:
                $calendar = sportspress_events_calendar( $id, false );
                break;
            endswitch;
        $content = $calendar . $content;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_calendar_content' );

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
        $id = get_the_ID();
        $leagues = get_the_terms( $id, 'sp_league' );
        $seasons = get_the_terms( $id, 'sp_season' );
        $terms = array();
        if ( $leagues ):
            $league = reset( $leagues );
            $terms[] = $league->name;
        endif;
        if ( $seasons ):
            $season = reset( $seasons );
            $terms[] = $season->name;
        endif;
        $title = '';
        if ( sizeof( $terms ) )
            $title = '<h4 class="sp-table-caption">' . implode( ' &mdash; ', $terms ) . '</h4>';
        $table = sportspress_league_table();
        $excerpt = has_excerpt() ? wpautop( get_the_excerpt() ) : '';
        $content = $title . $table . $content . $excerpt;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_table_content' );

function sportspress_default_player_content( $content ) {
    if ( is_singular( 'sp_player' ) && in_the_loop() ):
        $metrics = sportspress_player_metrics();
        $statistics = sportspress_player_statistics();
        $content .= $metrics . $statistics;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_player_content' );

function sportspress_default_list_content( $content ) {
    if ( is_singular( 'sp_list' ) && in_the_loop() ):
        $id = get_the_ID();
        $format = get_post_meta( $id, 'sp_format', true );
        switch ( $format ):
            case 'gallery':
                $list = sportspress_player_gallery( $id );
                break;
            default:
                $list = sportspress_player_list( $id );
                break;
            endswitch;
        $content = $list . $content;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_list_content' );
