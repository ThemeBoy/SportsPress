<?php
function sportspress_manage_posts_columns( $defaults ){
    $defaults['sp_views'] = __( 'Views', 'sportspress' );
    return $defaults;
}
add_filter( 'manage_posts_columns', 'sportspress_manage_posts_columns' );
add_filter( 'manage_page_posts_columns', 'sportspress_manage_posts_columns' );

function sportspress_manage_posts_custom_column( $column, $post_id ) {
	global $post;
	switch ( $column ):
		case 'sp_format':
			$format = get_post_meta( $post_id, 'sp_format', true );
			switch ( $format ):
				case 'league':
					echo '<span class="dashicons sp-icon-crown tips" title="' . __( 'League', 'sportspress' ) . '"></span>';
					break;
				case 'friendly':
					echo '<span class="dashicons sp-icon-smile tips" title="' . __( 'Friendly', 'sportspress' ) . '"></span>';
					break;
			endswitch;
			break;
		case 'sp_icon':
			edit_post_link( get_the_post_thumbnail( $post_id, 'sportspress-fit-icon' ), '', '', $post_id );
			break;
		case 'sp_number':
			$number = get_post_meta( $post_id, 'sp_number', true );
			if ( $number != null ):
				echo '<strong>' . $number . '</strong>';
			endif;
			break;
		case 'sp_views':
        	echo sportspress_get_post_views( $post_id );
			break;
		case 'sp_position':
			echo get_the_terms( $post_id, 'sp_position' ) ? the_terms( $post_id, 'sp_position' ) : '&mdash;';
			break;
		case 'sp_positions':
			echo get_the_terms( $post_id, 'sp_position' ) ? the_terms( $post_id, 'sp_position' ) : '&mdash;';
			break;
		case 'sp_team':
			$post_type = get_post_type( $post );
			$teams = (array)get_post_meta( $post_id, 'sp_team', false );
			$teams = array_filter( $teams );
			if ( empty( $teams ) ):
				echo '&mdash;';
				break;
			elseif ( $post_type == 'sp_event' ):
				$results = get_post_meta( $post_id, 'sp_results', true );
				global $sportspress_options;
				$main_result = sportspress_array_value( $sportspress_options, 'main_result', null );
				foreach( $teams as $team_id ):
					if ( ! $team_id ) continue;
					$team = get_post( $team_id );

					if ( $team ):
						$team_results = sportspress_array_value( $results, $team_id, null );

						if ( $main_result ):
							$team_result = sportspress_array_value( $team_results, $main_result, null );
						else:
							if ( is_array( $team_results ) ):
								end( $team_results );
								$team_result = prev( $team_results );
							else:
								$team_result = null;
							endif;
						endif;

						if ( $team_result != null ):
							unset( $team_results['outcome'] );
							$team_results = implode( ' | ', $team_results );
							echo '<a class="result tips" title="' . $team_results . '" href="' . get_edit_post_link( $post_id ) . '">' . $team_result . '</a> ';
						endif;

						echo $team->post_title;

						echo '<br>';
					endif;
				endforeach;
			elseif ( $post_type == 'sp_player' ):
				$current_team = get_post_meta( $post_id, 'sp_current_team', true );
				foreach( $teams as $team_id ):
					if ( ! $team_id ) continue;
					$team = get_post( $team_id );
					if ( $team ):
						echo $team->post_title;
						if ( $team_id == $current_team ):
							echo '<span class="dashicons dashicons-yes" title="' . __( 'Current Team', 'sportspress' ) . '"></span>';
						endif;
						echo '<br>';
					endif;
				endforeach;
			elseif ( $post_type == 'sp_table' ):
				echo sportspress_posts( $post_id, 'sp_team' );
			else:
				foreach( $teams as $team_id ):
					if ( ! $team_id ) continue;
					$team = get_post( $team_id );
					if ( $team ) echo $team->post_title . '<br>';
				endforeach;
			endif;
			break;
		case 'sp_equation':
			echo sportspress_get_post_equation( $post_id );
			break;
		case 'sp_order':
			echo sportspress_get_post_order( $post_id );
			break;
		case 'sp_key':
			echo $post->post_name;
			break;
		case 'sp_precision':
			echo sportspress_get_post_precision( $post_id );
			break;
		case 'sp_calculate':
			echo sportspress_get_post_calculate( $post_id );
			break;
		case 'sp_player':
			echo sportspress_posts( $post_id, 'sp_player' );
			break;
		case 'sp_league':
			echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : '&mdash;';
			break;
		case 'sp_season':
			echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : '&mdash;';
			break;
		case 'sp_venue':
			echo get_the_terms ( $post_id, 'sp_venue' ) ? the_terms( $post_id, 'sp_venue' ) : '&mdash;';
			break;
		case 'sp_time':
			echo get_post_time( 'H:i', false, $post );
			break;
		case 'sp_events':
			echo sizeof( sportspress_get_calendar_data( $post_id ) );
			break;
		case 'sp_address':
			echo get_post_meta( $post_id, 'sp_address', true ) ? get_post_meta( $post_id, 'sp_address', true ) : '&mdash;';
			break;
	endswitch;
}
add_action( 'manage_posts_custom_column', 'sportspress_manage_posts_custom_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'sportspress_manage_posts_custom_column', 10, 2 );
