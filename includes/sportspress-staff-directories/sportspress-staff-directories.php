<?php
/*
Plugin Name: SportsPress Staff Directories
Plugin URI: http://tboy.co/pro
Description: Adds staff directories to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.15
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Staff_Directories' ) ) :

/**
 * Main SportsPress Staff Directories Class
 *
 * @class SportsPress_Staff_Directories
 * @version	2.6.15
 */
class SportsPress_Staff_Directories {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Include required ajax files
		if ( defined( 'DOING_AJAX' ) ) {
			$this->ajax_includes();
		}

		// Hooks
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'widget_text', array( $this, 'widget_text' ), 9 );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handlers' ) );
		add_filter( 'sportspress_permalink_slugs', array( $this, 'add_permalink_slug' ) );
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_post_type_hierarchy', array( $this, 'add_to_hierarchy' ) );
		add_filter( 'sportspress_screen_ids', array( $this, 'add_screen_ids' ) );
		add_filter( 'sportspress_admin_datepicker_screen_ids', array( $this, 'add_datepicker_screen_ids' ) );
		add_action( 'sportspress_single_directory_content', array( $this, 'output_directory' ), 10 );
		add_action( 'sportspress_after_single_directory', 'sportspress_output_br_tag', 100 );
		add_filter( 'sportspress_league_object_types', array( $this, 'add_taxonomy_object' ) );
		add_filter( 'sportspress_season_object_types', array( $this, 'add_taxonomy_object' ) );
		add_filter( 'sportspress_role_object_types', array( $this, 'add_taxonomy_object' ) );
		add_filter( 'sportspress_formats', array( $this, 'add_formats' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		add_filter( 'sportspress_staff_settings', array( $this, 'add_options' ) );
		add_filter( 'sportspress_staff_templates', array( $this, 'staff_templates' ) );
		add_filter( 'sportspress_team_templates', array( $this, 'team_templates' ) );
		add_action( 'sportspress_widgets', array( $this, 'widgets' ) );
		add_action( 'sportspress_register_post_type_staff', array( $this, 'add_staff_attributes_support' ) );
		add_filter( 'sportspress_shortcodes', array( $this, 'add_shortcodes' ) );
		add_filter( 'sportspress_tinymce_strings', array( $this, 'add_tinymce_strings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_filter( 'sportspress_team_access_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_setup_pages', array( $this, 'setup_pages' ) );
		
		add_action( 'sportspress_create_rest_routes', array( $this, 'create_rest_routes' ) );
		add_action( 'sportspress_register_rest_fields', array( $this, 'register_rest_fields' ) );

		if ( defined( 'SP_PRO_PLUGIN_FILE' ) )
			register_activation_hook( SP_PRO_PLUGIN_FILE, array( $this, 'install' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_STAFF_DIRECTORIES_VERSION' ) )
			define( 'SP_STAFF_DIRECTORIES_VERSION', '2.6.15' );

		if ( !defined( 'SP_STAFF_DIRECTORIES_URL' ) )
			define( 'SP_STAFF_DIRECTORIES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_STAFF_DIRECTORIES_DIR' ) )
			define( 'SP_STAFF_DIRECTORIES_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required files.
	*/
	private function includes() {
		include_once( 'includes/class-sp-staff-directory.php' );

		if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
			$this->frontend_includes();
		}
	}

	/**
	 * Include required ajax files.
	 */
	public function ajax_includes() {
		include_once( 'includes/class-sp-directory-ajax.php' );
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		include_once( 'includes/class-sp-directory-template-loader.php' );
		include_once( 'includes/class-sp-shortcode-staff-list.php' );
		include_once( 'includes/class-sp-shortcode-staff-gallery.php' );
	}

	/**
	 * Init plugin when WordPress Initialises.
	 */
	public function init() {
		// Register post type
		$this->register_post_type();
	}

	public function register_post_type() {
		register_post_type( 'sp_directory',
			apply_filters( 'sportspress_register_post_type_directory',
				array(
					'labels' => array(
						'name' 					=> __( 'Directories', 'sportspress' ),
						'singular_name' 		=> __( 'Staff Directory', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Staff Directory', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Staff Directory', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_directory',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_directory_slug', 'directory' ) ),
					'supports' 				=> array( 'title', 'editor', 'author', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'show_in_menu' 			=> 'edit.php?post_type=sp_staff',
					'show_in_admin_bar' 	=> true,
					'show_in_rest' 			=> true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base' 			=> 'directories',
				)
			)
		);
	}

	/**
	 * Add post type
	 */
	public static function add_post_type( $post_types = array() ) {
		$post_types[] = 'sp_directory';
		return $post_types;
	}

	/**
	 * Add to hierarchy
	 */
	public static function add_to_hierarchy( $hierarchy = array() ) {
		$hierarchy['sp_staff'][] = 'sp_directory';
		return $hierarchy;
	}

	/**
	 * Add screen ids
	 */
	public static function add_screen_ids( $screen_ids = array() ) {
		$screen_ids[] = 'edit-sp_directory';
		$screen_ids[] = 'sp_directory';
		return $screen_ids;
	}

	/**
	 * Add datepicker screen ids
	 */
	public static function add_datepicker_screen_ids( $screen_ids = array() ) {
		$screen_ids[] = 'sp_directory';
		return $screen_ids;
	}

	/**
	 * Add pages to setup wizard.
	 */
	public function setup_pages( $pages = array() ) {
    $pages['sp_directory'] = __( 'Organize and display staff in list and gallery layouts.', 'sportspress' );
    return $pages;
  }

	/**
	 * Output the staff directory.
	 *
	 * @access public
	 * @subpackage	Directory
	 * @return void
	 */
	public static function output_directory() {
        $id = get_the_ID();
        $format = get_post_meta( $id, 'sp_format', true );
        if ( array_key_exists( $format, SP()->formats->directory ) )
			sp_get_template( 'staff-' . $format . '.php', array( 'id' => $id ), '', SP_STAFF_DIRECTORIES_DIR . 'templates/' );
        else
			sp_get_template( 'staff-list.php', array( 'id' => $id ), '', SP_STAFF_DIRECTORIES_DIR . 'templates/' );
	}

	/**
	 * Do shortcode in widgets
	 */
	function widget_text( $content ) {
		if ( ! preg_match( '/\[[\r\n\t ]*(staff(_|-)(list|gallery))?[\r\n\t ].*?\]/', $content ) )
			return $content;

		$content = do_shortcode( $content );

		return $content;
	}

	/**
	 * Add object to taxonomy.
	 *
	 * @return array
	 */
	public function add_taxonomy_object( $object_types ) {
		$object_types[] = 'sp_directory';
		return $object_types;
	}

	/**
	 * Conditonally load classes and functions only needed when viewing the post type.
	 */
	public function include_post_type_handlers() {
		include_once( 'includes/class-sp-staff-directory-meta-boxes.php' );
		include_once( 'includes/class-sp-admin-cpt-directory.php' );
	}

	/**
	 * Add slug to permalink options.
	 *
	 * @return array
	 */
	public function add_permalink_slug( $slugs ) {
		$slugs[] = array( 'directory', __( 'Staff Directories', 'sportspress' ) );
		return $slugs;
	}

	/** 
	 * Add formats.
	 */
	public function add_formats( $formats ) {
		$formats['directory'] = array(
			'list' => __( 'List', 'sportspress' ),
			'gallery' => __( 'Gallery', 'sportspress' ),
		);
		return $formats;
	}

	/**
	 * Add options to settings page.
	 *
	 * @return array
	 */
	public function add_options( $settings ) {
		array_splice( $settings, -1, 0, array(
			array(
				'title'     => __( 'Contact Info', 'sportspress' ),
				'desc' 		=> __( 'Link phone', 'sportspress' ),
				'id' 		=> 'sportspress_staff_link_phone',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'start',
			),

			array(
				'desc' 		=> __( 'Link email', 'sportspress' ),
				'id' 		=> 'sportspress_staff_link_email',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),
		) );

		return array_merge( $settings,
			array(
				array( 'title' => __( 'Staff Directories', 'sportspress' ), 'type' => 'title', 'id' => 'directory_options' ),
			),

			apply_filters( 'sportspress_post_type_options', array(
				array(
					'title'     => __( 'Pagination', 'sportspress' ),
					'desc' 		=> __( 'Paginate', 'sportspress' ),
					'id' 		=> 'sportspress_directory_paginated',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
				
				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_directory_rows',
					'class' 	=> 'small-text',
					'default'	=> '10',
					'desc' 		=> __( 'staff', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
			), 'directory' ),

			array(
				array( 'type' => 'sectionend', 'id' => 'directory_options' ),
			)
		);
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Contact Info', 'sportspress' ),
			__( 'Email', 'sportspress' ),
			__( 'Job', 'sportspress' ),
			__( 'Name', 'sportspress' ),
			__( 'Phone', 'sportspress' ),
			__( 'View all staff', 'sportspress' ),
		) );
	}

	/**
	 * Add templates to staff layout.
	 *
	 * @return array
	 */
	public function staff_templates( $templates = array() ) {
		$templates['contacts'] = array(
			'title' => __( 'Contact Info', 'sportspress' ),
			'option' => 'sportspress_staff_show_contacts',
			'action' => array( $this, 'output_staff_contacts' ),
			'default' => 'yes',
		);
		
		return $templates;
	}

	/**
	 * Add templates to team layout.
	 *
	 * @return array
	 */
	public function team_templates( $templates = array() ) {
		$templates['directories'] = array(
			'title' => __( 'Directories', 'sportspress' ),
			'label' => __( 'Staff', 'sportspress' ),
			'option' => 'sportspress_team_show_directories',
			'action' => array( $this, 'output_team_directories' ),
			'default' => 'yes',
		);
		
		return $templates;
	}

	/**
	 * Install
	 */
	public function install() {
		$this->add_capabilities();
		$this->register_post_type();

		// Queue upgrades
		$current_version = get_option( 'sportspress_staff_directories_version', null );

		// Update version
		update_option( 'sportspress_staff_directories_version', SP_STAFF_DIRECTORIES_VERSION );

		// Flush rules after install
		flush_rewrite_rules();
	}

	/**
	 * Add capabilities
	 */
	public function add_capabilities() {
		global $wp_roles;

		if ( class_exists( 'WP_Roles' ) ):
			if ( ! isset( $wp_roles ) ):
				$wp_roles = new WP_Roles();
			endif;
		endif;

		if ( is_object( $wp_roles ) ):
			$capability_type = 'sp_directory';
			$capabilities = array(
				"edit_{$capability_type}",
				"read_{$capability_type}",
				"edit_{$capability_type}s",
				"edit_published_{$capability_type}s",
				"assign_{$capability_type}_terms",
			);

			foreach ( $capabilities as $cap ):
				$wp_roles->add_cap( 'sp_team_manager', $cap );
			endforeach;

			$capabilities = array_merge( $capabilities, array(
				"delete_{$capability_type}",
				"edit_others_{$capability_type}s",
				"publish_{$capability_type}s",
				"read_private_{$capability_type}s",
				"delete_{$capability_type}s",
				"delete_private_{$capability_type}s",
				"delete_published_{$capability_type}s",
				"delete_others_{$capability_type}s",
				"edit_private_{$capability_type}s",
				"manage_{$capability_type}_terms",
				"edit_{$capability_type}_terms",
				"delete_{$capability_type}_terms",
			));

			foreach ( $capabilities as $cap ):
				$wp_roles->add_cap( 'sp_league_manager', $cap );
				$wp_roles->add_cap( 'administrator', $cap );
			endforeach;
		endif;
	}

	/**
	 * Add page attribute support to staff post type
	 */
	public function add_staff_attributes_support( $arr ) {
		$arr['supports'][] = 'page-attributes';
		return $arr;
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'sp_directory', 'edit-sp_directory' ) ) ) {
			wp_enqueue_script( 'sportspress-staff-directories-admin', SP_STAFF_DIRECTORIES_URL . 'js/admin.js', array( 'jquery' ), SP_STAFF_DIRECTORIES_VERSION );
		}
	}

	/**
	 * Output the staff contact info.
	 *
	 * @access public
	 * @subpackage	Staff
	 * @return void
	 */
	public function output_staff_contacts() {
		sp_get_template( 'staff-contacts.php', array(), '', SP_STAFF_DIRECTORIES_DIR . 'templates/' );
	}

	/**
	 * Output the team staff directories.
	 *
	 * @access public
	 * @subpackage	Team
	 * @return void
	 */
	public function output_team_directories() {
		sp_get_template( 'team-directories.php', array(), '', SP_STAFF_DIRECTORIES_DIR . 'templates/' );
	}

	/**
	 * Register widgets
	 */
	public static function widgets() {
		include_once( 'includes/class-sp-widget-staff-list.php' );
		include_once( 'includes/class-sp-widget-staff-gallery.php' );
	}

	/**
	 * Add shortcodes to TinyMCE
	 */
	public static function add_shortcodes( $shortcodes ) {
		$shortcodes['staff'][] = 'list';
		$shortcodes['staff'][] = 'gallery';
		return $shortcodes;
	}

	/**
	 * Add strings to TinyMCE
	 */
	public static function add_tinymce_strings( $strings ) {
		$strings['staff'] = __( 'Staff', 'sportspress' );
		$strings['directory'] = __( 'Directory', 'sportspress' );
		return $strings;
	}

	/**
	 * Create REST API routes.
	 */
	public function create_rest_routes() {
		$controller = new SP_REST_Posts_Controller( 'sp_directory' );
		$controller->register_routes();
	}

	/**
	 * Register REST API fields.
	 */
	public function register_rest_fields() {
		register_rest_field( 'sp_directory',
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
		
		register_rest_field( 'sp_directory',
			'columns',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta',
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Columns', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_directory',
			'teams',
			array(
				'get_callback'    => 'SP_REST_API::get_post_meta_recursive',
				'update_callback' => 'SP_REST_API::update_post_meta',
				'schema'          => array(
					'description'     => __( 'Team', 'sportspress' ),
					'type'            => 'integer',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
		
		register_rest_field( 'sp_directory',
			'data',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'schema'          => array(
					'description'     => __( 'Staff Directory', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
	}
}

endif;

if ( get_option( 'sportspress_load_staff_directories_module', 'yes' ) == 'yes' ) {
	new SportsPress_Staff_Directories();

	/**
	 * Create alias of SP_Staff_Directory class for REST API.
	 * Note: class_alias is not supported in PHP < 5.3 so extend the original class instead.
	*/
	class SP_Directory extends SP_Staff_Directory {}
}
