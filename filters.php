<?php
function sp_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    if( empty ( $html ) && in_array( get_post_type( $post_id ), array( 'sp_team', 'sp_tournament', 'sp_venue' ) ) ) {
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
add_filter( 'post_thumbnail_html', 'sp_post_thumbnail_html', 10, 5 );

function sp_gettext( $translated_text, $untranslated_text, $domain ) {
	global $typenow, $sportspress_texts;
	if ( is_admin() && array_key_exists( $typenow, $sportspress_texts ) && array_key_exists( $untranslated_text, $sportspress_texts[ $typenow ] ) )
		return $sportspress_texts[ $typenow ][ $untranslated_text ];
	else
		return $translated_text;
}
add_filter( 'gettext', 'sp_gettext', 20, 3 );

function sp_admin_post_thumbnail_html( $translated_text, $post_id ) {
	global $sportspress_texts;
	$typenow = get_post_type( $post_id );
	if ( is_admin() && array_key_exists( $typenow, $sportspress_texts ) ):
		foreach ( $sportspress_texts[ $typenow ] as $key => $value ):
			$translated_text = str_replace( __( $key ), $value, $translated_text );
		endforeach;
	endif;
	return $translated_text;
}
add_filter( 'admin_post_thumbnail_html', 'sp_admin_post_thumbnail_html', 10, 2 );
?>