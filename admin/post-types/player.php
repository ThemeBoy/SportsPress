<?php
function sp_player_cpt_init() {
	$name = __( 'Players', 'sportspress' );
	$singular_name = __( 'Player', 'sportspress' );
	$lowercase_name = __( 'players', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sp_player_meta_init',
		'rewrite' => array( 'slug' => 'player' ),
		'menu_position' => 44
	);
	register_post_type( 'sp_player', $args );
}
add_action( 'init', 'sp_player_cpt_init' );

function sp_player_meta_init() {
	remove_meta_box( 'submitdiv', 'sp_player', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'sp_player', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_player', 'side' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_player_team_meta', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_statsdiv', __( 'Statistics', 'sportspress' ), 'sp_player_stats_meta', 'sp_player', 'normal', 'high' );
	add_meta_box( 'sp_profilediv', __( 'Profile' ), 'sp_player_profile_meta', 'sp_player', 'normal', 'high' );
}

function sp_player_team_meta( $post ) {
	sp_post_checklist( $post->ID, 'sp_team' );
	sp_post_adder( 'sp_team' );
	sp_nonce();
}

function sp_player_stats_meta( $post ) {
	$team_ids = (array)get_post_meta( $post->ID, 'sp_team', false );
	$divisions = (array)get_the_terms( $post->ID, 'sp_div' );
	$stats = (array)get_post_meta( $post->ID, 'sp_metrics', true );

	// Equation Operating System
	$eos = new eqEOS();

	// Get labels from metric variables
	$metric_labels = (array)sp_get_var_labels( 'sp_metric' );

	// Generate array of all division ids
	$div_ids = array();
	foreach ( $divisions as $key => $value ):
		if ( is_object( $value ) && property_exists( $value, 'term_id' ) )
			$div_ids[] = $value->term_id;
	endforeach;

	unset( $team_ids[0] );

	if ( empty( $team_ids ) ):
		?>
		<p><strong><?php printf( __( 'Select %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ); ?></strong></p>
		<?php
		return;
	endif;

	// Initialize placeholders array
	$placeholders = array();

	$team_num = sizeof( $team_ids );

	// Loop through statistics for each team
	foreach ( $team_ids as $team_id ):

		$data = array();

		// Get all divisions populated with stats where available
		$data[ $team_id ] = sp_array_combine( $div_ids, $stats[ $team_id ] );

		// Get equations from statistics variables
		$equations = sp_get_var_equations( 'sp_metric' );

		foreach ( $div_ids as $div_id ):

			$totals = array( 'eventsattended' => 0, 'eventsplayed' => 0 );

			foreach ( $metric_labels as $key => $value ):
				$totals[ $key ] = 0;
			endforeach;
		
			$args = array(
				'post_type' => 'sp_event',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'sp_team',
						'value' => $team_id
					),
					array(
						'key' => 'sp_player',
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
				$totals['eventsattended']++;
				$totals['eventsplayed']++; // TODO: create tab for substitutes in sidebar
				$team_metrics = (array)get_post_meta( $event->ID, 'sp_players', true );
				if ( array_key_exists( $team_id, $team_metrics ) ):
					$players = sp_array_value( $team_metrics, $team_id, array() );
					if ( array_key_exists( $post->ID, $players ) ):
						$player_metrics = sp_array_value( $players, $post->ID, array() );
						foreach ( $player_metrics as $key => $value ):
							if ( array_key_exists( $key, $totals ) ):
								$totals[ $key ] += $value;
							endif;
						endforeach;
					endif;
				endif;
			endforeach;

			// Generate array of placeholder values for each division
			$placeholders[ $team_id ][ $div_id ] = array();
			foreach ( $equations as $key => $value ):

				if ( empty( $value ) ):

					// Reflect totals
					$placeholders[ $team_id ][ $div_id ][ $key ] = sp_array_value( $totals, $key, 0 );

				else:

					// Calculate value
					$placeholders[ $team_id ][ $div_id ][ $key ] = $eos->solveIF( str_replace( ' ', '', $value ), $totals );

				endif;

			endforeach;

		endforeach;

		// Get columns from statistics variables
		$columns = sp_get_var_labels( 'sp_metric' );

		if ( $team_num > 1 ):
			?>
			<p><strong><?php echo get_the_title( $team_id ); ?></strong></p>
			<?php
		endif;

		sp_player_metrics_table( $columns, $data, $placeholders );

	endforeach;
}

function sp_player_profile_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sp_player_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name', 'sportspress' ),
		'sp_pos' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_div' => __( 'Divisions', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_player_columns', 'sp_player_edit_columns' );
?>