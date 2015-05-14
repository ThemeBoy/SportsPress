<?php
/*
Plugin Name: SportsPress: More
Plugin URI: http://themeboy.com/
Description: Adds more to the SportsPress admin menu.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.8
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_More' ) ) :

/**
 * Main SportsPress_More Class
 *
 * @class SportsPress_More
 * @version	1.8
 */
class SportsPress_More {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_action( 'admin_menu', array( $this, 'admin_menu' ), 30 );
		add_action( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_EXTEND_VERSION' ) )
			define( 'SP_EXTEND_VERSION', '1.8' );

		if ( !defined( 'SP_EXTEND_URL' ) )
			define( 'SP_EXTEND_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_EXTEND_DIR' ) )
			define( 'SP_EXTEND_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add menu item
	 */
	public function admin_menu() {
		add_submenu_page( 'sportspress', __( 'More', 'sportspress' ), __( 'More' . '&hellip;', 'sportspress' ), 'manage_sportspress', 'sportspress-more', array( $this, 'page' ) );
	}

	/**
	 * The documentation page content
	 */
	public function page() {
		?>
		<iframe src="//localhost/themeboy" frameborder="0" height="0" width="100%" id="sp-more-iframe" onload="document.getElementById(this.id).height=document.getElementById(this.id).contentWindow.document.body.scrollHeight;" scrolling="no"><?php _e( 'This feature requires inline frames. You have iframes disabled or your browser does not support them.', 'sportspress' ); ?></iframe>
		<?php
	}

	/**
	 * Add screen ids
	 */
	public function screen_ids( $ids = array() ) {
		$ids[] = 'sportspress_page_sportspress-more';
		return $ids;
	}
}

endif;

new SportsPress_More();