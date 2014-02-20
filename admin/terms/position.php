<?php
function sportspress_position_term_init() {
	$labels = array(
		'name' => __( 'Positions', 'sportspress' ),
		'singular_name' => __( 'Position', 'sportspress' ),
		'all_items' => __( 'All Positions', 'sportspress' ),
		'edit_item' => __( 'Edit Position', 'sportspress' ),
		'view_item' => __( 'View Position', 'sportspress' ),
		'update_item' => __( 'Update Position', 'sportspress' ),
		'add_new_item' => __( 'Add New Position', 'sportspress' ),
		'new_item_name' => __( 'New Position Name', 'sportspress' ),
		'parent_item' => __( 'Parent Position', 'sportspress' ),
		'parent_item_colon' => __( 'Parent Position:', 'sportspress' ),
		'search_items' =>  __( 'Search Positions', 'sportspress' ),
		'not_found' => __( 'No positions found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Positions', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud' => false,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'position' ),
	);
	$object_types = array( 'sp_player', 'sp_statistic', 'sp_metric', 'attachment' );
	register_taxonomy( 'sp_position', $object_types, $args );
	foreach ( $object_types as $object_type ):
		register_taxonomy_for_object_type( 'sp_league', $object_type );
	endforeach;
}
add_action( 'init', 'sportspress_position_term_init' );
