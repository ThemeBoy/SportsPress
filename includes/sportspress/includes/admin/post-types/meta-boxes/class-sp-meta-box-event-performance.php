<?php
/**
 * Event Performance
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.13
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

		// Determine if columns are auto or manual
		if ( 'manual' == get_option( 'sportspress_event_performance_columns', 'auto' ) )
			$manual = true;
		else
			$manual = false;

		// Determine if we need checkboxes
		if ( $manual && $i == 0 )
			$has_checkboxes = true;
		else
			$has_checkboxes = false;

		// Get positions
		$positions = array();
		if ( taxonomy_exists( 'sp_position' ) ):
			$args = array(
				'hide_empty' => false,
				'parent' => 0,
				'include_children' => true,
			);
			$positions = get_terms( 'sp_position', $args );
		endif;

		// Get status option
		if ( 'yes' == get_option( 'sportspress_event_show_status', 'yes' ) )
			$status = true;
		else
			$status = false;

		// Apply filters to labels
		$labels = apply_filters( 'sportspress_event_performance_labels_admin', $labels );

		self::tables( $post->ID, $stats, $labels, $columns, $teams, $has_checkboxes, $positions, $status );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_players', sp_array_value( $_POST, 'sp_players', array() ) );
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
	}

	/**
	 * Admin edit tables
	 */
	public static function tables( $post_id, $stats = array(), $labels = array(), $columns = array(), $teams = array(), $has_checkboxes = false, $positions = array(), $status = true ) {
		$sections = get_option( 'sportspress_event_performance_sections', -1 );
		
		$i = 0;

		foreach ( $teams as $key => $team_id ):
			if ( -1 == $team_id ) continue;
			
			if ( -1 == $sections ) {
				// Get results for players in the team
				$players = sp_array_between( (array)get_post_meta( $post_id, 'sp_player', false ), 0, $key );
				$players[] = -1;
				$data = sp_array_combine( $players, sp_array_value( $stats, $team_id, array() ) );
				?>
				<div>
					<p><strong><?php echo get_the_title( $team_id ); ?></strong></p>
					<?php self::table( $labels, $columns, $data, $team_id, $has_checkboxes, $positions, $status ); ?>
					<?php do_action( 'sportspress_after_event_performance_table_admin', $labels, $columns, $data, $team_id ); ?>
				</div>
			<?php } else { ?>
				<?php
				// Get labels by section
				$args = array(
					'post_type' => 'sp_performance',
					'numberposts' => 100,
					'posts_per_page' => 100,
					'orderby' => 'menu_order',
					'order' => 'ASC',
				);

				$columns = get_posts( $args );

				$offense_labels = $defense_labels = array();
				foreach ( $columns as $column ):
					$section = get_post_meta( $column->ID, 'sp_section', true );
					if ( '' === $section ) {
						$section = -1;
					}
					switch ( $section ):
						case 1:
							$defense_labels[ $column->post_name ] = $column->post_title;
							break;
						case 0:
							$offense_labels[ $column->post_name ] = $column->post_title;
							break;
						default:
							$defense_labels[ $column->post_name ] = $column->post_title;
							$offense_labels[ $column->post_name ] = $column->post_title;
					endswitch;
				endforeach;
				
				// Get results for offensive players in the team
				$offense = sp_array_between( (array)get_post_meta( $post_id, 'sp_offense', false ), 0, $key );
				$offense[] = -1;
				$offense_data = sp_array_combine( $offense, sp_array_value( $stats, $team_id, array() ) );
				
				// Get results for defensive players in the team
				$defense = sp_array_between( (array)get_post_meta( $post_id, 'sp_defense', false ), 0, $key );
				$defense[] = -1;
				$defense_data = sp_array_combine( $defense, sp_array_value( $stats, $team_id, array() ) );
				?>
				<div>
					<p><strong><?php echo get_the_title( $team_id ); ?> &mdash; <?php _e( 'Offense', 'sportspress' ); ?></strong></p>
					<?php self::table( $offense_labels, $columns, $offense_data, $team_id, $has_checkboxes, $positions, $status ); ?>
					<?php do_action( 'sportspress_after_event_performance_table_admin', $offense_labels, $columns, $offense_data, $team_id ); ?>
				</div>
				<div>
					<p><strong><?php echo get_the_title( $team_id ); ?> &mdash; <?php _e( 'Defense', 'sportspress' ); ?></strong></p>
					<?php self::table( $defense_labels, $columns, $defense_data, $team_id, $has_checkboxes, $positions, $status ); ?>
					<?php do_action( 'sportspress_after_event_performance_table_admin', $defense_labels, $columns, $defense_data, $team_id ); ?>
				</div>
			<?php
			}
			$i ++;
		endforeach;
	}

	/**
	 * Admin edit table
	 */
	public static function table( $labels = array(), $columns = array(), $data = array(), $team_id, $has_checkboxes = false, $positions = array(), $status = true ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-performance-table sp-sortable-table">
				<?php self::header( $columns, $labels, $positions, $has_checkboxes, $status ); ?>
				<?php self::footer( $data, $labels, $team_id, $positions, $status ); ?>
				<tbody>
					<?php
					foreach ( $data as $player_id => $player_performance ):
						self::row( $labels, $player_id, $player_performance, $team_id, $data, ! empty( $positions ), $status );
					endforeach;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Admin edit table header
	 */
	public static function header( $columns = array(), $labels = array(), $positions = array(), $has_checkboxes = false, $status = true, $sortable = true, $numbers = true ) {
		?>
		<thead>
			<tr>
				<?php if ( $sortable ) { ?>
					<th class="icon">&nbsp;</th>
				<?php } ?>
				<?php if ( $numbers ) { ?>
					<th>#</th>
				<?php } ?>
				<th><?php _e( 'Player', 'sportspress' ); ?></th>
				<?php if ( ! empty( $positions ) ) { ?>
					<th class="column-position">
						<?php _e( 'Position', 'sportspress' ); ?>
					</th>
				<?php } ?>
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
				<?php if ( $status ) { ?>
					<th>
						<?php _e( 'Status', 'sportspress' ); ?>
					</th>
				<?php } ?>
			</tr>
		</thead>
		<?php
	}

	/**
	 * Admin edit table footer
	 */
	public static function footer( $data = array(), $labels = array(), $team_id = 0, $positions = array(), $status = true, $sortable = true, $numbers = true ) {
		?>
		<tfoot>
			<?php do_action( 'sportspress_event_performance_meta_box_table_footer', $data, $labels, $team_id, $positions, $status, $sortable, $numbers ); ?>
			<?php if ( $team_id ) { ?>
				<tr class="sp-row sp-total">
					<?php if ( $sortable ) { ?>
						<td>&nbsp;</td>
					<?php } ?>
					<?php if ( $numbers ) { ?>
						<td>&nbsp;</td>
					<?php } ?>
					<td><strong><?php _e( 'Total', 'sportspress' ); ?></strong></td>
					<?php if ( ! empty( $positions ) ) { ?>
						<td>&nbsp;</td>
					<?php } ?>
					<?php foreach( $labels as $column => $label ):
						$player_id = 0;
						$player_performance = sp_array_value( $data, $player_id, array() );
						$value = sp_array_value( $player_performance, $column, '' );
						?>
						<td><input type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" placeholder="0" value="<?php echo esc_attr( $value ); ?>" /></td>
					<?php endforeach; ?>
					<?php if ( $status ) { ?>
						<td>&nbsp;</td>
					<?php } ?>
				</tr>
			<?php } ?>
		</tfoot>
		<?php
	}

	/**
	 * Admin edit table row
	 */
	public static function row( $labels = array(), $player_id = 0, $player_performance = array(), $team_id = 0, $data = array(), $positions = true, $status = true, $sortable = true, $numbers = true ) {
		if ( $player_id <= 0 ) return;

		$number = get_post_meta( $player_id, 'sp_number', true );
		$value = sp_array_value( $player_performance, 'number', '' );
		?>
		<tr class="sp-row sp-post" data-player="<?php echo $player_id; ?>">
			<?php if ( $sortable ) { ?>
				<td class="icon"><span class="dashicons dashicons-menu post-state-format"></span></td>
			<?php } ?>
			<?php if ( $numbers ) { ?>
				<td>
					<input class="small-text sp-player-number-input" type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][number]" value="<?php echo esc_attr( $value ); ?>" />
				</td>
			<?php } ?>
			<td><?php echo get_the_title( $player_id ); ?></td>
			<?php if ( $positions ) { ?>
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
			<?php } ?>
			<?php foreach( $labels as $column => $label ):
				$value = sp_array_value( $player_performance, $column, '' );
				?>
				<td>
					<input class="sp-player-<?php echo $column; ?>-input" type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo esc_attr( $value ); ?>" placeholder="0" />
				</td>
			<?php endforeach; ?>
			<?php if ( $status ) { ?>
				<td class="sp-status-selector">
					<?php echo self::status_select( $team_id, $player_id, sp_array_value( $player_performance, 'status', null ) ); ?>
					<?php echo self::sub_select( $team_id, $player_id, sp_array_value( $player_performance, 'sub', null ), $data ); ?>
				</td>
			<?php } ?>
		</tr>
		<?php
	}

	/**
	 * Status selector
	 */
	public static function status_select( $team_id, $player_id, $value = null ) {

		if ( ! $team_id || ! $player_id )
			return '&mdash;';

		$options = apply_filters( 'sportspress_event_performance_status_options', array(
			'lineup' => __( 'Starting Lineup', 'sportspress' ),
			'sub' => __( 'Substitute', 'sportspress' ),
		) );

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