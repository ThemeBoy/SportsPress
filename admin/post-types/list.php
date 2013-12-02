<?php
function sp_list_cpt_init() {
	$name = __( 'Player Lists', 'sportspress' );
	$singular_name = __( 'Player List', 'sportspress' );
	$lowercase_name = __( 'player lists', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author' ),
		'register_meta_box_cb' => 'sp_list_meta_init',
		'rewrite' => array( 'slug' => 'list' ),
		'show_in_menu' => 'edit.php?post_type=sp_event'
	);
	register_post_type( 'sp_list', $args );
}
add_action( 'init', 'sp_list_cpt_init' );

function sp_list_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_player' => __( 'Players', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_div' => __( 'Divisions', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_list_columns', 'sp_list_edit_columns' );

function sp_list_meta_init() {
	add_meta_box( 'sp_playerdiv', __( 'Players', 'sportspress' ), 'sp_list_player_meta', 'sp_list', 'side', 'high' );
	add_meta_box( 'sp_statsdiv', __( 'Player List', 'sportspress' ), 'sp_list_stats_meta', 'sp_list', 'normal', 'high' );
}

function sp_list_player_meta( $post ) {
	$division_id = sp_get_the_term_id( $post->ID, 'sp_div', 0 );
	$team_id = get_post_meta( $post->ID, 'sp_team', true );
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
		<p class="sp-tab-select">
			<?php
			$args = array(
				'show_option_none' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ),
				'option_none_value' => '0',
				'post_type' => 'sp_team',
				'name' => 'sp_team',
				'selected' => $team_id
			);
			wp_dropdown_pages( $args );
			?>
		</p>
		<?php
		sp_post_checklist( $post->ID, 'sp_player', 'block', 'sp_team' );
		sp_post_adder( 'sp_player' );
		?>
	</div>
	<?php
	sp_nonce();
}

function sp_list_stats_meta( $post ) {
	$div_id = sp_get_the_term_id( $post->ID, 'sp_div', 0 );
	$team_id = get_post_meta( $post->ID, 'sp_team', true );
	$player_ids = (array)get_post_meta( $post->ID, 'sp_player', false );
	$stats = (array)get_post_meta( $post->ID, 'sp_players', true );

	// Equation Operating System
	$eos = new eqEOS();

	// Get labels from result variables
	$metric_labels = (array)sp_get_var_labels( 'sp_metric' );

	// Get all divisions populated with stats where available
	$data = sp_array_combine( $player_ids, $stats );

	// Get equations from statistics variables
	$equations = sp_get_var_equations( 'sp_metric' );

	// Create entry for each player in totals
	$totals = array();
	$placeholders = array();

	foreach ( $player_ids as $player_id ):
		if ( ! $player_id )
			continue;

		$totals[ $player_id ] = array( 'eventsattended' => 0, 'eventsplayed' => 0 );

		foreach ( $metric_labels as $key => $value ):
			$totals[ $player_id ][ $key ] = 0;
		endforeach;

		// Get static metrics
		$static = get_post_meta( $player_id, 'sp_metrics', true );

		// Create placeholders entry for the player
		$placeholders[ $player_id ] = array();

		// Add static metrics to placeholders
		if ( array_key_exists( $team_id, $static ) && array_key_exists( $div_id, $static[ $team_id ] ) ):
			$placeholders[ $player_id ] = $static[ $team_id ][ $div_id ];
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
		),
		'meta_query' => array(
			array(
				'key' => 'sp_team',
				'value' => $team_id,
			)
		)
	);
	$events = get_posts( $args );

	// Event loop
	foreach( $events as $event ):

		$teams = (array)get_post_meta( $event->ID, 'sp_players', true );

		if ( ! array_key_exists( $team_id, $teams ) )
			continue;

		$players = sp_array_value( $teams, $team_id, array() );

		foreach ( $players as $player_id => $player_metrics ):

			// Increment events played
			$totals[ $player_id ]['eventsplayed']++;

			foreach ( $player_metrics as $key => $value ):

				if ( array_key_exists( $key, $totals[ $player_id ] ) ):
					$totals[ $player_id ][ $key ] += $value;
				endif;

			endforeach;

		endforeach;

	endforeach;

	// Generate placeholder values for each team
	foreach ( $player_ids as $player_id ):
		if ( ! $player_id )
			continue;

		foreach ( $equations as $key => $value ):
			if ( sp_array_value( $placeholders[ $player_id ], $key, '' ) == '' ):

				if ( empty( $value ) ):

					// Reflect totals
					$placeholders[ $player_id ][ $key ] = sp_array_value( sp_array_value( $totals, $player_id, array() ), $key, 0 );

				else:

					// Calculate value
					$placeholders[ $player_id ][ $key ] = $eos->solveIF( str_replace( ' ', '', $value ), sp_array_value( $totals, $player_id, array() ) );

				endif;

			endif;

		endforeach;
	endforeach;

	sp_player_table( $metric_labels, $data, $placeholders );
	sp_nonce();
}
?>