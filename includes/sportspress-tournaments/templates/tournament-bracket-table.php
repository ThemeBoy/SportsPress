<?php
/**
 * Tournament Bracket Table
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Tournaments
 * @version   2.6.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'show_logos' => get_option( 'sportspress_tournament_show_logos', 'yes' ) == 'yes' ? true : false,
	'show_venue' => get_option( 'sportspress_tournament_show_venue', 'no' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
	'scrollable' => get_option( 'sportspress_enable_scrollable_tables', 'yes' ) == 'yes' ? true : false,
	'layout' => 'bracket',
	'type' => 'single',
);

extract( $defaults, EXTR_SKIP );

$tournament = new SP_Tournament( $id );
list( $labels, $data, $cols, $rows, $rounds, $raw ) = $tournament->data( $layout, true, $type );
?>
<table class="sp-data-table sp-tournament-bracket<?php if ( $scrollable ) { ?> sp-scrollable-table<?php } ?>">
	<thead>
		<tr>
			<?php for ( $col = 0; $col < $cols; $col++ ): ?>
				<th>
					<?php
					$label = sp_array_value( $labels, $col, null );
					if ( $label == null ) {
						printf( __( 'Round %s', 'sportspress' ), $col + 1 );
					} else {
						echo $label;
					}
					?>
				</th>
			<?php endfor; ?>
		</tr>
	</thead>
	<tbody>
		<?php for ( $row = 0; $row < $rows; $row++ ): ?>
			<tr>
				<?php
				for ( $col = 0; $col < $cols; $col++ ):
					$cell = sp_array_value( sp_array_value( $data, $row, array() ), $col, null );
					if ( $cell === null ) continue;

					$hidden = sp_array_value( $cell, 'hidden' );

					if ( $hidden ) {	
						echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '">&nbsp;</td>';
						continue;
					}

					$index = sp_array_value( $cell, 'index' );
					$event = sp_array_value( $cell, 'id', 0 );
					
					if ( sp_array_value( $cell, 'type', null ) === 'event' ):
						echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-event' . ( $col === 0 ? ' sp-first-round' : '' ) . ( $col === $cols - 1 ? ' sp-last-round' : '' ) . ' ' . sp_array_value( $cell, 'class', '' ) . '">';
						if ( $event ) {
							$status = get_post_meta( $event, 'sp_status', true );

							if ( 'tbd' == $status ) {
								$event_name = '<span class="sp-result">' . get_option( 'sportspress_event_teams_delimiter', 'vs' ) . '</span>';
							} else {
								$event_name = '<span class="sp-result">' . implode( '</span>-<span class="sp-result">', apply_filters( 'sportspress_main_results_or_time', sp_get_main_results_or_time( $event ), $event ) ) . '</span>';
							}

							if ( $show_logos ) {
								$teams = sp_get_teams( $event );
								if ( $teams && sizeof( $teams ) >= 2 ) {
									$home = reset( $teams );
									if ( $home ) {
										if ( sp_has_logo( $home ) ) {
											$event_name = sp_get_logo( $home, 'icon' ) . ' ' . $event_name;
										} else {
											$event_name = sp_team_short_name( $home ) . ' ' . $event_name;
										}
									}

									$away = end( $teams );
									if ( $away ) {
										if ( sp_has_logo( $away ) ) {
											$event_name .= ' ' . sp_get_logo( $away, 'icon' );
										} else {
											$event_name .= ' ' . sp_team_short_name( $away );
										}
									}
								}
							}

							if ( 'tbd' != $status ) {
								$event_date = '<div class="sp-event-date">' . get_the_date( get_option( 'date_format' ), $event ) . '</div>';
								$event_name = $event_date . '<div class="sp-event-main">' . $event_name . '</div>';
							}

							if ( $show_venue ) {
								$venues = get_the_terms( $event, 'sp_venue' );
								if ( $venues ) {
									$venue = array_shift( $venues );
									$event_name .= '<div class="sp-event-venue">' . $venue->name . '</div>';
								}
							}

							$video = get_post_meta( $event, 'sp_video', true );
							if ( $video ) {
								$event_name .= '<i class="dashicons dashicons-video-alt"></i>';
							} elseif ( has_post_thumbnail( $event ) ) {
								$event_name .= '<i class="dashicons dashicons-camera"></i>';
							}

							$post = get_post( $event );
							
							if ( null != $post->post_content ) {
								if ( 'publish' == $post->post_status ) {
									$event_name .= __( 'Recap', 'sportspress' );
								} else {
									$event_name .= __( 'Preview', 'sportspress' );
								}
							}

							if ( $link_events ) $event_name = '<a href="' . get_post_permalink( $event, false, true ) . '" class="sp-event-title" title="' . get_the_title( $event ) . '">' . $event_name . '</a>';
							else $event_name = '<span class="sp-event-title" title="' . get_the_title( $event ) . '">' . $event_name . '</span>';
							
							echo $event_name;
						} else {
							echo '<span class="sp-event-title" title="' . __( 'Event', 'sportspress' ) . '">&nbsp;</span>';
						}
						echo '</td>';
					elseif ( sp_array_value( $cell, 'type', null ) === 'team' ):
						$team = sp_array_value( $cell, 'id', 0 );
						$pos = sp_array_value( $cell, 'pos', 0 );
					
						// Initialize classes
						$classes = array( 'sp-team', sp_array_value( $cell, 'class', '' ) );
						
						// Add first and last round classes if applicable
						if ( $col === 0 ) {
							$classes[] = 'sp-first-round';
						}
						if ( $col === $cols - 1 ) {
							$classes[] = 'sp-last-round';
						}
					
						// Check if previous event is hidden
						$offset = pow( 2, $rounds - 1 );
						$prev = ( $index - $offset ) * 2 + $pos;
						if ( 0 <= $prev && sp_array_value( sp_array_value( $raw, $prev, array() ), 'hidden', 0 ) ) {
							$classes[] = 'sp-first-round';
						}
						
						// Remove duplicate classes
						$classes = array_unique( $classes );
					
						echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="' . implode( ' ', $classes ) . '">';
						if ( $team ) {
							$team_name = sp_team_name( $team, apply_filters( 'sp_tournament_name_format', 'short' )  );
							if ( $link_teams && $team_name ) {
								$team_name = '<a href="' . get_post_permalink( $team ) . '" class="sp-team-name sp-highlight" data-team="' . $team . '">' . $team_name . '</a>';
							} else {
								if ( ! $team_name ) {
									$team_name = '<span class="sp-team-name sp-highlight">&nbsp;</span>';
								} else {
									$team_name = '<span class="sp-team-name sp-highlight" data-team="' . $team . '">' . $team_name . '</span>';
								}
							}
							echo $team_name;
						} else {
							echo '<span class="sp-team-name sp-highlight">&nbsp;</span>';
						}
						echo '</td>';
					else:
						echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '">&nbsp;</td>';
					endif;

				endfor;
				?>
			</tr>
		<?php endfor;?>
	</tbody>
</table>