<?php
function sportspress_event_post_init() {
	$name = __( 'Events', 'sportspress' );
	$singular_name = __( 'Event', 'sportspress' );
	$lowercase_name = __( 'events', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'has_archive' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'comments' ),
		'register_meta_box_cb' => 'sportspress_event_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_event_slug', 'events' ) ),
		'menu_icon' => 'dashicons-calendar',
		'capability_type' => 'sp_event'
	);
	register_post_type( 'sp_event', $args );
}
add_action( 'init', 'sportspress_event_post_init' );

function sportspress_event_display_scheduled( $posts ) {
	global $wp_query, $wpdb;
	if ( is_single() && $wp_query->post_count == 0 && isset( $wp_query->query_vars['sp_event'] )) {
		$posts = $wpdb->get_results( $wp_query->request );
	}
	return $posts;
}
add_filter( 'the_posts', 'sportspress_event_display_scheduled' );

function sportspress_event_meta_init( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );

	remove_meta_box( 'submitdiv', 'sp_event', 'side' );
	remove_meta_box( 'sp_venuediv', 'sp_event', 'side' );
	
	add_meta_box( 'submitdiv', __( 'Event', 'sportspress' ), 'post_submit_meta_box', 'sp_event', 'side', 'high' );
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_event_details_meta', 'sp_event', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_event_team_meta', 'sp_event', 'side', 'high' );
	if ( sizeof( $teams ) > 0 ):
		add_meta_box( 'sp_resultsdiv', __( 'Results', 'sportspress' ), 'sportspress_event_results_meta', 'sp_event', 'normal', 'high' );
		add_meta_box( 'sp_playersdiv', __( 'Players', 'sportspress' ), 'sportspress_event_players_meta', 'sp_event', 'normal', 'high' );
	endif;
	add_meta_box( 'sp_articlediv', __( 'Article', 'sportspress' ), 'sportspress_event_article_meta', 'sp_event', 'normal', 'high' );
}

function sportspress_event_details_meta( $post ) {
	$league_id = sportspress_get_the_term_id( $post->ID, 'sp_league', 0 );
	$season_id = sportspress_get_the_term_id( $post->ID, 'sp_season', 0 );
	$venue_id = sportspress_get_the_term_id( $post->ID, 'sp_venue', 0 );
	?>
	<div>
		<p><strong><?php _e( 'League', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'taxonomy' => 'sp_league',
				'name' => 'sp_league',
				'selected' => $league_id,
				'value' => 'term_id',
				'show_option_none' => __( '-- Not set --', 'sportspress' ),
			);
			sportspress_dropdown_taxonomies( $args );
			?>
		</p>
		<p><strong><?php _e( 'Season', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'taxonomy' => 'sp_season',
				'name' => 'sp_season',
				'selected' => $season_id,
				'value' => 'term_id',
				'show_option_none' => __( '-- Not set --', 'sportspress' ),
			);
			sportspress_dropdown_taxonomies( $args );
			?>
		</p>
		<p><strong><?php _e( 'Venue', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'taxonomy' => 'sp_venue',
				'name' => 'sp_venue',
				'selected' => $venue_id,
				'value' => 'term_id',
				'show_option_none' => __( '-- Not set --', 'sportspress' ),
			);
			sportspress_dropdown_taxonomies( $args );
			?>
		</p>
	</div>
	<?php
}

function sportspress_event_team_meta( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );
	foreach ( $teams as $key => $value ):
	?>
		<div class="sp-clone">
			<p class="sp-tab-select sp-title-generator">
				<?php
				$args = array(
					'post_type' => 'sp_team',
					'name' => 'sp_team[]',
					'class' => 'sportspress-pages',
					'show_option_none' => sprintf( __( 'Remove', 'sportspress' ), 'Team' ),
					'option_none_value' => '0',
					'selected' => $value
				);
				wp_dropdown_pages( $args );
				?>
			</p>
			<ul id="sp_team-tabs" class="wp-tab-bar sp-tab-bar">
				<li class="wp-tab-active"><a href="#sp_player-all"><?php _e( 'Players', 'sportspress' ); ?></a></li>
				<li class="wp-tab"><a href="#sp_staff-all"><?php _e( 'Staff', 'sportspress' ); ?></a></li>
			</ul>
			<?php
			sportspress_post_checklist( $post->ID, 'sp_player', 'block', 'sp_team', $key );
			sportspress_post_checklist( $post->ID, 'sp_staff', 'none', 'sp_team', $key );
			?>
		</div>
	<?php endforeach; ?>
	<div class="sp-clone" data-clone-name="sp_team">
		<p class="sp-tab-select sp-title-generator">
			<?php
			$args = array(
				'post_type' => 'sp_team',
				'name' => 'sp_team_selector',
				'class' => 'sportspress-pages',
				'show_option_none' => '(' . sprintf( __( 'Select', 'sportspress' ) . ')', 'Team' ),
				'option_none_value' => '0'
			);
			wp_dropdown_pages( $args );
			?>
		</p>
	</div>
	<?php
	sportspress_post_adder( 'sp_team' );
	sportspress_nonce();
}

function sportspress_event_players_meta( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );
	$stats = (array)get_post_meta( $post->ID, 'sp_players', true );

	// Get columns from result variables
	$columns = sportspress_get_var_labels( 'sp_statistic' );

	foreach ( $teams as $key => $team_id ):
		if ( ! $team_id ) continue;

		// Get results for players in the team
		$players = sportspress_array_between( (array)get_post_meta( $post->ID, 'sp_player', false ), 0, $key );
		$data = sportspress_array_combine( $players, sportspress_array_value( $stats, $team_id, array() ) );

		?>
		<div>
			<p><strong><?php echo get_the_title( $team_id ); ?></strong></p>
			<?php sportspress_edit_event_players_table( $columns, $data, $team_id ); ?>
		</div>
		<?php

	endforeach;

}

function sportspress_event_results_meta( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );

	$results = (array)get_post_meta( $post->ID, 'sp_results', true );

	// Get columns from result variables
	$columns = sportspress_get_var_labels( 'sp_result' );

	// Get results for all teams
	$data = sportspress_array_combine( $teams, $results );

	?>
	<div>
		<?php sportspress_edit_event_results_table( $columns, $data ); ?>
	</div>
	<?php
}

function sportspress_event_article_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sportspress_event_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Event', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
		'sp_venue' => __( 'Venue', 'sportspress' ),
		'sp_kickoff' => __( 'Date/Time', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_event_columns', 'sportspress_event_edit_columns' );

function sportspress_event_edit_sortable_columns( $columns ) {
	$columns['sp_kickoff'] = 'sp_kickoff';
	return $columns;
}
add_filter( 'manage_edit-sp_event_sortable_columns', 'sportspress_event_edit_sortable_columns' );
