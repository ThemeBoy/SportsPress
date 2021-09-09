<?php
/*
Plugin Name: SportsPress Trophies
Plugin URI: https://tboy.co/pro
Description: Add trophies feature to SportsPress.
Author: ThemeBoy
Author URI: https://themeboy.com/
Version: 2.8.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Trophies' ) ) :

/**
 * Main SportsPress Trophies Class
 *
 * @class SportsPress_Trophies
 * @version	2.8.0
 */
class SportsPress_Trophies {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();
		
		// Include required files
		$this->includes();

		// Actions
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'admin_init', array( $this, 'check_version' ), 5 );
		add_action( 'sportspress_single_trophy_content', array( $this, 'output_trophy' ) );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handlers' ) );

		// Filters
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_team_settings', array( $this, 'add_settings' ) );
		add_filter( 'sportspress_team_templates', array( $this, 'add_team_template' ) );
		add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TROPHIES_VERSION' ) )
			define( 'SP_TROPHIES_VERSION', '2.8.0' );

		if ( !defined( 'SP_TROPHIES_URL' ) )
			define( 'SP_TROPHIES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TROPHIES_DIR' ) )
			define( 'SP_TROPHIES_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Include required files used in admin and on the frontend.
	 */
	private function includes() {
		include_once( 'includes/sp-trophies-functions.php' );
		if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
			$this->frontend_includes();
		}
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		include_once( 'includes/class-sp-trophy-template-loader.php' );
		include_once( 'includes/class-sp-shortcode-trophies.php' );
	}
	
	/**
	 * Conditonally load classes and functions only needed when viewing the post type.
	 */
	public function include_post_type_handlers() {
		include_once( 'includes/class-sp-trophy-meta-boxes.php' );
		include_once( 'includes/class-sp-admin-cpt-trophy.php' );
	}

	/**
	 * Register league tables post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_trophy',
			apply_filters( 'sportspress_register_post_type_trophy',
				array(
					'labels' => array(
						'name' 					=> __( 'Trophies', 'sportspress' ),
						'singular_name' 		=> __( 'Trophy', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Trophy', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Trophy', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View Trophy', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
						'featured_image'		=> __( 'Trophy Logo', 'sportspress' ),
 						'set_featured_image' 	=> __( 'Select Trophy Logo', 'sportspress' ),
 						'remove_featured_image' => __( 'Remove Trophy Logo', 'sportspress' ),
 						'use_featured_image' 	=> __( 'Select Trophy Logo', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_trophy',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_trophy_slug', 'trophy' ) ),
					'supports' 				=> array( 'title', 'editor', 'page-attributes', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'show_in_menu' 			=> 'edit.php?post_type=sp_team',
					'show_in_admin_bar' 	=> true,
					'show_in_rest' 			=> true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base' 			=> 'trophies',
				)
			)
		);
	}
	
	/**
	 * Add post type
	 */
	public static function add_post_type( $post_types = array() ) {
		$post_types[] = 'sp_trophy';
		return $post_types;
	}
	
	/**
	 * check_version function.
	 *
	 * @access public
	 * @return void
	 */
	public function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && get_option( 'sportspress_trophies_version' ) != SP_TROPHIES_VERSION ) {
			$this->install();
		}
	}
	
	/**
	 * Install
	 */
	public function install() {
		
		// Queue upgrades
		$current_version = get_option( 'sportspress_trophies_version', null );

		// Update version
		update_option( 'sportspress_trophies_version', SP_TROPHIES_VERSION );

		// Flush rules after install
		flush_rewrite_rules();
	}
	
	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		return array_merge( $settings,
			array(
				array( 'title' => __( 'Trophies', 'sportspress' ), 'type' => 'title', 'id' => 'trophy_options' ),
			),

			apply_filters( 'sportspress_trophy_options', array(
				array(
					'title'     => __( 'Title', 'sportspress' ),
					'desc' 		=> __( 'Display title', 'sportspress' ),
					'id' 		=> 'sportspress_trophy_show_title',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_trophy_show_logos',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Pagination', 'sportspress' ),
					'desc' 		=> __( 'Paginate', 'sportspress' ),
					'id' 		=> 'sportspress_trophy_paginated',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
				
				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_trophy_rows',
					'class' 	=> 'small-text',
					'default'	=> '10',
					'desc' 		=> __( 'seasons', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
				
				array(
					'title'     => __( 'Seasons Order', 'sportspress' ),
					'id'        => 'sportspress_trophy_order',
					'default'   => 'desc',
					'type'      => 'select',
					'options'   => array(
						'desc'  => __( 'DESC', 'sportspress' ),
						'asc'   => __( 'ASC', 'sportspress' ),
					),
				),
				
				array(
					'title' 	=> __( 'Template Format', 'sportspress' ),
					'id' 		=> 'sportspress_trophy_format',
					'default'	=> 'seasons',
					'type' 		=> 'radio',
					'options'   => array(
						'seasons'	  => __( 'Seasons list', 'sportspress' ),
						'teams'		  => __( 'Teams list', 'sportspress' ),
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
	public function add_team_template( $templates = array() ) {
		$templates['trophies'] = array(
			'title' => __( 'Trophies', 'sportspress' ),
			'label' => __( 'Trophies', 'sportspress' ),
			'option' => 'sportspress_team_show_trophies',
			'action' => array( $this, 'team_template_output' ),
			'default' => 'yes',
		);
		
		return $templates;
	}
	
	/**
	 * Output team trophies template.
	 *
	 * @access public
	 * @return void
	 */
	public function team_template_output() {
		sp_get_template( 'team-trophies.php', array(), '', SP_TROPHIES_DIR . 'templates/' );
	}
	
	/**
	 * Output the main trophy template.
	 *
	 * @access public
	 * @return void
	 */
	public static function output_trophy() {
		sp_get_template( 'trophy-data.php', array(), '', SP_TROPHIES_DIR . 'templates/' );
	}
	
	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-trophies'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_TROPHIES_URL ) . 'css/sportspress-trophies.css',
			'deps'    => 'sportspress-general',
			'version' => SP_TROPHIES_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}
}

endif;

if ( get_option( 'sportspress_load_trophies_module', 'yes' ) == 'yes' ) {
	new SportsPress_Trophies();
}
