<?php
/*
Plugin Name: SportsPress Highlight Places
Plugin URI: http://tboy.co/pro
Description: Highlight Places in League Tables.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Highlight_Places' ) ) :

/**
 * Main SportsPress Highlight Places Class
 *
 * @class SportsPress_Highlight_Places
 * @version	2.7
 *
 */
class SportsPress_Highlight_Places {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );

		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_HIGHLIGHT_PLACES_VERSION' ) )
			define( 'SP_HIGHLIGHT_PLACES_VERSION', '2.7.0' );

		if ( !defined( 'SP_HIGHLIGHT_PLACES_URL' ) )
			define( 'SP_HIGHLIGHT_PLACES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_HIGHLIGHT_PLACES_DIR' ) )
			define( 'SP_HIGHLIGHT_PLACES_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		wp_register_script( 'sportspress-admin-colorpicker', SP()->plugin_url() . '/assets/js/admin/colorpicker.js', array( 'jquery', 'wp-color-picker', 'iris' ), SP_VERSION, true );

		if ( in_array( $screen->id, array( 'sp_table' ) ) ) {
			wp_enqueue_script( 'sportspress-admin-colorpicker' );
			wp_enqueue_script( 'sportspress-highlight-places-admin', SP_HIGHLIGHT_PLACES_URL .'js/admin.js', array( 'jquery' ), SP_HIGHLIGHT_PLACES_VERSION, true );
			wp_enqueue_style( 'sportspress-highlight-places-admin', SP_HIGHLIGHT_PLACES_URL . 'css/admin.css', array(), SP_HIGHLIGHT_PLACES_VERSION );
		}
	}
	
	/**
	 * Add meta boxes.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_table']['highlight_places'] = array(
					'title' => __( 'Highlight Places', 'sportspress' ),
					'save' => 'SP_Meta_Box_Table_Highlight_Places::save',
					'output' => 'SP_Meta_Box_Table_Highlight_Places::output',
					'context' => 'normal',
					'priority' => 'default',
				);
		return $meta_boxes;
	}
	
	/**
	 * Conditonally load classes and functions only needed when viewing the post type.
	 */
	public function include_post_type_handler() {
		include( 'includes/class-sp-meta-box-table-highlight.php' );
	}

}

endif;

new SportsPress_Highlight_Places();
