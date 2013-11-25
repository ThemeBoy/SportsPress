<?php
function sp_pos_term_init() {
	$name = __( 'Positions', 'sportspress' );
	$singular_name = __( 'Position', 'sportspress' );
	$lowercase_name = __( 'position', 'sportspress' );
	$object_type = array( 'sp_player', 'sp_staff' );
	$labels = sp_tax_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'position' )
	);
	register_taxonomy( 'sp_pos', $object_type, $args );
	register_taxonomy_for_object_type( 'sp_pos', 'sp_player' );
	register_taxonomy_for_object_type( 'sp_pos', 'sp_staff' );
}
add_action( 'init', 'sp_pos_term_init' );
?>