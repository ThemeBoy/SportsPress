<?php
/**
 * Team League Tables
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Team_Tables
 */
class SP_Meta_Box_Team_Tables {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		global $pagenow;

		if ( $pagenow != 'post-new.php' ):

			$team = new SP_Team( $post );
			list( $data, $checked ) = $team->tables( true );
			self::table( $data, $checked );

		else:

			printf( __( 'No results found.', 'sportspress' ) );

		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		sp_update_post_meta_recursive( $post_id, 'sp_table', sp_array_value( $_POST, 'sp_table', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $data = array(), $checked = array() ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-team-table-table sp-select-all-range">
				<thead>
					<tr>
						<th class="check-column"><input class="sp-select-all" type="checkbox"></th>
						<th class="column-table">
							<?php _e( 'League Table', 'sportspress' ); ?>
						</th>
						<th class="column-teams">
							<?php _e( 'Teams', 'sportspress' ); ?>
						</th>
						<th class="column-league">
							<?php _e( 'Competition', 'sportspress' ); ?>
						</th>
						<th class="column-season">
							<?php _e( 'Season', 'sportspress' ); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) ):
						if ( sizeof( $data ) > 0 ):
							$i = 0;
							foreach ( $data as $table ):
								$teams = array_filter( get_post_meta( $table->ID, 'sp_team' ) );
								$format = get_post_meta( $table->ID, 'sp_format', true );
								?>
								<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
									<td>
										<input type="checkbox" name="sp_table[]" id="sp_table_<?php echo $table->ID; ?>" value="<?php echo $table->ID; ?>" <?php checked( in_array( $table->ID, $checked ) ); ?>>
									</td>
									<td>
										<a href="<?php echo get_edit_post_link( $table->ID ); ?>">
											<?php echo $table->post_title; ?>
										</a>
									</td>
									<td><?php echo sizeof( $teams ); ?></td>
									<td><?php echo get_the_terms ( $table->ID, 'sp_league' ) ? the_terms( $table->ID, 'sp_league' ) : '&mdash;'; ?></td>
									<td><?php echo get_the_terms ( $table->ID, 'sp_season' ) ? the_terms( $table->ID, 'sp_season' ) : '&mdash;'; ?></td>
								</tr>
								<?php
								$i++;
							endforeach;
						else:
							?>
							<tr class="sp-row alternate">
								<td colspan="6">
									<?php _e( 'No results found.', 'sportspress' ); ?>
								</td>
							</tr>
							<?php
						endif;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="5">
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