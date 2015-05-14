<?php
/**
 * Sponsors Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Class
 * @package 	SportsPress Sponsors
 * @version     1.6
 */
class SP_Shortcode_Sponsors {

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
		add_shortcode( 'sponsors', __CLASS__ . '::output' );
	}

	/**
	 * Output the sponsors shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {
		sp_get_template( 'sponsors.php', $atts, '', SP_SPONSORS_DIR . 'templates/' );
	}
}

new SP_Shortcode_Sponsors();
