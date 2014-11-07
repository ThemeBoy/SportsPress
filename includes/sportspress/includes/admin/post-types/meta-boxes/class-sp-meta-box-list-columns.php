<?php
/**
 * List Columns
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.1
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
		?>
		<div class="sp-instance">
			<ul id="sp_column-tabs" class="wp-tab-bar sp-tab-bar">
				<li class="wp-tab-active"><a href="#sp_performance-all"><?php _e( 'Performance', 'sportspress' ); ?></a></li>
				<li class="wp-tab"><a href="#sp_metric-all"><?php _e( 'Metrics', 'sportspress' ); ?></a></li>
				<li class="wp-tab"><a href="#sp_statistic-all"><?php _e( 'Statistics', 'sportspress' ); ?></a></li>
			</ul>
			<?php
			$selected = (array)get_post_meta( $post->ID, 'sp_columns', true );
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
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
	}
}