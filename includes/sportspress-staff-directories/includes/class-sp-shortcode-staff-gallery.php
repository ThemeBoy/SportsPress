<?php
/**
 * Staff Gallery Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress_Staff_Directories
 * @version     2.6.15
 */
class SP_Shortcode_Staff_Gallery {

	/**
	 * Constructor
	 */
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		//add_filter( 'sportspress_locate_template', array( $this, 'locate_template' ) );
	}

	/**
	 * Init shortcodes
	 */
	public function init() {
		add_shortcode( 'staff_gallery', array( $this, 'output' ) );
	}

	/**
	 * Output the staff gallery shortcode.
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
	 * Get staff gallery template.
	 *
	 * @param array $atts
	 */
	public static function get_template( $atts ) {
		sp_get_template( 'staff-gallery.php', $atts, '', trailingslashit( SP_STAFF_DIRECTORIES_DIR ) . 'templates/' );
	}
}

new SP_Shortcode_Staff_Gallery();
