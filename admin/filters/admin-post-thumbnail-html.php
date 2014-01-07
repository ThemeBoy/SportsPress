<?php
function sp_admin_post_thumbnail_html( $translated_text, $post_id ) {
	$texts = array(
		'sp_team' => array(
			'Set featured image' => 'Select Logo',
			'Remove featured image' => 'Remove Logo',
		),
		'sp_player' => array(
			'Set featured image' => 'Select Photo',
			'Remove featured image' => 'Remove Photo',
		),
		'sp_staff' => array(
			'Set featured image' => 'Select Photo',
			'Remove featured image' => 'Remove Photo',
		),
	);

	$typenow = get_post_type( $post_id );
	if ( is_admin() && array_key_exists( $typenow, $texts ) ):
		foreach ( $texts[ $typenow ] as $key => $value ):
			$translated_text = str_replace( __( $key ), __( $value, 'sportspress' ), $translated_text );
		endforeach;
	endif;
	return $translated_text;
}
add_filter( 'admin_post_thumbnail_html', 'sp_admin_post_thumbnail_html', 10, 2 );
