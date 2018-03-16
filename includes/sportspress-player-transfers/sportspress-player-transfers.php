<?php
/*
Plugin Name: SportsPress Player Transfers
Plugin URI: http://tboy.co/pro
Description: Add a Player Transfers to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Player_Transfers' ) ) :

/**
 * Main SportsPress Player Transfers Class
 *
 * @class SportsPress_Player_Transfers
 * @version	2.6.0
 *
 */
class SportsPress_Player_Transfers {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handlers' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ), 9 );
		add_filter( 'sportspress_player_templates', array( $this, 'player_templates' ) );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_PLAYER_TRANSFERS_VERSION' ) )
			define( 'SP_PLAYER_TRANSFERS_VERSION', '2.6.0' );

		if ( !defined( 'SP_PLAYER_TRANSFERS_URL' ) )
			define( 'SP_PLAYER_TRANSFERS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_PLAYER_TRANSFERS_DIR' ) )
			define( 'SP_PLAYER_TRANSFERS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Add meta boxes.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_player']['transfers'] = array(
			'title' => __( 'Player Transfers', 'sportspress' ),
			'output' => 'SP_Meta_Box_Player_Transfers::output',
			'save' => 'SP_Meta_Box_Player_Transfers::save',
			'context' => 'normal',
			'priority' => 'default',
		);
		return $meta_boxes;
	}
	
	/**
	 * Conditionally load classes and functions only needed when viewing the post type.
	 */
	public function include_post_type_handlers() {
		include_once( 'includes/class-sp-meta-box-player-transfers.php' );
	}
	
	/**
	 * Add templates to player layout.
	 *
	 * @return array
	 */
	public function player_templates( $templates = array() ) {
		$templates['transfers'] = array(
			'title' => __( 'Player Transfers', 'sportspress' ),
			'option' => 'sportspress_player_show_transfers',
			'action' => array( $this, 'output_transfers' ),
			'default' => 'no',
		);
		return $templates;
	}
	
	/**
	 * Output Player Transfers.
	 *
	 * @access public
	 * @return void
	 */
	public function output_transfers() {
		sp_get_template( 'player-transfers.php', array(), '', SP_PLAYER_TRANSFERS_DIR . 'templates/' );
	}
	
	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'sp_player', 'edit-sp_player' ) ) ) {
		    wp_enqueue_style( 'jquery-ui-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css' ); 
			wp_enqueue_style( 'sportspress-admin-datepicker-styles', SP()->plugin_url() . '/assets/css/datepicker.css', array( 'jquery-ui-style' ), SP_VERSION );
		}
	}
	
}
endif;

if ( get_option( 'sportspress_load_player_transfers_module', 'no' ) == 'yes' ) {
	new SportsPress_Player_Transfers();
}
