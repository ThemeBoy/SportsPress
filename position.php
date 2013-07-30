<?php
function sp_position_tax_init() {
	$name = __( 'Positions', 'sportspress' );
	$singular_name = __( 'Position', 'sportspress' );
	$object_type = array( 'sp_player', 'sp_staff' );
	$labels = sp_tax_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'position' ),
	);
	register_taxonomy( 'sp_position', $object_type, $args );
}
add_action( 'init', 'sp_position_tax_init' );
?>