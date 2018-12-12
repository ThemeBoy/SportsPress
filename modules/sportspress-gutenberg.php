<?php
/*
Plugin Name: SportsPress Gutenberg
Plugin URI: http://themeboy.com/
Description: Add Gutenberg support to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.6.13
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Gutenberg' ) ) :

/**
 * Main SportsPress Gutenberg Class
 *
 * @class SportsPress_Gutenberg
 * @version	2.6.13
 */
class SportsPress_Gutenberg {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_filter( 'gutenberg_can_edit_post_type', array( $this, 'can_edit_post_type' ), 10, 2 );
		add_filter( 'use_block_editor_for_post_type', array( $this, 'can_edit_post_type' ), 10, 2 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_GUTENBERG_VERSION' ) )
			define( 'SP_GUTENBERG_VERSION', '2.6.13' );

		if ( !defined( 'SP_GUTENBERG_URL' ) )
			define( 'SP_GUTENBERG_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_GUTENBERG_DIR' ) )
			define( 'SP_GUTENBERG_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Modify Gutenberg behavior for custom post types.
	 */
	function can_edit_post_type( $enabled, $post_type ) {
		return is_sp_post_type( $post_type ) ? false : $enabled;
	}
}

endif;

new SportsPress_Gutenberg();
