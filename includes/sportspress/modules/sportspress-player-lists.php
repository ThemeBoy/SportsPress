<?php
/*
Plugin Name: SportsPress Player Lists
Plugin URI: http://themeboy.com/
Description: Add player lists to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.1.4
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Player_Lists' ) ) :

/**
 * Main SportsPress Player Lists Class
 *
 * @class SportsPress_Player_Lists
 * @version	2.1.4
 */
class SportsPress_Player_Lists {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );
		add_action( 'sportspress_widgets', array( $this, 'include_widgets' ) );
		add_action( 'sportspress_create_rest_routes', array( $this, 'create_rest_routes' ) );
		add_action( 'sportspress_register_rest_fields', array( $this, 'register_rest_fields' ) );

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'sportspress_shortcodes', array( $this, 'add_shortcodes' ) );
		add_filter( 'sportspress_player_settings', array( $this, 'add_settings' ) );
		add_filter( 'sportspress_after_team_template', array( $this, 'add_team_template' ), 20 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_PLAYER_LISTS_VERSION' ) )
			define( 'SP_PLAYER_LISTS_VERSION', '2.1.4' );

		if ( !defined( 'SP_PLAYER_LISTS_URL' ) )
			define( 'SP_PLAYER_LISTS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_PLAYER_LISTS_DIR' ) )
			define( 'SP_PLAYER_LISTS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register league tables post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_list',
			apply_filters( 'sportspress_register_post_type_list',
				array(
					'labels' => array(
						'name' 					=> __( 'Player Lists', 'sportspress' ),
						'singular_name' 		=> __( 'Player List', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Player List', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Player List', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View Player List', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_list',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_list_slug', 'list' ) ),
					'supports' 				=> array( 'title', 'editor', 'page-attributes', 'author', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'show_in_menu' 			=> 'edit.php?post_type=sp_player',
					'show_in_admin_bar' 	=> true,
					'show_in_rest' 			=> true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base' 			=> 'lists',
				)
			)
		);
	}

	/**
	 * Remove meta boxes.
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'sp_positiondiv', 'sp_list', 'side' );
		remove_meta_box( 'sp_seasondiv', 'sp_list', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_list', 'side' );
	}

	/**
	 * Conditonally load the class and functions only needed when viewing this post type.
	 */
	public function include_post_type_handler() {
		include_once( SP()->plugin_path() . '/includes/admin/post-types/class-sp-admin-cpt-list.php' );
	}

	/**
	 * Add widgets.
	 *
	 * @return array
	 */
	public function include_widgets() {
		include_once( SP()->plugin_path() . '/includes/widgets/class-sp-widget-player-list.php' );
		include_once( SP()->plugin_path() . '/includes/widgets/class-sp-widget-player-gallery.php' );
	}

	/**
	 * Create REST API routes.
	 */
	public function create_rest_routes() {
		$controller = new SP_REST_Posts_Controller( 'sp_list' );
		$controller->register_routes();
	}

	/**
	 * Register REST API fields.
	 */
	public function register_rest_fields() {
		register_rest_field( 'sp_list',
			'format',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta',
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Layout', 'sportspress' ),
					'type'            => 'string',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_list',
			'data',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_arrays',
				'schema'          => array(
					'description'     => __( 'Player List', 'sportspress' ),
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
		$meta_boxes['sp_team']['lists'] = array(
			'title' => __( 'Player Lists', 'sportspress' ),
			'output' => 'SP_Meta_Box_Team_Lists::output',
			'save' => 'SP_Meta_Box_Team_Lists::save',
			'context' => 'normal',
			'priority' => 'high',
		);
		$meta_boxes['sp_list'] = array(
			'shortcode' => array(
				'title' => __( 'Shortcode', 'sportspress' ),
				'output' => 'SP_Meta_Box_List_Shortcode::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'format' => array(
				'title' => __( 'Layout', 'sportspress' ),
				'save' => 'SP_Meta_Box_List_Format::save',
				'output' => 'SP_Meta_Box_List_Format::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'columns' => array(
				'title' => __( 'Columns', 'sportspress' ),
				'save' => 'SP_Meta_Box_List_Columns::save',
				'output' => 'SP_Meta_Box_List_Columns::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'details' => array(
				'title' => __( 'Details', 'sportspress' ),
				'save' => 'SP_Meta_Box_List_Details::save',
				'output' => 'SP_Meta_Box_List_Details::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'data' => array(
				'title' => __( 'Player List', 'sportspress' ),
				'save' => 'SP_Meta_Box_List_Data::save',
				'output' => 'SP_Meta_Box_List_Data::output',
				'context' => 'normal',
				'priority' => 'high',
			),
		);
		return $meta_boxes;
	}

	/**
	 * Add shortcodes.
	 *
	 * @return array
	 */
	public function add_shortcodes( $shortcodes ) {
		$shortcodes['player'][] = 'list';
		$shortcodes['player'][] = 'gallery';
		return $shortcodes;
	}

	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		return array_merge( $settings, array_merge(
			array(
				array( 'title' => __( 'Player Lists', 'sportspress' ), 'type' => 'title', 'id' => 'list_options' ),
			),

			apply_filters( 'sportspress_player_list_options', array(
				array(
					'title'     => __( 'Title', 'sportspress' ),
					'desc' 		=> __( 'Display title', 'sportspress' ),
					'id' 		=> 'sportspress_list_show_title',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Players', 'sportspress' ),
					'desc' 		=> __( 'Display photos', 'sportspress' ),
					'id' 		=> 'sportspress_list_show_photos',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'start',
				),

				array(
					'desc' 		=> __( 'Display national flags', 'sportspress' ),
					'id' 		=> 'sportspress_list_show_flags',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'end',
				),

				array(
					'title'     => __( 'Pagination', 'sportspress' ),
					'desc' 		=> __( 'Paginate', 'sportspress' ),
					'id' 		=> 'sportspress_list_paginated',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
				
				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_list_rows',
					'class' 	=> 'small-text',
					'default'	=> '10',
					'desc' 		=> __( 'players', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'list_options' ),
			)
		) );
	}

	/**
	 * Add team template.
	 *
	 * @return array
	 */
	public function add_team_template( $templates ) {
		return array_merge( $templates, array(
			'lists' => array(
				'title' => __( 'Player Lists', 'sportspress' ),
				'label' => __( 'Players', 'sportspress' ),
				'option' => 'sportspress_team_show_lists',
				'action' => 'sportspress_output_team_lists',
				'default' => 'yes',
			),
		) );
	}
}

endif;

if ( get_option( 'sportspress_load_player_lists_module', 'yes' ) == 'yes' ) {
	new SportsPress_Player_Lists();

	/**
	 * Create alias of SP_Player_List class for REST API.
	 * Note: class_alias is not supported in PHP < 5.3 so extend the original class instead.
	*/
	class SP_List extends SP_Player_List {}
}
