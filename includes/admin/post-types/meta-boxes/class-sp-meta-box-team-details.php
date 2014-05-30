<?php
/**
 * Staff Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Team_Details
 */
class SP_Meta_Box_Team_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$abbreviation = get_post_meta( $post->ID, 'sp_abbreviation', true );
		$url = get_post_meta( $post->ID, 'sp_url', true );
		?>
		<p><strong><?php _e( 'Site URL', 'sportspress' ); ?></strong></p>
		<p><input type="text" class="widefat" id="sp_url" name="sp_url" value="<?php echo $url; ?>"></p>
		<?php if ( $url ): ?>
			<p><a class="sp-link" title="<?php _e( 'Visit Site', 'sportspress' ); ?>" href="<?php echo $url; ?>" target="_blank"><?php _e( 'Visit Site', 'sportspress' ); ?></a></p>
		<?php endif; ?>

		<p><strong><?php _e( 'Abbreviation', 'sportspress' ); ?></strong></p>
		<p><input type="text" id="sp_abbreviation" name="sp_abbreviation" value="<?php echo $abbreviation; ?>"></p>
		<p class="description"><?php _e( 'The abbreviation is not prominent by default; however, some themes may show it', 'sportspress' ); ?></p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_url', sp_array_value( $_POST, 'sp_url', '' ) );
		update_post_meta( $post_id, 'sp_abbreviation', sp_array_value( $_POST, 'sp_abbreviation', '' ) );
	}
}