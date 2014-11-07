<?php
/**
 * Player Gallery Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Player_Gallery
 * @version     0.7
 */
class SP_Shortcode_Player_Gallery {

	/**
	 * Output the player gallery shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'player-gallery.php', $atts );
	}
}
