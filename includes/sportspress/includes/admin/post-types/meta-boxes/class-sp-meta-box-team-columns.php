<?php
/**
 * Team Columns
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Team_Columns
 */
class SP_Meta_Box_Team_Columns {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$team = new SP_Team( $post );
		$leagues = get_the_terms( $post->ID, 'sp_league' );
		$league_num = sizeof( $leagues );

		// Loop through columns for each league
		if ( $leagues ): foreach ( $leagues as $league ):

			$league_id = $league->term_id;
			?>
			<p><strong><?php echo $league->name; ?></strong></p>
			<?php
			list( $columns, $data, $placeholders ) = $team->columns( $league_id );
			self::table( $league_id, $columns, $data, $placeholders );

		endforeach; endif;
		?>
		<p><strong><?php _e( 'Total', 'sportspress' ); ?></strong></p>
		<?php
		list( $columns, $data, $placeholders ) = $team->columns( 0 );
		self::table( 0, $columns, $data, $placeholders );
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
	public static function table( $league_id, $columns = array(), $data = array(), $placeholders = array() ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-team-column-table sp-select-all-range">
				<thead>
					<tr>
						<th><?php _e( 'Season', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $label ): ?>
							<th><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $data as $div_id => $div_stats ):
						$div = get_term( $div_id, 'sp_season' );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<label for="sp_leagues_<?php echo $league_id; ?>_<?php echo $div_id; ?>">
									<?php
									if ( 'WP_Error' == get_class( $div ) ) _e( 'Total', 'sportspress' );
									else echo $div->name;
									?>
								</label>
							</td>
							<?php foreach( $columns as $column => $label ):
								$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, 0 );
								?>
								<td><?php
									$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, null );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $div_id, array() ), $column, 0 );
									echo '<input type="text" name="sp_columns[' . $league_id . '][' . $div_id . '][' . $column . ']" value="' . $value . '" placeholder="' . $placeholder . '" />';
								?></td>
							<?php endforeach; ?>
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