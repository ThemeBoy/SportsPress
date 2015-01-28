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
		$shortcodes = apply_filters( 'sportspress_event_shortcodes', array(
			'event_results' => __( 'Event Results', 'sportspress' ),
			'event_details' => __( 'Details', 'sportspress' ),
			'event_performance' => __( 'Player Performance', 'sportspress' ),
		) );
		if ( $shortcodes ) {
		?>
		<p class="howto">
			<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
		<?php foreach ( $shortcodes as $id => $label ) { ?>
		<p>
			<strong><?php echo $label; ?></strong>
		</p>
		<p><input type="text" value="[<?php echo $id; ?> <?php echo $post->ID; ?>]" readonly="readonly" class="code widefat"></p>
		<?php } ?>
		<?php
		}
	}
}