<?php
/**
 * Player Statistics
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Player_Statistics
 */
class SP_Meta_Box_Player_Statistics {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$player = new SP_Player( $post );
		$leagues = get_the_terms( $post->ID, 'sp_league' );
		$league_num = sizeof( $leagues );

		// Loop through statistics for each league
		if ( $leagues ):
			$i = 0;
			foreach ( $leagues as $league ):
				?>
				<p><strong><?php echo $league->name; ?></strong></p>
				<?php
				list( $columns, $data, $placeholders, $merged, $seasons_teams ) = $player->data( $league->term_id, true );
				self::table( $post->ID, $league->term_id, $columns, $data, $placeholders, $merged, $seasons_teams, $i == 0 );
				$i ++;
			endforeach;
		endif;
		?>
		<p><strong><?php _e( 'Career Total', 'sportspress' ); ?></strong></p>
		<?php
		list( $columns, $data, $placeholders, $merged, $seasons_teams ) = $player->data( 0, true );
		self::table( $post->ID, 0, $columns, $data, $placeholders, $merged, $seasons_teams );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_leagues', sp_array_value( $_POST, 'sp_leagues', array() ) );
		update_post_meta( $post_id, 'sp_statistics', sp_array_value( $_POST, 'sp_statistics', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $id = null, $league_id, $columns = array(), $data = array(), $placeholders = array(), $merged = array(), $leagues = array(), $has_checkboxes = false, $readonly = false ) {
		$teams = array_filter( get_post_meta( $id, 'sp_team', false ) );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table">
				<thead>
					<tr>
						<th><?php _e( 'Season', 'sportspress' ); ?></th>
						<?php if ( apply_filters( 'sportspress_player_team_statistics', $league_id ) ): ?>
							<th>
								<?php if ( $has_checkboxes ): ?>
									<label for="sp_columns_team">
										<input type="checkbox" name="sp_columns[]" value="team" id="sp_columns_team" <?php checked( ! is_array( $columns ) || array_key_exists( 'team', $columns ) ); ?>>
										<?php _e( 'Team', 'sportspress' ); ?>
									</label>
								<?php else: ?>
									<?php _e( 'Team', 'sportspress' ); ?>
								<?php endif; ?>
							</th>
						<?php endif; ?>
						<?php foreach ( $columns as $key => $label ): if ( $key == 'team' ) continue; ?>
							<th><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $data as $div_id => $div_stats ):
						if ( $div_id === 'statistics' ) continue;
						$div = get_term( $div_id, 'sp_season' );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<?php if ( 0 !== $div_id ): ?>
									<label>
										<?php if ( ! apply_filters( 'sportspress_player_team_statistics', $league_id ) ): ?>
											<?php $value = sp_array_value( $leagues, $div_id, '-1' ); ?>
											<input type="hidden" name="sp_leagues[<?php echo $league_id; ?>][<?php echo $div_id; ?>]" value="-1">
											<input type="checkbox" name="sp_leagues[<?php echo $league_id; ?>][<?php echo $div_id; ?>]" value="1" <?php checked( $value ); ?>>
										<?php endif; ?>
										<?php
										if ( 'WP_Error' == get_class( $div ) ) _e( 'Total', 'sportspress' );
										else echo $div->name;
										?>
									</label>
								<?php else: ?>
									<?php
									if ( 'WP_Error' == get_class( $div ) ) _e( 'Total', 'sportspress' );
									else echo $div->name;
									?>
								<?php endif; ?>
							</td>
							<?php if ( apply_filters( 'sportspress_player_team_statistics', $league_id ) ): ?>
								<?php if ( $div_id == 0 ): ?>
									<td>&nbsp;</td>
								<?php else: ?>
									<td>
										<?php $value = sp_array_value( $leagues, $div_id, '-1' ); ?>
										<?php
										$args = array(
											'post_type' => 'sp_team',
											'name' => 'sp_leagues[' . $league_id . '][' . $div_id . ']',
											'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
										    'sort_order'   => 'ASC',
										    'sort_column'  => 'menu_order',
											'selected' => $value,
											'values' => 'ID',
											'include' => $teams,
											'tax_query' => array(
												'relation' => 'AND',
												array(
													'taxonomy' => 'sp_league',
													'terms' => $league_id,
													'field' => 'id',
												),
												array(
													'taxonomy' => 'sp_season',
													'terms' => $div_id,
													'field' => 'id',
												),
											),
										);
										if ( ! sp_dropdown_pages( $args ) ):
											_e( '&mdash; None &mdash;', 'sportspress' );
										endif;
										?>
									</td>
								<?php endif; ?>
							<?php endif; ?>
							<?php foreach ( $columns as $column => $label ): if ( $column == 'team' ) continue;
								?>
								<td><?php
									$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, null );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $div_id, array() ), $column, 0 );
									if ( $readonly )
										echo $value ? $value : $placeholder;
									else
										echo '<input type="text" name="sp_statistics[' . $league_id . '][' . $div_id . '][' . $column . ']" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $placeholder ) . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . '  />';
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