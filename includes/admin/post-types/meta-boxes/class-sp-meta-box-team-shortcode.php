<?php
/**
 * Team Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Team_Shortcode
 */
class SP_Meta_Box_Team_Shortcode {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<p>
			<strong><?php _e( 'Table Columns', 'sportspress' ); ?></strong>
		</p>
		<p><input type="text" value="[team_columns <?php echo $post->ID; ?>]" readonly="readonly" class="code"></p>
		<?php
	}
}