<?php
function sp_event_cpt_init() {
	$name = __( 'Events', 'sportspress' );
	$singular_name = __( 'Event', 'sportspress' );
	$lowercase_name = __( 'events', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'comments' ),
		'register_meta_box_cb' => 'sp_event_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_event_slug', 'event' ) ),
		'capability_type' => 'sp_event'
	);
	register_post_type( 'sp_event', $args );
}
add_action( 'init', 'sp_event_cpt_init' );

function sp_event_display_scheduled( $posts ) {
	global $wp_query, $wpdb;
	if ( is_single() && $wp_query->post_count == 0 && isset( $wp_query->query_vars['sp_event'] )) {
		$posts = $wpdb->get_results( $wp_query->request );
	}
	return $posts;
}
add_filter( 'the_posts', 'sp_event_display_scheduled' );

function sp_event_meta_init( $post ) {
	$limit = get_option( 'sp_event_team_count' );
	$teams = array_pad( array_slice( (array)get_post_meta( $post->ID, 'sp_team', false ), 0, $limit ), $limit, 0 );

	remove_meta_box( 'submitdiv', 'sp_event', 'side' );
	add_meta_box( 'submitdiv', __( 'Event', 'sportspress' ), 'post_submit_meta_box', 'sp_event', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_event_team_meta', 'sp_event', 'side', 'high' );
	if ( $teams != array_pad( array_slice( array(), 0, $limit ), $limit, 0 ) ):
		add_meta_box( 'sp_playersdiv', __( 'Players', 'sportspress' ), 'sp_event_players_meta', 'sp_event', 'normal', 'high' );
		add_meta_box( 'sp_resultsdiv', __( 'Results', 'sportspress' ), 'sp_event_results_meta', 'sp_event', 'normal', 'high' );
	endif;
	add_meta_box( 'sp_articlediv', __( 'Article', 'sportspress' ), 'sp_event_article_meta', 'sp_event', 'normal', 'high' );
}

function sp_event_team_meta( $post ) {
	$limit = get_option( 'sp_event_team_count' );
	$teams = array_pad( array_slice( (array)get_post_meta( $post->ID, 'sp_team', false ), 0, $limit ), $limit, 0 );
	$players = (array)get_post_meta( $post->ID, 'sp_player', false );
	for ( $i = 0; $i < $limit; $i++ ):
		?>
		<div>
			<p class="sp-tab-select sp-title-generator">
				<?php
				$args = array(
					'post_type' => 'sp_team',
					'name' => 'sp_team[]',
					'class' => 'sportspress-pages',
					'show_option_none' => sprintf( __( 'Select %s' ), 'Team' ),
					'selected' => $teams[ $i ]
				);
				wp_dropdown_pages( $args );
				?>
			</p>
			<ul id="sp_team-tabs" class="wp-tab-bar sp-tab-bar">
				<li class="wp-tab-active"><a href="#sp_player-all"><?php _e( 'Players', 'sportspress' ); ?></a></li>
				<li class="wp-tab"><a href="#sp_staff-all"><?php _e( 'Staff', 'sportspress' ); ?></a></li>
			</ul>
			<?php
			sp_post_checklist( $post->ID, 'sp_player', 'block', 'sp_team', $i );
			sp_post_checklist( $post->ID, 'sp_staff', 'none', 'sp_team', $i );
			?>
		</div>
		<?php
	endfor;
	sp_post_adder( 'sp_team' );
	sp_nonce();
}

function sp_event_players_meta( $post ) {
	$limit = get_option( 'sp_event_team_count' );
	$teams = array_pad( array_slice( (array)get_post_meta( $post->ID, 'sp_team', false ), 0, $limit ), $limit, 0 );

	$stats = (array)get_post_meta( $post->ID, 'sp_players', true );

	// Get columns from result variables
	$columns = sp_get_var_labels( 'sp_statistic', true );

	foreach ( $teams as $key => $team_id ):
		if ( ! $team_id ) continue;

		// Get results for players in the team
		$players = sp_array_between( (array)get_post_meta( $post->ID, 'sp_player', false ), 0, $key );
		$data = sp_array_combine( $players, sp_array_value( $stats, $team_id, array() ) );

		?>
		<div>
			<p><strong><?php echo get_the_title( $team_id ); ?></strong></p>
			<?php sp_event_players_table( $columns, $data, $team_id ); ?>
		</div>
		<?php

	endforeach;

}

function sp_event_results_meta( $post ) {
	$limit = get_option( 'sp_event_team_count' );
	$teams = array_pad( array_slice( (array)get_post_meta( $post->ID, 'sp_team', false ), 0, $limit ), $limit, 0 );
	
	// Teams
	if ( $teams == array_pad( array_slice( array(), 0, $limit ), $limit, 0 ) ):

		?>
		<p><strong><?php echo $team_id ? get_the_title( $team_id ) : sprintf( __( 'Select %s' ), 'Teams' ); ?></strong></p>
		<?php

	else:

		$results = (array)get_post_meta( $post->ID, 'sp_results', true );

		// Get columns from result variables
		$columns = sp_get_var_labels( 'sp_result' );

		// Get results for all teams
		$data = sp_array_combine( $teams, $results );

		?>
		<div>
			<?php sp_event_results_table( $columns, $data ); ?>
		</div>
		<?php

	endif;
}

function sp_event_article_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sp_event_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Event', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_kickoff' => __( 'Kick-off', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_event_columns', 'sp_event_edit_columns' );

function sp_event_edit_sortable_columns( $columns ) {
	$columns['sp_kickoff'] = 'sp_kickoff';
	return $columns;
}
add_filter( 'manage_edit-sp_event_sortable_columns', 'sp_event_edit_sortable_columns' );
?>