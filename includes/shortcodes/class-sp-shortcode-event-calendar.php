<?php
/**
 * Event Calendar Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Event_Calendar
 * @version     0.7
 */
class SP_Shortcode_Event_Calendar {

	/**
	 * Output the event calendar shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'event-calendar.php', $atts );
	}
}
