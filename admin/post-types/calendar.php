<?php
function sportspress_calendar_post_init() {
	$name = __( 'Calendars', 'sportspress' );
	$singular_name = __( 'Calendar', 'sportspress' );
	$lowercase_name = __( 'calendars', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'excerpt' ),
		'register_meta_box_cb' => 'sportspress_calendar_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_calendar_slug', 'calendars' ) ),
		'show_in_menu' => 'edit.php?post_type=sp_event',
		'show_in_admin_bar' => true,
//		'capability_type' => 'sp_calendar'
	);
	register_post_type( 'sp_calendar', $args );
}
add_action( 'init', 'sportspress_calendar_post_init' );

function sportspress_calendar_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_calendar_columns', 'sportspress_calendar_edit_columns' );

function sportspress_calendar_meta_init( $post ) {
	$seasons = get_the_terms( $post->ID, 'sp_season' );
	$venues = get_the_terms( $post->ID, 'sp_venue' );

	add_meta_box( 'sp_eventsdiv', __( 'Events', 'sportspress' ), 'sportspress_calendar_events_meta', 'sp_calendar', 'normal', 'high' );
}

function sportspress_calendar_events_meta( $post ) {
	$seasons = get_the_terms( $post->ID, 'sp_season' );

	$data = sportspress_get_calendar_data( $post->ID, true );

	sportspress_edit_calendar_table( $data );

	sportspress_nonce();
}
