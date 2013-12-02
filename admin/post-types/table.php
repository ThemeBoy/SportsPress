<?php
function sp_table_cpt_init() {
	$name = __( 'League Tables', 'sportspress' );
	$singular_name = __( 'League Table', 'sportspress' );
	$lowercase_name = __( 'league tables', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author' ),
		'register_meta_box_cb' => 'sp_table_meta_init',
		'rewrite' => array( 'slug' => 'table' ),
		'show_in_menu' => 'edit.php?post_type=sp_event'
	);
	register_post_type( 'sp_table', $args );
}
add_action( 'init', 'sp_table_cpt_init' );

function sp_table_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_div' => __( 'Divisions', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_table_columns', 'sp_table_edit_columns' );

function sp_table_meta_init() {
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_table_team_meta', 'sp_table', 'side', 'high' );
	add_meta_box( 'sp_statsdiv', __( 'League Table', 'sportspress' ), 'sp_table_stats_meta', 'sp_table', 'normal', 'high' );
}

function sp_table_team_meta( $post ) {
	$division_id = sp_get_the_term_id( $post->ID, 'sp_div', 0 );
	?>
	<div>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Divisions', 'sportspress' ) ),
				'taxonomy' => 'sp_div',
				'name' => 'sp_div',
				'selected' => $division_id
			);
			sp_dropdown_taxonomies( $args );
			?>
		</p>
		<?php
		sp_post_checklist( $post->ID, 'sp_team', 'block', 'sp_div' );
		sp_post_adder( 'sp_team' );
		?>
	</div>
	<?php
	sp_nonce();
}

function sp_table_stats_meta( $post ) {
	$div_id = sp_get_the_term_id( $post->ID, 'sp_div', 0 );
	$team_ids = (array)get_post_meta( $post->ID, 'sp_team', false );
	$stats = (array)get_post_meta( $post->ID, 'sp_teams', true );

	// Equation Operating System
	$eos = new eqEOS();

	// Get labels from result variables
	$result_labels = (array)sp_get_var_labels( 'sp_result' );

	// Get labels from outcome variables
	$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

	// Get all divisions populated with stats where available
	$data = sp_array_combine( $team_ids, $stats );

	// Get equations from statistics variables
	$equations = sp_get_var_equations( 'sp_stat' );

	// Create entry for each team in totals
	$totals = array();
	$placeholders = array();

	foreach ( $team_ids as $team_id ):
		if ( ! $team_id )
			continue;

		$totals[ $team_id ] = array( 'eventsplayed' => 0 );

		foreach ( $result_labels as $key => $value ):
			$totals[ $team_id ][ $key . 'for' ] = 0;
			$totals[ $team_id ][ $key . 'against' ] = 0;
		endforeach;

		foreach ( $outcome_labels as $key => $value ):
			$totals[ $team_id ][ $key ] = 0;
		endforeach;

		// Get statis stats
		$static = get_post_meta( $team_id, 'sp_stats', true );

		// Create placeholders entry for the team
		$placeholders[ $team_id ] = array();

		// Add static stats to placeholders
		if ( array_key_exists( $div_id, $static ) ):
			$placeholders[ $team_id ] = $static[ $div_id ];
		endif;

	endforeach;

	$args = array(
		'post_type' => 'sp_event',
		'numberposts' => -1,
		'posts_per_page' => -1,
		'tax_query' => array(
			array(
				'taxonomy' => 'sp_div',
				'field' => 'id',
				'terms' => $div_id
			)
		)
	);
	$events = get_posts( $args );

	// Event loop
	foreach( $events as $event ):

		$results = (array)get_post_meta( $event->ID, 'sp_results', true );

		foreach ( $results as $team_id => $team_result ):

			// Increment events played
			$totals[ $team_id ]['eventsplayed']++;

			foreach ( $team_result as $key => $value ):

				if ( $key == 'outcome' ):
					if ( array_key_exists( $value, $totals[ $team_id ] ) ):
						$totals[ $team_id ][ $value ]++;
					endif;
				else:
					if ( array_key_exists( $key . 'for', $totals[ $team_id ] ) ):
						$totals[ $team_id ][ $key . 'for' ] += $value;
					endif;
				endif;

			endforeach;

		endforeach;

	endforeach;

	// Fill in empty placeholder values for each team
	foreach ( $team_ids as $team_id ):
		foreach ( $equations as $key => $value ):
			if ( sp_array_value( $placeholders[ $team_id ], $key, '' ) == '' ):
				$placeholders[ $team_id ][ $key ] = $eos->solveIF( str_replace( ' ', '', $value ), $totals[ $team_id ] );
			endif;
		endforeach;
	endforeach;

	// Get columns from statistics variables
	$columns = sp_get_var_labels( 'sp_stat' );

	sp_league_table( $columns, $data, $placeholders );
	sp_nonce();
}
?>