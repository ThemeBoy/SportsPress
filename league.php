<?php
function sp_league_tax_init() {
	$name = __( 'Leagues', 'sportspress' );
	$singular_name = __( 'League', 'sportspress' );
	$object_type = array( 'sp_team', 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_list' );
	$labels = sp_tax_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'league' ),
	);
	register_taxonomy( 'sp_league', $object_type, $args );
}
add_action( 'init', 'sp_league_tax_init' );
?>