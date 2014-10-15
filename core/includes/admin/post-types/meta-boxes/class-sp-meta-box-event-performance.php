<?php
/**
 * Event Performance
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.3
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
			if ( ! $team_id ) continue;

			// Get results for players in the team
			$players = sp_array_between( (array)get_post_meta( $post->ID, 'sp_player', false ), 0, $key );
			$data = sp_array_combine( $players, sp_array_value( $stats, $team_id, array() ) );

			?>
			<div>
				<p><strong><?php echo get_the_title( $team_id ); ?></strong></p>
				<?php self::table( $labels, $columns, $data, $team_id, $i == 0 ); ?>
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
	public static function table( $labels = array(), $columns = array(), $data = array(), $team_id, $has_checkboxes = false ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-performance-table sp-sortable-table">
				<thead>
					<tr>
						<th class="icon">&nbsp;</th>
						<th>#</th>
						<th><?php _e( 'Player', 'sportspress' ); ?></th>
						<th>
							<?php if ( $has_checkboxes ): ?>
								<label for="sp_columns_position">
									<input type="checkbox" name="sp_columns[]" value="position" id="sp_columns_position" <?php checked( ! is_array( $columns ) || in_array( 'position', $columns ) ); ?>>
									<?php _e( 'Position', 'sportspress' ); ?>
								</label>
							<?php else: ?>
								<?php _e( 'Position', 'sportspress' ); ?>
							<?php endif; ?>
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
						<th><?php _e( 'Status', 'sportspress' ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr class="sp-row sp-total">
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td><strong><?php _e( 'Total', 'sportspress' ); ?></strong></td>
						<td>&nbsp;</td>
						<?php foreach( $labels as $column => $label ):
							$player_id = 0;
							$player_performance = sp_array_value( $data, 0, array() );
							$value = sp_array_value( $player_performance, $column, '' );
							?>
							<td><input type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="0" /></td>
						<?php endforeach; ?>
						<td>&nbsp;</td>
					</tr>
				</tfoot>
				<tbody>
					<?php
					foreach ( $data as $player_id => $player_performance ):
						if ( !$player_id ) continue;
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
								$selected = sp_array_value( $player_performance, 'position', null );
								if ( $selected == null ):
									$selected = sp_get_the_term_id( $player_id, 'sp_position', 0 );
								endif;
								$args = array(
									'taxonomy' => 'sp_position',
									'name' => 'sp_players[' . $team_id . '][' . $player_id . '][position]',
									'values' => 'term_id',
									'selected' => $selected
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
							<td class="sp-status-selector">
								<?php echo self::status_select( $team_id, $player_id, sp_array_value( $player_performance, 'status', null ) ); ?>
								<?php echo self::sub_select( $team_id, $player_id, sp_array_value( $player_performance, 'sub', null ), $data ); ?>
							</td>
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