<?php
function sp_division_tax_init() {
	$name = __( 'Divisions', 'sportspress' );
	$singular_name = __( 'Division', 'sportspress' );
	$lowercase_name = __( 'divisions', 'sportspress' );
	$object_type = array( 'sp_team', 'sp_event', 'sp_player', 'sp_staff' );
	$labels = sp_tax_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'division' ),
	);
	register_taxonomy( 'sp_division', $object_type, $args );
}
add_action( 'init', 'sp_division_tax_init' );
?>