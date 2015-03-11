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

		ob_start();

		echo '<div class="sportspress">';
		sp_get_template( 'tournament-winner.php', $atts, '', SP_TOURNAMENTS_DIR . 'templates/' );
		echo '</div>';

		return ob_get_clean();
	}
}

new SP_Shortcode_Tournament_Winner();
