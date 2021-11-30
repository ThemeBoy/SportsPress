<?php
/**
 * Team Player Lists
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Meta_Boxes
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SP_Meta_Box_Team_Lists
 */
class SP_Meta_Box_Team_Lists {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		global $pagenow;

		if ( $pagenow != 'post-new.php' ) :

			$team                   = new SP_Team( $post );
			list( $data, $checked ) = $team->lists( true );
			self::table( $data, $checked );

		else :

			printf( esc_attr__( 'No results found.', 'sportspress' ) );

		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		sp_update_post_meta_recursive( $post_id, 'sp_list', sp_array_value( $_POST, 'sp_list', array(), 'int' ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $data = array(), $checked = array() ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-team-list-table sp-select-all-range">
				<thead>
					<tr>
						<th class="check-column"><input class="sp-select-all" type="checkbox"></th>
						<th class="column-list">
							<?php esc_attr_e( 'Player List', 'sportspress' ); ?>
						</th>
						<th class="column-players">
							<?php esc_attr_e( 'Players', 'sportspress' ); ?>
						</th>
						<th class="column-league">
							<?php esc_attr_e( 'League', 'sportspress' ); ?>
						</th>
						<th class="column-season">
							<?php esc_attr_e( 'Season', 'sportspress' ); ?>
						</th>
						<th class="column-layout">
							<?php esc_attr_e( 'Layout', 'sportspress' ); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) ) :
						if ( sizeof( $data ) > 0 ) :
							$i = 0;
							foreach ( $data as $list ) :
								$players = array_filter( get_post_meta( $list->ID, 'sp_player' ) );
								$format  = get_post_meta( $list->ID, 'sp_format', true );
								?>
								<tr class="sp-row sp-post
								<?php
								if ( $i % 2 == 0 ) {
									echo ' alternate';}
								?>
								">
									<td>
										<input type="checkbox" name="sp_list[]" id="sp_list_<?php echo esc_attr( $list->ID ); ?>" value="<?php echo esc_attr( $list->ID ); ?>" <?php checked( in_array( $list->ID, $checked ) ); ?>>
									</td>
									<td>
										<a href="<?php echo esc_url( get_edit_post_link( $list->ID ) ); ?>">
											<?php echo esc_html( $list->post_title ); ?>
										</a>
									</td>
									<td><?php echo esc_attr( sizeof( $players ) ); ?></td>
									<td><?php echo get_the_terms( $list->ID, 'sp_league' ) ? wp_kses_post( the_terms( $list->ID, 'sp_league' ) ) : '&mdash;'; ?></td>
									<td><?php echo get_the_terms( $list->ID, 'sp_season' ) ? wp_kses_post( the_terms( $list->ID, 'sp_season' ) ) : '&mdash;'; ?></td>
									<td><?php echo esc_html( sp_array_value( SP()->formats->list, $format, '&mdash;' ) ); ?></td>
								</tr>
								<?php
								$i++;
							endforeach;
						else :
							?>
							<tr class="sp-row alternate">
								<td colspan="6">
									<?php esc_attr_e( 'No results found.', 'sportspress' ); ?>
								</td>
							</tr>
							<?php
						endif;
					else :
						?>
					<tr class="sp-row alternate">
						<td colspan="6">
							<?php printf( esc_attr__( 'Select %s', 'sportspress' ), esc_attr__( 'Details', 'sportspress' ) ); ?>
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
