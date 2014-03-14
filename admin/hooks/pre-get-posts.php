<?php
function sportspress_pre_get_posts( $query ) {

	if ( is_admin() ):
		if ( isset( $query->query[ 'orderby' ] ) || isset( $query->query[ 'order' ] ) ):
			return $query;
		endif;
		$post_type = $query->query['post_type'];

		if ( in_array( $post_type, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_statistic' ) ) ):
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );
		elseif ( $post_type == 'sp_event' ):
			$query->set( 'orderby', 'post_date' );
			$query->set( 'order', 'ASC' );
		endif;
	else:
		$post_type = $query->get( 'post_type' );
		if ( $query->is_post_type_archive && $post_type == 'sp_event' ):
			$query->set( 'order' , 'ASC' );
		endif;
	endif;

	return $query;
}
add_filter('pre_get_posts', 'sportspress_pre_get_posts');
