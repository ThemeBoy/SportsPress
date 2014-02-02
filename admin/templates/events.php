<?php
if ( !function_exists( 'sportspress_events' ) ) {
	function sportspress_events( $args = array() ) {

		$options = array(
			'post_type' => 'sp_event',
			'posts_per_page' => 1,
			'post_status' => 'publish',
			'tax_query' => array(),
		);

		if ( isset( $args['number'] ) ):
			$options['posts_per_page'] = $args['number'];
		endif;

		if ( isset( $args['status'] ) && $args['status'] == 'future' ):
			$options['post_status'] = array( 'future' );
			$options['order'] = 'ASC';
		endif;

		if ( isset( $args['league'] ) ):
			$options['tax_query'][] = array(
				'taxonomy' => 'sp_league',
				'field' => 'id',
				'terms' => $league
			);
		endif;

		if ( isset( $args['season'] ) ):
			$options['tax_query'][] = array(
				'taxonomy' => 'sp_season',
				'field' => 'id',
				'terms' => $season
			);
		endif;

		if ( isset( $args['venue'] ) ):
			$options['tax_query'][] = array(
				'taxonomy' => 'sp_venue',
				'field' => 'id',
				'terms' => $venue
			);
		endif;
		
		$query = new WP_Query( $options );
		 
		if ( $query->have_posts() ):
			$output = '<ul class="sp-events-list">';
			while ( $query->have_posts() ):
				$query->the_post();

				$output .=
				'<li>' .
					'<span class="post-date">' . get_the_date() . '</span>' .
					'<a href="' . get_permalink() . '">' . get_the_title() . '</a>' .
				'</li>';
		 
			endwhile;
			$output .= '</ul>';
			wp_reset_postdata();
		endif;

		return apply_filters( 'sportspress_events',  $output );

	}
}
