<?php
/**
 * Player Gallery Thumbnail
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     1.8.8
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
);

extract( $defaults, EXTR_SKIP );

$tournament = new SP_Tournament( $id );
list( $labels, $data, $rounds, $rows ) = $tournament->data( $layout );
?>
<table class="sp-data-table sp-tournament-bracket<?php if ( $scrollable ) { ?> sp-scrollable-table<?php } ?>">
	<thead>
		<tr>
			<?php for ( $round = 0; $round < $rounds; $round++ ): ?>
				<th>
					<?php
					$label = sp_array_value( $labels, $round, null );
					if ( $label == null ) {
						printf( __( 'Round %s', 'sportspress' ), $round + 1 );
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
				for ( $round = 0; $round < $rounds; $round++ ):
					$cell = sp_array_value( sp_array_value( $data, $row, array() ), $round, null );
					if ( $cell === null ) continue;

					$index = sp_array_value( $cell, 'index' );
					$event = sp_array_value( $cell, 'id', 0 );

					if ( sp_array_value( $cell, 'type', null ) === 'event' ):
						echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-event' . ( $round === 0 ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . ' ' . sp_array_value( $cell, 'class', '' ) . '">';
						if ( $event ) {
							$event_name = '<span class="sp-result">' . implode( '</span>-<span class="sp-result">', sp_get_main_results_or_time( $event ) ) . '</span>';

							if ( $show_logos ) {
								$teams = sp_get_teams( $event );
								if ( $teams && sizeof( $teams ) >= 2 ) {
									$home = reset( $teams );
									if ( sp_has_logo( $home ) ) {
										$event_name = sp_get_logo( $home, 'mini' ) . ' ' . $event_name;
									} elseif ( sp_get_abbreviation( $home ) ) {
										$event_name = sp_get_abbreviation( $home ) . ' ' . $event_name;
									}

									$away = end( $teams );
									if ( sp_has_logo( $away ) ) {
										$event_name .= ' ' . sp_get_logo( $away, 'mini' );
									} elseif ( sp_get_abbreviation( $away ) ) {
										$event_name .= ' ' . sp_get_abbreviation( $away );
									}
								}
							}

							$event_date = '<div class="sp-event-date">' . get_the_date( get_option( 'date_format' ), $event ) . '</div>';

							$event_name = $event_date . '<div class="sp-event-main">' . $event_name . '</div>';

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
						echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-team' . ( $round === 0 ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . ' ' . sp_array_value( $cell, 'class', '' ) . '">';
						if ( $team ) {
							$team_name = get_the_title( $team );
							if ( $link_teams ) $team_name = '<a href="' . get_post_permalink( $team ) . '" class="sp-team-name sp-highlight" data-team="' . $team . '">' . $team_name . '</a>';
							else $team_name = '<span class="sp-team-name sp-highlight" data-team="' . $team . '">' . $team_name . '</span>';;
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