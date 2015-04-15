<?php
/**
 * Event Performance
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.7.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Performance
 */
class SP_Meta_Box_Event_Performance {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$event = new SP_Event( $post );
		list( $labels, $columns, $stats, $teams ) = $event->performance( true );

		$i = 0;

		foreach ( $teams as $key => $team_id ):
			if ( -1 == $team_id ) continue;

			// Get results for players in the team
			$players = sp_array_between( (array)get_post_meta( $post->ID, 'sp_player', false ), 0, $key );
			$players[] = -1;
			$data = sp_array_combine( $players, sp_array_value( $stats, $team_id, array() ) );

			// Determine if we need checkboxes
			if ( 'manual' == get_option( 'sportspress_event_performance_columns', 'auto' ) && $i == 0 )
				$has_checkboxes = true;
			else
				$has_checkboxes = false;

			// Determine if we need extras
			if ( 'yes' == get_option( 'sportspress_event_show_extras', 'no' ) )
				$show_extras = true;
			else
				$show_extras = false;

			// Determine if we are splitting positions
			if ( 'yes' == get_option( 'sportspress_event_split_players_by_position', 'no' ) )
				$split_positions = true;
			else
				$split_positions = false;

			?>
			<div>
				<?php if ( $team_id ): ?>
					<p><strong><?php echo get_the_title( $team_id ); ?></strong></p>
				<?php elseif ( $i ): ?>
					<br>
				<?php endif; ?>
				<?php self::table( $labels, $columns, $data, $team_id, $has_checkboxes, $show_extras, $split_positions ); ?>
			</div>
			<?php
			$i ++;
		endforeach;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_players', sp_array_value( $_POST, 'sp_players', array() ) );
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $labels = array(), $columns = array(), $data = array(), $team_id, $has_checkboxes = false, $show_extras = false, $split_positions = false ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-performance-table sp-sortable-table">
				<thead>
					<tr>
						<th class="icon">&nbsp;</th>
						<th>#</th>
						<th><?php _e( 'Player', 'sportspress' ); ?></th>
						<th class="column-position">
							<?php _e( 'Position', 'sportspress' ); ?>
						</th>
						<?php foreach ( $labels as $key => $label ): ?>
							<th>
								<?php if ( $has_checkboxes ): ?>
									<label for="sp_columns_<?php echo $key; ?>">
										<input type="checkbox" name="sp_columns[]" value="<?php echo $key; ?>" id="sp_columns_<?php echo $key; ?>" <?php checked( ! is_array( $columns ) || in_array( $key, $columns ) ); ?>>
										<?php echo $label; ?>
									</label>
								<?php else: ?>
									<?php echo $label; ?>
								<?php endif; ?>
							</th>
						<?php endforeach; ?>
						<?php if ( $team_id ): ?>
							<th>
								<?php _e( 'Status', 'sportspress' ); ?>
							</th>
						<?php else: ?>
							<th>
								<?php _e( 'Outcome', 'sportspress' ); ?>
							</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tfoot>
					<?php if ( $show_extras ) { ?>
						<tr class="sp-row sp-post sp-extras">
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><strong><?php _e( 'Extras', 'sportspress' ); ?></strong></td>
							<td>&nbsp;</td>
							<?php foreach( $labels as $column => $label ):
								$player_id = -1;
								$player_performance = sp_array_value( $data, $player_id, array() );
								$value = sp_array_value( $player_performance, $column, '' );
								?>
								<td><input type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" /></td>
							<?php endforeach; ?>
							<td>&nbsp;</td>
						</tr>
					<?php } ?>
					<tr class="sp-row sp-total">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><strong><?php _e( 'Total', 'sportspress' ); ?></strong></td>
						<td>&nbsp;</td>
						<?php foreach( $labels as $column => $label ):
							$player_id = 0;
							$player_performance = sp_array_value( $data, $player_id, array() );
							$value = sp_array_value( $player_performance, $column, '' );
							?>
							<td><input type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" placeholder="0" <?php if ( $split_positions ) { ?>readonly="readonly"<?php } else { ?>value="<?php echo $value; ?>"<?php } ?> /></td>
						<?php endforeach; ?>
						<td>&nbsp;</td>
					</tr>
				</tfoot>
				<tbody>
					<?php
					foreach ( $data as $player_id => $player_performance ):
						if ( $player_id <= 0 ) continue;
						$number = get_post_meta( $player_id, 'sp_number', true );
						$value = sp_array_value( $player_performance, 'number', '' );
						?>
						<tr class="sp-row sp-post" data-player="<?php echo $player_id; ?>">
							<td class="icon"><span class="dashicons dashicons-menu post-state-format"></span></td>
							<td>
								<input class="small-text sp-player-number-input" type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][number]" value="<?php echo $value; ?>" />
							</td>
							<td><?php echo get_the_title( $player_id ); ?></td>
							<td>
								<?php
								$selected = (array) sp_array_value( $player_performance, 'position', null );
								if ( $selected == null ):
									$selected = (array) sp_get_the_term_id( $player_id, 'sp_position', 0 );
								endif;
								$args = array(
									'taxonomy' => 'sp_position',
									'name' => 'sp_players[' . $team_id . '][' . $player_id . '][position][]',
									'values' => 'term_id',
									'orderby' => 'slug',
									'selected' => $selected,
									'class' => 'sp-position',
									'property' => 'multiple',
									'chosen' => true,
									'include_children' => ( 'no' == get_option( 'sportspress_event_hide_child_positions', 'no' ) ),
								);
								sp_dropdown_taxonomies( $args );
								?>
							</td>
							<?php foreach( $labels as $column => $label ):
								$value = sp_array_value( $player_performance, $column, '' );
								?>
								<td>
									<input class="sp-player-<?php echo $column; ?>-input" type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="0" />
								</td>
							<?php endforeach; ?>
							<?php if ( $team_id ): ?>
								<td class="sp-status-selector">
									<?php echo self::status_select( $team_id, $player_id, sp_array_value( $player_performance, 'status', null ) ); ?>
									<?php echo self::sub_select( $team_id, $player_id, sp_array_value( $player_performance, 'sub', null ), $data ); ?>
								</td>
							<?php else: ?>
								<td>
									<?php
									$values = sp_array_value( $player_performance, 'outcome', '' );
									if ( ! is_array( $values ) )
										$values = array( $values );

									$args = array(
										'post_type' => 'sp_outcome',
										'name' => 'sp_players[' . $team_id . '][' . $player_id . '][outcome][]',
										'option_none_value' => '',
									    'sort_order'   => 'ASC',
									    'sort_column'  => 'menu_order',
										'selected' => $values,
										'class' => 'sp-outcome',
										'property' => 'multiple',
										'chosen' => true,
										'placeholder' => __( 'None', 'sportspress' ),
									);
									sp_dropdown_pages( $args );
									?>
								</td>
							<?php endif; ?>
						</tr>
						<?php
					endforeach;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Status selector
	 */
	public static function status_select( $team_id, $player_id, $value = null ) {

		if ( ! $team_id || ! $player_id )
			return '&mdash;';

		$options = array(
			'lineup' => __( 'Starting Lineup', 'sportspress' ),
			'sub' => __( 'Substitute', 'sportspress' ),
		);

		$output = '<select name="sp_players[' . $team_id . '][' . $player_id . '][status]">';

		foreach( $options as $key => $name ):
			$output .= '<option value="' . $key . '"' . ( $key == $value ? ' selected' : '' ) . '>' . $name . '</option>';
		endforeach;

		$output .= '</select>';

		return $output;

	}

	/**
	 * Substitute selector
	 */
	public static function sub_select( $team_id, $player_id, $value, $data = array() ) {

		if ( ! $team_id || ! $player_id )
			return '&mdash;';

		$output = '<select name="sp_players[' . $team_id . '][' . $player_id . '][sub]" style="display: none;">';

		$output .= '<option value="0">' . __( 'None', 'sportspress' ) . '</option>';

		// Add players as selectable options
		foreach( $data as $id => $performance ):
			if ( ! $id || $id == $player_id ) continue;
			$number = get_post_meta( $id, 'sp_number', true );
			$output .= '<option value="' . $id . '"' . ( $id == $value ? ' selected' : '' ) . '>' . ( $number ? $number . '. ' : '' ) . get_the_title( $id ) . '</option>';
		endforeach;

		$output .= '</select>';

		return $output;

	}
}