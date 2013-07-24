<?php
function sp_sponsor_tax_init() {
	$name = __( 'Sponsors', 'sportspress' );
	$singular_name = __( 'Sponsor', 'sportspress' );
	$object_type = array( 'sp_team', 'sp_event', 'sp_player', 'sp_tournament', 'sp_venue' );
	$labels = sp_get_tax_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'sponsor' ),
	);
	register_taxonomy( 'sp_sponsor', $object_type, $args );
}
add_action( 'init', 'sp_sponsor_tax_init' );
?>