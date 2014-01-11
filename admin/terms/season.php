<?php
function sportspress_season_term_init() {
	$name = __( 'Seasons', 'sportspress' );
	$singular_name = __( 'Season', 'sportspress' );
	$lowercase_name = __( 'season', 'sportspress' );
	$object_type = array( 'sp_calendar', 'sp_team', 'sp_player', 'sp_staff' );
	$labels = sportspress_get_term_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'season' )
	);
	register_taxonomy( 'sp_season', $object_type, $args );
	register_taxonomy_for_object_type( 'sp_season', 'sp_calendar' );
	register_taxonomy_for_object_type( 'sp_season', 'sp_team' );
	register_taxonomy_for_object_type( 'sp_season', 'sp_player' );
	register_taxonomy_for_object_type( 'sp_season', 'sp_staff' );
}
add_action( 'init', 'sportspress_season_term_init' );
