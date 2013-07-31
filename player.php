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

	// Overall
	$data = sp_array_combine( $teams, sp_array_value( $stats, 0, array() ) );
	?>
	<p><strong><?php _e( 'Overall', 'sportspress' ); ?></strong></p>
	<?php sp_stats_table( $data, array(), 0, array( 'Team', 'Played', 'Goals', 'Assists', 'Yellow Cards', 'Red Cards' ) ); ?>
	<?php

	// Leagues
	foreach ( $leagues as $league ):
		if ( !$league ) continue;
		$data = sp_array_combine( $teams, sp_array_value( $stats, $league->term_id, array() ) );
		?>
		<p><strong><?php echo $league->name; ?></strong></p>
		<?php sp_stats_table( $data, array(), $league->term_id, array( 'Team', 'Played', 'Goals', 'Assists', 'Yellow Cards', 'Red Cards' ) ); ?>
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