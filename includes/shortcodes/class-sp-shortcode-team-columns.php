<?php
/**
 * Team Columns Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Team_Columns
 * @version     1.3
 */
class SP_Shortcode_Team_Columns {

	/**
	 * Output the team columns shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'team-columns.php', $atts );
	}
}
