<?php
/**
 * Tournament Bracket Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Class
 * @package 	SportsPress Tournaments
 * @version     1.7
 */
class SP_Shortcode_Tournament_Bracket {

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
		add_shortcode( 'tournament_bracket', __CLASS__ . '::output' );
	}

	/**
	 * Output the tournament_bracket shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		ob_start();
		
		echo '<div class="sportspress">';
		sp_get_template( 'tournament-bracket.php', $atts, '', SP_TOURNAMENTS_DIR . 'templates/' );
		echo '</div>';

		return ob_get_clean();
	}
}

new SP_Shortcode_Tournament_Bracket();
