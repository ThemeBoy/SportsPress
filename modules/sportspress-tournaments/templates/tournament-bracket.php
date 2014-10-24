<?php
/**
 * Tournament
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Tournaments
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'id' => get_the_ID(),
	'show_logos' => get_option( 'sportspress_tournament_show_logos', 'yes' ) == 'yes' ? true : false,
	'link_teams' => get_option( 'sportspress_link_teams', 'no' ) == 'yes' ? true : false,
	'link_events' => get_option( 'sportspress_link_events', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$tournament = new SP_Tournament( $id );
list( $labels, $data, $rounds, $rows ) = $tournament->data();
?>
<div class="sp-template sp-template-tournament-bracket">
	<table class="sp-data-table sp-tournament-bracket">
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
						$event = sp_array_value( $cell, 'event', 0 );

						if ( sp_array_value( $cell, 'type', null ) === 'event' ):
							echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="' . ( $event ? ' sp-event' : '' ) . ( $round === 0 ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . '">';
							if ( $event ) {
								$event_name = get_the_title( $event );
								if ( $link_events ) $event_name = '<a href="' . get_post_permalink( $event ) . '" class="sp-event-title">' . $event_name . '</a>';
								else $event_name = '<span class="sp-event-title">' . $event_name . '</span>';
								echo $event_name;
							} else {
								echo '&nbsp;';
							}
							echo '</td>';
						elseif ( sp_array_value( $cell, 'type', null ) === 'team' ):
							$team = sp_array_value( $cell, 'team', 0 );
							echo '<td rowspan="' . sp_array_value( $cell, 'rows', 1 ) . '" class="sp-team' . ( $round === 0 ? ' sp-first-round' : '' ) . ( $round === $rounds - 1 ? ' sp-last-round' : '' ) . '">';
							if ( $team ) {
								$team_name = get_the_title( $team );
								if ( $show_logos ) $team_name = get_the_post_thumbnail( $team, 'sportspress-fit-mini' ) . ' ' . $team_name;
								if ( $link_teams ) $team_name = '<a href="' . get_post_permalink( $team ) . '" class="sp-team-name sp-highlight" data-team="' . $team . '">' . $team_name . '</a>';
								else $team_name = '<span class="sp-team-name sp-highlight" data-team="' . $team . '">' . $team_name . '</span>';;
								echo $team_name;
							} else {
								echo '&nbsp;';
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
</div>