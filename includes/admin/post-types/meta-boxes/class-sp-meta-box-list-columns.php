<?php
/**
 * List Columns
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
 * SP_Meta_Box_List_Columns
 */
class SP_Meta_Box_List_Columns {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$selected = (array) get_post_meta( $post->ID, 'sp_columns', true );
		$orderby  = get_post_meta( $post->ID, 'sp_orderby', true );
		?>
		<p><strong><?php esc_attr_e( 'General', 'sportspress' ); ?></strong></p>
		<ul class="categorychecklist form-no-clear">
			<li>
				<label class="selectit">
					<input value="number" type="checkbox" name="sp_columns[]" id="sp_columns_number" <?php checked( in_array( 'number', $selected ) ); ?>>
					<?php
					if ( in_array( $orderby, array( 'number', 'name' ) ) ) {
						esc_attr_e( 'Squad Number', 'sportspress' );
					} else {
						esc_attr_e( 'Rank', 'sportspress' );
					}
					?>
						
				</label>
			</li>
			<li>
				<label class="selectit">
					<input value="team" type="checkbox" name="sp_columns[]" id="sp_columns_team" <?php checked( in_array( 'team', $selected ) ); ?>>
					<?php esc_attr_e( 'Team', 'sportspress' ); ?>
				</label>
			</li>
			<li>
				<label class="selectit">
					<input value="position" type="checkbox" name="sp_columns[]" id="sp_columns_position" <?php checked( in_array( 'position', $selected ) ); ?>>
					<?php esc_attr_e( 'Position', 'sportspress' ); ?>
				</label>
			</li>
			<?php do_action( 'sportspress_list_general_columns', $selected ); ?>
		</ul>
		<p><strong><?php esc_attr_e( 'Data', 'sportspress' ); ?></strong></p>
		<div class="sp-instance">
			<ul id="sp_column-tabs" class="sp-tab-bar category-tabs">
				<li class="tabs"><a href="#sp_performance-all"><?php esc_attr_e( 'Performance', 'sportspress' ); ?></a></li>
				<li><a href="#sp_metric-all"><?php esc_attr_e( 'Metrics', 'sportspress' ); ?></a></li>
				<li><a href="#sp_statistic-all"><?php esc_attr_e( 'Statistics', 'sportspress' ); ?></a></li>
			</ul>
			<?php
			sp_column_checklist( $post->ID, 'sp_performance', 'block', $selected );
			sp_column_checklist( $post->ID, 'sp_metric', 'none', $selected );
			sp_column_checklist( $post->ID, 'sp_statistic', 'none', $selected );
			?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array(), 'key' ) );
	}
}
