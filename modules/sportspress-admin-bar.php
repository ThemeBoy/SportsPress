<?php
/**
 * Admin Bar
 *
 * @author    ThemeBoy
 * @category  Modules
 * @package   SportsPress/Modules
 * @version   2.7.9
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SportsPress_Admin_Bar' ) ) :

	/**
	 * Main SportsPress Admin Bar Class
	 *
	 * @class SportsPress_Admin_Bar
	 * @version 2.2
	 */
	class SportsPress_Admin_Bar {

		/**
		 * Constructor
		 */
		public function __construct() {
			// Define constants
			$this->define_constants();

			add_action( 'admin_bar_menu', array( $this, 'add_node' ), 40 );
		}

		/**
		 * Define constants.
		 */
		private function define_constants() {
			if ( ! defined( 'SP_ADMIN_BAR_VERSION' ) ) {
				define( 'SP_ADMIN_BAR_VERSION', '2.2' );
			}

			if ( ! defined( 'SP_ADMIN_BAR_URL' ) ) {
				define( 'SP_ADMIN_BAR_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( ! defined( 'SP_ADMIN_BAR_DIR' ) ) {
				define( 'SP_ADMIN_BAR_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

		/**
		 * Add node to admin bar menu.
		 */
		public function add_node( $wp_admin_bar ) {
			if ( is_admin() ) {
				return;
			}

			$args = array(
				'id'    => 'sportspress',
				'title' => esc_attr__( 'SportsPress', 'sportspress' ),
				'href'  => add_query_arg( 'page', 'sportspress', admin_url( 'admin.php' ) ),
			);
			$wp_admin_bar->add_node( $args );
		}
	}

endif;

new SportsPress_Admin_Bar();
