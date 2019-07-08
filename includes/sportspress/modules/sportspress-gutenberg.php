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
		//add_filter( 'block_categories', array( $this, 'add_category' ), 10, 2 );
		//add_action( 'enqueue_block_editor_assets', array( $this, 'load_blocks' ) );
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

	/**
	 * Add SportsPress category to Gutenberg.
	 */
	function add_category( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'sportspress',
					'title' => __( 'SportsPress', 'sportspress' ),
				),
			)
		);
	}

	/**
	 * Load Gutenberg blocks.
	 */
	function load_blocks() {
	  wp_enqueue_script( 'sp-block-event-calendar', plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/js/blocks/event-calendar.js', array( 'wp-blocks', 'wp-editor' ), true );

		$strings = apply_filters( 'sportspress_localized_strings', array(
			'event_calendar' => __( 'Event Calendar', 'sportspress' ),
			'properties' => __( 'Properties', 'sportspress' ),
			'title' => __( 'Title', 'sportspress' ),
			'select_calendar' => sprintf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ),
			'all' => __( 'All', 'sportspress' ),
		) );

		$posts = array(
			'events' => (array) get_posts(
				array(
					'post_type' => 'sp_event',
					'posts_per_page' => -1,
				)
			),
		);

		wp_localize_script( 'sp-block-event-calendar', 'strings', $strings );
		wp_localize_script( 'sp-block-event-calendar', 'posts', $posts );
	}
}

endif;

new SportsPress_Gutenberg();
