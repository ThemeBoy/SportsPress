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
        $id = get_the_ID();

        // Video
        $video_url = get_post_meta( $id, 'sp_video', true );
        if ( $video_url ):
            global $wp_embed;
            echo $wp_embed->autoembed( $video_url );
        endif;

        // Results
        sp_get_template( 'event-results.php' );

        // Details
        sp_get_template( 'event-details.php' );

        // Venue
        sp_get_template( 'event-venue.php' );

        // Performance
        sp_get_template( 'event-performance.php' );

        // Staff
        sp_get_template( 'event-staff.php' );
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
                sp_get_template( 'event-list.php', array(
                    'id' => $id
                ) );
                break;
            default:
                sp_get_template( 'event-calendar.php', array(
                    'id' => $id,
                    'initial' => false
                ) );
                break;
            endswitch;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_calendar_content' );

function sportspress_default_team_content( $content ) {
    if ( is_singular( 'sp_team' ) && in_the_loop() ):
        sp_get_template( 'team-columns.php' );
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
            echo '<h4 class="sp-table-caption">' . implode( ' &mdash; ', $terms ) . '</h4>';

        sp_get_template( 'league-table.php' );
        $excerpt = has_excerpt() ? wpautop( get_the_excerpt() ) : '';
        $content = $content . $excerpt;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_table_content' );

function sportspress_default_player_content( $content ) {
    if ( is_singular( 'sp_player' ) && in_the_loop() ):
        sp_get_template( 'player-metrics.php' );
        sp_get_template( 'player-performance.php' );
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
                sp_get_template( 'player-gallery.php' );
                break;
            default:
                sp_get_template( 'player-list.php' );
                break;
            endswitch;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_list_content' );
