<?php
/**
 * Event Performance
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$show_players = get_option( 'sportspress_event_show_players', 'yes' ) === 'yes' ? true : false;
$show_staff = get_option( 'sportspress_event_show_staff', 'yes' ) === 'yes' ? true : false;
$show_total = get_option( 'sportspress_event_show_total', 'yes' ) === 'yes' ? true : false;

if ( ! $show_players && ! $show_staff && ! $show_total ) return;

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
	$responsive = get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false;
	$mode = get_option( 'sportspress_event_performance_mode', 'values' );

	// Get performance ids for icons
	if ( $mode == 'icons' ):
		$responsive = false;
		$performance_ids = array();
		$performance_posts = get_posts( array( 'posts_per_page' => -1, 'post_type' => 'sp_performance' ) );
		foreach ( $performance_posts as $post ):
			$performance_ids[ $post->post_name ] = $post->ID;
		endforeach;
	endif;

	foreach( $teams as $index => $team_id ):
		if ( -1 == $team_id ) continue;

		// Get results for players in the team
		$players = sp_array_between( (array)get_post_meta( $id, 'sp_player', false ), 0, $index );
		$has_players = sizeof( $players ) > 1;

		$show_team_players = $show_players && $has_players;

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

		if ( ! $show_team_players && ! $show_staff && ! $show_total ) continue;
		?>
		<div class="sp-template sp-template-event-performance sp-template-event-performance-<?php echo $mode; ?>">
			<?php if ( $team_id ): ?>
				<h4 class="sp-table-caption"><?php echo get_the_title( $team_id ); ?></h4>
			<?php endif; ?>
			<?php
			if ( $show_staff ):
				sp_get_template( 'event-staff.php', array( 'id' => $id, 'index' => $index ) );
			endif;
			?>
			<?php if ( $show_team_players || $show_total ): ?>
				<div class="sp-table-wrapper">
					<table class="sp-event-performance sp-data-table <?php if ( $responsive ) { ?> sp-responsive-table<?php } if ( $scrollable ) { ?> sp-scrollable-table<?php } ?>">
						<thead>
							<tr>
								<?php if ( $show_team_players ): ?>
									<th class="data-number">#</th>
									<th class="data-name"><?php _e( 'Player', 'sportspress' ); ?></th>
								<?php endif; ?>
								<?php if ( $mode == 'values' ): foreach( $labels as $key => $label ): ?>
									<th class="data-<?php echo $key; ?>"><?php echo $label; ?></th>
								<?php endforeach; else: ?>
									<th class="sp-performance-icons">&nbsp;</th>
								<?php endif; ?>
							</tr>
						</thead>
						<?php if ( $show_team_players ): ?>
							<tbody>
								<?php

								$lineups = array_filter( $data, array( $event, 'lineup_filter' ) );
								$subs = array_filter( $data, array( $event, 'sub_filter' ) );

								$lineup_sub_relation = array();
								foreach ( $subs as $sub_id => $sub ):
									if ( ! $sub_id )
										continue;
									$lineup_sub_relation[ sp_array_value( $sub, 'sub', 0 ) ] = $sub_id;
								endforeach;

								$i = 0;
								foreach( $data as $player_id => $row ):

									if ( ! $player_id )
										continue;

									$name = get_the_title( $player_id );

									if ( ! $name )
										continue;

									echo '<tr class="' . sp_array_value( $row, 'status', 'lineup' ) . ' ' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

									$number = sp_array_value( $row, 'number', '&nbsp;' );

									// Player number
									echo '<td class="data-number">' . $number . '</td>';

									if ( $link_posts ):
										$permalink = get_post_permalink( $player_id );
										$name =  '<a href="' . $permalink . '">' . $name . '</a>';
										if ( isset( $row['status'] ) && $row['status'] == 'sub' ):
											$name = '(' . $name . ')';
										endif;
									endif;

									if ( array_key_exists( $player_id, $lineup_sub_relation ) ):
										$name .= ' <span class="sub-in" title="' . get_the_title( $lineup_sub_relation[ $player_id ] ) . '">' . sp_array_value( sp_array_value( $data, $lineup_sub_relation[ $player_id ], array() ), 'number', null ) . '</span>';
									elseif ( isset( $row['sub'] ) && $row['sub'] ):
										$name .= ' <span class="sub-out" title="' . get_the_title( $row[ 'sub' ] ) . '">' . sp_array_value( sp_array_value( $data, $row['sub'], array() ), 'number', null ) . '</span>';
									endif;

									echo '<td class="data-name">' . $name . '</td>';
									
									if ( $mode == 'icons' ) echo '<td class="sp-performance-icons">';

									foreach( $labels as $key => $label ):
										if ( $key == 'name' )
											continue;
										$value = '&mdash;';
										if ( $key == 'position' ):
											if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
												$position = get_term_by( 'id', $row[ $key ], 'sp_position' );
												if ( $position ) $value = $position->name;
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
								?>
							</tbody>
						<?php endif; ?>
						<?php if ( $show_total ): ?>
							<<?php echo ( $show_team_players ? 'tfoot' : 'tbody' ); ?>>
								<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">
									<?php
									if ( $show_team_players ):
										echo '<td class="data-number">&nbsp;</td>';
										echo '<td class="data-name">' . __( 'Total', 'sportspress' ) . '</td>';
									endif;

									$row = sp_array_value( $data, 0, array() );

									if ( $mode == 'icons' ) echo '<td class="sp-performance-icons">';

									foreach( $labels as $key => $label ):
										if ( $key == 'name' )
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
							</<?php echo ( $show_team_players ? 'tfoot' : 'tbody' ); ?>>
						<?php endif; ?>
					</table>
				</div>
			<?php endif; ?>
		</div>
		<?php
	endforeach;
endif;
