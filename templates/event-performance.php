<?php
/**
 * Event Performance
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$show_players = get_option( 'sportspress_event_show_players', 'yes' ) === 'yes' ? true : false;
$show_staff = get_option( 'sportspress_event_show_staff', 'yes' ) === 'yes' ? true : false;
$show_extras = get_option( 'sportspress_event_show_extras', 'no' ) === 'yes' ? true : false;
$show_total = get_option( 'sportspress_event_show_total', 'yes' ) === 'yes' ? true : false;
$show_numbers = get_option( 'sportspress_event_show_player_numbers', 'yes' ) === 'yes' ? true : false;
$split_positions = get_option( 'sportspress_event_split_players_by_position', 'no' ) === 'yes' ? true : false;
$split_teams = get_option( 'sportspress_event_split_players_by_team', 'yes' ) === 'yes' ? true : false;
$primary = get_option( 'sportspress_primary_performance', null );
$total = get_option( 'sportspress_event_total_performance', 'all');

if ( ! $show_players && ! $show_staff && ! $show_extras && ! $show_total ) return;

if ( ! isset( $id ) )
	$id = get_the_ID();

$teams = get_post_meta( $id, 'sp_team', false );

if ( is_array( $teams ) ):

	$event = new SP_Event( $id );
	$performance = $event->performance();

	// The first row should be column labels
	$labels = $performance[0];

	// Remove the first row to leave us with the actual data
	unset( $performance[0] );

	$performance = array_filter( $performance );

	$status = $event->status();

	$link_posts = get_option( 'sportspress_link_players', 'yes' ) == 'yes' ? true : false;
	$scrollable = get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false;
	$sortable = get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false;
	$mode = get_option( 'sportspress_event_performance_mode', 'values' );

	// Get performance ids for icons
	if ( $mode == 'icons' ):
		$performance_ids = array();
		$performance_posts = get_posts( array( 'posts_per_page' => -1, 'post_type' => 'sp_performance' ) );
		foreach ( $performance_posts as $post ):
			$performance_ids[ $post->post_name ] = $post->ID;
		endforeach;
	endif;

	if ( $split_teams ) {
		// Split tables
		foreach( $teams as $index => $team_id ):
			if ( -1 == $team_id ) continue;

			// Get results for players in the team
			$players = sp_array_between( (array)get_post_meta( $id, 'sp_player', false ), 0, $index );
			$has_players = sizeof( $players ) > 1;

			if ( $show_extras ) {
				$players[] = -1;
			}

			$show_team_players = $show_players && $has_players;

			if ( 0 < $team_id ) {
				$data = sp_array_combine( $players, sp_array_value( $performance, $team_id, array() ) );
			} elseif ( 0 == $team_id ) {
				$data = array();
				foreach ( $players as $player_id ) {
					if ( isset( $performance[ $player_id ][ $player_id ] ) ) {
						$data[ $player_id ] = $performance[ $player_id ][ $player_id ];
					}
				}
			} else {
				$data = sp_array_value( array_values( $performance ), $index );
			}

			if ( ! $show_team_players && ! $show_staff && ! $show_extras && ! $show_total ) continue;

			if ( $show_team_players || $show_extras || $show_total ) {
				if ( $split_positions ) {
					$positions = get_terms( 'sp_position', array(
						'orderby' => 'slug',
					) );

					foreach ( $positions as $position_index => $position ) {
						$subdata = array();
						foreach ( $data as $player_id => $player ) {
							$player_positions = (array) sp_array_value( $player, 'position' );
							$player_positions = array_filter( $player_positions );
							if ( empty( $player_positions ) ) {
								$player_positions = (array) sp_get_the_term_id( $player_id, 'sp_position' );
							}
							if ( in_array( $position->term_id, $player_positions ) ) {
								$subdata[ $player_id ] = $data[ $player_id ];
							}
						}

						// Initialize Sublabels
						$sublabels = $labels;

						$performance_labels = get_posts( array(
							'post_type' => 'sp_performance',
							'posts_per_page' => -1,
							'tax_query' => array(
								array(
									'taxonomy' => 'sp_position',
									'terms' => $position->term_id,
								),
							),
						) );

						$allowed_labels = array();
						if ( ! empty( $performance_labels ) ) {
							foreach ( $performance_labels as $label ) {
								$allowed_labels[ $label->post_name ] = $label->post_title;
							}

							$sublabels = array_intersect_key( $sublabels, $allowed_labels );
						}

						if ( sizeof( $subdata ) ) {
							if ( $show_extras ) {
								$subdata[-1] = sp_array_value( $data, -1 );
							}

							sp_get_template( 'event-performance-table.php', array(
								'position' => $position->name,
								'scrollable' => $scrollable,
								'sortable' => $sortable,
								'show_players' => $show_team_players,
								'show_numbers' => $show_numbers,
								'show_extras' => $show_extras,
								'show_total' => $show_total,
								'caption' => 0 == $position_index && $team_id ? get_the_title( $team_id ) : null,
								'labels' => $sublabels,
								'mode' => $mode,
								'data' => $subdata,
								'event' => $event,
								'link_posts' => $link_posts,
								'performance_ids' => isset( $performance_ids ) ? $performance_ids : null,
								'primary' => 'primary' == $total ? $primary : null,
							) );
						}
					}
				} else {
					sp_get_template( 'event-performance-table.php', array(
						'scrollable' => $scrollable,
						'sortable' => $sortable,
						'show_players' => $show_team_players,
						'show_numbers' => $show_numbers,
						'show_extras' => $show_extras,
						'show_total' => $show_total,
						'caption' => $team_id ? get_the_title( $team_id ) : null,
						'labels' => $labels,
						'mode' => $mode,
						'data' => $data,
						'event' => $event,
						'link_posts' => $link_posts,
						'performance_ids' => isset( $performance_ids ) ? $performance_ids : null,
						'primary' => 'primary' == $total ? $primary : null,

					) );
				}
			}
			if ( $show_staff ):
				sp_get_template( 'event-staff.php', array( 'id' => $id, 'index' => $index ) );
			endif;
			?>
			<?php
		endforeach;
	} else {
		// Combined table
		$data = array();
		foreach ( $performance as $players ) {
			foreach ( $players as $player_id => $player ) {
				if ( $player_id <= 0 ) continue;
				$data[ $player_id ] = $player;
			}
		}

		if ( $split_positions ) {
			$positions = get_terms( 'sp_position', array(
				'orderby' => 'slug',
			) );

			foreach ( $positions as $position_index => $position ) {
				$subdata = array();
				foreach ( $data as $player_id => $player ) {
					$player_positions = (array) sp_array_value( $player, 'position' );
					$player_positions = array_filter( $player_positions );
					if ( empty( $player_positions ) ) {
						$player_positions = (array) sp_get_the_term_id( $player_id, 'sp_position' );
					}
					if ( in_array( $position->term_id, $player_positions ) ) {
						$subdata[ $player_id ] = $data[ $player_id ];
					}
				}

				if ( sizeof( $subdata ) ) {
					sp_get_template( 'event-performance-table-combined.php', array(
						'scrollable' => $scrollable,
						'sortable' => $sortable,
						'show_players' => $show_players,
						'show_numbers' => $show_numbers,
						'show_extras' => $show_extras,
						'show_total' => $show_total,
						'caption' => $position->name,
						'labels' => $labels,
						'mode' => $mode,
						'data' => $subdata,
						'event' => $event,
						'link_posts' => $link_posts,
						'performance_ids' => isset( $performance_ids ) ? $performance_ids : null,
						'primary' => 'primary' == $total ? $primary : null,
					) );
				}
			}
		} else {
			sp_get_template( 'event-performance-table-combined.php', array(
				'scrollable' => $scrollable,
				'sortable' => $sortable,
				'show_players' => $show_players,
				'show_numbers' => $show_numbers,
				'show_extras' => $show_extras,
				'show_total' => $show_total,
				'caption' => __( 'Performance', 'sportspress' ),
				'labels' => $labels,
				'mode' => $mode,
				'data' => $data,
				'event' => $event,
				'link_posts' => $link_posts,
				'performance_ids' => isset( $performance_ids ) ? $performance_ids : null,
				'primary' => 'primary' == $total ? $primary : null,
			) );
		}
	}
endif;
