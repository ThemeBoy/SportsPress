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
 * @version		0.7
 * @package		SportsPress/Classes/Products
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Post_types {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
	}

	/**
	 * Register SportsPress taxonomies.
	 */
	public static function register_taxonomies() {
		if ( taxonomy_exists( 'product_type' ) )
			return;

		do_action( 'sportspress_register_taxonomy' );

		$labels = array(
			'name' => __( 'Leagues', 'sportspress' ),
			'singular_name' => __( 'League', 'sportspress' ),
			'all_items' => __( 'All', 'sportspress' ),
			'edit_item' => __( 'Edit League', 'sportspress' ),
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
			'label' => __( 'Leagues', 'sportspress' ),
			'labels' => $labels,
			'public' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => get_option( 'sportspress_league_slug', 'league' ) ),
		);
		$object_types = array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' );
		register_taxonomy( 'sp_league', $object_types, $args );
		foreach ( $object_types as $object_type ):
			register_taxonomy_for_object_type( 'sp_league', $object_type );
		endforeach;

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
		$object_types = array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' );
		register_taxonomy( 'sp_season', $object_types, $args );
		foreach ( $object_types as $object_type ):
			register_taxonomy_for_object_type( 'sp_league', $object_type );
		endforeach;

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
		$object_types = array( 'sp_event', 'sp_calendar', 'attachment' );
		register_taxonomy( 'sp_venue', $object_types, $args );
		foreach ( $object_types as $object_type ):
			register_taxonomy_for_object_type( 'sp_league', $object_type );
		endforeach;

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
		$object_types = array( 'sp_player', 'sp_performance', 'sp_metric', 'attachment' );
		register_taxonomy( 'sp_position', $object_types, $args );
		foreach ( $object_types as $object_type ):
			register_taxonomy_for_object_type( 'sp_league', $object_type );
		endforeach;
	}

	/**
	 * Register core post types
	 */
	public static function register_post_types() {

		do_action( 'sportspress_register_post_type' );


	}
}

new SP_Post_types();
