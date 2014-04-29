<?php
/**
 * List Columns
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_List_Columns
 */
class SP_Meta_Box_List_Columns {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$column_groups = (array) get_post_meta( $post->ID, 'sp_column_group' );
		?>
		<div id="sp-column-group-select">
			<label class="selectit">
				<input type="checkbox" name="sp_column_group[]" value="sp_performance" <?php checked( true, ( empty( $column_groups ) || in_array( 'sp_performance', $column_groups ) ) ); ?>>
				<?php _e( 'Performance', 'sportspress' ); ?>
			</label><br>
			<label class="selectit">
				<input type="checkbox" name="sp_column_group[]" value="sp_metric" <?php checked( true, ( empty( $column_groups ) || in_array( 'sp_metric', $column_groups ) ) ); ?>>
				<?php _e( 'Metrics', 'sportspress' ); ?>
			</label><br>
			<label class="selectit">
				<input type="checkbox" name="sp_column_group[]" value="sp_statistic" <?php checked( true, ( empty( $column_groups ) || in_array( 'sp_statistic', $column_groups ) ) ); ?>>
				<?php _e( 'Statistics', 'sportspress' ); ?>
			</label><br>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		sp_update_post_meta_recursive( $post_id, 'sp_column_group', sp_array_value( $_POST, 'sp_column_group', array() ) );
	}
}