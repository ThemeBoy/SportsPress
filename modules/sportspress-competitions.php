<?php
/*
Plugin Name: SportsPress Competitions
Plugin URI: http://themeboy.com/
Description: Add competitions to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.5.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Competitions' ) ) :

/**
 * Main SportsPress Competitions Class
 *
 * @class SportsPress_Competitions
 * @version	2.5.3
 */
class SportsPress_Competitions {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		add_action( 'sportspress_after_register_post_type', array( $this, 'register_post_type' ) );
		//add_action( 'admin_menu', array( $this, 'admin_menu' ), 22 );
		//add_action( 'parent_file', array( $this, 'parent_file' ) );
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );
		add_action( 'sportspress_create_rest_routes', array( $this, 'create_rest_routes' ) );
		add_action( 'sportspress_register_rest_fields', array( $this, 'register_rest_fields' ) );

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		//add_filter( 'sportspress_menu_items', array( $this, 'add_menu_item' ) );
		add_filter( 'sportspress_glance_items', array( $this, 'add_glance_item' ) );
		add_filter( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_primary_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_post_type_hierarchy', array( $this, 'add_to_hierarchy' ) );
		//add_filter( 'sportspress_get_settings_pages', array( $this, 'add_settings_page' ) );
		add_filter( 'sportspress_competition_templates', array( $this, 'templates' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_COMPETITIONS_VERSION' ) )
			define( 'SP_COMPETITIONS_VERSION', '2.5.3' );

		if ( !defined( 'SP_COMPETITIONS_URL' ) )
			define( 'SP_COMPETITIONS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_COMPETITIONS_DIR' ) )
			define( 'SP_COMPETITIONS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register competitions post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_competition',
			apply_filters( 'sportspress_register_post_type_competition',
				array(
					'labels' => array(
						'name' 					=> __( 'Competitions', 'sportspress' ),
						'singular_name' 		=> __( 'Competition', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Competition', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Competition', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View Competition', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
						'featured_image'		=> __( 'Logo', 'sportspress' ),
 						'set_featured_image' 	=> __( 'Select Logo', 'sportspress' ),
 						'remove_featured_image' => __( 'Remove Logo', 'sportspress' ),
 						'use_featured_image' 	=> __( 'Select Logo', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_competition',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> true,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_competition_slug', 'competition' ) ),
					'supports' 				=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'menu_icon' 			=> 'dashicons-shield',
					//'show_in_menu' 			=> 'admin.php?page=sportspress',
					//'show_in_admin_bar' 	=> true,
					'show_in_rest' 			=> true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base' 			=> 'competitions',
				)
			)
		);
	}

	/**
	 * Add menu item TO DELETE
	 */
	/*public function admin_menu() {
		add_submenu_page( 'sportspress', __( 'Competitions', 'sportspress' ), __( 'Competitions', 'sportspress' ), 'manage_sportspress', 'edit.php?post_type=sp_competition');
	}*/

	public function parent_file( $parent_file ) {
		if ( 'sportspress' == $parent_file )
			return $parent_file;

		global $current_screen;

		$post_type = $current_screen->post_type;

		if ( 'sp_competition' == $post_type )
			$parent_file = 'sportspress';

		return $parent_file;
	}

	/**
	 * Conditonally load the class and functions only needed when viewing this post type.
	 */
	public function include_post_type_handler() {
		include_once( SP()->plugin_path() . '/includes/admin/post-types/class-sp-admin-cpt-competition.php' );
	}

	/**
	 * Remove meta boxes.
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'sp_seasondiv', 'sp_competition', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_competition', 'side' );
	}

	/**
	 * Create REST API routes.
	 */
	public function create_rest_routes() {
		$controller = new SP_REST_Posts_Controller( 'sp_competition' );
		$controller->register_routes();
	}

	/**
	 * Register REST API fields.
	 */
	public function register_rest_fields() {
		register_rest_field( 'sp_competition',
			'data',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_arrays',
				'schema'          => array(
					'description'     => __( 'Competition', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
	}

	/**
	 * Add meta boxes.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_competition'] = array(
			'shortcode' => array(
					'title' => __( 'Shortcode', 'sportspress' ),
					'output' => 'SP_Meta_Box_Competition_Shortcode::output',
					'context' => 'side',
					'priority' => 'default',
				),
			'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Competition_Details::save',
					'output' => 'SP_Meta_Box_Competition_Details::output',
					'context' => 'side',
					'priority' => 'default',
				),
		);
		return $meta_boxes;
	}

	/**
	 * Add settings page TO DELETE
	 */
	/*public function add_settings_page( $settings = array() ) {
		$settings[] = include( SP()->plugin_path() . '/includes/admin/settings/class-sp-settings-competitions.php' );
		return $settings;
	}*/

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Competitions', 'sportspress' ),
		) );
	}

	/**
	 * Add menu item TO DELETE
	 */
	/*public function add_menu_item( $items ) {
		$items[] = 'edit.php?post_type=sp_competition';
		return $items;
	}*/

	/**
	 * Add glance item
	 */
	public function add_glance_item( $items ) {
		$items[] = 'sp_competition';
		return $items;
	}

	/**
	 * Add screen ids.
	 *
	 * @return array
	 */
	public function screen_ids( $ids ) {
		return array_merge( $ids, array(
			'sp_competition',
			'edit-sp_competition',
		) );
	}

	public static function add_post_type( $post_types = array() ) {
		$post_types[] = 'sp_competition';
		return $post_types;
	}

	public static function add_to_hierarchy( $hierarchy = array() ) {
		$hierarchy['sp_competition'] = array();
		return $hierarchy;
	}

	/**
	 * Add templates to competition layout.
	 *
	 * @return array
	 */
	public function templates( $templates = array() ) {
		$templates['table'] = array(
			'title' => __( 'League Table', 'sportspress' ),
			'option' => 'sportspress_competition_show_table',
			'action' => 'sportspress_output_league_table',
			'default' => 'yes',
		);
		$templates['players'] = array(
			'title' => __( 'Players List', 'sportspress' ),
			'option' => 'sportspress_competition_show_players',
			'action' => 'sportspress_output_player_list',
			'default' => 'yes',
		);
		/*$templates['calendar'] = array(
			'title' => __( 'Calendar', 'sportspress' ),
			'option' => 'sportspress_competition_show_calendar',
			'action' => 'sportspress_output_calendar',
			'default' => 'yes',
		);*/
		$templates['events'] = array(
			'title' => __( 'Events', 'sportspress' ),
			'option' => 'sportspress_competition_show_events',
			'action' => 'sportspress_output_competition_events',
			'default' => 'yes',
		);
		
		return $templates;
	}
}

endif;

//if ( get_option( 'sportspress_load_competition_module', 'yes' ) == 'yes' ) {
	new SportsPress_Competitions();
//}
