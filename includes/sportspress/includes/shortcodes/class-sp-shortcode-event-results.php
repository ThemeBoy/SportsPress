<?php
/**
 * Event Results Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Event_Results
 * @version     0.8
 */
class SP_Shortcode_Event_Results {

	/**
	 * Output the event results shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'event-results.php', $atts );
	}
}
