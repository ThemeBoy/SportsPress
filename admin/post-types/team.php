<?php
function sportspress_team_post_init() {
	$name = __( 'Teams', 'sportspress' );
	$singular_name = __( 'Team', 'sportspress' );
	$lowercase_name = __( 'teams', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_team_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_team_slug', 'teams' ) ),
		'menu_icon' => 'dashicons-shield-alt',
		'capability_type' => 'sp_team'
	);
	register_post_type( 'sp_team', $args );
}
add_action( 'init', 'sportspress_team_post_init' );

function sportspress_team_meta_init( $post ) {
	$leagues = (array)get_the_terms( $post->ID, 'sp_season' );

	remove_meta_box( 'submitdiv', 'sp_team', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'sp_team', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_team', 'side' );
	add_meta_box( 'postimagediv', __( 'Logo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_team', 'side', 'high' );

	if ( $leagues && $leagues != array(0) ):
		add_meta_box( 'sp_columnssdiv', __( 'Table Columns', 'sportspress' ), 'sportspress_team_columns_meta', 'sp_team', 'normal', 'high' );
	endif;
}

function sportspress_team_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_logo' => '&nbsp;',
		'title' => __( 'Team', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_team_columns', 'sportspress_team_edit_columns' );

function sportspress_team_columns_meta( $post ) {
	$leagues = (array)get_the_terms( $post->ID, 'sp_season' );
	$columns = (array)get_post_meta( $post->ID, 'sp_columns', true );

	// Equation Operating System
	$eos = new eqEOS();

	// Get labels from result variables
	$result_labels = (array)sportspress_get_var_labels( 'sp_result' );

	// Get labels from outcome variables
	$outcome_labels = (array)sportspress_get_var_labels( 'sp_outcome' );

	// Generate array of all league ids
	$div_ids = array();
	foreach ( $leagues as $key => $value ):
		if ( is_object( $value ) && property_exists( $value, 'term_id' ) )
			$div_ids[] = $value->term_id;
	endforeach;

	// Get all leagues populated with columns where available
	$data = sportspress_array_combine( $div_ids, $columns );

	// Get equations from column variables
	$equations = sportspress_get_var_equations( 'sp_column' );

	// Initialize placeholders array
	$placeholders = array();

	foreach ( $div_ids as $div_id ):

		$totals = array( 'eventsplayed' => 0, 'streak' => 0, 'last10' => null );

		foreach ( $result_labels as $key => $value ):
			$totals[ $key . 'for' ] = 0;
			$totals[ $key . 'against' ] = 0;
		endforeach;

		foreach ( $outcome_labels as $key => $value ):
			$totals[ $key ] = 0;
		endforeach;

		// Initialize streaks counter
		$streak = array( 'name' => '', 'count' => 0, 'fire' => 1 );

		// Initialize last 10 counter
		$last10 = array();

		// Add outcome types to last 10 counter
		foreach( $outcome_labels as $key => $value ):
			$last10[ $key ] = 0;
		endforeach;

		// Get all events involving the team in current season
		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'sp_team',
					'value' => $post->ID
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'sp_season',
					'field' => 'id',
					'terms' => $div_id
				)
			)
		);
		$events = get_posts( $args );

		foreach( $events as $event ):
			$results = (array)get_post_meta( $event->ID, 'sp_results', true );
			foreach ( $results as $team_id => $team_result ):
				foreach ( $team_result as $key => $value ):
					if ( $team_id == $post->ID ):
						if ( $key == 'outcome' ):

							// Increment events played and outcome count
							if ( array_key_exists( $value, $totals ) ):
								$totals['eventsplayed']++;
								$totals[ $value ]++;
							endif;

							if ( $value && $value != '-1' ):

								// Add to streak counter
								if ( $streak['fire'] && ( $streak['name'] == '' || $streak['name'] == $value ) ):
									$streak['name'] = $value;
									$streak['count'] ++;
								else:
									$streak['fire'] = 0;
								endif;

								// Add to last 10 counter if sum is less than 10
								if ( array_key_exists( $value, $last10 ) && array_sum( $last10 ) < 10 ):
									$last10[ $value ] ++;
								endif;

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

		// Compile streaks counter and add to totals
		$args=array(
			'name' => $streak['name'],
			'post_type' => 'sp_outcome',
			'post_status' => 'publish',
			'posts_per_page' => 1
		);
		$outcomes = get_posts( $args );

		if ( $outcomes ):
			$outcome = $outcomes[0];
			$totals['streak'] = $outcome->post_title . $streak['count'];
		endif;

		// Add last 10 to totals
		$totals['last10'] = $last10;

		// Generate array of placeholder values for each league
		$placeholders[ $div_id ] = array();
		foreach ( $equations as $key => $value ):
			if ( $totals['eventsplayed'] > 0 ):
				$placeholders[ $div_id ][ $key ] = sportspress_solve( $value, $totals );
			else:
				$placeholders[ $div_id ][ $key ] = 0;
			endif;
		endforeach;

	endforeach;

	// Get columns from statistics variables
	$columns = sportspress_get_var_labels( 'sp_column' );

	sportspress_edit_team_columns_table( $columns, $data, $placeholders );
	sportspress_nonce();
}
