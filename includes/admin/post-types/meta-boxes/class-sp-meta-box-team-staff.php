<?php
/**
 * Team Player Staff
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version		2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Team_Staff
 */
class SP_Meta_Box_Team_Staff {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		global $pagenow;

		if ( $pagenow != 'post-new.php' ):

			$team = new SP_Team( $post );
			list( $data, $checked ) = $team->staff( true );
			self::table( $data, $checked );

		else:

			printf( __( 'No results found.', 'sportspress' ) );

		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		sp_update_post_meta_recursive( $post_id, 'sp_staff', sp_array_value( $_POST, 'sp_staff', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $data = array(), $checked = array() ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-team-staff-table sp-select-all-range">
				<thead>
					<tr>
						<th class="check-column"><input class="sp-select-all" type="checkbox"></th>
						<th class="column-staff">
							<?php _e( 'Staff', 'sportspress' ); ?>
						</th>
						<th class="column-role">
							<?php _e( 'Job', 'sportspress' ); ?>
						</th>
						<th class="column-competition">
							<?php _e( 'Competition', 'sportspress' ); ?>
						</th>
						<th class="column-league">
							<?php _e( 'League', 'sportspress' ); ?>
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
							foreach ( $data as $staff ):
								$role = get_post_meta( $staff->ID, 'sp_role', true );
								$competitions = get_post_meta( $staff->ID, 'sp_competition', false );
								$competitions = array_filter( $competitions );
								$competitions_names = array();
								if ( !empty( $competitions ) ) {
									foreach ( $competitions as $comp_id ) {
										if ( $comp_id != -1 ) {
											$competitions_names[] = get_the_title( $comp_id );
										}
									}
								}
								?>
								<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
									<td>
										<input type="checkbox" name="sp_staff[]" id="sp_staff_<?php echo $staff->ID; ?>" value="<?php echo $staff->ID; ?>" <?php checked( in_array( $staff->ID, $checked ) ); ?>>
									</td>
									<td>
										<a href="<?php echo get_edit_post_link( $staff->ID ); ?>">
											<?php echo $staff->post_title; ?>
										</a>
									</td>
									<td><?php echo get_the_terms ( $staff->ID, 'sp_role' ) ? the_terms( $staff->ID, 'sp_role' ) : '&mdash;'; ?></td>
									<td><?php echo  !empty( $competitions_names ) ? implode( ', ', $competitions_names ) : '&mdash;'; ?></td>
									<td><?php echo get_the_terms ( $staff->ID, 'sp_league' ) ? the_terms( $staff->ID, 'sp_league' ) : '&mdash;'; ?></td>
									<td><?php echo get_the_terms ( $staff->ID, 'sp_season' ) ? the_terms( $staff->ID, 'sp_season' ) : '&mdash;'; ?></td>
								</tr>
								<?php
								$i++;
							endforeach;
						else:
							?>
							<tr class="sp-row alternate">
								<td colspan="5">
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