<?php
/**
 * Event Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     2.6.9
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
			'event_results' => __( 'Results', 'sportspress' ),
			'event_details' => __( 'Details', 'sportspress' ),
			'event_performance' => __( 'Box Score', 'sportspress' ),
			'event_venue' => __( 'Venue', 'sportspress' ),
			'event_officials' => __( 'Officials', 'sportspress' ),
			'event_teams' => __( 'Teams', 'sportspress' ),
			'event_full' => __( 'Full Info', 'sportspress' ),
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
		<p><input type="text" value="<?php sp_shortcode_template( $id, $post->ID ); ?>" readonly="readonly" class="code widefat"></p>
		<?php } ?>
		<?php
		}
	}
}