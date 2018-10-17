<?php
/**
 * Staff List Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress_Staff_Directories
 * @version     2.6.9
 */
class SP_Shortcode_Staff_List {

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
		add_shortcode( 'staff_list', array( $this, 'output' ) );
	}

	/**
	 * Output the staff list shortcode.
	 *
	 * @param array $atts
	 */
	public function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		ob_start();

		echo SP_Shortcodes::shortcode_wrapper( array( $this, 'get_template' ), $atts );

		return ob_get_clean();
	}

	/**
	 * Get staff list template.
	 *
	 * @param array $atts
	 */
	public function get_template( $atts ) {
		sp_get_template( 'staff-list.php', $atts, '', trailingslashit( SP_STAFF_DIRECTORIES_DIR ) . 'templates/' );
	}
}

new SP_Shortcode_Staff_List();
