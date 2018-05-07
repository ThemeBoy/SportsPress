<?php
/**
 * Spec Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version		2.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Meta_Box_Config' ) )
	include( 'class-sp-meta-box-config.php' );

/**
 * SP_Meta_Box_Spec_Details
 */
class SP_Meta_Box_Spec_Details extends SP_Meta_Box_Config {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$visible = get_post_meta( $post->ID, 'sp_visible', true );
		if ( '' === $visible ) $visible = 1;
		?>
		<p><strong><?php _e( 'Variable', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_default_key" type="hidden" id="sp_default_key" value="<?php echo $post->post_name; ?>">
			<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>">
		</p>
		<p>
			<strong><?php _e( 'Visible', 'sportspress' ); ?></strong>
			<i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Display in event pages?', 'sportspress' ); ?>"></i>
		</p>
		<ul class="sp-visible-selector">
			<li>
				<label class="selectit">
					<input name="sp_visible" id="sp_visible_yes" type="radio" value="1" <?php checked( $visible ); ?>>
					<?php _e( 'Yes', 'sportspress' ); ?>
				</label>
			</li>
			<li>
				<label class="selectit">
					<input name="sp_visible" id="sp_visible_no" type="radio" value="0" <?php checked( ! $visible ); ?>>
					<?php _e( 'No', 'sportspress' ); ?>
				</label>
			</li>
		</ul>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		self::delete_duplicate( $_POST );
		update_post_meta( $post_id, 'sp_visible', sp_array_value( $_POST, 'sp_visible', 1 ) );
	}
}