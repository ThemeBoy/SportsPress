<?php
function sp_calendar_cpt_init() {
	$name = __( 'Calendars', 'sportspress' );
	$singular_name = __( 'Calendar', 'sportspress' );
	$labels = sp_get_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'rewrite' => array( 'slug' => 'calendar' )
	);
	register_post_type( 'sp_calendar', $args );
}
add_action( 'init', 'sp_calendar_cpt_init' );

function sp_calendar_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_calendar_columns', 'sp_calendar_edit_columns' );
?>