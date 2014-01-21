<?php
function sportspress_post_updated_messages( $messages ) {
	global $typenow, $post;

	if ( in_array( $typenow, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_metric', 'sp_statistic' ) ) ):
		$obj = get_post_type_object( $typenow );

		$messages['post'][1] = sprintf( __( '%s updated.', 'sportspress' ), $obj->labels->singular_name ) .
			' <a href="' . esc_url( admin_url( 'edit.php?post_type=' . $typenow ) ) . '">' .
			sprintf( __( 'Edit %s', 'sportspress' ), $obj->labels->name ) . '</a>';

	elseif ( in_array( $typenow, array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) ) ):
		$obj = get_post_type_object( $typenow );

		$messages['post'][1] = sprintf(	__( '%s updated.', 'sportspress' ),	$obj->labels->singular_name ) .
			' <a href="' . esc_url( get_permalink($post->ID) ) . '">' . $obj->labels->view_item . '</a>';

		$messages['post'][4] = sprintf(	__( '%s updated.', 'sportspress' ), $obj->labels->singular_name );

		$messages['post'][6] = sprintf(	__( '%s published.', 'sportspress' ), $obj->labels->singular_name ) .
			' <a href="' . esc_url( get_permalink($post->ID) ) . '">' . $obj->labels->view_item . '</a>';

		$messages['post'][7] = sprintf(	__( '%s saved.'), $obj->labels->singular_name );

		$messages['post'][8] = sprintf(	__( '%s submitted.', 'sportspress' ), $obj->labels->singular_name ) .
			' <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) . '">' .
			sprintf( __( 'Preview %s', 'sportspress' ), $obj->labels->singular_name ) . '</a>';

		$messages['post'][9] = sprintf(
			__( '%s scheduled for: <strong>%s</strong>.', 'sportspress' ),
			date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post->ID) ) ) .
			' <a target="_blank" href="' . esc_url( get_permalink($post->ID) ) . '">' .
			sprintf( __( 'Preview %s', 'sportspress' ), $obj->labels->singular_name ) . '</a>';

		$messages['post'][8] = sprintf(	__( '%s draft updated.', 'sportspress' ), $obj->labels->singular_name ) .
			' <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) . '">' .
			sprintf( __( 'Preview %s', 'sportspress' ), $obj->labels->singular_name ) . '</a>';

	endif;

	return $messages;
}

add_filter('post_updated_messages', 'sportspress_post_updated_messages');
?>