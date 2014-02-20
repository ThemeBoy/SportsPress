<?php
function sportspress_season_term_init() {
	$labels = array(
		'name' => __( 'Seasons', 'sportspress' ),
		'singular_name' => __( 'Season', 'sportspress' ),
		'all_items' => __( 'All Seasons', 'sportspress' ),
		'edit_item' => __( 'Edit Season', 'sportspress' ),
		'view_item' => __( 'View Season', 'sportspress' ),
		'update_item' => __( 'Update Season', 'sportspress' ),
		'add_new_item' => __( 'Add New Season', 'sportspress' ),
		'new_item_name' => __( 'New Season Name', 'sportspress' ),
		'parent_item' => __( 'Parent Season', 'sportspress' ),
		'parent_item_colon' => __( 'Parent Season:', 'sportspress' ),
		'search_items' =>  __( 'Search Seasons', 'sportspress' ),
		'not_found' => __( 'No seasons found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Seasons', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud' => false,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'season' ),
	);
	$object_types = array( 'sp_event', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' );
	register_taxonomy( 'sp_season', $object_types, $args );
	foreach ( $object_types as $object_type ):
		register_taxonomy_for_object_type( 'sp_league', $object_type );
	endforeach;
}
add_action( 'init', 'sportspress_season_term_init' );
