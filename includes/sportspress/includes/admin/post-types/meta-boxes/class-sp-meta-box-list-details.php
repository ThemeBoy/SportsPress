<?php
/**
 * List Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_List_Details
 */
class SP_Meta_Box_List_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$taxonomies = get_object_taxonomies( 'sp_list' );
		$caption = get_post_meta( $post->ID, 'sp_caption', true );
		$team_id = get_post_meta( $post->ID, 'sp_team', true );
		$grouping = get_post_meta( $post->ID, 'sp_grouping', true );
		$orderby = get_post_meta( $post->ID, 'sp_orderby', true );
		$order = get_post_meta( $post->ID, 'sp_order', true );
		$select = get_post_meta( $post->ID, 'sp_select', true );
		if ( ! $select ) {
			global $pagenow;
			$select = ( 'post-new.php' == $pagenow ? 'auto' : 'manual' );
		}
		?>
		<div>
			<p><strong><?php _e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>" placeholder="<?php echo esc_attr( get_the_title() ); ?>"></p>

			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			?>
			<?php if ( apply_filters( 'sportspress_list_team_selector', true ) ) { ?>
			<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
			<p class="sp-tab-select">
				<?php
				$args = array(
					'post_type' => 'sp_team',
					'name' => 'sp_team',
					'show_option_all' => __( 'All', 'sportspress' ),
					'selected' => $team_id,
					'values' => 'ID',
				);
				if ( ! sp_dropdown_pages( $args ) ):
					sp_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
				endif;
				?>
			</p>
			<?php } ?>
			<p><strong><?php _e( 'Grouping', 'sportspress' ); ?></strong></p>
			<p>
			<select name="sp_grouping">
				<option value="0"><?php _e( 'None', 'sportspress' ); ?></option>
				<option value="position" <?php selected( $grouping, 'position' ); ?>><?php _e( 'Position', 'sportspress' ); ?></option>
			</select>
			</p>
			<p><strong><?php _e( 'Sort by', 'sportspress' ); ?></strong></p>
			<p>
			<?php
			$args = array(
				'prepend_options' => array(
					'number' => __( 'Squad Number', 'sportspress' ),
					'name' => __( 'Name', 'sportspress' ),
				),
				'post_type' => array( 'sp_performance', 'sp_metric', 'sp_statistic' ),
				'name' => 'sp_orderby',
				'selected' => $orderby,
				'values' => 'slug',
			);
			sp_dropdown_pages( $args );
			?>
			</p>
			<p><strong><?php _e( 'Sort Order', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_order">
					<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
					<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
				</select>
			</p>
			<p><strong><?php _e( 'Players', 'sportspress' ); ?></strong></p>
			<p class="sp-select-setting">
				<select name="sp_select">
					<option value="auto" <?php selected( 'auto', $select ); ?>><?php _e( 'Auto', 'sportspress' ); ?></option>
					<option value="manual" <?php selected( 'manual', $select ); ?>><?php _e( 'Manual', 'sportspress' ); ?></option>
				</select>
			</p>
			<?php
			if ( 'manual' == $select ) {
				sp_post_checklist( $post->ID, 'sp_player', ( 'auto' == $select ? 'none' : 'block' ), array( 'sp_league', 'sp_season', 'sp_current_team' ) );
				sp_post_adder( 'sp_player', __( 'Add New', 'sportspress' ) );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_caption', esc_attr( sp_array_value( $_POST, 'sp_caption', 0 ) ) );
		update_post_meta( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
		update_post_meta( $post_id, 'sp_grouping', sp_array_value( $_POST, 'sp_grouping', array() ) );
		update_post_meta( $post_id, 'sp_orderby', sp_array_value( $_POST, 'sp_orderby', array() ) );
		update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', array() ) );
		update_post_meta( $post_id, 'sp_select', sp_array_value( $_POST, 'sp_select', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );
	}
}