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
		'rewrite' => array( 'slug' => 'team' ),
		'menu_position' => 43
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
		'sp_logo' => '&nbsp;',
		'title' => __( 'Team', 'sportspress' ),
		'sp_div' => __( 'Divisions', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_team_columns', 'sp_team_edit_columns' );

function sp_team_stats_meta( $post ) {
	$divisions = (array)get_the_terms( $post->ID, 'sp_div' );
	$stats = (array)get_post_meta( $post->ID, 'sp_stats', true );

	// Equation Operating System
	$eos = new eqEOS();

	// Get labels from result variables
	$result_labels = (array)sp_get_var_labels( 'sp_result' );

	// Get labels from outcome variables
	$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

	// Merge result and outcome variable labels
	$labels = array_merge( $result_labels, $outcome_labels );

	$totals = array( 'eventsplayed' => 0 );
	$placeholders = array();

	foreach ( $result_labels as $key => $value ):
		$totals[ $key . 'for' ] = 0;
		$totals[ $key . 'against' ] = 0;
	endforeach;

	foreach ( $outcome_labels as $key => $value ):
		$totals[ $key ] = 0;
	endforeach;

	// Generate array of all division ids
	$div_id = array();
	foreach ( $divisions as $key => $value ):
		if ( is_object( $value ) && property_exists( $value, 'term_id' ) )
			$div_id[] = $value->term_id;
	endforeach;

	// Get all divisions populated with stats where available
	$data = sp_array_combine( $div_id, $stats );

	// Get equations from statistics variables
	$equations = sp_get_var_equations( 'sp_stat' );

	foreach ( $div_id as $div_id ):
		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'sp_team',
					'value' => $post->ID
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'sp_div',
					'field' => 'id',
					'terms' => $div_id
				)
			)
		);
		$events = get_posts( $args );
		foreach( $events as $event ):
			$results = (array)get_post_meta( $event->ID, 'sp_results', true );
			$totals['eventsplayed']++;
			foreach ( $results as $team_id => $team_result ):
				foreach ( $team_result as $key => $value ):
					if ( $team_id == $post->ID ):
						if ( $key == 'outcome' ):
							if ( array_key_exists( $value, $totals ) ):
								$totals[ $value]++;
							endif;
						else:
							if ( array_key_exists( $key . 'for', $totals ) ):
								$totals[ $key . 'for' ] += $value;
							endif;
						endif;
					else:
						if ( $key != 'outcome' ):
							if ( array_key_exists( $key . 'against', $totals ) ):
								$totals[ $key . 'against' ] += $value;
							endif;
						endif;
					endif;
				endforeach;
			endforeach;
		endforeach;

		// Generate array of placeholder values for each division
		$placeholders[ $div_id ] = array();
		foreach ( $equations as $key => $value ):
			$placeholders[ $div_id ][ $key ] = $eos->solveIF( str_replace( ' ', '', $value ), $totals );
		endforeach;

		//$placeholders[ $division_id ] = sp_get_stats_row( 'sp_team', $args );
	endforeach;

	// Get columns from statistics variables
	$columns = sp_get_var_labels( 'sp_stat' );

	sp_team_stats_table( $columns, $data, $placeholders );
	sp_nonce();
}
?>