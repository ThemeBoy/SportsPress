<?php
function sportspress_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    if( empty ( $html ) && in_array( get_post_type( $post_id ), array( 'sp_team', 'sp_tournament' ) ) ) {
		$parents = get_post_ancestors( $post_id );
		foreach ( $parents as $parent ) {
			if( has_post_thumbnail( $parent ) ) {
				$html = get_the_post_thumbnail( $parent, $size, $attr );
				break;
			}
		}
    }
    return $html;
}
add_filter( 'post_thumbnail_html', 'sportspress_post_thumbnail_html', 10, 5 );
