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

	// First one is empty
	unset( $teams[0] );

	remove_meta_box( 'submitdiv', 'sp_player', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'sp_player', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_player', 'side' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_player_team_meta', 'sp_player', 'side', 'high' );

	if ( $teams && ! empty( $teams ) && $seasons && is_array( $seasons ) && is_object( $seasons[0] ) ):
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

	// First one is empty
	unset( $team_ids[0] );

	// Initialize placeholders array
	$placeholders = array();

	$team_num = sizeof( $team_ids );

	// Loop through statistics for each team
	foreach ( $team_ids as $team_id ):
		
		if ( $team_num > 1 ):
			?>
			<p><strong><?php echo get_the_title( $team_id ); ?></strong></p>
			<?php
		endif;

		list( $columns, $data, $placeholders, $merged ) = sportspress_get_player_statistics_data( $post->ID, $team_id, true );

		sportspress_edit_player_statistics_table( $team_id, $columns, $data, $placeholders );

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
