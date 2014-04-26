<?php
/**
 * Player Statistics
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.8
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
		$leagues = get_the_terms( $post->ID, 'sp_league' );

		$league_num = sizeof( $leagues );

		// Loop through statistics for each league
		if ( $leagues ): foreach ( $leagues as $league ):
			
			if ( $league_num > 1 ):
				?>
				<p><strong><?php echo $league->name; ?></strong></p>
				<?php
			endif;

			$player = new SP_Player( $post );
			list( $columns, $data, $placeholders, $merged, $seasons_teams ) = $player->data( $league->term_id, true );

			self::table( $post->ID, $league->term_id, $columns, $data, $placeholders, $merged, $seasons_teams, ! current_user_can( 'edit_sp_teams' ) );

		endforeach; else:

			printf( __( 'Select %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) );

		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_leagues', sp_array_value( $_POST, 'sp_leagues', array() ) );
		if ( current_user_can( 'edit_sp_teams' ) )
			update_post_meta( $post_id, 'sp_statistics', sp_array_value( $_POST, 'sp_statistics', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $id = null, $league_id, $columns = array(), $data = array(), $placeholders = array(), $merged = array(), $leagues = array(), $readonly = true ) {
		$teams = array_filter( get_post_meta( $id, 'sp_team', false ) );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table">
				<thead>
					<tr>
						<th><?php _e( 'Season', 'sportspress' ); ?></th>
						<th><?php _e( 'Team', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $label ): ?>
							<th><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $data as $div_id => $div_stats ):
						if ( !$div_id || $div_id == 'statistics' ) continue;
						$div = get_term( $div_id, 'sp_season' );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<?php echo $div->name; ?>
							</td>
							<td>
								<?php
								$value = sp_array_value( $leagues, $div_id, '-1' );
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
									_e( 'No results found.', 'sportspress' );
								endif;
								?>
							</td>
							<?php foreach( $columns as $column => $label ):
								?>
								<td><?php
									$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, null );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $div_id, array() ), $column, 0 );
									echo '<input type="text" name="sp_statistics[' . $league_id . '][' . $div_id . '][' . $column . ']" value="' . $value . '" placeholder="' . $placeholder . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . '  />';
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