<?php
/**
 * Statistic Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Statistic_Details
 */
class SP_Meta_Box_Statistic_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$equation = explode( ' ', get_post_meta( $post->ID, 'sp_equation', true ) );
		$precision = get_post_meta( $post->ID, 'sp_precision', true );

		// Defaults
		if ( $precision == '' ) $precision = 0;
		?>
		<p><strong><?php _e( 'Key', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_default_key" type="hidden" id="sp_default_key" value="<?php echo $post->post_name; ?>">
			<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>">
		</p>
		<p><strong><?php _e( 'Equation', 'sportspress' ); ?></strong></p>
		<p class="sp-equation-selector">
			<?php
			foreach ( $equation as $piece ):
				sp_equation_selector( $post->ID, $piece, array( 'player_event', 'outcome', 'performance', 'metric' ) );
			endforeach;
			?>
		</p>
		<p><strong><?php _e( 'Rounding', 'sportspress' ); ?></strong></p>
		<p class="sp-precision-selector">
			<input name="sp_precision" type="text" size="4" id="sp_precision" value="<?php echo $precision; ?>" placeholder="0">
		</p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		sp_delete_duplicate_post( $_POST );
		update_post_meta( $post_id, 'sp_equation', implode( ' ', sp_array_value( $_POST, 'sp_equation', array() ) ) );
		update_post_meta( $post_id, 'sp_precision', (int) sp_array_value( $_POST, 'sp_precision', 1 ) );
	}
}