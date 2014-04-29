<?php
/**
 * Calendar Events
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
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
						<th class="column-event">
							<label for="sp_columns_event">
								<input type="checkbox" name="sp_columns[]" value="event" id="sp_columns_event" <?php checked( ! is_array( $usecolumns ) || in_array( 'event', $usecolumns ) ); ?>>
								<?php _e( 'Event', 'sportspress' ); ?>
							</label>
						</th>
						<th class="column-teams">
							<label for="sp_columns_teams">
								<input type="checkbox" name="sp_columns[]" value="teams" id="sp_columns_teams" <?php checked( ! is_array( $usecolumns ) || in_array( 'teams', $usecolumns ) ); ?>>
								<?php _e( 'Teams', 'sportspress' ); ?>
							</label>
						</th>
						<th class="column-time">
							<label for="sp_columns_time">
								<input type="checkbox" name="sp_columns[]" value="time" id="sp_columns_time" <?php checked( ! is_array( $usecolumns ) || in_array( 'time', $usecolumns ) ); ?>>
								<?php _e( 'Time/Results', 'sportspress' ); ?>
							</label>
						</th>
						<th class="column-venue">
							<label for="sp_columns_venue">
								<input type="checkbox" name="sp_columns[]" value="venue" id="sp_columns_venue" <?php checked( ! is_array( $usecolumns ) || in_array( 'venue', $usecolumns ) ); ?>>
								<?php _e( 'Venue', 'sportspress' ); ?>
							</label>
						</th>
						<th class="column-article">
							<label for="sp_columns_article">
								<input type="checkbox" name="sp_columns[]" value="article" id="sp_columns_article" <?php checked( ! is_array( $usecolumns ) || in_array( 'article', $usecolumns ) ); ?>>
								<?php _e( 'Article', 'sportspress' ); ?>
							</label>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) && sizeof( $data ) > 0 ):
						$main_result = get_option( 'sportspress_primary_result', null );
						$i = 0;
						foreach ( $data as $event ):
							$teams = get_post_meta( $event->ID, 'sp_team' );
							$results = get_post_meta( $event->ID, 'sp_results', true );
							$main_results = array();
							$video = get_post_meta( $event->ID, 'sp_video', true );
							?>
							<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
								<td><?php echo get_post_time( get_option( 'date_format' ), false, $event ); ?></td>
								<td><?php echo $event->post_title; ?></td>
								<td>
									<?php
									foreach ( $teams as $team ):
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
												$main_results[] = $team_result;
												unset( $team_results['outcome'] );
												$team_results = implode( ' | ', $team_results );
												echo '<a class="result tips" title="' . $team_results . '" href="' . get_edit_post_link( $event->ID ) . '">' . $team_result . '</a> ';
											endif;

											echo $name . '<br>';
										endif;
									endforeach;
									?>
								</td>
								<td>
									<?php
										if ( ! empty( $main_results ) ):
											echo implode( ' - ', $main_results );
										else:
											echo get_post_time( get_option( 'time_format' ), false, $event );
										endif;
									?>
								</td>
								<td><?php the_terms( $event->ID, 'sp_venue' ); ?></td>
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
							</tr>
							<?php
							$i++;
						endforeach;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="6">
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