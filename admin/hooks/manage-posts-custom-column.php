<?php
function sportspress_manage_posts_custom_column( $column, $post_id ) {
	global $post;
	switch ( $column ):
		case 'sp_icon':
			edit_post_link( get_the_post_thumbnail( $post_id, 'sportspress-icon' ), '', '', $post_id );
			break;
		case 'sp_position':
			echo get_the_terms ( $post_id, 'sp_position' ) ? the_terms( $post_id, 'sp_position' ) : '—';
			break;
		case 'sp_team':
			$post_type = get_post_type( $post );
			$teams = get_post_meta ( $post_id, 'sp_team', false );
			if ( empty( $teams ) ):
				echo '—';
				break;
			elseif ( $post_type == 'sp_event' ):
				$results = get_post_meta( $post_id, 'sp_results', true );
				foreach( $teams as $team_id ):
					if ( ! $team_id ) continue;
					$team = get_post( $team_id );
					$outcome_slug = sportspress_array_value( sportspress_array_value( $results, $team_id, null ), 'outcome', null );

					if ( $outcome_slug && $outcome_slug != '-1' ):
						$args=array(
							'name' => $outcome_slug,
							'post_type' => 'sp_outcome',
							'post_status' => 'publish',
							'posts_per_page' => 1
						);
						$outcomes = get_posts( $args );

						echo $team->post_title . ( $outcomes ? ' — ' . $outcomes[0]->post_title : '' ) . '<br>';
					else:
						echo $team->post_title . '<br>';
					endif;
				endforeach;
			elseif ( $post_type == 'sp_player' ):
				$results = get_post_meta( $post_id, 'sp_results', true );
				foreach( $teams as $team_id ):
					if ( ! $team_id ) continue;
					$team = get_post( $team_id );
					$outcome_slug = sportspress_array_value( sportspress_array_value( $results, $team_id, null ), 'outcome', null );
					echo $team->post_title . '<br>';
				endforeach;
			else:
				foreach( $teams as $team_id ):
					if ( ! $team_id ) continue;
					$team = get_post( $team_id );
					echo $team->post_title . '<br>';
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
		case 'sp_format':
			echo sportspress_get_post_format( $post_id );
			break;
		case 'sp_player':
			echo sportspress_the_posts( $post_id, 'sp_player' );
			break;
		case 'sp_event':
			echo get_post_meta ( $post_id, 'sp_event' ) ? sizeof( get_post_meta ( $post_id, 'sp_event' ) ) : '—';
			break;
		case 'sp_league':
			echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : '—';
			break;
		case 'sp_season':
			echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : '—';
			break;
		case 'sp_venue':
			echo get_the_terms ( $post_id, 'sp_venue' ) ? the_terms( $post_id, 'sp_venue' ) : '—';
			break;
		case 'sp_sponsor':
			echo get_the_terms ( $post_id, 'sp_sponsor' ) ? the_terms( $post_id, 'sp_sponsor' ) : '—';
			break;
		case 'sp_kickoff':
			if ( $post->post_status == 'future' ):
				_e( 'Scheduled', 'sportspress' );
			elseif( $post->post_status == 'publish' ):
				_e( 'Played', 'sportspress' );
			elseif( $post->post_status == 'draft' ):
				_e( 'Draft', 'sportspress' );
			else:
				_e( 'Pending Review', 'sportspress' );
			endif;
			echo '<br />' . date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) );
			break;
		case 'sp_address':
			echo get_post_meta( $post_id, 'sp_address', true ) ? get_post_meta( $post_id, 'sp_address', true ) : '—';
			break;
	endswitch;
}
add_action( 'manage_posts_custom_column', 'sportspress_manage_posts_custom_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'sportspress_manage_posts_custom_column', 10, 2 );
