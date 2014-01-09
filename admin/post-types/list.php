<?php
function sportspress_list_post_init() {
	$name = __( 'Player Lists', 'sportspress' );
	$singular_name = __( 'Player List', 'sportspress' );
	$lowercase_name = __( 'player lists', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author' ),
		'register_meta_box_cb' => 'sportspress_list_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_list_slug', 'lists' ) ),
		'show_in_menu' => 'edit.php?post_type=sp_player',
		'show_in_admin_bar' => true,
		'capability_type' => 'sp_list'
	);
	register_post_type( 'sp_list', $args );
}
add_action( 'init', 'sportspress_list_post_init' );

function sportspress_list_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_player' => __( 'Players', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_list_columns', 'sportspress_list_edit_columns' );

function sportspress_list_meta_init( $post ) {
	$players = (array)get_post_meta( $post->ID, 'sp_player', false );

	remove_meta_box( 'sp_seasondiv', 'sp_list', 'side' );
	add_meta_box( 'sp_playerdiv', __( 'Players', 'sportspress' ), 'sportspress_list_player_meta', 'sp_list', 'side', 'high' );

	if ( $players && $players != array(0) ):
		add_meta_box( 'sp_statsdiv', __( 'Player List', 'sportspress' ), 'sportspress_list_stats_meta', 'sp_list', 'normal', 'high' );
	endif;
}

function sportspress_list_player_meta( $post ) {
	$season_id = sportspress_get_the_term_id( $post->ID, 'sp_season', 0 );
	$team_id = get_post_meta( $post->ID, 'sp_team', true );
	?>
	<div>
		<p><strong><?php _e( 'Season', 'sportspress' ); ?></strong></p>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'taxonomy' => 'sp_season',
				'name' => 'sp_season',
				'selected' => $season_id,
				'value' => 'term_id'
			);
			sportspress_dropdown_taxonomies( $args );
			?>
		</p>
		<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'post_type' => 'sp_team',
				'name' => 'sp_team',
				'selected' => $team_id
			);
			wp_dropdown_pages( $args );
			?>
		</p>
		<p><strong><?php _e( 'Players', 'sportspress' ); ?></strong></p>
		<?php
		sportspress_post_checklist( $post->ID, 'sp_player', 'block', 'sp_team' );
		sportspress_post_adder( 'sp_player' );
		?>
	</div>
	<?php
	sportspress_nonce();
}

function sportspress_list_stats_meta( $post ) {

	list( $columns, $data, $placeholders, $merged ) = sportspress_get_player_list_data( $post->ID, true );

	sportspress_edit_player_table( $columns, $data, $placeholders );
	sportspress_nonce();
}
