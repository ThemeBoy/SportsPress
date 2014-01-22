<?php
function sportspress_league_term_init() {
	$name = __( 'Leagues', 'sportspress' );
	$singular_name = __( 'League', 'sportspress' );
	$lowercase_name = __( 'league', 'sportspress' );
	$object_type = array( 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' );
	$labels = sportspress_get_term_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud' => false,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'league' ),
	);
	register_taxonomy( 'sp_league', $object_type, $args );
	register_taxonomy_for_object_type( 'sp_league', 'sp_calendar' );
	register_taxonomy_for_object_type( 'sp_league', 'sp_team' );
	register_taxonomy_for_object_type( 'sp_league', 'sp_player' );
	register_taxonomy_for_object_type( 'sp_league', 'sp_staff' );
}
add_action( 'init', 'sportspress_league_term_init' );
