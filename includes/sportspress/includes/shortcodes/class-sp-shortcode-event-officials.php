<?php
/**
 * Event Officials Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Event_Officials
 * @version     2.6.9
 */
class SP_Shortcode_Event_Officials {

	/**
	 * Output the event officials shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'event-officials.php', $atts );
	}
}
