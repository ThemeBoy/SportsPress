<?php
function sportspress_position_term_init() {
	$name = __( 'Positions', 'sportspress' );
	$singular_name = __( 'Position', 'sportspress' );
	$lowercase_name = __( 'position', 'sportspress' );
	$object_type = array( 'sp_player' );
	$labels = sportspress_get_term_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'position' )
	);
	register_taxonomy( 'sp_position', $object_type, $args );
	register_taxonomy_for_object_type( 'sp_position', 'sp_player' );
}
add_action( 'init', 'sportspress_position_term_init' );
