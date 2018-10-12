<?php
/**
 * Event Venue Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Event_Venue
 * @version     2.6.9
 */
class SP_Shortcode_Event_Venue {

	/**
	 * Output the event venue shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'event-venue.php', $atts );
	}
}
