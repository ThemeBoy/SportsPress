<?php
function sp_table_cpt_init() {
	$name = __( 'League Tables', 'sportspress' );
	$singular_name = __( 'League Table', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_table_meta_init',
		'rewrite' => array( 'slug' => 'table' )
	);
	register_post_type( 'sp_table', $args );
}
add_action( 'init', 'sp_table_cpt_init' );

function sp_table_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_table_columns', 'sp_table_edit_columns' );

function sp_table_meta_init() {
	remove_meta_box( 'submitdiv', 'sp_table', 'side' );
	add_meta_box( 'submitdiv', __( 'Event', 'sportspress' ), 'post_submit_meta_box', 'sp_table', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_table_team_meta', 'sp_table', 'side', 'high' );
	add_meta_box( 'sp_statsdiv', __( 'League Table', 'sportspress' ), 'sp_table_stats_meta', 'sp_table', 'normal', 'high' );
}

function sp_table_team_meta( $post ) {
	sp_post_checklist( $post->ID, 'sp_team' );
	sp_post_adder( 'sp_team' );
	sp_nonce();
}

function sp_table_stats_meta( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );
	$stats = (array)get_post_meta( $post->ID, 'sp_stats', true );
	$leagues = (array)get_the_terms( $post->ID, 'sp_league' );
	foreach ( $leagues as $league ):
		if ( is_object( $league ) && property_exists( $league, 'term_id' ) )
			$index = $league->term_id;
		else
			$index = 0;

		$data = sp_array_combine( $teams, sp_array_value( $stats, $index, array() ) );

		$placeholders = array();
		foreach ( $teams as $team ):
			$placeholders[ $team ] = sp_get_stats( $team, 0, $index );
		endforeach;

		sp_stats_table( $data, $placeholders, $index, array( 'Team', 'P', 'W', 'D', 'L', 'F', 'A', 'GD', 'Pts' ), false );
	endforeach;
}
?>