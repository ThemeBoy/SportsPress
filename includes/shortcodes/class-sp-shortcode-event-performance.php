<?php
/**
 * Event Performance Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Event_Performance
 * @version     0.8
 */
class SP_Shortcode_Event_Performance {

	/**
	 * Output the event performance shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'event-performance.php', $atts );
	}
}
