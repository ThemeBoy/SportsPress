<?php
function sportspress_admin_post_thumbnail_html( $translated_text, $post_id ) {
	$texts = array(
		'sp_team' => array(
			'Set featured image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Logo', 'sportspress' ) ),
			'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Logo', 'sportspress' ) ),
		),
		'sp_player' => array(
			'Set featured image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
			'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		),
		'sp_staff' => array(
			'Set featured image' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
			'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		),
	);

	$typenow = get_post_type( $post_id );
	if ( is_admin() && array_key_exists( $typenow, $texts ) ):
		foreach ( $texts[ $typenow ] as $key => $value ):
			$translated_text = str_replace( __( $key ), $value, $translated_text );
		endforeach;
	endif;
	return $translated_text;
}
add_filter( 'admin_post_thumbnail_html', 'sportspress_admin_post_thumbnail_html', 10, 2 );
