<?php
function sportspress_player_post_init() {
	$name = __( 'Players', 'sportspress' );
	$singular_name = __( 'Player', 'sportspress' );
	$lowercase_name = __( 'players', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_player_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_player_slug', 'players' ) ),
		'menu_icon' => 'dashicons-groups',
		'capability_type' => 'sp_player',
	);
	register_post_type( 'sp_player', $args );
}
add_action( 'init', 'sportspress_player_post_init' );

function sportspress_player_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_player_columns', 'sportspress_player_edit_columns' );

function sportspress_player_meta_init( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );
	$seasons = (array)get_the_terms( $post->ID, 'sp_season' );

	remove_meta_box( 'submitdiv', 'sp_player', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'sp_player', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_player', 'side' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_player_team_meta', 'sp_player', 'side', 'high' );

	if ( $teams && $teams != array(0) && $seasons && is_array( $seasons ) && is_object( $seasons[0] ) ):
		add_meta_box( 'sp_statsdiv', __( 'Player Statistics', 'sportspress' ), 'sportspress_player_stats_meta', 'sp_player', 'normal', 'high' );
	endif;

	add_meta_box( 'sp_metricsdiv', __( 'Player Metrics', 'sportspress' ), 'sportspress_player_metrics_meta', 'sp_player', 'normal', 'high' );
	add_meta_box( 'sp_profilediv', __( 'Profile' ), 'sportspress_player_profile_meta', 'sp_player', 'normal', 'high' );

}

function sportspress_player_team_meta( $post ) {
	sportspress_post_checklist( $post->ID, 'sp_team' );
	sportspress_post_adder( 'sp_team' );
}

function sportspress_player_stats_meta( $post ) {
	$team_ids = (array)get_post_meta( $post->ID, 'sp_team', false );
	$seasons = (array)get_the_terms( $post->ID, 'sp_season' );
	$stats = (array)get_post_meta( $post->ID, 'sp_statistics', true );

	// Equation Operating System
	$eos = new eqEOS();

	// Get labels from statistic variables
	$statistic_labels = (array)sportspress_get_var_labels( 'sp_statistic' );

	// Generate array of all league ids
	$div_ids = array();
	foreach ( $seasons as $key => $value ):
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

		// Get all seasons populated with stats where available
		$data[ $team_id ] = sportspress_array_combine( $div_ids, sportspress_array_value( $stats, $team_id, array() ) );

		// Get equations from statistics variables
		$equations = sportspress_get_var_equations( 'sp_statistic' );

		foreach ( $div_ids as $div_id ):

			$totals = array( 'eventsattended' => 0, 'eventsplayed' => 0 );

			foreach ( $statistic_labels as $key => $value ):
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
						'taxonomy' => 'sp_season',
						'field' => 'id',
						'terms' => $div_id
					)
				)
			);
			$events = get_posts( $args );
			foreach( $events as $event ):
				$totals['eventsattended']++;
				$totals['eventsplayed']++; // TODO: create tab for substitutes in sidebar
				$team_statistics = (array)get_post_meta( $event->ID, 'sp_players', true );
				if ( array_key_exists( $team_id, $team_statistics ) ):
					$players = sportspress_array_value( $team_statistics, $team_id, array() );
					if ( array_key_exists( $post->ID, $players ) ):
						$player_statistics = sportspress_array_value( $players, $post->ID, array() );
						foreach ( $player_statistics as $key => $value ):
							if ( array_key_exists( $key, $totals ) ):
								$totals[ $key ] += $value;
							endif;
						endforeach;
					endif;
				endif;
			endforeach;

			// Generate array of placeholder values for each league
			$placeholders[ $team_id ][ $div_id ] = array();
			foreach ( $equations as $key => $value ):

				if ( empty( $value ) ):

					// Reflect totals
					$placeholders[ $team_id ][ $div_id ][ $key ] = sportspress_array_value( $totals, $key, 0 );

				else:

					// Calculate value
					if ( sizeof( $events ) > 0 ):
						$placeholders[ $team_id ][ $div_id ][ $key ] = sportspress_solve( $value, $totals );
					else:
						$placeholders[ $team_id ][ $div_id ][ $key ] = 0;
					endif;

				endif;

			endforeach;

		endforeach;

		// Get columns from statistics variables
		$columns = sportspress_get_var_labels( 'sp_statistic' );

		if ( $team_num > 1 ):
			?>
			<p><strong><?php echo get_the_title( $team_id ); ?></strong></p>
			<?php
		endif;

		sportspress_edit_player_statistics_table( $columns, $data, $placeholders );

	endforeach;
}

function sportspress_player_profile_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sportspress_player_metrics_meta( $post ) {

	$number = get_post_meta( $post->ID, 'sp_number', true );
	$details = get_post_meta( $post->ID, 'sp_metrics', true );

	?>
	<p><strong><?php _e( 'Player Number', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_number" type="text" size="4" id="sp_number" value="<?php echo $number; ?>">
	</p>
	<?php

	$args = array(
		'post_type' => 'sp_metric',
		'numberposts' => -1,
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	);

	$vars = get_posts( $args );

	$custom = array();
	foreach ( $vars as $var ):
	?>
		<p><strong><?php echo $var->post_title; ?></strong></p>
		<p>
			<input name="sp_metrics[<?php echo $var->post_name; ?>]" type="text" value="<?php echo sportspress_array_value( $details, $var->post_name, ''); ?>">
		</p>
	<?php
	endforeach;
	sportspress_nonce();
}
