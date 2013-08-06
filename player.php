<?php
function sp_player_cpt_init() {
	$name = __( 'Players', 'sportspress' );
	$singular_name = __( 'Player', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_player_meta_init',
		'rewrite' => array( 'slug' => 'player' )
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
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );
	$leagues = (array)get_the_terms( $post->ID, 'sp_league' );
	$stats = (array)get_post_meta( $post->ID, 'sp_stats', true );

	// Generate array of all league ids
	$league_ids = array( 0 );
	foreach ( $leagues as $key => $value ):
		if ( is_object( $value ) && property_exists( $value, 'term_id' ) )
			$league_ids[] = $value->term_id;
	endforeach;

	// Get all teams populated with overall stats where availabled
	$data = sp_array_combine( $league_ids, sp_array_value( $stats, 0, array() ) );

	// Generate array of placeholder values for each league
	$placeholders = array();
	foreach ( $league_ids as $league_id ):
		$args = array(
			'post_type' => 'sp_event',
			'meta_query' => array(
				array(
					'key' => 'sp_player',
					'value' => $post->ID
				)
			)
		);
		if ( $league_id ):
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => $league_id
				)
			);
		endif;
		$placeholders[ $league_id ] = sp_get_stats_row( 'sp_player', $args );
	endforeach;
	?>
	<p><strong><?php _e( 'Overall', 'sportspress' ); ?></strong></p>
	<?php sp_stats_table( $data, $placeholders, 0, array( 'Team', 'Played', 'Goals', 'Assists', 'Yellow Cards', 'Red Cards' ), true, 'sp_league' ); ?>
	<?php

	// Leagues
	foreach ( $teams as $team ):
		if ( !$team ) continue;

		// Get all leagues populated with stats where availabled
		$data = sp_array_combine( $league_ids, sp_array_value( $stats, $team, array() ) );

		// Generate array of placeholder values for each league
		$placeholders = array();
		foreach ( $league_ids as $league_id ):
			$args = array(
				'post_type' => 'sp_event',
				'meta_query' => array(
					array(
						'key' => 'sp_player',
						'value' => $post->ID
					),
					array(
						'key' => 'sp_team',
						'value' => $team
					)
				),
				'tax_query' => array(
					array(
						'taxonomy' => 'sp_league',
						'field' => 'id',
						'terms' => $league_id
					)
				)
			);
			$placeholders[ $league_id ] = sp_get_stats_row( 'sp_player', $args );
		endforeach;
		?>
		<p><strong><?php echo get_the_title( $team ); ?></strong></p>
		<?php sp_stats_table( $data, $placeholders, $team, array( 'Team', 'Played', 'Goals', 'Assists', 'Yellow Cards', 'Red Cards' ), true, 'sp_league' ); ?>
		<?php
	endforeach;

}

function sp_player_profile_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sp_player_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsors', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_player_columns', 'sp_player_edit_columns' );
?>