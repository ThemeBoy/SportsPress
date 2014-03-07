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
        if ( $results ):
            $content = $results . $details . $players . $staff . $content;
        else:
            $venue = sportspress_event_venue();
            $content = $details . $venue . $players . $staff . $content;
        endif;
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
        $list = sportspress_player_list( get_the_ID() );
        $content = $list . $content;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_list_content' );
