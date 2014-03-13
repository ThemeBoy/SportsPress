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
		case 'sp_icon':
			edit_post_link( get_the_post_thumbnail( $post_id, 'sportspress-fit-icon' ), '', '', $post_id );
			break;
		case 'sp_number':
			echo '<strong>' . get_post_meta( $post_id, 'sp_number', true ) . '</strong>';
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
			$teams = get_post_meta( $post_id, 'sp_team', false );
			if ( empty( $teams ) ):
				echo '&mdash;';
				break;
			elseif ( $post_type == 'sp_event' ):
				$results = get_post_meta( $post_id, 'sp_results', true );
				$options = get_option( 'sportspress' );
				$main_result = sportspress_array_value( $options, 'main_result', null );
				foreach( $teams as $team_id ):
					if ( ! $team_id ) continue;
					$team = get_post( $team_id );

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
						echo '<strong>' . $team_result . '</strong> ';
					endif;

					echo $team->post_title;

					echo '<br>';
				endforeach;
			elseif ( $post_type == 'sp_player' ):
				$current_team = get_post_meta( $post_id, 'sp_current_team', true );
				foreach( $teams as $team_id ):
					if ( ! $team_id ) continue;
					$team = get_post( $team_id );
					echo $team->post_title;
					if ( $team_id == $current_team ):
						echo '<span class="dashicons dashicons-yes" title="' . __( 'Current Team', 'sportspress' ) . '"></span>';
					endif;
					echo '<br>';
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
		case 'sp_precision':
			echo sportspress_get_post_precision( $post_id );
			break;
		case 'sp_calculate':
			echo sportspress_get_post_calculate( $post_id );
			break;
		case 'sp_player':
			echo sportspress_posts( $post_id, 'sp_player' );
			break;
		case 'sp_event':
			echo get_post_meta ( $post_id, 'sp_event' ) ? sizeof( get_post_meta ( $post_id, 'sp_event' ) ) : '&mdash;';
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
		case 'sp_sponsor':
			echo get_the_terms ( $post_id, 'sp_sponsor' ) ? the_terms( $post_id, 'sp_sponsor' ) : '&mdash;';
			break;
		case 'sp_datetime':
			echo sportspress_get_post_datetime( $post );
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
