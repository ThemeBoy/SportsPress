<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Post types
 *
 * Registers post types and taxonomies
 *
 * @class       SP_Post_types
 * @version     2.7.1
 * @package     SportsPress/Classes
 * @category    Class
 * @author      ThemeBoy
 */
class SP_Post_types {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 10 );
		add_action( 'trashed_post', array( $this, 'delete_config_post' ) );
		add_filter( 'get_post_status', array( $this, 'display_scheduled_events' ), 10, 2 );
	}

	/**
	 * Register SportsPress taxonomies.
	 */
	public static function register_taxonomies() {
		do_action( 'sportspress_register_taxonomy' );

		$show = current_user_can( 'edit_sp_events' );

		if ( apply_filters( 'sportspress_has_leagues', true ) ) :
			$labels       = array(
				'name'              => esc_attr__( 'Leagues', 'sportspress' ),
				'singular_name'     => esc_attr__( 'League', 'sportspress' ),
				'all_items'         => esc_attr__( 'All', 'sportspress' ),
				'edit_item'         => esc_attr__( 'Edit League', 'sportspress' ),
				'view_item'         => esc_attr__( 'View', 'sportspress' ),
				'update_item'       => esc_attr__( 'Update', 'sportspress' ),
				'add_new_item'      => esc_attr__( 'Add New', 'sportspress' ),
				'new_item_name'     => esc_attr__( 'Name', 'sportspress' ),
				'parent_item'       => esc_attr__( 'Parent', 'sportspress' ),
				'parent_item_colon' => esc_attr__( 'Parent:', 'sportspress' ),
				'search_items'      => esc_attr__( 'Search', 'sportspress' ),
				'not_found'         => esc_attr__( 'No results found.', 'sportspress' ),
			);
			$args         = apply_filters(
				'sportspress_register_taxonomy_league',
				array(
					'label'                 => esc_attr__( 'Leagues', 'sportspress' ),
					'labels'                => $labels,
					'public'                => true,
					'show_ui'               => $show,
					'show_in_menu'          => $show,
					'show_in_nav_menus'     => false,
					'show_tagcloud'         => false,
					'hierarchical'          => true,
					'rewrite'               => array( 'slug' => get_option( 'sportspress_league_slug', 'league' ) ),
					'show_in_rest'          => true,
					'rest_controller_class' => 'SP_REST_Terms_Controller',
					'rest_base'             => 'leagues',
				)
			);
			$object_types = apply_filters( 'sportspress_league_object_types', array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) );
			register_taxonomy( 'sp_league', $object_types, $args );
			foreach ( $object_types as $object_type ) :
				register_taxonomy_for_object_type( 'sp_league', $object_type );
			endforeach;
		endif;

		if ( apply_filters( 'sportspress_has_seasons', true ) ) :
			$labels       = array(
				'name'              => esc_attr__( 'Seasons', 'sportspress' ),
				'singular_name'     => esc_attr__( 'Season', 'sportspress' ),
				'all_items'         => esc_attr__( 'All', 'sportspress' ),
				'edit_item'         => esc_attr__( 'Edit Season', 'sportspress' ),
				'view_item'         => esc_attr__( 'View', 'sportspress' ),
				'update_item'       => esc_attr__( 'Update', 'sportspress' ),
				'add_new_item'      => esc_attr__( 'Add New', 'sportspress' ),
				'new_item_name'     => esc_attr__( 'Name', 'sportspress' ),
				'parent_item'       => esc_attr__( 'Parent', 'sportspress' ),
				'parent_item_colon' => esc_attr__( 'Parent:', 'sportspress' ),
				'search_items'      => esc_attr__( 'Search', 'sportspress' ),
				'not_found'         => esc_attr__( 'No results found.', 'sportspress' ),
			);
			$args         = apply_filters(
				'sportspress_register_taxonomy_season',
				array(
					'label'                 => esc_attr__( 'Seasons', 'sportspress' ),
					'labels'                => $labels,
					'public'                => true,
					'show_ui'               => $show,
					'show_in_menu'          => $show,
					'show_in_nav_menus'     => false,
					'show_tagcloud'         => false,
					'hierarchical'          => true,
					'rewrite'               => array( 'slug' => get_option( 'sportspress_season_slug', 'season' ) ),
					'show_in_rest'          => true,
					'rest_controller_class' => 'SP_REST_Terms_Controller',
					'rest_base'             => 'seasons',
				)
			);
			$object_types = apply_filters( 'sportspress_season_object_types', array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) );
			register_taxonomy( 'sp_season', $object_types, $args );
			foreach ( $object_types as $object_type ) :
				register_taxonomy_for_object_type( 'sp_season', $object_type );
			endforeach;
		endif;

		if ( apply_filters( 'sportspress_has_venues', true ) ) :
			$labels       = array(
				'name'              => esc_attr__( 'Venues', 'sportspress' ),
				'singular_name'     => esc_attr__( 'Venue', 'sportspress' ),
				'all_items'         => esc_attr__( 'All', 'sportspress' ),
				'edit_item'         => esc_attr__( 'Edit Venue', 'sportspress' ),
				'view_item'         => esc_attr__( 'View', 'sportspress' ),
				'update_item'       => esc_attr__( 'Update', 'sportspress' ),
				'add_new_item'      => esc_attr__( 'Add New', 'sportspress' ),
				'new_item_name'     => esc_attr__( 'Name', 'sportspress' ),
				'parent_item'       => esc_attr__( 'Parent', 'sportspress' ),
				'parent_item_colon' => esc_attr__( 'Parent:', 'sportspress' ),
				'search_items'      => esc_attr__( 'Search', 'sportspress' ),
				'not_found'         => esc_attr__( 'No results found.', 'sportspress' ),
			);
			$args         = apply_filters(
				'sportspress_register_taxonomy_venue',
				array(
					'label'                 => esc_attr__( 'Venues', 'sportspress' ),
					'labels'                => $labels,
					'public'                => true,
					'show_ui'               => $show,
					'show_in_menu'          => $show,
					'show_in_nav_menus'     => false,
					'show_tagcloud'         => false,
					'hierarchical'          => true,
					'rewrite'               => array( 'slug' => get_option( 'sportspress_venue_slug', 'venue' ) ),
					'show_in_rest'          => true,
					'rest_controller_class' => 'SP_REST_Terms_Controller',
					'rest_base'             => 'venues',
				)
			);
			$object_types = apply_filters( 'sportspress_event_object_types', array( 'sp_event', 'sp_calendar', 'sp_team' ) );
			register_taxonomy( 'sp_venue', $object_types, $args );
			foreach ( $object_types as $object_type ) :
				register_taxonomy_for_object_type( 'sp_venue', $object_type );
			endforeach;
		endif;

		if ( apply_filters( 'sportspress_has_positions', true ) ) :
			$labels       = array(
				'name'              => esc_attr__( 'Positions', 'sportspress' ),
				'singular_name'     => esc_attr__( 'Position', 'sportspress' ),
				'all_items'         => esc_attr__( 'All', 'sportspress' ),
				'edit_item'         => esc_attr__( 'Edit Position', 'sportspress' ),
				'view_item'         => esc_attr__( 'View', 'sportspress' ),
				'update_item'       => esc_attr__( 'Update', 'sportspress' ),
				'add_new_item'      => esc_attr__( 'Add New', 'sportspress' ),
				'new_item_name'     => esc_attr__( 'Name', 'sportspress' ),
				'parent_item'       => esc_attr__( 'Parent', 'sportspress' ),
				'parent_item_colon' => esc_attr__( 'Parent:', 'sportspress' ),
				'search_items'      => esc_attr__( 'Search', 'sportspress' ),
				'not_found'         => esc_attr__( 'No results found.', 'sportspress' ),
			);
			$args         = apply_filters(
				'sportspress_register_taxonomy_position',
				array(
					'label'                 => esc_attr__( 'Positions', 'sportspress' ),
					'labels'                => $labels,
					'public'                => true,
					'show_ui'               => $show,
					'show_in_menu'          => $show,
					'show_in_nav_menus'     => false,
					'show_tagcloud'         => false,
					'hierarchical'          => true,
					'rewrite'               => array( 'slug' => get_option( 'sportspress_position_slug', 'position' ) ),
					'show_in_rest'          => true,
					'rest_controller_class' => 'SP_REST_Terms_Controller',
					'rest_base'             => 'positions',
				)
			);
			$object_types = apply_filters( 'sportspress_position_object_types', array( 'sp_player', 'sp_list' ) );
			register_taxonomy( 'sp_position', $object_types, $args );
			foreach ( $object_types as $object_type ) :
				register_taxonomy_for_object_type( 'sp_position', $object_type );
			endforeach;
		endif;

		if ( apply_filters( 'sportspress_has_roles', true ) ) :
			$labels       = array(
				'name'              => esc_attr__( 'Jobs', 'sportspress' ),
				'singular_name'     => esc_attr__( 'Job', 'sportspress' ),
				'all_items'         => esc_attr__( 'All', 'sportspress' ),
				'edit_item'         => esc_attr__( 'Edit Job', 'sportspress' ),
				'view_item'         => esc_attr__( 'View', 'sportspress' ),
				'update_item'       => esc_attr__( 'Update', 'sportspress' ),
				'add_new_item'      => esc_attr__( 'Add New', 'sportspress' ),
				'new_item_name'     => esc_attr__( 'Name', 'sportspress' ),
				'parent_item'       => esc_attr__( 'Parent', 'sportspress' ),
				'parent_item_colon' => esc_attr__( 'Parent:', 'sportspress' ),
				'search_items'      => esc_attr__( 'Search', 'sportspress' ),
				'not_found'         => esc_attr__( 'No results found.', 'sportspress' ),
			);
			$args         = apply_filters(
				'sportspress_register_taxonomy_role',
				array(
					'label'                 => esc_attr__( 'Jobs', 'sportspress' ),
					'labels'                => $labels,
					'public'                => true,
					'show_ui'               => $show,
					'show_in_menu'          => $show,
					'show_in_nav_menus'     => false,
					'show_tagcloud'         => false,
					'hierarchical'          => true,
					'rewrite'               => array( 'slug' => get_option( 'sportspress_role_slug', 'role' ) ),
					'show_in_rest'          => true,
					'rest_controller_class' => 'SP_REST_Terms_Controller',
					'rest_base'             => 'roles',
				)
			);
			$object_types = apply_filters( 'sportspress_role_object_types', array( 'sp_staff' ) );
			register_taxonomy( 'sp_role', $object_types, $args );
			foreach ( $object_types as $object_type ) :
				register_taxonomy_for_object_type( 'sp_role', $object_type );
			endforeach;
		endif;

		do_action( 'sportspress_after_register_taxonomy' );
	}

	/**
	 * Register core post types
	 */
	public static function register_post_types() {
		do_action( 'sportspress_register_post_type' );

		register_post_type(
			'sp_result',
			apply_filters(
				'sportspress_register_post_type_result',
				array(
					'labels'              => array(
						'name'               => esc_attr__( 'Event Results', 'sportspress' ),
						'singular_name'      => esc_attr__( 'Result', 'sportspress' ),
						'add_new_item'       => esc_attr__( 'Add New Result', 'sportspress' ),
						'edit_item'          => esc_attr__( 'Edit Result', 'sportspress' ),
						'new_item'           => esc_attr__( 'New', 'sportspress' ),
						'view_item'          => esc_attr__( 'View', 'sportspress' ),
						'search_items'       => esc_attr__( 'Search', 'sportspress' ),
						'not_found'          => esc_attr__( 'No results found.', 'sportspress' ),
						'not_found_in_trash' => esc_attr__( 'No results found.', 'sportspress' ),
					),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'sp_config',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'supports'            => array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive'         => false,
					'show_in_nav_menus'   => false,
					'can_export'          => false,
					'show_in_menu'        => false,
				)
			)
		);

		register_post_type(
			'sp_outcome',
			apply_filters(
				'sportspress_register_post_type_outcome',
				array(
					'labels'              => array(
						'name'               => esc_attr__( 'Event Outcomes', 'sportspress' ),
						'singular_name'      => esc_attr__( 'Outcome', 'sportspress' ),
						'add_new_item'       => esc_attr__( 'Add New Outcome', 'sportspress' ),
						'edit_item'          => esc_attr__( 'Edit Outcome', 'sportspress' ),
						'new_item'           => esc_attr__( 'New', 'sportspress' ),
						'view_item'          => esc_attr__( 'View', 'sportspress' ),
						'search_items'       => esc_attr__( 'Search', 'sportspress' ),
						'not_found'          => esc_attr__( 'No results found.', 'sportspress' ),
						'not_found_in_trash' => esc_attr__( 'No results found.', 'sportspress' ),
					),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'sp_config',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'supports'            => array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive'         => false,
					'show_in_nav_menus'   => false,
					'can_export'          => false,
					'show_in_menu'        => false,
				)
			)
		);

		register_post_type(
			'sp_column',
			apply_filters(
				'sportspress_register_post_type_column',
				array(
					'labels'              => array(
						'name'               => esc_attr__( 'Table Columns', 'sportspress' ),
						'singular_name'      => esc_attr__( 'Column', 'sportspress' ),
						'add_new_item'       => esc_attr__( 'Add New Column', 'sportspress' ),
						'edit_item'          => esc_attr__( 'Edit Column', 'sportspress' ),
						'new_item'           => esc_attr__( 'New', 'sportspress' ),
						'view_item'          => esc_attr__( 'View', 'sportspress' ),
						'search_items'       => esc_attr__( 'Search', 'sportspress' ),
						'not_found'          => esc_attr__( 'No results found.', 'sportspress' ),
						'not_found_in_trash' => esc_attr__( 'No results found.', 'sportspress' ),
					),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'sp_config',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'supports'            => array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive'         => false,
					'show_in_nav_menus'   => false,
					'can_export'          => false,
					'show_in_menu'        => false,
				)
			)
		);

		register_post_type(
			'sp_metric',
			apply_filters(
				'sportspress_register_post_type_metric',
				array(
					'labels'              => array(
						'name'               => esc_attr__( 'Player Metrics', 'sportspress' ),
						'singular_name'      => esc_attr__( 'Metric', 'sportspress' ),
						'add_new_item'       => esc_attr__( 'Add New Metric', 'sportspress' ),
						'edit_item'          => esc_attr__( 'Edit Metric', 'sportspress' ),
						'new_item'           => esc_attr__( 'New', 'sportspress' ),
						'view_item'          => esc_attr__( 'View', 'sportspress' ),
						'search_items'       => esc_attr__( 'Search', 'sportspress' ),
						'not_found'          => esc_attr__( 'No results found.', 'sportspress' ),
						'not_found_in_trash' => esc_attr__( 'No results found.', 'sportspress' ),
					),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'sp_config',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'supports'            => array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive'         => false,
					'show_in_nav_menus'   => false,
					'can_export'          => false,
					'show_in_menu'        => false,
				)
			)
		);

		register_post_type(
			'sp_performance',
			apply_filters(
				'sportspress_register_post_type_performance',
				array(
					'labels'              => array(
						'name'                  => esc_attr__( 'Player Performance', 'sportspress' ),
						'menu_name'             => esc_attr__( 'Performance', 'sportspress' ),
						'singular_name'         => esc_attr__( 'Player Performance', 'sportspress' ),
						'add_new_item'          => esc_attr__( 'Add New Performance', 'sportspress' ),
						'edit_item'             => esc_attr__( 'Edit Performance', 'sportspress' ),
						'new_item'              => esc_attr__( 'New', 'sportspress' ),
						'view_item'             => esc_attr__( 'View', 'sportspress' ),
						'search_items'          => esc_attr__( 'Search', 'sportspress' ),
						'not_found'             => esc_attr__( 'No results found.', 'sportspress' ),
						'not_found_in_trash'    => esc_attr__( 'No results found.', 'sportspress' ),
						'featured_image'        => esc_attr__( 'Icon', 'sportspress' ),
						'set_featured_image'    => esc_attr__( 'Select Icon', 'sportspress' ),
						'remove_featured_image' => esc_attr__( 'Remove icon', 'sportspress' ),
						'use_featured_image'    => esc_attr__( 'Add icon', 'sportspress' ),
					),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'sp_config',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'supports'            => array( 'title', 'thumbnail', 'page-attributes', 'excerpt' ),
					'has_archive'         => false,
					'show_in_nav_menus'   => false,
					'can_export'          => false,
					'show_in_menu'        => false,
				)
			)
		);

		register_post_type(
			'sp_statistic',
			apply_filters(
				'sportspress_register_post_type_statistic',
				array(
					'labels'              => array(
						'name'                  => esc_attr__( 'Player Statistics', 'sportspress' ),
						'menu_name'             => esc_attr__( 'Statistics', 'sportspress' ),
						'singular_name'         => esc_attr__( 'Statistic', 'sportspress' ),
						'add_new_item'          => esc_attr__( 'Add New Statistic', 'sportspress' ),
						'edit_item'             => esc_attr__( 'Edit Statistic', 'sportspress' ),
						'new_item'              => esc_attr__( 'New', 'sportspress' ),
						'view_item'             => esc_attr__( 'View', 'sportspress' ),
						'search_items'          => esc_attr__( 'Search', 'sportspress' ),
						'not_found'             => esc_attr__( 'No results found.', 'sportspress' ),
						'not_found_in_trash'    => esc_attr__( 'No results found.', 'sportspress' ),
						'featured_image'        => esc_attr__( 'Icon', 'sportspress' ),
						'set_featured_image'    => esc_attr__( 'Select Icon', 'sportspress' ),
						'remove_featured_image' => esc_attr__( 'Remove icon', 'sportspress' ),
						'use_featured_image'    => esc_attr__( 'Add icon', 'sportspress' ),
					),
					'public'              => false,
					'show_ui'             => true,
					'capability_type'     => 'sp_config',
					'map_meta_cap'        => true,
					'publicly_queryable'  => false,
					'exclude_from_search' => true,
					'hierarchical'        => false,
					'supports'            => array( 'title', 'thumbnail', 'page-attributes', 'excerpt' ),
					'has_archive'         => false,
					'show_in_nav_menus'   => false,
					'can_export'          => false,
					'show_in_menu'        => false,
				)
			)
		);

		$args = array(
			'labels'                => array(
				'name'               => esc_attr__( 'Events', 'sportspress' ),
				'singular_name'      => esc_attr__( 'Event', 'sportspress' ),
				'add_new'      	     => esc_attr__( 'Add New Event', 'sportspress' ),
				'add_new_item'       => esc_attr__( 'Add New Event', 'sportspress' ),
				'edit_item'          => esc_attr__( 'Edit Event', 'sportspress' ),
				'new_item'           => esc_attr__( 'New', 'sportspress' ),
				'view_item'          => esc_attr__( 'View Event', 'sportspress' ),
				'search_items'       => esc_attr__( 'Search', 'sportspress' ),
				'not_found'          => esc_attr__( 'No results found.', 'sportspress' ),
				'not_found_in_trash' => esc_attr__( 'No results found.', 'sportspress' ),
			),
			'public'                => true,
			'show_ui'               => true,
			'capability_type'       => 'sp_event',
			'map_meta_cap'          => true,
			'publicly_queryable'    => true,
			'exclude_from_search'   => false,
			'hierarchical'          => false,
			'rewrite'               => array( 'slug' => get_option( 'sportspress_event_slug', 'event' ) ),
			'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
			'has_archive'           => false,
			'show_in_nav_menus'     => true,
			'menu_icon'             => 'dashicons-calendar',
			'show_in_rest'          => true,
			'rest_controller_class' => 'SP_REST_Posts_Controller',
			'rest_base'             => 'events',
		);

		if ( get_option( 'sportspress_event_comment_status', 'no' ) == 'yes' ) :
			$args['supports'][] = 'comments';
		endif;

		register_post_type( 'sp_event', apply_filters( 'sportspress_register_post_type_event', $args ) );

		$args = array(
			'labels'                => array(
				'name'                  => esc_attr__( 'Teams', 'sportspress' ),
				'singular_name'         => esc_attr__( 'Team', 'sportspress' ),
				'add_new'          		=> esc_attr__( 'Add New Team', 'sportspress' ),
				'add_new_item'          => esc_attr__( 'Add New Team', 'sportspress' ),
				'edit_item'             => esc_attr__( 'Edit Team', 'sportspress' ),
				'new_item'              => esc_attr__( 'New', 'sportspress' ),
				'view_item'             => esc_attr__( 'View Team', 'sportspress' ),
				'search_items'          => esc_attr__( 'Search', 'sportspress' ),
				'not_found'             => esc_attr__( 'No results found.', 'sportspress' ),
				'not_found_in_trash'    => esc_attr__( 'No results found.', 'sportspress' ),
				'featured_image'        => esc_attr__( 'Logo', 'sportspress' ),
				'set_featured_image'    => esc_attr__( 'Select Logo', 'sportspress' ),
				'remove_featured_image' => esc_attr__( 'Remove Logo', 'sportspress' ),
				'use_featured_image'    => esc_attr__( 'Select Logo', 'sportspress' ),
			),
			'public'                => true,
			'show_ui'               => true,
			'capability_type'       => 'sp_team',
			'map_meta_cap'          => true,
			'publicly_queryable'    => true,
			'exclude_from_search'   => false,
			'hierarchical'          => true,
			'rewrite'               => array( 'slug' => get_option( 'sportspress_team_slug', 'team' ) ),
			'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes', 'excerpt' ),
			'has_archive'           => false,
			'show_in_nav_menus'     => true,
			'menu_icon'             => 'dashicons-shield-alt',
			'show_in_rest'          => true,
			'rest_controller_class' => 'SP_REST_Posts_Controller',
			'rest_base'             => 'teams',
		);

		if ( get_option( 'sportspress_team_comment_status', 'no' ) == 'yes' ) :
			$args['supports'][] = 'comments';
		endif;

		register_post_type( 'sp_team', apply_filters( 'sportspress_register_post_type_team', $args ) );

		register_post_type(
			'sp_player',
			apply_filters(
				'sportspress_register_post_type_player',
				array(
					'labels'                => array(
						'name'                  => esc_attr__( 'Players', 'sportspress' ),
						'singular_name'         => esc_attr__( 'Player', 'sportspress' ),
						'add_new'	            => esc_attr__( 'Add New Player', 'sportspress' ),
						'add_new_item'          => esc_attr__( 'Add New Player', 'sportspress' ),
						'edit_item'             => esc_attr__( 'Edit Player', 'sportspress' ),
						'new_item'              => esc_attr__( 'New', 'sportspress' ),
						'view_item'             => esc_attr__( 'View Player', 'sportspress' ),
						'search_items'          => esc_attr__( 'Search', 'sportspress' ),
						'not_found'             => esc_attr__( 'No results found.', 'sportspress' ),
						'not_found_in_trash'    => esc_attr__( 'No results found.', 'sportspress' ),
						'featured_image'        => esc_attr__( 'Photo', 'sportspress' ),
						'set_featured_image'    => esc_attr__( 'Select Photo', 'sportspress' ),
						'remove_featured_image' => esc_attr__( 'Remove Photo', 'sportspress' ),
						'use_featured_image'    => esc_attr__( 'Select Photo', 'sportspress' ),
					),
					'public'                => true,
					'show_ui'               => true,
					'capability_type'       => 'sp_player',
					'map_meta_cap'          => true,
					'publicly_queryable'    => true,
					'exclude_from_search'   => false,
					'hierarchical'          => false,
					'rewrite'               => array( 'slug' => get_option( 'sportspress_player_slug', 'player' ) ),
					'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes' ),
					'has_archive'           => false,
					'show_in_nav_menus'     => true,
					'menu_icon'             => 'dashicons-groups',
					'show_in_rest'          => true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base'             => 'players',
				)
			)
		);

		register_post_type(
			'sp_staff',
			apply_filters(
				'sportspress_register_post_type_staff',
				array(
					'labels'                => array(
						'name'                  => esc_attr__( 'Staff', 'sportspress' ),
						'singular_name'         => esc_attr__( 'Staff', 'sportspress' ),
						'add_new'	            => esc_attr__( 'Add New Staff', 'sportspress' ),
						'add_new_item'          => esc_attr__( 'Add New Staff', 'sportspress' ),
						'edit_item'             => esc_attr__( 'Edit Staff', 'sportspress' ),
						'new_item'              => esc_attr__( 'New', 'sportspress' ),
						'view_item'             => esc_attr__( 'View Staff', 'sportspress' ),
						'search_items'          => esc_attr__( 'Search', 'sportspress' ),
						'not_found'             => esc_attr__( 'No results found.', 'sportspress' ),
						'not_found_in_trash'    => esc_attr__( 'No results found.', 'sportspress' ),
						'featured_image'        => esc_attr__( 'Photo', 'sportspress' ),
						'set_featured_image'    => esc_attr__( 'Select Photo', 'sportspress' ),
						'remove_featured_image' => esc_attr__( 'Remove Photo', 'sportspress' ),
						'use_featured_image'    => esc_attr__( 'Select Photo', 'sportspress' ),
					),
					'public'                => true,
					'show_ui'               => true,
					'capability_type'       => 'sp_staff',
					'map_meta_cap'          => true,
					'publicly_queryable'    => true,
					'exclude_from_search'   => false,
					'hierarchical'          => false,
					'rewrite'               => array( 'slug' => get_option( 'sportspress_staff_slug', 'staff' ) ),
					'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
					'has_archive'           => false,
					'show_in_nav_menus'     => true,
					'menu_icon'             => 'dashicons-businessman',
					'show_in_rest'          => true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base'             => 'staff',
				)
			)
		);

		do_action( 'sportspress_after_register_post_type' );
	}

	public function delete_config_post( $post_id ) {
		$post_type = get_post_type( $post_id );
		if ( is_sp_config_type( $post_type ) ) {
			wp_delete_post( $post_id, true );
		}
	}

	public function display_scheduled_events( $post_status, $post ) {
		if ($post->post_type == 'sp_event' && $post_status == 'future') {
			return "publish";
		}
		return $post_status;
	}
	
}

new SP_Post_types();
