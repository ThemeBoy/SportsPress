<?php
/*
Plugin Name: SportsPress Tournaments
Plugin URI: http://tboy.co/pro
Description: Adds tournament groups and brackets to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.7.4
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Tournaments' ) ) :

/**
 * Main SportsPress Tournaments Class
 *
 * @class SportsPress_Tournaments
 * @version	1.7.4
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
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handlers' ) );
		add_filter( 'sportspress_permalink_slugs', array( $this, 'add_permalink_slug' ) );
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_screen_ids', array( $this, 'add_screen_ids' ) );
		add_action( 'sportspress_single_tournament_content', array( $this, 'output_tournament_winner' ), 0 );
		add_action( 'sportspress_single_tournament_content', array( $this, 'output_tournament_bracket' ), 10 );
		add_action( 'sportspress_after_single_tournament', 'sportspress_output_br_tag', 100 );
		add_filter( 'sportspress_league_object_types', array( $this, 'add_taxonomy_object' ) );
		add_filter( 'sportspress_season_object_types', array( $this, 'add_taxonomy_object' ) );
		add_filter( 'sportspress_formats', array( $this, 'add_formats' ) );
		add_filter( 'sportspress_competitive_event_formats', array( $this, 'competitive_event_formats' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
	    add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter( 'sportspress_menu_items', array( $this, 'add_menu_item' ), 30 );
		add_filter( 'sportspress_event_settings', array( $this, 'add_options' ) );
		add_filter( 'sportspress_team_access_post_types', array( $this, 'add_post_type' ) );

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
			define( 'SP_TOURNAMENTS_VERSION', '1.7.4' );

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
		include_once( 'includes/class-sp-shortcode-tournament-winner.php' );
		include_once( 'includes/class-sp-shortcode-tournament-bracket.php' );
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
					'supports' 				=> array( 'title', 'author', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'show_in_menu' 			=> 'edit.php?post_type=sp_event',
					'show_in_admin_bar' 	=> true,
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
	 * Output the tournament winner.
	 *
	 * @access public
	 * @return void
	 */
	public static function output_tournament_winner() {
		$id = get_the_ID();
		sp_get_template( 'tournament-winner.php', array( 'id' => $id ), '', SP_TOURNAMENTS_DIR . 'templates/' );
	}

	/**
	 * Output the tournament bracket.
	 *
	 * @access public
	 * @return void
	 */
	public static function output_tournament_bracket() {
		$id = get_the_ID();
		sp_get_template( 'tournament-bracket.php', array( 'id' => $id ), '', SP_TOURNAMENTS_DIR . 'templates/' );
	}

	/**
	 * Add object to taxonomy.
	 *
	 * @return array
	 */
	public function add_taxonomy_object( $object_types ) {
		$object_types[] = 'sp_tournament';
		return $object_types;
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
	 * Add options to settings page.
	 *
	 * @return array
	 */
	public function add_options( $settings ) {
		return array_merge( $settings,
			array(
				array( 'title' => __( 'Tournaments', 'sportspress' ), 'type' => 'title', 'id' => 'tournament_options' ),
			),

			apply_filters( 'sportspress_post_type_options', array(
				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_tournament_show_logos',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Details', 'sportspress' ),
					'desc' 		=> __( 'Display venue', 'sportspress' ),
					'id' 		=> 'sportspress_tournament_show_venue',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup' => 'start',
				),

				array(
					'desc' 		=> __( 'Display winner', 'sportspress' ),
					'id' 		=> 'sportspress_tournament_show_winner',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup' => 'end',
				),
					
				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_tournament_rounds',
					'class' 	=> 'small-text',
					'default'	=> '6',
					'desc' 		=> __( 'rounds', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
			), 'tournament' ),

			array(
				array( 'type' => 'sectionend', 'id' => 'tournament_options' ),
			)
		);
	}


	/** 
	 * Add formats.
	 */
	public function add_formats( $formats ) {
		$formats['tournament'] = array(
			'bracket' => __( 'Default', 'sportspress' ),
			'center' => __( 'Center', 'sportspress' ),
		);

		$formats['event']['tournament'] = __( 'Tournament', 'sportspress' );

		return $formats;
	}

	/**
	 * Add format to competitive event formats. 
	 */
	public function competitive_event_formats( $formats = array() ) {
		$formats[] = 'tournament';
		return $formats;
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Winner', 'sportspress' ),
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
		    wp_enqueue_style( 'jquery-ui-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css' ); 
			wp_enqueue_style( 'sportspress-admin-datepicker-styles', SP()->plugin_url() . '/assets/css/datepicker.css', array( 'jquery-ui-style' ), SP_VERSION );
		}
	}

	/**
	 * Frontend CSS
	 */
	public static function frontend_css( $colors ) {
		if ( current_theme_supports( 'sportspress' ) )
			return;

		if ( isset( $colors['highlight'] ) ) {
			echo '.sp-tournament-bracket .sp-event{border-color:' . $colors['highlight'] . ' !important}';
			echo '.sp-tournament-bracket .sp-team .sp-team-name:before{border-left-color:' . $colors['highlight'] . ' !important}';
		}
		if ( isset( $colors['text'] ) ) {
			echo '.sp-tournament-bracket .sp-event .sp-event-main, .sp-tournament-bracket .sp-team .sp-team-name{color:' . $colors['text'] . ' !important}';
		}
		if ( isset( $colors['heading'] ) ) {
			echo '.sp-tournament-bracket .sp-team .sp-team-name.sp-heading{color:' . $colors['heading'] . ' !important}';
		}
	}
}

endif;

if ( get_option( 'sportspress_load_tournaments_module', 'yes' ) == 'yes' ) {
	new SportsPress_Tournaments();
}
