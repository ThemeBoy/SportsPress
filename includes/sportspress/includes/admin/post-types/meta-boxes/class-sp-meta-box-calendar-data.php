<?php
/**
 * Calendar Events
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Calendar_Data
 */
class SP_Meta_Box_Calendar_Data {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$calendar = new SP_Calendar( $post );
		$data = $calendar->data();
		$usecolumns = $calendar->columns;
		self::table( $data, $usecolumns );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $data = array(), $usecolumns = null ) {
		$title_format = get_option( 'sportspress_event_list_title_format', 'title' );
		$time_format = get_option( 'sportspress_event_list_time_format', 'combined' );

		if ( is_array( $usecolumns ) )
			$usecolumns = array_filter( $usecolumns );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-calendar-table">
				<thead>
					<tr>
						<th class="column-date">
							<?php _e( 'Date', 'sportspress' ); ?>
						</th>
						<?php if ( ! is_array( $usecolumns ) || in_array( 'event', $usecolumns ) ) { ?>
						<th class="column-event">
							<label for="sp_columns_event">
								<?php
								if ( 'teams' == $title_format ) {
									_e( 'Home', 'sportspress' ); ?> | <?php _e( 'Away', 'sportspress' );
								} elseif ( 'homeaway' == $title_format ) {
									_e( 'Teams', 'sportspress' );
								} else {
									_e( 'Title', 'sportspress' );
								}
								?>
							</label>
						</th>
						<?php } ?>
						<?php if ( ( ! is_array( $usecolumns ) || in_array( 'time', $usecolumns ) ) && in_array( $time_format, array( 'combined', 'separate', 'time' ) ) ) { ?>
							<th class="column-time">
								<label for="sp_columns_time">
									<?php
									if ( 'time' == $time_format || 'separate' == $time_format ) {
										_e( 'Time', 'sportspress' );
									} else {
										_e( 'Time/Results', 'sportspress' );
									}
									?>
								</label>
							</th>
						<?php } ?>
						<?php if ( ( ! is_array( $usecolumns ) || in_array( 'results', $usecolumns ) ) && in_array( $time_format, array( 'separate', 'results' ) ) ) { ?>
							<th class="column-results">
								<label for="sp_columns_results">
									<?php _e( 'Results', 'sportspress' ); ?>
								</label>
							</th>
						<?php } ?>
						<?php if ( ! is_array( $usecolumns ) || in_array( 'league', $usecolumns ) ) { ?>
							<th class="column-league">
								<label for="sp_columns_league">
									<?php _e( 'Competition', 'sportspress' ); ?>
								</label>
							</th>
						<?php } ?>
						<?php if ( ! is_array( $usecolumns ) || in_array( 'season', $usecolumns ) ) { ?>
							<th class="column-season">
								<label for="sp_columns_season">
									<?php _e( 'Season', 'sportspress' ); ?>
								</label>
							</th>
						<?php } ?>
						<?php if ( ! is_array( $usecolumns ) || in_array( 'venue', $usecolumns ) ) { ?>
							<th class="column-venue">
								<label for="sp_columns_venue">
									<?php _e( 'Venue', 'sportspress' ); ?>
								</label>
							</th>
						<?php } ?>
						<?php if ( ! is_array( $usecolumns ) || in_array( 'article', $usecolumns ) ) { ?>
							<th class="column-article">
								<label for="sp_columns_article">
									<?php _e( 'Article', 'sportspress' ); ?>
								</label>
							</th>
						<?php } ?>
						<?php if ( ! is_array( $usecolumns ) || in_array( 'day', $usecolumns ) ) { ?>
							<th class="column-day">
								<label for="sp_columns_day">
									<?php _e( 'Match Day', 'sportspress' ); ?>
								</label>
							</th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) ):
						if ( sizeof( $data ) > 0 ):
							$main_result = get_option( 'sportspress_primary_result', null );
							$i = 0;
							foreach ( $data as $event ):
								$teams = get_post_meta( $event->ID, 'sp_team' );
								$results = get_post_meta( $event->ID, 'sp_results', true );
								$video = get_post_meta( $event->ID, 'sp_video', true );
								$main_results = array();
								?>
								<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
									<td><?php echo get_post_time( get_option( 'date_format' ), false, $event, true ); ?></td>
									<?php if ( ! is_array( $usecolumns ) || in_array( 'event', $usecolumns ) ) { ?>
										<td>
											<div class="sp-title-format sp-title-format-title<?php if ( $title_format && $title_format != 'title' ): ?> hidden<?php endif; ?>"><?php echo $event->post_title; ?></div>
											<div class="sp-title-format sp-title-format-teams sp-title-format-homeaway<?php if ( ! in_array( $title_format, array( 'teams', 'homeaway' ) ) ): ?> hidden<?php endif; ?>">
												<?php
												if ( $teams ): foreach ( $teams as $team ):
													$name = get_the_title( $team );
													if ( $name ):
														$team_results = sp_array_value( $results, $team, null );

														if ( $main_result ):
															$team_result = sp_array_value( $team_results, $main_result, null );
														else:
															if ( is_array( $team_results ) ):
																end( $team_results );
																$team_result = prev( $team_results );
															else:
																$team_result = null;
															endif;
														endif;

														if ( $team_result != null ):
															$team_result = apply_filters( 'sportspress_calendar_team_result_admin', $team_result, $event->ID, $team );
															$main_results[] = $team_result;
															unset( $team_results['outcome'] );
															$team_results = implode( ' | ', $team_results );
															echo '<a class="result sp-tip" title="' . $team_results . '" href="' . get_edit_post_link( $event->ID ) . '">' . $team_result . '</a> ';
														endif;

														echo $name . '<br>';
													endif;
												endforeach; else:
													echo '&mdash;';
												endif;
												?>
											</div>
										</td>
									<?php } ?>
									<?php if ( ( ! is_array( $usecolumns ) || in_array( 'time', $usecolumns ) ) && in_array( $time_format, array( 'combined', 'separate', 'time' ) ) ) { ?>
										<?php if ( 'time' == $time_format || 'separate' == $time_format ) { ?>
											<td>
												<?php echo apply_filters( 'sportspress_event_time_admin', get_post_time( get_option( 'time_format' ), false, $event, true ), $event->ID ); ?>
											</td>
										<?php } else { ?>
											<td>
												<?php
													if ( ! empty( $main_results ) ):
														echo implode( ' - ', $main_results );
													else:
														echo apply_filters( 'sportspress_event_time_admin', get_post_time( get_option( 'time_format' ), false, $event, true ), $event->ID );
													endif;
												?>
											</td>
										<?php } ?>
									<?php } ?>
									<?php if ( ( ! is_array( $usecolumns ) || in_array( 'results', $usecolumns ) ) && in_array( $time_format, array( 'separate', 'results' ) ) ) { ?>
										<td>
											<?php
												if ( ! empty( $main_results ) ):
													echo implode( ' - ', $main_results );
												else:
													echo '-';
												endif;
											?>
										</td>
									<?php } ?>
									<?php if ( ! is_array( $usecolumns ) || in_array( 'league', $usecolumns ) ) { ?>
										<td><?php the_terms( $event->ID, 'sp_league' ); ?></td>
									<?php } ?>
									<?php if ( ! is_array( $usecolumns ) || in_array( 'season', $usecolumns ) ) { ?>
										<td><?php the_terms( $event->ID, 'sp_season' ); ?></td>
									<?php } ?>
									<?php if ( ! is_array( $usecolumns ) || in_array( 'venue', $usecolumns ) ) { ?>
										<td><?php the_terms( $event->ID, 'sp_venue' ); ?></td>
									<?php } ?>
									<?php if ( ! is_array( $usecolumns ) || in_array( 'article', $usecolumns ) ) { ?>
										<td>
											<a href="<?php echo get_edit_post_link( $event->ID ); ?>#sp_articlediv">
												<?php if ( $video ): ?>
													<div class="dashicons dashicons-video-alt"></div>
												<?php elseif ( has_post_thumbnail( $event->ID ) ): ?>
													<div class="dashicons dashicons-camera"></div>
												<?php endif; ?>
												<?php
												if ( $event->post_content == null ):
													_e( 'None', 'sportspress' );
												elseif ( $event->post_status == 'publish' ):
													_e( 'Recap', 'sportspress' );
												else:
													_e( 'Preview', 'sportspress' );
												endif;
												?>
											</a>
										</td>
									<?php } ?>
									<?php if ( ! is_array( $usecolumns ) || in_array( 'day', $usecolumns ) ) { ?>
										<td>
											<?php
											$day = get_post_meta( $event->ID, 'sp_day', true );
											if ( '' == $day ) {
												echo '&mdash;';
											} else {
												echo $day;
											}
											?>
										</td>
									<?php } ?>
								</tr>
								<?php
								$i++;
							endforeach;
						else:
							?>
							<tr class="sp-row alternate">
								<td colspan="7">
									<?php _e( 'No results found.', 'sportspress' ); ?>
								</td>
							</tr>
							<?php
						endif;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="7">
							<?php printf( __( 'Select %s', 'sportspress' ), __( 'Details', 'sportspress' ) ); ?>
						</td>
					</tr>
					<?php
					endif;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}