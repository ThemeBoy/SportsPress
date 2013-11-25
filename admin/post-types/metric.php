<?php
function sp_metric_cpt_init() {
	$name = __( 'Metrics', 'sportspress' );
	$singular_name = __( 'Metric', 'sportspress' );
	$lowercase_name = __( 'metrics', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_metric_meta_init',
		'rewrite' => array( 'slug' => 'metric' ),
		'show_in_menu' => 'edit.php?post_type=sp_player'
	);
	register_post_type( 'sp_metric', $args );
}
add_action( 'init', 'sp_metric_cpt_init' );

function sp_metric_meta_init() {
}
?>