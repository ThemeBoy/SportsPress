<?php
/*
Plugin Name: SportsPress League Tables
Plugin URI: http://themeboy.com/
Description: Add league tables to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.8.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_League_Tables' ) ) :

/**
 * Main SportsPress League Tables Class
 *
 * @class SportsPress_League_Tables
 * @version	1.8.3
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

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'sportspress_shortcodes', array( $this, 'add_shortcodes' ) );
		add_filter( 'sportspress_team_settings', array( $this, 'add_settings' ) );
		add_filter( 'sportspress_team_options', array( $this, 'add_options' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_LEAGUE_TABLES_VERSION' ) )
			define( 'SP_LEAGUE_TABLES_VERSION', '1.8.3' );

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
					'supports' 				=> array( 'title', 'page-attributes', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'show_in_menu' 			=> 'edit.php?post_type=sp_team',
					'show_in_admin_bar' 	=> true,
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
			'editor' => array(
				'title' => __( 'Description', 'sportspress' ),
				'output' => 'SP_Meta_Box_Table_Editor::output',
				'context' => 'normal',
				'priority' => 'low',
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
					'title'     => __( 'Pos', 'sportspress' ),
					'desc' 		=> __( 'Always increment', 'sportspress' ),
					'id' 		=> 'sportspress_table_increment',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'table_options' ),
			)
		);
	}

	/**
	 * Add options.
	 *
	 * @return array
	 */
	public function add_options( $options ) {
		return array_merge( $options,
			array(
				array(
					'title'     => __( 'Table Columns', 'sportspress' ),
					'desc' 		=> __( 'Enable column editing', 'sportspress' ),
					'id' 		=> 'sportspress_team_column_editing',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),
			)
		);
	}
}

endif;

if ( get_option( 'sportspress_load_league_tables_module', 'yes' ) == 'yes' ) {
	new SportsPress_League_Tables();
}
