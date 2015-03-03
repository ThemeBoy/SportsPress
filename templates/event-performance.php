<?php
/**
 * Event Performance
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.6
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
			?>
			<?php if ( $team_id ): ?>
				<h4 class="sp-table-caption"><?php echo get_the_title( $team_id ); ?></h4>
			<?php endif; ?>
			<?php
			if ( $show_team_players || $show_extras || $show_total ) {
				if ( $split_positions ) {
					$positions = get_terms( 'sp_position', array(
						'orderby' => 'slug',
					) );

					foreach ( $positions as $position ) {
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
								'show_team_players' => $show_team_players,
								'show_numbers' => $show_numbers,
								'show_extras' => $show_extras,
								'show_total' => $show_total,
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
						'show_team_players' => $show_team_players,
						'show_numbers' => $show_numbers,
						'show_extras' => $show_extras,
						'show_total' => $show_total,
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
		?>
		<div class="sp-template sp-template-event-performance sp-template-event-performance-<?php echo $mode; ?>">
			<h4 class="sp-table-caption"><?php _e( 'Performance', 'sportspress' ); ?></h4>
			<div class="sp-table-wrapper">
				<table class="sp-event-performance sp-data-table <?php if ( $scrollable ) { ?> sp-scrollable-table<?php } ?>">
					<thead>
						<tr>
							<?php if ( isset( $labels['number'] ) ): ?>
								<th class="data-number">#</th>
							<?php endif; ?>
							<th class="data-name"><?php _e( 'Player', 'sportspress' ); ?></th>
							<?php if ( $mode == 'values' ): foreach( $labels as $key => $label ): ?>
								<?php if ( 'number' == $key ) continue; ?>
								<th class="data-<?php echo $key; ?>"><?php echo $label; ?></th>
							<?php endforeach; else: ?>
								<th class="sp-performance-icons">&nbsp;</th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $teams as $index => $team_id ) {
							if ( -1 == $team_id ) continue;

							// Get results for players in the team
							$players = sp_array_between( (array) get_post_meta( $id, 'sp_player', false ), 0, $index );
							$has_players = sizeof( $players ) > 1;

							$show_team_players = $show_players && $has_players;
							if ( ! $show_team_players && ! $show_total ) continue;

							$totals = array();

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

							if ( $show_team_players ) {

								$i = 0;
								foreach ( $data as $player_id => $row ):

									if ( ! $player_id )
										continue;

									$name = get_the_title( $player_id );

									if ( ! $name )
										continue;

									echo '<tr class="' . sp_array_value( $row, 'status', 'lineup' ) . ' ' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

									if ( isset( $labels['number'] ) ):
										$number = sp_array_value( $row, 'number', '&nbsp;' );

										// Player number
										echo '<td class="data-number">' . $number . '</td>';
									endif;

									if ( $link_posts ):
										$permalink = get_post_permalink( $player_id );
										$name =  '<a href="' . $permalink . '">' . $name . '</a>';
									endif;

									echo '<td class="data-name">' . $name . '</td>';
									
									if ( $mode == 'icons' ) echo '<td class="sp-performance-icons">';

									foreach ( $labels as $key => $label ):
										if ( in_array( $key, array( 'number', 'name' ) ) )
											continue;
										$value = '&mdash;';
										if ( $key == 'position' ):
											if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
												$positions = array();
												$position_ids = (array) $row[ $key ];
												foreach ( $position_ids as $position_id ) {
													$player_position = get_term_by( 'id', $position_id, 'sp_position' );
													if ( $player_position ) $positions[] = $player_position->name;
												}
												$value = implode( ', ', $positions );
											endif;
										else:
											if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
												$value = $row[ $key ];
											else:
												$value = 0;
											endif;
										endif;
										if ( ! array_key_exists( $key, $totals ) ):
											$totals[ $key ] = 0;
										endif;
										$totals[ $key ] += $value;

										if ( $mode == 'values' ):
											echo '<td class="data-' . $key . '">' . $value . '</td>';
										elseif ( intval( $value ) && $mode == 'icons' ):
											$performance_id = sp_array_value( $performance_ids, $key, null );
											if ( $performance_id && has_post_thumbnail( $performance_id ) ):
												echo str_repeat( get_the_post_thumbnail( $performance_id, 'sportspress-fit-mini' ) . ' ', $value );
											endif;
										endif;
									endforeach;
									
									if ( $mode == 'icons' ) echo '</td>';

									echo '</tr>';

									$i++;

								endforeach;

							}
						}
						?>
						</tbody>
						<?php if ( $show_total ): ?>
							<tfoot>
								<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">
									<?php
									if ( isset( $labels['number'] ) ):
										echo '<td class="data-number">&nbsp;</td>';
									endif;
									echo '<td class="data-name">' . __( 'Total', 'sportspress' ) . '</td>';

									$row = sp_array_value( $data, 0, array() );

									if ( $mode == 'icons' ) echo '<td class="sp-performance-icons">';

									foreach ( $labels as $key => $label ):
										if ( in_array( $key, array( 'number', 'name' ) ) )
											continue;
										if ( $key == 'position' ):
											$value = '&mdash;';
										elseif ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
											$value = $row[ $key ];
										else:
											$value = sp_array_value( $totals, $key, 0 );
										endif;

										if ( $mode == 'values' ):
											echo '<td class="data-' . $key . '">' . $value . '</td>';
										elseif ( intval( $value ) && $mode == 'icons' ):
											$performance_id = sp_array_value( $performance_ids, $key, null );
											if ( $performance_id && has_post_thumbnail( $performance_id ) ):
												echo str_repeat( get_the_post_thumbnail( $performance_id, 'sportspress-fit-mini' ) . ' ', $value );
											endif;
										endif;
									endforeach;

									if ( $mode == 'icons' ) echo '</td>';
									?>
								</tr>
							</tfoot>
						<?php endif; ?>
				</table>
			</div>
		</div>
		<?php
	}
endif;
