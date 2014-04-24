<?php
/**
 * Performance Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Performance_Details
 */
class SP_Meta_Box_Performance_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$calculate = get_post_meta( $post->ID, 'sp_calculate', true );
		?>
		<p><strong><?php _e( 'Variable', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_default_variable" type="hidden" id="sp_default_variable" value="<?php echo $post->post_name; ?>">
			<input name="sp_variable" type="text" id="sp_variable" value="<?php echo $post->post_name; ?>">
		</p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		sp_delete_duplicate_post( $_POST );
	}
}