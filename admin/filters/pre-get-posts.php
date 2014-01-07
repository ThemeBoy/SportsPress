<?php
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
