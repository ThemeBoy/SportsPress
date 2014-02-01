<?php
function sportspress_the_posts( $posts ) {
	global $wp_query, $wpdb;
	if( is_single() && $wp_query->post_count == 0 && isset( $wp_query->query_vars['sp_event'] ) ) {
		$posts = $wpdb->get_results( $wp_query->request );
	}
	return $posts;
}
//add_filter( 'the_posts', 'sportspress_the_posts' );

function sportspress_posts_where( $where, $that ) {
    global $wpdb;
    if( 'sp_event' == $that->query_vars['post_type'] && is_archive() )
        $where = str_replace( "{$wpdb->posts}.post_status = 'publish'", "{$wpdb->posts}.post_status = 'publish' OR $wpdb->posts.post_status = 'future'", $where );
    return $where;
}
add_filter( 'posts_where', 'sportspress_posts_where', 2, 10 );
