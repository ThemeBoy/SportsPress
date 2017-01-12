<?php
/**
 * Event Logos Block
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     2.2
 */
?>
<div class="sp-template sp-template-event-logos sp-template-event-blocks sp-template-event-logos-block">
	<div class="sp-table-wrapper">
		<table class="sp-event-blocks sp-data-table" data-sp-rows="1">
			<thead><tr><th></th></tr></thead> <?php # Required for DataTables ?>
			<tbody>
				<tr class="sp-row sp-post alternate">
					<td>
						<?php
						$j = 0;
						foreach( $teams as $team ):
							$j++;

							if ( has_post_thumbnail ( $team ) ) {
								$logo = get_the_post_thumbnail( $team, 'sportspress-fit-icon' );
							} else {
								$logo = '';
							}

							if ( $show_team_names ) {
								if ( $j % 2 ) {
									$logo .= ' <strong class="sp-team-name">' . sp_get_team_name( $team, $abbreviate_teams ) . '</strong>';
								} else {
									$logo = '<strong class="sp-team-name">' . sp_get_team_name( $team, $abbreviate_teams ) . '</strong> ' . $logo;
								}
							}

							if ( $link_teams ):
								$logo = '<a class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" href="' . get_permalink( $team, false, true ) . '" title="' . get_the_title( $team ) . '">' . $logo . '</a>';
							else:
								$logo = '<span class="team-logo logo-' . ( $j % 2 ? 'odd' : 'even' ) . '" title="' . get_the_title( $team ) . '">' . $logo . '</span>';
							endif;
							
							echo $logo;
						endforeach;
						?>
						<time class="sp-event-date" datetime="<?php echo get_the_time( 'Y-m-d H:i:s' ); ?>">
							<?php echo get_the_time( get_option( 'date_format' ) ); ?>
						</time>
						<?php

						$status = __( 'Preview', 'sportspress' );
						
						if ( $show_time ) {
							?>
							<h5 class="sp-event-results">
								<?php echo '<span class="sp-result">' . apply_filters( 'sportspress_event_time', sp_get_time( $id ), $id ) . '</span>'; ?>
							</h5>
							<?php
						}
						
						if ( $show_results && ! empty( $results ) ) {
							?>
							<h5 class="sp-event-results">
								<?php echo '<span class="sp-result">' . implode( '</span> - <span class="sp-result">', apply_filters( 'sportspress_event_blocks_team_result_or_time', $results, $id ) ) . '</span>'; ?>
							</h5>
							<?php
							$status = __( 'Full Time', 'sportspress' );
						}
						?>
						<span class="sp-event-status">
							<?php echo apply_filters( 'sportspress_event_logos_status', $status, $id ); ?>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>