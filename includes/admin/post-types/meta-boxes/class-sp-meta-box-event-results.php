<?php
/**
 * Event Results
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Results
 */
class SP_Meta_Box_Event_Results {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$teams = (array)get_post_meta( $post->ID, 'sp_team', false );

		$results = (array)get_post_meta( $post->ID, 'sp_results', true );

		// Get columns from result variables
		$columns = sp_get_var_labels( 'sp_result' );

		// Get results for all teams
		$data = sp_array_combine( $teams, $results );

		?>
		<div>
			<?php self::table( $columns, $data ); ?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		$results = (array)sp_array_value( $_POST, 'sp_results', array() );
		update_post_meta( $post_id, 'sp_results', $results );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $columns = array(), $data = array() ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table">
				<thead>
					<tr>
						<th class="column-team"><?php _e( 'Team', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $key => $label ): ?>
							<th class="outcome-<?php echo $key; ?>"><?php echo $label; ?></th>
						<?php endforeach; ?>
						<th class="column-outcome"><?php _e( 'Outcome', 'sportspress' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $data as $team_id => $team_results ):
						if ( !$team_id ) continue;
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<?php echo get_the_title( $team_id ); ?>
							</td>
							<?php foreach( $columns as $column => $label ):
								$value = sp_array_value( $team_results, $column, '' );
								?>
								<td><input type="text" name="sp_results[<?php echo $team_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" /></td>
							<?php endforeach; ?>
							<td>
								<?php
								$values = sp_array_value( $team_results, 'outcome', '' );
								if ( ! is_array( $values ) )
									$values = array( $values );

								$args = array(
									'post_type' => 'sp_outcome',
									'name' => 'sp_results[' . $team_id . '][outcome][]',
									'option_none_value' => '',
								    'sort_order'   => 'ASC',
								    'sort_column'  => 'menu_order',
									'selected' => $values,
									'class' => 'sp-outcome',
									'property' => 'multiple',
									'chosen' => true,
								);
								sp_dropdown_pages( $args );
								?>
							</td>
						</tr>
						<?php
						$i++;
					endforeach;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}