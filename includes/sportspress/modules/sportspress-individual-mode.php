<?php
/*
Plugin Name: SportsPress Individual Mode
Plugin URI: http://themeboy.com/
Description: Modify SportsPress to work with individual (player vs player) sports.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.9
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Individual_Mode' ) ) :

/**
 * Main SportsPress Individual Mode Class
 *
 * @class SportsPress_Individual_Mode
 * @version	1.9
 */
class SportsPress_Individual_Mode {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );
		add_action( 'sportspress_process_sp_event_meta', array( $this, 'save_player_meta' ), 99, 2 );

		// Filters
		add_filter( 'gettext', array( $this, 'gettext' ), 99, 3 );
		add_filter( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		add_filter( 'sportspress_register_post_type_team', array( $this, 'hide_post_type' ), 99 );
		add_filter( 'sportspress_register_post_type_table', array( $this, 'move_table_post_type' ), 99 );
		add_filter( 'sportspress_settings_tabs_array', array( $this, 'remove_team_settings_tab' ), 99 );
		add_filter( 'sportspress_get_settings_pages', array( $this, 'remove_team_settings' ), 99 );
		add_filter( 'sportspress_performance_options', array( $this, 'remove_performance_options' ), 99 );
		add_filter( 'sportspress_player_options', array( $this, 'add_player_options' ), 99 );
		add_filter( 'sportspress_player_settings', array( $this, 'add_player_settings' ), 99 );
		add_filter( 'sportspress_next_steps', array( $this, 'remove_team_step' ), 99 );
		add_filter( 'sportspress_modules', array( $this, 'rearrange_modules' ), 99 );
		add_filter( 'sportspress_glance_items', array( $this, 'remove_glance_item' ), 99 );
		add_filter( 'sportspress_player_admin_columns', array( $this, 'remove_team_column' ), 99 );
		add_filter( 'sportspress_list_admin_columns', array( $this, 'remove_team_column' ), 99 );
		add_filter( 'sportspress_staff_admin_columns', array( $this, 'remove_team_column' ), 99 );
		add_filter( 'sportspress_directory_admin_columns', array( $this, 'remove_team_column' ), 99 );
		add_filter( 'sportspress_importers', array( $this, 'remove_teams_importer' ), 99 );
		add_filter( 'sportspress_permalink_slugs', array( $this, 'remove_team_permalink_slug' ), 99 );
		add_filter( 'sportspress_primary_post_types', array( $this, 'primary_post_types' ) );
		add_filter( 'sportspress_post_type_hierarchy', array( $this, 'post_type_hierarchy' ) );
		add_filter( 'sportspress_event_team_tabs', '__return_false' );
		add_filter( 'sportspress_player_team_statistics', '__return_false' );
		add_filter( 'sportspress_player_teams', '__return_false' );
		add_filter( 'sportspress_staff_teams', '__return_false' );
		add_filter( 'sportspress_list_team_selector', '__return_false' );
		add_filter( 'pre_option_sportspress_event_split_players_by_team', array( $this, 'no' ) );
		add_filter( 'pre_option_sportspress_event_show_status', array( $this, 'no' ) );
		add_filter( 'pre_option_sportspress_link_teams', array( $this, 'link_players' ) );
		add_filter( 'sportspress_has_teams', '__return_false' );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_INDIVIDUAL_MODE_VERSION' ) )
			define( 'SP_INDIVIDUAL_MODE_VERSION', '1.9' );

		if ( !defined( 'SP_INDIVIDUAL_MODE_URL' ) )
			define( 'SP_INDIVIDUAL_MODE_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_INDIVIDUAL_MODE_DIR' ) )
			define( 'SP_INDIVIDUAL_MODE_DIR', plugin_dir_path( __FILE__ ) );
	}

	/** 
	 * Return no.
	 */
	public function no() {
		return 'no';
	}

	/**
	 * Return link players instead of teams.
	 */
	public function link_players() {
		return get_option( 'sportspress_link_players', 'yes' );
	}

	/**
	 * Save teams as players in events.
	 */
	public function save_player_meta( $post_id, $post ) {
		if ( isset( $_POST['sp_team'] ) && is_array( $_POST['sp_team'] ) ) {
			$players = array();
			foreach ( $_POST['sp_team'] as $player ) {
				$players[] = array( 0, $player );
			}
			sp_update_post_meta_recursive( $post_id, 'sp_player', $players );
		}
	}

	/** 
	 * Modify all team-related strings for players.
	 */
	public function gettext( $translated_text, $untranslated_text, $domain ) {
		if ( 'sportspress' !== $domain ) return $translated_text;

		switch ( $untranslated_text ) {
			case 'Teams':
				return __( 'Players', 'sportspress' );
				break;
			case 'Team':
				return __( 'Player', 'sportspress' );
				break;
			case 'teams':
				return __( 'players', 'sportspress' );
				break;
		}
		
		return $translated_text;
	}

	/**
	 * Modify all team post type queries for players.
	 */
	public function pre_get_posts( $query ) {
		if ( 'sp_team' !== $query->get( 'post_type' ) ) return $query;

		$query->set( 'post_type', 'sp_player' );

		return $query;
	}

	/**
	 * Remove meta boxes.
	 */
	public function remove_meta_boxes( $meta_boxes ) {
		unset( $meta_boxes['sp_event']['performance'] );
		return $meta_boxes;
	}

	/**
	 * Hide post types.
	 */
	public function hide_post_type( $args ) {
		return array_merge( $args, array(
			'public' => false,
			'exclude_from_search' => true,
			'publicly_queryable' => false,
			'show_ui' => false,
			'show_in_nav_menus' => false,
			'show_in_menu' => false,
			'show_in_admin_bar' => false,
			'can_export' => false,
		) );
	}

	/**
	 * Move league table post type under players.
	 */
	public function move_table_post_type( $args ) {
		return array_merge( $args, array(
			'show_in_menu' => 'edit.php?post_type=sp_player',
		) );
	}

	/**
	 * Remove team settings tab.
	 */
	public function remove_team_settings_tab( $tabs ) {
		unset( $tabs['teams'] );
		return $tabs;
	}

	/**
	 * Remove team settings section.
	 */
	public function remove_team_settings( $settings ) {
		foreach ( $settings as $index => $section ) {
			if ( is_a( $section, 'SP_Settings_Teams' ) ) {
				unset( $settings[ $index ] );
			}
		}
		return $settings;
	}

	/**
	 * Remove option to split players by team.
	 */
	public function remove_performance_options( $options ) {
		foreach ( $options as $index => $option ) {
			if ( 'sportspress_event_split_players_by_team' == sp_array_value( $option, 'id' ) ) {
				unset( $options[ $index ] );
			}
		}
		return $options;
	}

	/**
	 * Add options from teams to players tab.
	 */
	public function add_player_options( $options ) {
		return apply_filters( 'sportspress_team_options', $options );
	}

	/**
	 * Add settings from teams to players tab.
	 */
	public function add_player_settings( $settings ) {
		return apply_filters( 'sportspress_team_settings', $settings );
	}

	/**
	 * Remove team step from welcome screen.
	 */
	public function remove_team_step( $steps ) {
		unset( $steps['teams'] );
		return $steps;
	}

	/**
	 * Rearrange modules.
	 */
	public function rearrange_modules( $modules ) {
		$modules['player_staff'] = array_merge(
			sp_array_value( $modules, 'team', array() ),
			sp_array_value( $modules, 'player_staff', array() )
		);
		unset( $modules['team'] );
		unset( $modules['player_staff']['team_colors'] );
		return $modules;
	}

	/**
	 * Remove teams glance item.
	 */
	public function remove_glance_item( $items ) {
		if ( ( $index = array_search ( 'sp_team', $items ) ) !== false ) {
			unset( $items[ $index ] );
		}
		return $items;
	}

	/**
	 * Remove team column from player list admin.
	 */
	public function remove_team_column( $columns ) {
		unset( $columns['sp_team'] );
		return $columns;
	}

	/**
	 * Remove the teams csv importer.
	 */
	public function remove_teams_importer( $importers ) {
		unset( $importers['sp_team_csv'] );
		return $importers;
	}

	/**
	 * Remove the team permalink slug setting.
	 */
	public function remove_team_permalink_slug( $slugs ) {
		if ( ( $index = array_search ( array( 'team', __( 'Teams', 'sportspress' ) ), $slugs ) ) !== false ) {
			unset( $slugs[ $index ] );
		}
		return $slugs;
	}

	/**
	 * Remove the team primary post type.
	 */
	public function primary_post_types( $post_types ) {
		if ( ( $key = array_search( 'sp_team', $post_types ) ) !== false ) {
			unset( $post_types[ $key ] );
		}
		return $post_types;
	}

	/**
	 * Adjust post type hierarchy.
	 */
	public function post_type_hierarchy( $hierarchy ) {
		$hierarchy['sp_player'] = array_merge( sp_array_value( $hierarchy, 'sp_player', array() ),  sp_array_value( $hierarchy, 'sp_team', array() ) );
		unset( $hierarchy['sp_team'] );
		return $hierarchy;
	}

	/**
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @access public
	 * @return void
	 */
	public function menu_highlight() {
		global $typenow, $parent_file, $submenu_file;
		if ( 'sp_table' == $typenow ) {
			$parent_file = 'edit.php?post_type=sp_player';
			$submenu_file = 'edit.php?post_type=sp_table';
		}
	}
}

endif;

if ( get_option( 'sportspress_load_individual_mode_module', 'no' ) == 'yes' ) {
	new SportsPress_Individual_Mode();
}
