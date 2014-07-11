<?php
/**
 * Outcome Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Meta_Box_Config' ) )
	include( 'class-sp-meta-box-config.php' );

/**
 * SP_Meta_Box_Outcome_Details
 */
class SP_Meta_Box_Outcome_Details extends SP_Meta_Box_Config {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$abbreviation = get_post_meta( $post->ID, 'sp_abbreviation', true );
		?>
		<p><strong><?php _e( 'Variable', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_default_key" type="hidden" id="sp_default_key" value="<?php echo $post->post_name; ?>">
			<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>">
		</p>
		<p><strong><?php _e( 'Abbreviation', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_abbreviation" type="text" id="sp_abbreviation" value="<?php echo $abbreviation; ?>" placeholder="<?php echo substr( $post->post_title, 0, 1 ); ?>">
		</p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_abbreviation', sp_array_value( $_POST, 'sp_abbreviation', array() ) );
	}
}