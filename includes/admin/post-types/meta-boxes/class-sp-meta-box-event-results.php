<?php
/**
 * Event Results
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.6
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
		$event = new SP_Event( $post );
		list( $columns, $usecolumns, $data ) = $event->results( true );
		self::table( $columns, $usecolumns, $data );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		$results = (array)sp_array_value( $_POST, 'sp_results', array() );
		$main_result = get_option( 'sportspress_primary_result', null );

		// Auto outcome
		$primary_results = array();
		foreach ( $results as $team => $team_results ) {
			if ( $main_result ) {
				$primary_results[ $team ] = sp_array_value( $team_results, $main_result, null );
			} else {
				if ( is_array( $team_results ) ) {
					end( $team_results );
					$primary_results[ $team ] = prev( $team_results );
				} else {
					$primary_results[ $team ] = null;
				}
			}
		}

		arsort( $primary_results );

		if ( count( $primary_results ) && ! in_array( null, $primary_results ) ) {
			if ( count( array_unique( $primary_results ) ) === 1 ) {
				$args = array(
					'post_type' => 'sp_outcome',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'meta_key' => 'sp_condition',
					'meta_value' => '=',
				);
				$outcomes = get_posts( $args );
				foreach ( $results as $team => $team_results ) {
					if ( array_key_exists( 'outcome', $team_results ) ) continue;
					if ( $outcomes ) {
						$results[ $team ][ 'outcome' ] = array();
						foreach ( $outcomes as $outcome ) {
							$results[ $team ][ 'outcome' ][] = $outcome->post_name;
						}
					}
				}
			} else {
				reset( $primary_results );
				$max = key( $primary_results );
				if ( ! array_key_exists( 'outcome', $results[ $max ] ) ) {
					$args = array(
						'post_type' => 'sp_outcome',
						'numberposts' => -1,
						'posts_per_page' => -1,
						'meta_key' => 'sp_condition',
						'meta_value' => '>',
					);
					$outcomes = get_posts( $args );
					if ( $outcomes ) {
						$results[ $max ][ 'outcome' ] = array();
						foreach ( $outcomes as $outcome ) {
							$results[ $max ][ 'outcome' ][] = $outcome->post_name;
						}
					}
				}

				end( $primary_results );
				$min = key( $primary_results );
				if ( ! array_key_exists( 'outcome', $results[ $min ] ) ) {
					$args = array(
						'post_type' => 'sp_outcome',
						'numberposts' => -1,
						'posts_per_page' => -1,
						'meta_key' => 'sp_condition',
						'meta_value' => '<',
					);
					$outcomes = get_posts( $args );
					if ( $outcomes ) {
						$results[ $min ][ 'outcome' ] = array();
						foreach ( $outcomes as $outcome ) {
							$results[ $min ][ 'outcome' ][] = $outcome->post_name;
						}
					}
				}
			}
		}

		// Update meta
		update_post_meta( $post_id, 'sp_results', $results );
		update_post_meta( $post_id, 'sp_result_columns', sp_array_value( $_POST, 'sp_result_columns', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $columns = array(), $usecolumns = array(), $data = array() ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table">
				<thead>
					<tr>
						<th class="column-team">
							<?php _e( 'Team', 'sportspress' ); ?>
						</th>
						<?php foreach ( $columns as $key => $label ): ?>
							<th class="column-<?php echo $key; ?>">
								<label for="sp_result_columns_<?php echo $key; ?>">
									<input type="checkbox" name="sp_result_columns[]" value="<?php echo $key; ?>" id="sp_result_columns_<?php echo $key; ?>" <?php checked( ! is_array( $usecolumns ) || in_array( $key, $usecolumns ) ); ?>>
									<?php echo $label; ?>
								</label>
							</th>
						<?php endforeach; ?>
						<th class="column-outcome">
							<label for="sp_result_columns_outcome">
								<input type="checkbox" name="sp_result_columns[]" value="outcome" id="sp_result_columns_outcome" <?php checked( ! is_array( $usecolumns ) || in_array( 'outcome', $usecolumns ) ); ?>>
								<?php _e( 'Outcome', 'sportspress' ); ?>
							</label>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $data as $team_id => $team_results ):
						if ( ! $team_id || -1 == $team_id ) continue;
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
									'placeholder' => __( '(Auto)', 'sportspress' ),
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