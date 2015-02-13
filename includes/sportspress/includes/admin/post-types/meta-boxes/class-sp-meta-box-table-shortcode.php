<?php
/**
 * Table Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.6.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Table_Shortcode
 */
class SP_Meta_Box_Table_Shortcode {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<p><input type="text" value="<?php sp_shortcode_template( 'league_table', $post->ID ); ?>" readonly="readonly" class="code widefat"></p>
		<?php
	}
}