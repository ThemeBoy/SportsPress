<?php
/**
 * List Data
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     1.0.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_List_Data
 */
class SP_Meta_Box_List_Data {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$list = new SP_Player_List( $post );
		list( $columns, $usecolumns, $data, $placeholders, $merged ) = $list->data( true );
		$adjustments = $list->adjustments;
		self::table( $columns, $usecolumns, $data, $placeholders, $adjustments );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
		update_post_meta( $post_id, 'sp_adjustments', sp_array_value( $_POST, 'sp_adjustments', array() ) );
		update_post_meta( $post_id, 'sp_players', sp_array_value( $_POST, 'sp_players', array() ) );
	}

	/**
	 * Admin edit table
	 */
	public static function table( $columns = array(), $usecolumns = null, $data = array(), $placeholders = array(), $adjustments = array() ) {
		if ( is_array( $usecolumns ) )
			$usecolumns = array_filter( $usecolumns );
		?>
		<ul class="subsubsub sp-table-bar">
			<li><a href="#sp-table-values" class="current"><?php _e( 'Values', 'sportspress' ); ?></a></li> | 
			<li><a href="#sp-table-adjustments" class=""><?php _e( 'Adjustments', 'sportspress' ); ?></a></li>
		</ul>
		<div class="sp-data-table-container sp-table-panel sp-table-values" id="sp-table-values">
			<table class="widefat sp-data-table sp-player-list-table">
				<thead>
					<tr>
						<th>#</th>
						<th><?php _e( 'Player', 'sportspress' ); ?></th>
						<th><label for="sp_columns_team">
							<input type="checkbox" name="sp_columns[]" value="team" id="sp_columns_team" <?php checked( ! is_array( $usecolumns ) || in_array( 'team', $usecolumns ) ); ?>>
							<?php _e( 'Team', 'sportspress' ); ?>
						</label></th>
						<?php foreach ( $columns as $key => $label ): ?>
							<th><label for="sp_columns_<?php echo $key; ?>">
								<input type="checkbox" name="sp_columns[]" value="<?php echo $key; ?>" id="sp_columns_<?php echo $key; ?>" <?php checked( ! is_array( $usecolumns ) || in_array( $key, $usecolumns ) ); ?>>
								<?php echo $label; ?>
							</label></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) && sizeof( $data ) > 0 ):
						$i = 0;
						foreach ( $data as $player_id => $player_stats ):
							if ( !$player_id ) continue;
							$team = get_post_meta( $player_id, 'sp_team', true );
							$div = get_term( $player_id, 'sp_season' );
							$number = get_post_meta( $player_id, 'sp_number', true );

							$default_name = sp_array_value( $player_stats, 'name', '' );
							if ( $default_name == null )
								$default_name = get_the_title( $player_id );
							?>
							<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
								<td><?php echo ( $number ? $number : '&nbsp;' ); ?></td>
								<td>
									<span class="sp-default-value">
										<span class="sp-default-value-input"><?php echo $default_name; ?></span>
										<a class="dashicons dashicons-edit sp-edit" title="<?php _e( 'Edit', 'sportspress' ); ?>"></a>
									</span>
									<span class="hidden sp-custom-value">
										<input type="text" name="sp_players[<?php echo $player_id; ?>][name]" class="name sp-custom-value-input" value="<?php echo sp_array_value( $player_stats, 'name', '' ); ?>" placeholder="<?php echo get_the_title( $player_id ); ?>" size="6">
										<a class="button button-secondary sp-cancel"><?php _e( 'Cancel', 'sportspress' ); ?></a>
										<a class="button button-primary sp-save"><?php _e( 'Save', 'sportspress' ); ?></a>
									</span>
								</td>
								<td><?php echo get_the_title( $team ); ?></td>
								<?php foreach( $columns as $column => $label ):
									$value = sp_array_value( $player_stats, $column, '' );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $player_id, array() ), $column, 0 );
									?>
									<td><input type="text" name="sp_players[<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" data-placeholder="<?php echo $placeholder; ?>" data-matrix="<?php echo $player_id; ?>_<?php echo $column; ?>" data-adjustment="<?php echo sp_array_value( sp_array_value( $adjustments, $player_id, array() ), $column, 0 ); ?>" /></td>
								<?php endforeach; ?>
							</tr>
							<?php
							$i++;
						endforeach;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="<?php $colspan = sizeof( $columns ) + 3; echo $colspan; ?>">
							<?php printf( __( 'Select %s', 'sportspress' ), __( 'Players', 'sportspress' ) ); ?>
						</td>
					</tr>
					<?php
					endif;
					?>
				</tbody>
			</table>
		</div>
		<div class="sp-data-table-container sp-table-panel sp-table-adjustments hidden" id="sp-table-adjustments">
			<table class="widefat sp-data-table sp-player-list-table">
				<thead>
					<tr>
						<th>#</th>
						<th><?php _e( 'Player', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $key => $label ): ?>
							<th><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) && sizeof( $data ) > 0 ):
						$i = 0;
						foreach ( $data as $player_id => $player_stats ):
							if ( !$player_id ) continue;
							$div = get_term( $player_id, 'sp_season' );
							$number = get_post_meta( $player_id, 'sp_number', true );
							?>
							<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
								<td><?php echo ( $number ? $number : '&nbsp;' ); ?></td>
								<td>
									<?php echo get_the_title( $player_id ); ?>
								</td>
								<?php foreach( $columns as $column => $label ):
									$value = sp_array_value( sp_array_value( $adjustments, $player_id, array() ), $column, '' );
									?>
									<td><input type="text" name="sp_adjustments[<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="0" data-matrix="<?php echo $player_id; ?>_<?php echo $column; ?>" /></td>
								<?php endforeach; ?>
							</tr>
							<?php
							$i++;
						endforeach;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="<?php $colspan = sizeof( $columns ) + 3; echo $colspan; ?>">
							<?php printf( __( 'Select %s', 'sportspress' ), __( 'Players', 'sportspress' ) ); ?>
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