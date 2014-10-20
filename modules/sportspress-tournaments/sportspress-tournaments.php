<?php
/*
Plugin Name: SportsPress Tournaments
Plugin URI: http://sportspresspro.com/
Description: Adds tournament groups and brackets to SportsPress.
Author: ThemeBoy
Author URI: http://sportspresspro.com
Version: 1.4
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main SportsPress Tournaments Class
 *
 * @class SportsPress_Tournaments
 * @version	1.4
 */
class SportsPress_Tournaments {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Hooks
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'widget_text', array( $this, 'widget_text' ), 9 );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handlers' ) );
		add_filter( 'sportspress_permalink_slugs', array( $this, 'add_permalink_slug' ) );
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_screen_ids', array( $this, 'add_screen_ids' ) );
		add_action( 'sportspress_single_tournament_content', array( $this, 'output_tournament' ), 10 );
		add_action( 'sportspress_after_single_tournament', 'sportspress_output_br_tag', 100 );
	    add_filter( 'sportspress_get_settings_pages', array( $this, 'add_settings_page' ) );
		add_filter( 'sportspress_formats', array( $this, 'add_formats' ) );
	    add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		add_filter( 'sportspress_staff_settings', array( $this, 'add_options' ) );
		add_action( 'sportspress_widgets', array( $this, 'widgets' ) );
		add_filter( 'sportspress_menu_items', array( $this, 'add_menu_item' ), 30 );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'sportspress_frontend_css', array( $this, 'frontend_css' ) );

		if ( defined( 'SP_PRO_PLUGIN_FILE' ) )
			register_activation_hook( SP_PRO_PLUGIN_FILE, array( $this, 'install' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TOURNAMENTS_VERSION' ) )
			define( 'SP_TOURNAMENTS_VERSION', '1.4' );

		if ( !defined( 'SP_TOURNAMENTS_URL' ) )
			define( 'SP_TOURNAMENTS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TOURNAMENTS_DIR' ) )
			define( 'SP_TOURNAMENTS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required files.
	*/
	private function includes() {
		include_once( 'includes/class-sp-tournament.php' );

		if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
			$this->frontend_includes();
		}
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		include_once( 'includes/class-sp-tournament-template-loader.php' );
		//include_once( 'includes/class-sp-shortcode-staff-list.php' );
		//include_once( 'includes/class-sp-shortcode-staff-gallery.php' );
	}

	/**
	 * Init plugin when WordPress Initialises.
	 */
	public function init() {
		// Register post type
		$this->register_post_type();
	}

	public function register_post_type() {
		register_post_type( 'sp_tournament',
			apply_filters( 'sportspress_register_post_type_tournament',
				array(
					'labels' => array(
						'name' 					=> __( 'Tournaments', 'sportspress' ),
						'singular_name' 		=> __( 'Tournament', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Tournament', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Tournament', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_tournament',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_tournament_slug', 'tournament' ) ),
					'supports' 				=> array( 'title', 'author', 'thumbnail', 'comments' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'menu_icon' 			=> 'dashicons-chart-bar',
				)
			)
		);
	}

	/**
	 * Add post type
	 */
	public static function add_post_type( $post_types = array() ) {
		$post_types[] = 'sp_tournament';
		return $post_types;
	}
	/**
	 * Add screen ids
	 */
	public static function add_screen_ids( $screen_ids = array() ) {
		$screen_ids[] = 'edit-sp_tournament';
		$screen_ids[] = 'sp_tournament';
		return $screen_ids;
	}

	/**
	 * Output the tournament.
	 *
	 * @access public
	 * @subpackage	Directory
	 * @return void
	 */
	public static function output_tournament() {
        $id = get_the_ID();
		sp_get_template( 'tournament-bracket.php', array( 'id' => $id ), 'tournament-bracket', SP_TOURNAMENTS_DIR . 'templates/' );
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
	 * Conditonally load classes and functions only needed when viewing the post type.
	 */
	public function include_post_type_handlers() {
		include_once( 'includes/class-sp-tournament-meta-boxes.php' );
		include_once( 'includes/class-sp-admin-cpt-tournament.php' );
	}

	/**
	 * Add slug to permalink options.
	 *
	 * @return array
	 */
	public function add_permalink_slug( $slugs ) {
		$slugs[] = array( 'tournament', __( 'Tournaments', 'sportspress' ) );
		return $slugs;
	}

	/**
	 * Add settings page
	 */
	public function add_settings_page( $settings = array() ) {
		$settings[] = include( 'includes/class-sp-settings-tournaments.php' );
		return $settings;
	}


	/** 
	 * Add formats.
	 */
	public function add_formats( $formats ) {
		$formats['event']['tournament'] = __( 'Tournament', 'sportspress' );
		return $formats;
	}

	/**
	 * Add options to settings page.
	 *
	 * @return array
	 */
	public function add_options( $settings ) {
		array_splice( $settings, 2, 0, array(
			array(
				'title'     => __( 'Contact Info', 'sportspress' ),
				'desc' 		=> __( 'Link phone', 'sportspress' ),
				'id' 		=> 'sportspress_link_phone',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'start',
			),

			array(
				'desc' 		=> __( 'Link email', 'sportspress' ),
				'id' 		=> 'sportspress_link_email',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),
		) );

		return array_merge( $settings, array(
			array( 'title' => __( 'Tournament Brackets', 'sportspress' ), 'type' => 'title', 'id' => 'bracket_options' ),

			array(
				'title'     => __( 'Pagination', 'sportspress' ),
				'desc' 		=> __( 'Paginate', 'sportspress' ),
				'id' 		=> 'sportspress_bracket_paginated',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),
			
			array(
				'title' 	=> __( 'Limit', 'sportspress' ),
				'id' 		=> 'sportspress_bracket_rows',
				'class' 	=> 'small-text',
				'default'	=> '10',
				'desc' 		=> __( 'staff', 'sportspress' ),
				'type' 		=> 'number',
				'custom_attributes' => array(
					'min' 	=> 1,
					'step' 	=> 1
				),
			),

			array( 'type' => 'sectionend', 'id' => 'bracket_options' ),
		) );
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-tournaments'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_TOURNAMENTS_URL ) . 'css/sportspress-tournaments.css',
			'deps'    => 'sportspress-general',
			'version' => SP_TOURNAMENTS_VERSION,
			'media'   => 'all'
		);

		if ( is_rtl() ) {
			$styles['sportspress-tournaments-rtl'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', SP_TOURNAMENTS_URL ) . 'css/sportspress-tournaments-rtl.css',
				'deps'    => 'sportspress-tournaments',
				'version' => SP_TOURNAMENTS_VERSION,
				'media'   => 'all'
			);
		} else {
			$styles['sportspress-tournaments-ltr'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', SP_TOURNAMENTS_URL ) . 'css/sportspress-tournaments-ltr.css',
				'deps'    => 'sportspress-tournaments',
				'version' => SP_TOURNAMENTS_VERSION,
				'media'   => 'all'
			);
		}
		return $styles;
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Phone', 'sportspress' ),
			__( 'Email', 'sportspress' ),
			__( 'View all staff', 'sportspress' ),
		) );
	}

	/**
	 * Install
	 */
	public function install() {
		$this->add_capabilities();
		$this->register_post_type();

		// Update version
		update_option( 'sportspress_tournaments_version', SP_TOURNAMENTS_VERSION );

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
			$capability_type = 'sp_tournament';
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
	 * Add menu item
	 */
	public function add_menu_item( $items ) {
		$items[] = 'edit.php?post_type=sp_tournament';
		return $items;
	}

	/**
	 * Add taxonomy to event
	 */
	public function add_event_taxonomy( $taxonomies ) {
		$taxonomies = array_merge( array( 'sp_group' => 'sp_tournament' ), $taxonomies );
		return $taxonomies;
	}

	/**
	 * Register/queue frontend scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function load_scripts() {
		wp_enqueue_script( 'sportspress-tournaments', SP_TOURNAMENTS_URL .'js/sportspress-tournaments.js', array( 'jquery' ), time(), true );
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		wp_enqueue_style( 'sportspress-tournaments-admin', SP_TOURNAMENTS_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), time() );

		if ( in_array( $screen->id, array( 'sp_tournament', 'edit-sp_tournament' ) ) ) {
			wp_enqueue_script( 'sportspress-tournaments-admin', SP_TOURNAMENTS_URL . 'js/admin.js', array( 'jquery' ), SP_TOURNAMENTS_VERSION );
		}
	}

	/**
	 * Register widgets
	 */
	public static function widgets() {
		//include_once( 'includes/class-sp-widget-staff-list.php' );
		//include_once( 'includes/class-sp-widget-staff-gallery.php' );
	}

	public static function frontend_css( $colors ) {
		if ( isset( $colors['highlight'] ) ) {
			echo '.sp-tournament .sp-event{border-color:' . $colors['highlight'] . ' !important}';
			echo '.sp-tournament .sp-team .sp-team-name:before{border-left-color:' . $colors['highlight'] . ' !important}';
		}
		if ( isset( $colors['text'] ) ) {
			echo '.sp-tournament .sp-team .sp-team-name{color:' . $colors['text'] . ' !important}';
		}
		if ( isset( $colors['heading'] ) ) {
			echo '.sp-tournament .sp-team .sp-team-name.sp-heading{color:' . $colors['heading'] . ' !important}';
		}
	}
}

new SportsPress_Tournaments();
