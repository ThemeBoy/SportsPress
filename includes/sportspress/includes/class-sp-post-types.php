<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Post types
 *
 * Registers post types and taxonomies
 *
 * @class 		SP_Post_types
 * @version		1.9
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Post_types {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 10 );
		add_action( 'wp_trash_post', array( $this, 'delete_config_post' ) );
		add_filter( 'the_posts', array( $this, 'display_scheduled_events' ) );
	}

	/**
	 * Register SportsPress taxonomies.
	 */
	public static function register_taxonomies() {
		do_action( 'sportspress_register_taxonomy' );

		if ( apply_filters( 'sportspress_has_leagues', true ) ):
			$labels = array(
				'name' => __( 'Competitions', 'sportspress' ),
				'singular_name' => __( 'Competition', 'sportspress' ),
				'all_items' => __( 'All', 'sportspress' ),
				'edit_item' => __( 'Edit Competition', 'sportspress' ),
				'view_item' => __( 'View', 'sportspress' ),
				'update_item' => __( 'Update', 'sportspress' ),
				'add_new_item' => __( 'Add New', 'sportspress' ),
				'new_item_name' => __( 'Name', 'sportspress' ),
				'parent_item' => __( 'Parent', 'sportspress' ),
				'parent_item_colon' => __( 'Parent:', 'sportspress' ),
				'search_items' =>  __( 'Search', 'sportspress' ),
				'not_found' => __( 'No results found.', 'sportspress' ),
			);
			$args = array(
				'label' => __( 'Competitions', 'sportspress' ),
				'labels' => $labels,
				'public' => true,
				'show_in_nav_menus' => false,
				'show_tagcloud' => false,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => get_option( 'sportspress_league_slug', 'league' ) ),
			);
			$object_types = apply_filters( 'sportspress_league_object_types', array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) );
			register_taxonomy( 'sp_league', $object_types, $args );
			foreach ( $object_types as $object_type ):
				register_taxonomy_for_object_type( 'sp_league', $object_type );
			endforeach;
		endif;

		if ( apply_filters( 'sportspress_has_seasons', true ) ):
			$labels = array(
				'name' => __( 'Seasons', 'sportspress' ),
				'singular_name' => __( 'Season', 'sportspress' ),
				'all_items' => __( 'All', 'sportspress' ),
				'edit_item' => __( 'Edit Season', 'sportspress' ),
				'view_item' => __( 'View', 'sportspress' ),
				'update_item' => __( 'Update', 'sportspress' ),
				'add_new_item' => __( 'Add New', 'sportspress' ),
				'new_item_name' => __( 'Name', 'sportspress' ),
				'parent_item' => __( 'Parent', 'sportspress' ),
				'parent_item_colon' => __( 'Parent:', 'sportspress' ),
				'search_items' =>  __( 'Search', 'sportspress' ),
				'not_found' => __( 'No results found.', 'sportspress' ),
			);
			$args = array(
				'label' => __( 'Seasons', 'sportspress' ),
				'labels' => $labels,
				'public' => true,
				'show_in_nav_menus' => false,
				'show_tagcloud' => false,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => get_option( 'sportspress_season_slug', 'season' ) ),
			);
			$object_types = apply_filters( 'sportspress_season_object_types', array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) );
			register_taxonomy( 'sp_season', $object_types, $args );
			foreach ( $object_types as $object_type ):
				register_taxonomy_for_object_type( 'sp_season', $object_type );
			endforeach;
		endif;

		if ( apply_filters( 'sportspress_has_venues', true ) ):
			$labels = array(
				'name' => __( 'Venues', 'sportspress' ),
				'singular_name' => __( 'Venue', 'sportspress' ),
				'all_items' => __( 'All', 'sportspress' ),
				'edit_item' => __( 'Edit Venue', 'sportspress' ),
				'view_item' => __( 'View', 'sportspress' ),
				'update_item' => __( 'Update', 'sportspress' ),
				'add_new_item' => __( 'Add New', 'sportspress' ),
				'new_item_name' => __( 'Name', 'sportspress' ),
				'parent_item' => __( 'Parent', 'sportspress' ),
				'parent_item_colon' => __( 'Parent:', 'sportspress' ),
				'search_items' =>  __( 'Search', 'sportspress' ),
				'not_found' => __( 'No results found.', 'sportspress' ),
			);
			$args = array(
				'label' => __( 'Venues', 'sportspress' ),
				'labels' => $labels,
				'public' => true,
				'show_in_nav_menus' => false,
				'show_tagcloud' => false,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => get_option( 'sportspress_venue_slug', 'venue' ) ),
			);
			$object_types = apply_filters( 'sportspress_event_object_types', array( 'sp_event', 'sp_calendar', 'sp_team' ) );
			register_taxonomy( 'sp_venue', $object_types, $args );
			foreach ( $object_types as $object_type ):
				register_taxonomy_for_object_type( 'sp_venue', $object_type );
			endforeach;
		endif;

		if ( apply_filters( 'sportspress_has_positions', true ) ):
			$labels = array(
				'name' => __( 'Positions', 'sportspress' ),
				'singular_name' => __( 'Position', 'sportspress' ),
				'all_items' => __( 'All', 'sportspress' ),
				'edit_item' => __( 'Edit Position', 'sportspress' ),
				'view_item' => __( 'View', 'sportspress' ),
				'update_item' => __( 'Update', 'sportspress' ),
				'add_new_item' => __( 'Add New', 'sportspress' ),
				'new_item_name' => __( 'Name', 'sportspress' ),
				'parent_item' => __( 'Parent', 'sportspress' ),
				'parent_item_colon' => __( 'Parent:', 'sportspress' ),
				'search_items' =>  __( 'Search', 'sportspress' ),
				'not_found' => __( 'No results found.', 'sportspress' ),
			);
			$args = array(
				'label' => __( 'Positions', 'sportspress' ),
				'labels' => $labels,
				'public' => true,
				'show_in_nav_menus' => false,
				'show_tagcloud' => false,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => get_option( 'sportspress_position_slug', 'position' ) ),
			);
			$object_types = apply_filters( 'sportspress_position_object_types', array( 'sp_player', 'sp_list', 'sp_performance' ) );
			register_taxonomy( 'sp_position', $object_types, $args );
			foreach ( $object_types as $object_type ):
				register_taxonomy_for_object_type( 'sp_position', $object_type );
			endforeach;
		endif;

		if ( apply_filters( 'sportspress_has_roles', true ) ):
			$labels = array(
				'name' => __( 'Jobs', 'sportspress' ),
				'singular_name' => __( 'Job', 'sportspress' ),
				'all_items' => __( 'All', 'sportspress' ),
				'edit_item' => __( 'Edit Job', 'sportspress' ),
				'view_item' => __( 'View', 'sportspress' ),
				'update_item' => __( 'Update', 'sportspress' ),
				'add_new_item' => __( 'Add New', 'sportspress' ),
				'new_item_name' => __( 'Name', 'sportspress' ),
				'parent_item' => __( 'Parent', 'sportspress' ),
				'parent_item_colon' => __( 'Parent:', 'sportspress' ),
				'search_items' =>  __( 'Search', 'sportspress' ),
				'not_found' => __( 'No results found.', 'sportspress' ),
			);
			$args = array(
				'label' => __( 'Jobs', 'sportspress' ),
				'labels' => $labels,
				'public' => true,
				'show_in_nav_menus' => false,
				'show_tagcloud' => false,
				'hierarchical' => true,
				'rewrite' => array( 'slug' => get_option( 'sportspress_role_slug', 'role' ) ),
			);
			$object_types = apply_filters( 'sportspress_role_object_types', array( 'sp_staff' ) );
			register_taxonomy( 'sp_role', $object_types, $args );
			foreach ( $object_types as $object_type ):
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

		register_post_type( 'sp_result',
			apply_filters( 'sportspress_register_post_type_result',
				array(
					'labels' => array(
						'name' 					=> __( 'Event Results', 'sportspress' ),
						'singular_name' 		=> __( 'Result', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Result', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Result', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> false,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_config',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> true,
					'hierarchical' 			=> false,
					'supports' 				=> array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> false,
					'can_export' 			=> false,
					'show_in_menu' => false,
				)
			)
		);

		register_post_type( 'sp_outcome',
			apply_filters( 'sportspress_register_post_type_outcome',
				array(
					'labels' => array(
						'name' 					=> __( 'Event Outcomes', 'sportspress' ),
						'singular_name' 		=> __( 'Outcome', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Outcome', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Outcome', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> false,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_config',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> true,
					'hierarchical' 			=> false,
					'supports' 				=> array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> false,
					'can_export' 			=> false,
					'show_in_menu' => false,
				)
			)
		);

		register_post_type( 'sp_column',
			apply_filters( 'sportspress_register_post_type_column',
				array(
					'labels' => array(
						'name' 					=> __( 'Table Columns', 'sportspress' ),
						'singular_name' 		=> __( 'Column', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Column', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Column', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> false,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_config',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> true,
					'hierarchical' 			=> false,
					'supports' 				=> array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> false,
					'can_export' 			=> false,
					'show_in_menu' => false,
				)
			)
		);

		register_post_type( 'sp_metric',
			apply_filters( 'sportspress_register_post_type_metric',
				array(
					'labels' => array(
						'name' 					=> __( 'Player Metrics', 'sportspress' ),
						'singular_name' 		=> __( 'Metric', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Metric', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Metric', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> false,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_config',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> true,
					'hierarchical' 			=> false,
					'supports' 				=> array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> false,
					'can_export' 			=> false,
					'show_in_menu' => false,
				)
			)
		);

		register_post_type( 'sp_performance',
			apply_filters( 'sportspress_register_post_type_performance',
				array(
					'labels' => array(
						'name' 					=> __( 'Player Performance', 'sportspress' ),
						'singular_name' 		=> __( 'Player Performance', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Performance', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Performance', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> false,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_config',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> true,
					'hierarchical' 			=> false,
					'supports' 				=> array( 'title', 'thumbnail', 'page-attributes', 'excerpt' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> false,
					'can_export' 			=> false,
					'show_in_menu' => false,
				)
			)
		);

		register_post_type( 'sp_statistic',
			apply_filters( 'sportspress_register_post_type_statistic',
				array(
					'labels' => array(
						'name' 					=> __( 'Player Statistics', 'sportspress' ),
						'singular_name' 		=> __( 'Statistic', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Statistic', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Statistic', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> false,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_config',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> true,
					'hierarchical' 			=> false,
					'supports' 				=> array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> false,
					'can_export' 			=> false,
					'show_in_menu' => false,
				)
			)
		);

		$args = array(
			'labels' => array(
				'name' 					=> __( 'Events', 'sportspress' ),
				'singular_name' 		=> __( 'Event', 'sportspress' ),
				'add_new_item' 			=> __( 'Add New Event', 'sportspress' ),
				'edit_item' 			=> __( 'Edit Event', 'sportspress' ),
				'new_item' 				=> __( 'New', 'sportspress' ),
				'view_item' 			=> __( 'View Event', 'sportspress' ),
				'search_items' 			=> __( 'Search', 'sportspress' ),
				'not_found' 			=> __( 'No results found.', 'sportspress' ),
				'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
			),
			'public' 				=> true,
			'show_ui' 				=> true,
			'capability_type' 		=> 'sp_event',
			'map_meta_cap' 			=> true,
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> false,
			'hierarchical' 			=> false,
			'rewrite' 				=> array( 'slug' => get_option( 'sportspress_event_slug', 'event' ) ),
			'supports' 				=> array( 'title', 'author', 'thumbnail', 'excerpt' ),
			'has_archive' 			=> false,
			'show_in_nav_menus' 	=> true,
			'menu_icon' 			=> 'dashicons-calendar',
		);

		if ( get_option( 'sportspress_event_comment_status', 'no' ) == 'yes' ):
			$args[ 'supports' ][] = 'comments';
		endif;

		register_post_type( 'sp_event', apply_filters( 'sportspress_register_post_type_event', $args  ) );

		register_post_type( 'sp_team',
			apply_filters( 'sportspress_register_post_type_team',
				array(
					'labels' => array(
						'name' 					=> __( 'Teams', 'sportspress' ),
						'singular_name' 		=> __( 'Team', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Team', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Team', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View Team', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_team',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> true,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_team_slug', 'team' ) ),
					'supports' 				=> array( 'title', 'author', 'thumbnail', 'page-attributes', 'excerpt' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'menu_icon' 			=> 'dashicons-shield-alt',
				)
			)
		);

		register_post_type( 'sp_player',
			apply_filters( 'sportspress_register_post_type_player',
				array(
					'labels' => array(
						'name' 					=> __( 'Players', 'sportspress' ),
						'singular_name' 		=> __( 'Player', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Player', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Player', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View Player', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_player',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_player_slug', 'player' ) ),
					'supports' 				=> array( 'title', 'author', 'thumbnail', 'excerpt', 'page-attributes' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'menu_icon' 			=> 'dashicons-groups',
				)
			)
		);

		register_post_type( 'sp_staff',
			apply_filters( 'sportspress_register_post_type_staff',
				array(
					'labels' => array(
						'name' 					=> __( 'Staff', 'sportspress' ),
						'singular_name' 		=> __( 'Staff', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Staff', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Staff', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View Staff', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_staff',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_staff_slug', 'staff' ) ),
					'supports' 				=> array( 'title', 'author', 'thumbnail', 'excerpt' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'menu_icon' 			=> 'dashicons-businessman',
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

	public function display_scheduled_events( $posts ) {
		global $wp_query, $wpdb;
		if ( is_single() && $wp_query->post_count == 0 && isset( $wp_query->query_vars['sp_event'] )) {
			$posts = $wpdb->get_results( $wp_query->request );
		}
		return $posts;
	}
}

new SP_Post_types();
