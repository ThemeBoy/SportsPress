<?php
function sp_season_tax_init() {
	$name = __( 'Seasons', 'sportspress' );
	$singular_name = __( 'Season', 'sportspress' );
	$object_type = array( 'sp_team', 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_calendar' );
	$labels = sp_get_tax_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'season' ),
	);
	register_taxonomy( 'sp_season', $object_type, $args );
}
add_action( 'init', 'sp_season_tax_init' );
?>