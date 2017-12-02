<?php
/**
 * Competition Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version   2.5.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Competition_Shortcode
 */
class SP_Meta_Box_Competition_Shortcode {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<p><input type="text" value="<?php sp_shortcode_template( 'competition', $post->ID ); ?>" readonly="readonly" class="code widefat"></p>
		<?php
	}
}