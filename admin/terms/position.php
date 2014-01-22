<?php
function sportspress_position_term_init() {
	$name = __( 'Positions', 'sportspress' );
	$singular_name = __( 'Position', 'sportspress' );
	$lowercase_name = __( 'position', 'sportspress' );
	$object_type = array( 'sp_player', 'sp_statistic', 'sp_metric', 'attachment' );
	$labels = sportspress_get_term_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud' => false,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'position' ),
	);
	register_taxonomy( 'sp_position', $object_type, $args );
	register_taxonomy_for_object_type( 'sp_position', 'sp_player' );
	register_taxonomy_for_object_type( 'sp_position', 'sp_statistic' );
	register_taxonomy_for_object_type( 'sp_position', 'sp_metric' );
	register_taxonomy_for_object_type( 'sp_position', 'attachment' );
}
add_action( 'init', 'sportspress_position_term_init' );
