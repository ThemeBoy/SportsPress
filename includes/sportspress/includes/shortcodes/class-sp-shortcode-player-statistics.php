<?php
/**
 * Player Statistics Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Player_Statistics
 * @version     1.2
 */
class SP_Shortcode_Player_Statistics {

	/**
	 * Output the player statistics shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'player-statistics.php', $atts );
	}
}
