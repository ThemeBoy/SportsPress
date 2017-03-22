<?php
/*
Plugin Name: SportsPress League Tables
Plugin URI: http://themeboy.com/
Description: Add league tables to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_League_Tables' ) ) :

/**
 * Main SportsPress League Tables Class
 *
 * @class SportsPress_League_Tables
 * @version	2.3
 */
class SportsPress_League_Tables {

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
		add_filter( 'sportspress_team_settings', array( $this, 'add_settings' ) );
		add_filter( 'sportspress_after_team_template', array( $this, 'add_team_template' ), 30 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_LEAGUE_TABLES_VERSION' ) )
			define( 'SP_LEAGUE_TABLES_VERSION', '2.3' );

		if ( !defined( 'SP_LEAGUE_TABLES_URL' ) )
			define( 'SP_LEAGUE_TABLES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_LEAGUE_TABLES_DIR' ) )
			define( 'SP_LEAGUE_TABLES_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register league tables post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_table',
			apply_filters( 'sportspress_register_post_type_table',
				array(
					'labels' => array(
						'name' 					=> __( 'League Tables', 'sportspress' ),
						'singular_name' 		=> __( 'League Table', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New League Table', 'sportspress' ),
						'edit_item' 			=> __( 'Edit League Table', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View League Table', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_table',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_table_slug', 'table' ) ),
					'supports' 				=> array( 'title', 'editor', 'page-attributes', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'show_in_menu' 			=> 'edit.php?post_type=sp_team',
					'show_in_admin_bar' 	=> true,
					'show_in_rest' 			=> true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base' 			=> 'tables',
				)
			)
		);
	}

	/**
	 * Remove meta boxes.
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'sp_seasondiv', 'sp_table', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_table', 'side' );
	}

	/**
	 * Conditonally load the class and functions only needed when viewing this post type.
	 */
	public function include_post_type_handler() {
		include_once( SP()->plugin_path() . '/includes/admin/post-types/class-sp-admin-cpt-table.php' );
	}

	/**
	 * Add widgets.
	 *
	 * @return array
	 */
	public function include_widgets() {
		include_once( SP()->plugin_path() . '/includes/widgets/class-sp-widget-league-table.php' );
	}

	/**
	 * Create REST API routes.
	 */
	public function create_rest_routes() {
		$controller = new SP_REST_Posts_Controller( 'sp_table' );
		$controller->register_routes();
	}

	/**
	 * Register REST API fields.
	 */
	public function register_rest_fields() {
		register_rest_field( 'sp_table',
			'data',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_arrays',
				'schema'          => array(
					'description'     => __( 'League Table', 'sportspress' ),
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
		if ( 'yes' == get_option( 'sportspress_team_column_editing', 'no' ) ) {
			$meta_boxes['sp_team']['columns'] = array(
				'title' => __( 'Table Columns', 'sportspress' ),
				'output' => 'SP_Meta_Box_Team_Columns::output',
				'save' => 'SP_Meta_Box_Team_Columns::save',
				'context' => 'normal',
				'priority' => 'high',
			);
		}
		$meta_boxes['sp_team']['tables'] = array(
			'title' => __( 'League Tables', 'sportspress' ),
			'output' => 'SP_Meta_Box_Team_Tables::output',
			'save' => 'SP_Meta_Box_Team_Tables::save',
			'context' => 'normal',
			'priority' => 'high',
		);
		$meta_boxes['sp_table'] = array(
			'mode' => array(
				'title' => __( 'Mode', 'sportspress' ),
				'save' => 'SP_Meta_Box_Table_Mode::save',
				'output' => 'SP_Meta_Box_Table_Mode::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'shortcode' => array(
				'title' => __( 'Shortcode', 'sportspress' ),
				'output' => 'SP_Meta_Box_Table_Shortcode::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'details' => array(
				'title' => __( 'Details', 'sportspress' ),
				'save' => 'SP_Meta_Box_Table_Details::save',
				'output' => 'SP_Meta_Box_Table_Details::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'data' => array(
				'title' => __( 'League Table', 'sportspress' ),
				'save' => 'SP_Meta_Box_Table_Data::save',
				'output' => 'SP_Meta_Box_Table_Data::output',
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
		$shortcodes['table'] = array( 'table' );
		return $shortcodes;
	}

	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		return array_merge( $settings,
			array(
				array( 'title' => __( 'League Tables', 'sportspress' ), 'type' => 'title', 'id' => 'table_options' ),
			),

			apply_filters( 'sportspress_table_options', array(
				array(
					'title'     => __( 'Title', 'sportspress' ),
					'desc' 		=> __( 'Display title', 'sportspress' ),
					'id' 		=> 'sportspress_table_show_title',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_table_show_logos',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Pagination', 'sportspress' ),
					'desc' 		=> __( 'Paginate', 'sportspress' ),
					'id' 		=> 'sportspress_table_paginated',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
				
				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_table_rows',
					'class' 	=> 'small-text',
					'default'	=> '10',
					'desc' 		=> __( 'teams', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
				
				array(
					'title' 	=> __( 'Form', 'sportspress' ),
					'id' 		=> 'sportspress_form_limit',
					'class' 	=> 'small-text',
					'default'	=> '5',
					'desc' 		=> __( 'events', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),

				array(
					'title'     => __( 'Pos', 'sportspress' ),
					'desc' 		=> __( 'Always increment', 'sportspress' ),
					'id' 		=> 'sportspress_table_increment',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Tiebreaker', 'sportspress' ),
					'id'        => 'sportspress_table_tiebreaker',
					'default'   => 'none',
					'type'      => 'select',
					'options'   => array(
						'none' => __( 'None', 'sportspress' ),
						'h2h' => __( 'Head to head', 'sportspress' ),
					),
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'table_options' ),
			)
		);
	}

	/**
	 * Add team template.
	 *
	 * @return array
	 */
	public function add_team_template( $templates ) {
		return array_merge( $templates, array(
			'tables' => array(
				'title' => __( 'League Tables', 'sportspress' ),
				'label' => __( 'League Table', 'sportspress' ),
				'option' => 'sportspress_team_show_tables',
				'action' => 'sportspress_output_team_tables',
				'default' => 'yes',
			),
		) );
	}
}

endif;

if ( get_option( 'sportspress_load_league_tables_module', 'yes' ) == 'yes' ) {
	new SportsPress_League_Tables();

	/**
	 * Create alias of SP_League_Table class for REST API.
	 * Note: class_alias is not supported in PHP < 5.3 so extend the original class instead.
	*/
	class SP_Table extends SP_League_Table {}
}
