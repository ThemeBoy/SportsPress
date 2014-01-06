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
		return __( $sportspress_texts[ $typenow ][ $untranslated_text ], 'sportspress' );
	else
		return $translated_text;
}
add_filter( 'gettext', 'sp_gettext', 20, 3 );

function sp_admin_post_thumbnail_html( $translated_text, $post_id ) {
	global $sportspress_thumbnail_texts;
	$typenow = get_post_type( $post_id );
	if ( is_admin() && array_key_exists( $typenow, $sportspress_thumbnail_texts ) ):
		foreach ( $sportspress_thumbnail_texts[ $typenow ] as $key => $value ):
			$translated_text = str_replace( __( $key ), __( $value, 'sportspress' ), $translated_text );
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
	
	if ( isset( $_POST ) && array_key_exists( 'post_type', $_POST ) && in_array( $_POST['post_type'], array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_statistic' ) ) ):

		$key = $_POST['sp_key'];

		if ( ! $key ) $key = $_POST['post_title'];

		$title = sp_get_eos_safe_slug( $key, sp_array_value( $_POST, 'ID', 'var' ) );

	elseif ( isset( $_POST ) && array_key_exists( 'post_type', $_POST ) && $_POST['post_type'] == 'sp_event' ):

		// Auto slug generation
		if ( $_POST['post_title'] == '' && ( $_POST['post_name'] == '' || is_int( $_POST['post_name'] ) ) ):

			$title = '';

		endif;

	endif;

	return $title;
}
add_filter( 'sanitize_title', 'sp_sanitize_title' );

function sp_insert_post_data( $data, $postarr ) {
  
	if( $data['post_type'] == 'sp_event' && $data['post_title'] == '' ):

			$teams = (array)$postarr['sp_team'];

			$team_names = array();
			foreach( $teams as $team ):
				$team_names[] = get_the_title( $team );
			endforeach;

			$data['post_title'] = implode( ' ' . __( 'vs', 'sportspress' ) . ' ', $team_names );

	endif;

	return $data;
}
add_filter( 'wp_insert_post_data' , 'sp_insert_post_data' , '99', 2 );

function sp_pre_get_posts( $wp_query ) {
	if ( is_admin() ):
		$post_type = $wp_query->query['post_type'];

		if ( in_array( $post_type, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_statistic' ) ) ):
			$wp_query->set( 'orderby', 'menu_order' );
			$wp_query->set( 'order', 'ASC' );
		elseif ( $post_type == 'sp_event' ):
			$wp_query->set( 'orderby', 'post_date' );
			$wp_query->set( 'order', 'ASC' );
		endif;
	endif;
}
add_filter('pre_get_posts', 'sp_pre_get_posts');
?>