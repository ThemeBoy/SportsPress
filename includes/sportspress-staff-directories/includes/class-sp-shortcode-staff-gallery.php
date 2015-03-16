<?php
/**
 * Staff Gallery Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress_Staff_Directories
 * @version     1.7
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
	public static function init() {
		add_shortcode( 'staff_gallery', __CLASS__ . '::output' );
	}

	/**
	 * Output the staff gallery shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		ob_start();

		echo '<div class="sportspress">';
		sp_get_template( 'staff-gallery.php', $atts, '', SP_STAFF_DIRECTORIES_DIR . 'templates/' );
		echo '</div>';

		return ob_get_clean();
	}

	public static function locate_template( $template = null, $template_name = null, $template_path = null ) {
		if ( ! $template_path && $template_name == 'staff-gallery' ) {
			return SP_STAFF_DIRECTORIES_DIR . '/templates/staff-gallery.php';
		}
	}
}

new SP_Shortcode_Staff_Gallery();
