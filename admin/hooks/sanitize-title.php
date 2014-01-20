<?php
function sportspress_sanitize_title( $title ) {

	if ( isset( $_POST ) && array_key_exists( 'taxonomy', $_POST ) ):

		return $title;
	
	elseif ( isset( $_POST ) && array_key_exists( 'post_type', $_POST ) && in_array( $_POST['post_type'], array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_statistic', 'sp_metric' ) ) ):

		$key = $_POST['sp_key'];

		if ( ! $key ) $key = $_POST['post_title'];

		$title = sportspress_get_eos_safe_slug( $key, sportspress_array_value( $_POST, 'ID', 'var' ) );

	elseif ( isset( $_POST ) && array_key_exists( 'post_type', $_POST ) && $_POST['post_type'] == 'sp_event' ):

		// Auto slug generation
		if ( $_POST['post_title'] == '' && ( $_POST['post_name'] == '' || is_int( $_POST['post_name'] ) ) ):

			$title = '';

		endif;

	endif;

	return $title;
}
add_filter( 'sanitize_title', 'sportspress_sanitize_title' );
