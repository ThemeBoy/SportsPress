<?php
function sportspress_metric_post_init() {
	$labels = array(
		'name' => __( 'Metrics', 'sportspress' ),
		'singular_name' => __( 'Metric', 'sportspress' ),
		'add_new_item' => __( 'Add New Metric', 'sportspress' ),
		'edit_item' => __( 'Edit Metric', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Metrics', 'sportspress' ),
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_metric', $args );
}
add_action( 'init', 'sportspress_metric_post_init' );

function sportspress_metric_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_positions' => __( 'Positions', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_metric_columns', 'sportspress_metric_edit_columns' );
