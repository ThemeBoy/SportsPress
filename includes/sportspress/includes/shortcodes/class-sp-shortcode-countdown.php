<?php
/**
 * Countdown Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Countdown
 * @version     0.7
 */
class SP_Shortcode_Countdown {

	/**
	 * Output the countdown shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'countdown.php', $atts );
	}
}
