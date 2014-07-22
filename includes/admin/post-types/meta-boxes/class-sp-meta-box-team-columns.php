<?php
/**
 * Team Columns
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     0.8
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
		$leagues = get_the_terms( $post->ID, 'sp_league' );
		$league_num = sizeof( $leagues );

		// Loop through columns for each league
		if ( $leagues ): foreach ( $leagues as $league ):

			$league_id = $league->term_id;
			
			if ( $league_num > 1 ):
				?>
				<p><strong><?php echo $league->name; ?></strong></p>
				<?php
			endif;

			$team = new SP_Team( $post );

			list( $columns, $data, $placeholders, $merged, $leagues_seasons ) = $team->columns( $league_id, true );

			self::table( $league_id, $columns, $data, $placeholders, $merged, $leagues_seasons );

		endforeach; else:

			printf( __( 'Select %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) );

		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_leagues', sp_array_value( $_POST, 'sp_leagues', array() ) );
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $league_id, $columns = array(), $data = array(), $placeholders = array(), $merged = array(), $seasons = array(), $readonly = false ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-team-column-table sp-select-all-range">
				<thead>
					<tr>
						<th class="check-column"><input class="sp-select-all" type="checkbox"></th>
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
						if ( !$div_id ) continue;
						$div = get_term( $div_id, 'sp_season' );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<input type="checkbox" name="sp_leagues[<?php echo $league_id; ?>][<?php echo $div_id; ?>]" id="sp_leagues_<?php echo $league_id; ?>_<?php echo $div_id; ?>" value="1" <?php checked( sp_array_value( $seasons, $div_id, 0 ), 1 ); ?>>
							</td>
							<td>
								<label for="sp_leagues_<?php echo $league_id; ?>_<?php echo $div_id; ?>"><?php echo $div->name; ?></label>
							</td>
							<?php foreach( $columns as $column => $label ):
								$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, 0 );
								?>
								<td><?php
									$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, null );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $div_id, array() ), $column, 0 );
									if ( $readonly )
										echo $value ? $value : $placeholder;
									else
										echo '<input type="text" name="sp_columns[' . $league_id . '][' . $div_id . '][' . $column . ']" value="' . $value . '" placeholder="' . $placeholder . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . ' />';
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