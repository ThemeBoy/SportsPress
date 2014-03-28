<?php
/**
 * Performance Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
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
		<p><strong><?php _e( 'Calculate', 'sportspress' ); ?></strong></p>
		<p class="sp-calculate-selector">
			<?php sp_calculate_selector( $post->ID, $calculate ); ?>
		</p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		sp_delete_duplicate_post( $_POST );
		update_post_meta( $post_id, 'sp_calculate', sp_array_value( $_POST, 'sp_calculate', 'DESC' ) );
	}
}