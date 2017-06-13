<?php
/**
 * Team Gallery Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Team_Gallery
 * @version   2.4
 */
class SP_Shortcode_Team_Gallery {

	/**
	 * Output the team gallery shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'team-gallery.php', $atts );
	}
}
