<?php
function sp_team_cpt_init() {
	$name = __( 'Teams', 'sportspress' );
	$singular_name = __( 'Team', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name );
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
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsors', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_team_columns', 'sp_team_edit_columns' );

function sp_team_stats_meta( $post ) {
	$leagues = (array)get_the_terms( $post->ID, 'sp_league' );
	$stats = (array)get_post_meta( $post->ID, 'sp_stats', true );

	// Generate array of all league ids
	$league_ids = array( 0 );
	foreach ( $leagues as $key => $value ):
		if ( is_object( $value ) && property_exists( $value, 'term_id' ) )
			$league_ids[] = $value->term_id;
	endforeach;

	// Get all leagues populated with stats where availabled
	$data = sp_array_combine( $league_ids, sp_array_value( $stats, 0, array() ) );

	// Generate array of placeholder values for each league
	$placeholders = array();
	foreach ( $league_ids as $league_id ):
		$args = array(
			'post_type' => 'sp_event',
			'meta_key' => 'sp_team',
			'meta_value' => $post->ID,
			'tax_query' => array(
				array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => $league_id
				)
			)
		);
		$placeholders[ $league_id ] = sp_get_stats_row( $args );
	endforeach;

	sp_stats_table( $data, $placeholders, 0, array( 'League', 'P', 'W', 'D', 'L', 'F', 'A', 'GD', 'Pts' ), false, 'sp_league' );
	sp_nonce();
}
?>