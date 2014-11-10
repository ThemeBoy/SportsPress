<?php
/**
 * Player Details Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Player_Details
 * @version     1.4.7
 */
class SP_Shortcode_Player_Details {

	/**
	 * Output the player details shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'player-photo.php', $atts );
		sp_get_template( 'player-details.php', $atts );
	}
}
