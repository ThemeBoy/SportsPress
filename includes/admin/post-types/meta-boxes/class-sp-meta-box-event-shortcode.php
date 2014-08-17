<?php
/**
 * Event Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     0.7.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Shortcode
 */
class SP_Meta_Box_Event_Shortcode {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<p>
			<strong><?php _e( 'Team Results', 'sportspress' ); ?></strong>
		</p>
		<p><input type="text" value="[event_results <?php echo $post->ID; ?>]" readonly="readonly" class="code"></p>
		<p>
			<strong><?php _e( 'Details', 'sportspress' ); ?></strong>
		</p>
		<p><input type="text" value="[event_details <?php echo $post->ID; ?>]" readonly="readonly" class="code"></p>
		<p>
			<strong><?php _e( 'Player Performance', 'sportspress' ); ?></strong>
		</p>
		<p><input type="text" value="[event_performance <?php echo $post->ID; ?>]" readonly="readonly" class="code"></p>
		<?php
	}
}