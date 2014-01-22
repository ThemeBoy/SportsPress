<?php
function sportspress_metric_post_init() {
	$name = __( 'Metrics', 'sportspress' );
	$singular_name = __( 'Metric', 'sportspress' );
	$lowercase_name = __( 'metrics', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
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
