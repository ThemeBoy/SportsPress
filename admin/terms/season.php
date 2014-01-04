<?php
function sp_season_term_init() {
	$name = __( 'Seasons', 'sportspress' );
	$singular_name = __( 'Season', 'sportspress' );
	$lowercase_name = __( 'season', 'sportspress' );
	$object_type = array( 'sp_team', 'sp_event', 'sp_player', 'sp_staff' );
	$labels = sp_tax_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'league' )
	);
	register_taxonomy( 'sp_season', $object_type, $args );
}
add_action( 'init', 'sp_season_term_init' );
?>