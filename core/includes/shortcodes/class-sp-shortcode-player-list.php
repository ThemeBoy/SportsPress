<?php
/**
 * Player List Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Player_List
 * @version     0.7
 */
class SP_Shortcode_Player_List {

	/**
	 * Output the player list shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'player-list.php', $atts );
	}
}
