<?php
function sp_team_cpt_init() {
	$name = __( 'Teams', 'sportspress' );
	$singular_name = __( 'Team', 'sportspress' );
	$lowercase_name = __( 'teams', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_team_meta_init',
		'rewrite' => array( 'slug' => 'team' )
	);
	register_post_type( 'sp_team', $args );
}
add_action( 'init', 'sp_team_cpt_init' );

function sp_team_meta_init() {
	remove_meta_box( 'submitdiv', 'sp_team', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'sp_team', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_team', 'side' );
	add_meta_box( 'postimagediv', __( 'Logo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_team', 'side', 'high' );
	add_meta_box( 'sp_statsdiv', __( 'Statistics', 'sportspress' ), 'sp_team_stats_meta', 'sp_team', 'normal', 'high' );
}

function sp_team_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '&nbsp;',
		'title' => __( 'Team', 'sportspress' ),
		'sp_division' => __( 'Divisions', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_team_columns', 'sp_team_edit_columns' );

function sp_team_stats_meta( $post ) {
	$divisions = (array)get_the_terms( $post->ID, 'sp_division' );
	$stats = (array)get_post_meta( $post->ID, 'sp_stats', true );

	// Generate array of all division ids
	$division_ids = array( 0 );
	foreach ( $divisions as $key => $value ):
		if ( is_object( $value ) && property_exists( $value, 'term_id' ) )
			$division_ids[] = $value->term_id;
	endforeach;

	// Get all divisions populated with stats where available
	$data = sp_array_combine( $division_ids, sp_array_value( $stats, 0, array() ) );

	// Generate array of placeholder values for each division
	$placeholders = array();
	foreach ( $division_ids as $division_id ):
		$args = array(
			'post_type' => 'sp_event',
			'meta_query' => array(
				array(
					'key' => 'sp_team',
					'value' => $post->ID
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'sp_division',
					'field' => 'id',
					'terms' => $division_id
				)
			)
		);
		$placeholders[ $division_id ] = sp_get_stats_row( 'sp_team', $args );
	endforeach;

	// Get column names from settings
	$stats_settings = get_option( 'sportspress_stats' );
	$columns = sp_get_eos_keys( $stats_settings['team'] );

	// Add first column label
	array_unshift( $columns, __( 'Division', 'sportspress' ) );

	sp_stats_table( $data, $placeholders, 0, $columns, false, 'sp_division' );
	sp_nonce();
}
?>