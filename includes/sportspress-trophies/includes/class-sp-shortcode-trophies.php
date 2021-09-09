<?php
/**
 * Trophies Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Class
 * @package 	SportsPress Trophies
 * @version     2.8.0
 */
class SP_Shortcode_Trophies {

	/**
	 * Constructor
	 */
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init shortcodes
	 */
	public function init() {
		add_shortcode( 'team_trophies', array( $this, 'output' ) );
	}

	/**
	 * Output the trophies shortcode.
	 *
	 * @param array $atts
	 */
	public function output( $atts ) {
		
		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		sp_get_template( 'trophy-data.php', $atts, '', SP_TROPHIES_DIR . 'templates/' );
	}
}

new SP_Shortcode_Trophies();
