<?php
/**
 * Table Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     2.3
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
		$caption = get_post_meta( $post->ID, 'sp_caption', true );
		$select = get_post_meta( $post->ID, 'sp_select', true );
		$post_type = sp_get_post_mode_type( $post->ID );
		?>
		<div>
			<p><strong><?php _e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>" placeholder="<?php echo esc_attr( get_the_title() ); ?>"></p>

			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			?>
			<p><strong>
				<?php echo sp_get_post_mode_label( $post->ID ); ?>
			</strong></p>
			<p class="sp-select-setting">
				<select name="sp_select">
					<option value="auto" <?php selected( 'auto', $select ); ?>><?php _e( 'Auto', 'sportspress' ); ?></option>
					<option value="manual" <?php selected( 'manual', $select ); ?>><?php _e( 'Manual', 'sportspress' ); ?></option>
				</select>
			</p>
			<?php
			if ( 'manual' == $select ) {
				sp_post_checklist( $post->ID, $post_type, ( 'auto' == $select ? 'none' : 'block' ), array( 'sp_league', 'sp_season' ), null, 'sp_team' );
				sp_post_adder( $post_type, __( 'Add New', 'sportspress' ) );
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
		update_post_meta( $post_id, 'sp_select', sp_array_value( $_POST, 'sp_select', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
	}
}