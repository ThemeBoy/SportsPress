<?php
function sp_post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
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
	global $sportspress_thumbnail_texts;
	$typenow = get_post_type( $post_id );
	if ( is_admin() && array_key_exists( $typenow, $sportspress_thumbnail_texts ) ):
		foreach ( $sportspress_thumbnail_texts[ $typenow ] as $key => $value ):
			$translated_text = str_replace( __( $key ), $value, $translated_text );
		endforeach;
	endif;
	return $translated_text;
}
add_filter( 'admin_post_thumbnail_html', 'sp_admin_post_thumbnail_html', 10, 2 );

function sportspress_the_content( $content ) {
    if ( is_singular( 'sp_team' ) && in_the_loop() ):
    
    elseif ( is_singular( 'sp_table' ) && in_the_loop() ):

    	global $post;

        // Display league table
        $content .= '<p>' . sp_get_table_html( $post->ID  ) . '</p>';
    
    elseif ( is_singular( 'sp_list' ) && in_the_loop() ):

    	global $post;

        // Display player list
        $content .= '<p>' . sp_get_list_html( $post->ID  ) . '</p>';

    endif;

    return $content;
}
add_filter('the_content', 'sportspress_the_content');

function sp_sanitize_title( $title ) {
	
	if ( isset( $_POST ) && array_key_exists( 'post_type', $_POST ) && in_array( $_POST['post_type'], array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_metric' ) ) ):

		// Get post title
		$title = $_POST['post_title'];

		// String to lowercase
		$title = strtolower( $title );

		// Replace all numbers with words
		$title = sp_numbers_to_words( $title );

		// Remove all other non-alphabet characters
		$title = preg_replace( "/[^a-z]/", '', $title );

		// Convert post ID to words if title is empty
		if ( $title == '' ) $title = sp_numbers_to_words( $_POST['ID'] );

	endif;

	return $title;
}
add_filter( 'sanitize_title', 'sp_sanitize_title' );

function sp_pre_get_posts( $wp_query ) {
	if ( is_admin() ):
		$post_type = $wp_query->query['post_type'];

		if ( in_array( $post_type, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_metric' ) ) ):
			$wp_query->set( 'orderby', 'menu_order' );
			$wp_query->set( 'order', 'ASC' );
		endif;
	endif;
}
add_filter('pre_get_posts', 'sp_pre_get_posts');
?>