<?php
/**
 * Player Statistics
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version   2.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Player_Statistics
 */
class SP_Meta_Box_Player_Statistics {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$player = new SP_Player( $post );
		$leagues = get_the_terms( $post->ID, 'sp_league' );
		$league_num = sizeof( $leagues );
		$sections = get_option( 'sportspress_player_performance_sections', -1 );
		$show_career_totals = 'yes' === get_option( 'sportspress_player_show_career_total', 'no' ) ? true : false;

		if ( $leagues ) {
			if ( -1 == $sections ) {
				// Loop through statistics for each league
				$i = 0;
				foreach ( $leagues as $league ):
					?>
					<p><strong><?php echo $league->name; ?></strong></p>
					<?php
					list( $columns, $data, $placeholders, $merged, $seasons_teams, $has_checkboxes, $formats, $total_types ) = $player->data( $league->term_id, true );
					self::table( $post->ID, $league->term_id, $columns, $data, $placeholders, $merged, $seasons_teams, $has_checkboxes && $i == 0, true, $formats, $total_types );
					$i ++;
				endforeach;
				if ( $show_career_totals ) {
					?>
					<p><strong><?php _e( 'Career Total', 'sportspress' ); ?></strong></p>
					<?php
					list( $columns, $data, $placeholders, $merged, $seasons_teams, $has_checkboxes, $formats, $total_types ) = $player->data( 0, true );
					self::table( $post->ID, 0, $columns, $data, $placeholders, $merged, $seasons_teams, false, false, $formats, $total_types );
				}
			} else {
				// Determine order of sections
				if ( 1 == $sections ) {
					$section_order = array( 1 => __( 'Defense', 'sportspress' ), 0 => __( 'Offense', 'sportspress' ) );
				} else {
					$section_order = array( __( 'Offense', 'sportspress' ), __( 'Defense', 'sportspress' ) );
				}
				
				$s = 0;
				foreach ( $section_order as $section_id => $section_label ) {
					// Loop through statistics for each league
					$i = 0;
					foreach ( $leagues as $league ):
						?>
						<p><strong><?php echo $league->name; ?> &mdash; <?php echo $section_label; ?></strong></p>
						<?php
						list( $columns, $data, $placeholders, $merged, $seasons_teams, $has_checkboxes, $formats, $total_types ) = $player->data( $league->term_id, true, $section_id );
						self::table( $post->ID, $league->term_id, $columns, $data, $placeholders, $merged, $seasons_teams, $has_checkboxes && $i == 0 && $s == 0, $s == 0, $formats, $total_types );
						$i ++;
					endforeach;
					if ( $show_career_totals ) {
						?>
						<p><strong><?php _e( 'Career Total', 'sportspress' ); ?> &mdash; <?php echo $section_label; ?></strong></p>
						<?php
						list( $columns, $data, $placeholders, $merged, $seasons_teams, $has_checkboxes, $formats, $total_types ) = $player->data( 0, true, $section_id );
						self::table( $post->ID, 0, $columns, $data, $placeholders, $merged, $seasons_teams, $has_checkboxes && $i == 0 && $s == 0, $s == 0, $formats, $total_types );
					}
					$s ++;
				}
			}
		}
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_leagues', sp_array_value( $_POST, 'sp_leagues', array() ) );
		update_post_meta( $post_id, 'sp_statistics', sp_array_value( $_POST, 'sp_statistics', array() ) );
		
		$old = get_post_meta($post_id, 'sp_additional_statistics', true);
		$new = array();
		
		$leagues = $_POST['sp_add_league'];
		$seasons = $_POST['sp_add_season'];
		$teams = $_POST['sp_add_team'];
		$columns = $_POST['sp_add_columns'];
		$labels = array_keys($columns);
		
		$i = 0;
		foreach ( $leagues as $league ) {
			if ( $league != '-99' ) {
				foreach ( $labels as $label ) {
					$new[$league][$seasons[$i]][$teams[$i]][$label] = $columns[$label][$i];
				}
			}
			$i++;
		}
		if ( !empty( $new ) && $new != $old ) {
			update_post_meta( $post_id, 'sp_additional_statistics', $new );
		}
		elseif ( empty($new) && $old ) {
			delete_post_meta( $post_id, 'sp_additional_statistics', $old );
		}
	}

	/**
	 * Admin edit table
	 */
	public static function table( $id = null, $league_id, $columns = array(), $data = array(), $placeholders = array(), $merged = array(), $leagues = array(), $has_checkboxes = false, $team_select = false, $formats = array(), $total_types = array() ) {
		$readonly = false;
		$teams = array_filter( get_post_meta( $id, 'sp_team', false ) );
		$player = new SP_Player( $id );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table">
				<thead>
					<tr>
						<th><?php _e( 'Season', 'sportspress' ); ?></th>
						<?php if ( $team_select && apply_filters( 'sportspress_player_team_statistics', $league_id ) ): ?>
							<th>
								<?php _e( 'Team', 'sportspress' ); ?>
							</th>
						<?php endif; ?>
						<?php foreach ( $columns as $key => $label ): if ( $key == 'team' ) continue; ?>
							<th><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tfoot>
					<?php $div_stats = sp_array_value( $data, 0, array() ); ?>
					<tr class="sp-row sp-total">
						<td>
							<label><strong><?php _e( 'Total', 'sportspress' ); ?></strong></label>
						</td>
						<?php if ( $team_select && apply_filters( 'sportspress_player_team_statistics', $league_id ) ) { ?>
							<td>&nbsp;</td>
						<?php } ?>
						<?php foreach ( $columns as $column => $label ): if ( $column == 'team' ) continue;
							?>
							<td><?php
								$value = sp_array_value( sp_array_value( $data, 0, array() ), $column, null );
								$placeholder = sp_array_value( sp_array_value( $placeholders, 0, array() ), $column, 0 );

								// Convert value and placeholder to time format
								if ( 'time' === sp_array_value( $formats, $column, 'number' ) ) {
									$timeval = sp_time_value( $value );
									$placeholder = sp_time_value( $placeholder );
								}

								if ( $readonly ) {
									echo $value ? $value : $placeholder;
								} else {
									if ( 'time' === sp_array_value( $formats, $column, 'number' ) ) {
										echo '<input class="sp-convert-time-input" type="text" name="sp_times[' . $league_id . '][0][' . $column . ']" value="' . ( '' === $value ? '' : esc_attr( $timeval ) ) . '" placeholder="' . esc_attr( $placeholder ) . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . '  />';
										echo '<input class="sp-convert-time-output" type="hidden" name="sp_statistics[' . $league_id . '][0][' . $column . ']" value="' . esc_attr( $value ) . '" data-sp-format="' . sp_array_value( $formats, $column, 'number' ) . '" data-sp-total-type="' . sp_array_value( $total_types, $column, 'total' ) . '" />';
									} else {
										echo '<input type="text" name="sp_statistics[' . $league_id . '][0][' . $column . ']" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $placeholder ) . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . ' data-sp-format="' . sp_array_value( $formats, $column, 'number' ) . '" data-sp-total-type="' . sp_array_value( $total_types, $column, 'total' ) . '" />';
									}
								}
							?></td>
						<?php endforeach; ?>
					</tr>
				</tfoot>
				<tbody>
					<?php
					$additional_stats = get_post_meta( $id , 'sp_additional_statistics' , true );
					//var_dump( $additional_stats );
					$i = 0;
					foreach ( $data as $div_id => $div_stats ):
						if ( $div_id === 'statistics' ) continue;
						if ( $div_id === 0 ) continue;
						$div = get_term( $div_id, 'sp_season' );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<label>
									<?php if ( ! apply_filters( 'sportspress_player_team_statistics', $league_id ) ): ?>
										<?php $value = sp_array_value( $leagues, $div_id, '-1' ); ?>
										<input type="hidden" name="sp_leagues[<?php echo $league_id; ?>][<?php echo $div_id; ?>]" value="-1">
										<input type="checkbox" name="sp_leagues[<?php echo $league_id; ?>][<?php echo $div_id; ?>]" value="1" <?php checked( $value ); ?>>
									<?php endif; ?>
									<?php
									if ( 0 === $div_id ) _e( 'Total', 'sportspress' );
									elseif ( 'WP_Error' != get_class( $div ) ) echo $div->name;
									?>
								</label>
							</td>
							<?php if ( $team_select && apply_filters( 'sportspress_player_team_statistics', $league_id ) ): ?>
								<?php if ( $div_id == 0 ): ?>
									<td>&nbsp;</td>
								<?php else: ?>
									<td>
										<?php $value = sp_array_value( $leagues, $div_id, '-1' ); ?>
										<?php
										$args = array(
											'post_type' => 'sp_team',
											'name' => 'sp_leagues[' . $league_id . '][' . $div_id . ']',
											'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
										    'sort_order'   => 'ASC',
										    'sort_column'  => 'menu_order',
											'selected' => $value,
											'values' => 'ID',
											'include' => $teams,
											'tax_query' => array(
												'relation' => 'AND',
												array(
													'taxonomy' => 'sp_league',
													'terms' => $league_id,
													'field' => 'term_id',
												),
												array(
													'taxonomy' => 'sp_season',
													'terms' => $div_id,
													'field' => 'term_id',
												),
											),
										);
										if ( ! sp_dropdown_pages( $args ) ):
											_e( '&mdash; None &mdash;', 'sportspress' );
										endif;
										?>
									</td>
								<?php endif; ?>
							<?php endif; ?>
							<?php foreach ( $columns as $column => $label ): if ( $column == 'team' ) continue;
								?>
								<td><?php
									$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, null );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $div_id, array() ), $column, 0 );

									// Convert value and placeholder to time format
									if ( 'time' === sp_array_value( $formats, $column, 'number' ) ) {
										$timeval = sp_time_value( $value );
										$placeholder = sp_time_value( $placeholder );
									}

									if ( $readonly ) {
										echo $timeval ? $timeval : $placeholder;
									} else {
										if ( 'time' === sp_array_value( $formats, $column, 'number' ) ) {
											echo '<input class="sp-convert-time-input" type="text" name="sp_times[' . $league_id . '][' . $div_id . '][' . $column . ']" value="' . ( '' === $value ? '' : esc_attr( $timeval ) ) . '" placeholder="' . esc_attr( $placeholder ) . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . '  />';
											echo '<input class="sp-convert-time-output" type="hidden" name="sp_statistics[' . $league_id . '][' . $div_id . '][' . $column . ']" value="' . esc_attr( $value ) . '" />';
										} else {
											echo '<input type="text" name="sp_statistics[' . $league_id . '][' . $div_id . '][' . $column . ']" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $placeholder ) . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . '  />';
										}
									}
								?></td>
							<?php endforeach; ?>
							<td class="sp-actions-column">
								<a href="#" title="<?php _e( 'Delete row', 'sportspress' ); ?>" class="dashicons dashicons-dismiss sp-delete-row" style="display:none; color:red;"></a>
								<a href="#" title="<?php _e( 'Insert row after', 'sportspress' ); ?>" class="dashicons dashicons-plus-alt sp-add-row" data-league_id="<?php echo $league_id; ?>" data-season_id="<?php echo $div_id; ?>"></a>
							</td>
						</tr>
						<?php
						$i++;
						//Check if there was a mid-season transfer and show the statistics for each team separately
						if ( isset( $additional_stats[ $league_id ][ $div_id ] ) ) {
							foreach ( $additional_stats[ $league_id ][ $div_id ] as $key => $teamstats ) :
								//Get the stats for the team
								list( $columns_add, $data_add, $placeholders_add, $merged, $seasons_teams, $has_checkboxes, $formats_add, $total_types ) = $player->data( $league_id, $div_id, $key, true, true );
								var_dump($data_add);
						 ?>
							<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<label>
									&Gt;
								</label>
								<input id="leagueHidden" type="hidden" name="sp_add_league[]" value="<?php echo $league_id; ?>">
								<input id="seasonHidden" type="hidden" name="sp_add_season[]" value="<?php echo $div_id; ?>">
								<input id="teamHidden" type="hidden" name="sp_add_team[]" value="<?php echo $key; ?>">
							</td>
							<?php if ( $team_select && apply_filters( 'sportspress_player_team_statistics', $league_id ) ): ?>
							<td>
							<?php echo get_the_title( $key );?>
							</td>
							<?php endif ?>
							<?php foreach ( $columns_add as $column => $label ): if ( $column == 'team' ) continue; ?>
							<td><?php
									$value = sp_array_value( sp_array_value( $data_add, $div_id, array() ), $column, null );
									$placeholder = sp_array_value( sp_array_value( $placeholders_add, $div_id, array() ), $column, 0 );

									// Convert value and placeholder to time format
									if ( 'time' === sp_array_value( $formats_add, $column, 'number' ) ) {
										$timeval = sp_time_value( $value );
										$placeholder = sp_time_value( $placeholder );
									}

									if ( $readonly ) {
										echo $timeval ? $timeval : $placeholder;
									} else {
										if ( 'time' === sp_array_value( $formats_add, $column, 'number' ) ) {
											echo '<input class="sp-convert-time-input" type="text" name="sp_additional_times[' . $column . '][]" value="' . ( '' === $value ? '' : esc_attr( $timeval ) ) . '" placeholder="' . esc_attr( $placeholder ) . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . '  />';
											echo '<input class="sp-convert-time-output" type="hidden" name="sp_add_columns[' . $column . '][]" value="' . esc_attr( $value ) . '" />';
										} else {
											echo '<input type="text" name="sp_add_columns[' . $column . '][]" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $placeholder ) . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . '  />';
										}
									}
								?></td>
							<?php endforeach; ?>
							<td class="sp-actions-column">
								<a href="#" title="<?php _e( 'Delete row', 'sportspress' ); ?>" class="dashicons dashicons-dismiss sp-delete-row" style="color:red;"></a>
							</td>
							</tr>
						<?php
							endforeach;
						}
					endforeach;
					?>
					<tr class="empty-row screen-reader-text">
							<td>
								<label>
									&Gt;
								</label>
								<input id="leagueHidden" type="hidden" name="sp_add_league[]" value="-99">
								<input id="seasonHidden" type="hidden" name="sp_add_season[]" value="-99">
								<input id="teamHidden" type="hidden" name="sp_add_team[]" value="-1">
							</td>
							<?php if ( $team_select && apply_filters( 'sportspress_player_team_statistics', $league_id ) ): ?>
									<td>
										<?php
										$args = array(
											'post_type' => 'sp_team',
											//'name' => 'sp_additional_team[-99][-99][]',
											'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
										    'sort_order'   => 'ASC',
										    'sort_column'  => 'menu_order',
											'selected' => null,
											'values' => 'ID',
											'id' => 'additional_team',
											'include' => $teams,
											'tax_query' => array(
												'relation' => 'AND',
												array(
													'taxonomy' => 'sp_league',
													'terms' => $league_id,
													'field' => 'term_id',
												),
												array(
													'taxonomy' => 'sp_season',
													'terms' => $div_id,
													'field' => 'term_id',
												),
											),
										);
										if ( ! sp_dropdown_pages( $args ) ):
											_e( '&mdash; None &mdash;', 'sportspress' );
										?>
									</td>
								<?php endif; ?>
							<?php endif; ?>
							<?php foreach ( $columns as $column => $label ): if ( $column == 'team' ) continue;
								?>
								<td><?php
										if ( 'time' === sp_array_value( $formats, $column, 'number' ) ) {
											echo '<input class="sp-convert-time-input" type="text" name="sp_additional_times[' . $column . '][]" placeholder="0" />';
											echo '<input class="sp-convert-time-output" type="hidden" name="sp_add_columns[' . $column . '][]" />';
										} else {
											echo '<input type="text" name="sp_add_columns[' . $column . '][]" placeholder="0" />';
										}
								?></td>
							<?php endforeach; ?>
							<td class="sp-actions-column">
								<a href="#" title="<?php _e( 'Delete row', 'sportspress' ); ?>" class="dashicons dashicons-dismiss sp-delete-row" style="display:none; color:red;"></a>
								<a href="#" title="<?php _e( 'Insert row after', 'sportspress' ); ?>" class="dashicons dashicons-plus-alt sp-add-row"></a>
							</td>
						</tr>
				</tbody>
			</table>
		</div>
		<?php
	}
}