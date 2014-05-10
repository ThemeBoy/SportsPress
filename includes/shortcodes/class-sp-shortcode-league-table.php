<?php
/**
 * League Table Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/League_Table
 * @version     0.7
 */
class SP_Shortcode_League_Table {

	/**
	 * Output the league table shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'league-table.php', $atts );
	}
}
