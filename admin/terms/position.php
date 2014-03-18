<?php
function sportspress_position_term_init() {
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
	$object_types = array( 'sp_player', 'sp_statistic', 'sp_metric', 'attachment' );
	register_taxonomy( 'sp_position', $object_types, $args );
	foreach ( $object_types as $object_type ):
		register_taxonomy_for_object_type( 'sp_league', $object_type );
	endforeach;
}
add_action( 'init', 'sportspress_position_term_init' );
