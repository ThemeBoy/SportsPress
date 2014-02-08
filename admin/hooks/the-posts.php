<?php
function sportspress_posts_where( $where, $that ) {
    global $wpdb;
    if( 'sp_event' == $that->query_vars['post_type'] && is_archive() )
        $where = str_replace( "{$wpdb->posts}.post_status = 'publish'", "{$wpdb->posts}.post_status = 'publish' OR $wpdb->posts.post_status = 'future'", $where );
    return $where;
}
add_filter( 'posts_where', 'sportspress_posts_where', 2, 10 );
