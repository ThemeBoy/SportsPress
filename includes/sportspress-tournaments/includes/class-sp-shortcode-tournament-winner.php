<?php
/**
 * Tournament Winner Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Class
 * @package 	SportsPress Tournaments
 * @version     1.6
 */
class SP_Shortcode_Tournament_Winner {

	/**
	 * Constructor
	 */
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init shortcodes
	 */
	public static function init() {
		add_shortcode( 'tournament_winner', __CLASS__ . '::output' );
	}

	/**
	 * Output the tournament_winner shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'tournament-winner.php', $atts, 'tournaments', SP_TOURNAMENTS_DIR . 'templates/' );
	}
}

new SP_Shortcode_Tournament_Winner();
