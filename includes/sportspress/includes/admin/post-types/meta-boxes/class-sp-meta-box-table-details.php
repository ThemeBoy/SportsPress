<?php
/**
 * Table Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Table_Details
 */
class SP_Meta_Box_Table_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$taxonomies = get_object_taxonomies( 'sp_table' );
		$select = get_post_meta( $post->ID, 'sp_select', true );
		if ( ! $select ) {
			global $pagenow;
			$select = ( 'post-new.php' ? 'auto' : 'manual' );
		}
		?>
		<div>
			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			?>
			<p><strong>
				<?php _e( 'Teams', 'sportspress' ); ?>
			</strong></p>
			<p class="sp-select-setting">
				<select name="sp_select">
					<option value="auto" <?php selected( 'auto', $select ); ?>><?php _e( 'Auto', 'sportspress' ); ?></option>
					<option value="manual" <?php selected( 'manual', $select ); ?>><?php _e( 'Manual', 'sportspress' ); ?></option>
				</select>
			</p>
			<?php
			sp_post_checklist( $post->ID, 'sp_team', ( 'auto' == $select ? 'none' : 'block' ), array( 'sp_league', 'sp_season' ) );
			sp_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
			?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_select', sp_array_value( $_POST, 'sp_select', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
	}
}