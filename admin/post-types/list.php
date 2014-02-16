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
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail' ),
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
		'title' => __( 'Title', 'sportspress' ),
		'sp_player' => __( 'Players', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
		'sp_team' => __( 'Team', 'sportspress' ),
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_list_columns', 'sportspress_list_edit_columns' );

function sportspress_list_meta_init( $post ) {
	$players = (array)get_post_meta( $post->ID, 'sp_player', false );

	remove_meta_box( 'sp_seasondiv', 'sp_list', 'side' );
	remove_meta_box( 'sp_leaguediv', 'sp_list', 'side' );
	add_meta_box( 'sp_playerdiv', __( 'Players', 'sportspress' ), 'sportspress_list_player_meta', 'sp_list', 'side', 'high' );

	if ( $players && $players != array(0) ):
		add_meta_box( 'sp_statsdiv', __( 'Player List', 'sportspress' ), 'sportspress_list_stats_meta', 'sp_list', 'normal', 'high' );
	endif;

	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_list_details_meta', 'sp_list', 'normal', 'high' );
}

function sportspress_list_player_meta( $post ) {
	$league_id = sportspress_get_the_term_id( $post->ID, 'sp_league', 0 );
	$season_id = sportspress_get_the_term_id( $post->ID, 'sp_season', 0 );
	$team_id = get_post_meta( $post->ID, 'sp_team', true );
	$orderby = get_post_meta( $post->ID, 'sp_orderby', true );
	$order = get_post_meta( $post->ID, 'sp_order', true );
	?>
	<div>
		<p><strong><?php _e( 'League', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'taxonomy' => 'sp_league',
				'name' => 'sp_league',
				'selected' => $league_id,
				'values' => 'term_id',
			);
			if ( ! sportspress_dropdown_taxonomies( $args ) ):
				sportspress_taxonomy_adder( 'sp_league', 'sp_team' );
			endif;
			?>
		</p>
		<p><strong><?php _e( 'Season', 'sportspress' ); ?></strong></p>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'taxonomy' => 'sp_season',
				'name' => 'sp_season',
				'selected' => $season_id,
				'values' => 'term_id',
			);
			if ( ! sportspress_dropdown_taxonomies( $args ) ):
				sportspress_taxonomy_adder( 'sp_season', 'sp_team' );
			endif;
			?>
		</p>
		<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'post_type' => 'sp_team',
				'name' => 'sp_team',
				'show_option_all' => sprintf( __( 'All %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ),
				'selected' => $team_id,
				'values' => 'ID',
			);
			if ( ! sportspress_dropdown_pages( $args ) ):
				sportspress_post_adder( 'sp_team' );
			endif;
			?>
		</p>
		<p><strong><?php _e( 'Sort by:', 'sportspress' ); ?></strong></p>
		<p>
		<?php
		$args = array(
			'prepend_options' => array(
				'number' => __( 'Number', 'sportspress' ),
				'name' => __( 'Name', 'sportspress' ),
				'eventsplayed' => __( 'Played', 'sportspress' )
			),
			'post_type' => 'sp_statistic',
			'name' => 'sp_orderby',
			'selected' => $orderby,
			'values' => 'slug',
		);
		if ( ! sportspress_dropdown_pages( $args ) ):
			sportspress_post_adder( 'sp_list' );
		endif;
		?>
		</p>
		<p><strong><?php _e( 'Sort Order:', 'sportspress' ); ?></strong></p>
		<p>
			<select name="sp_order">
				<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
				<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
			</select>
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

	sportspress_edit_player_list_table( $columns, $data, $placeholders );
	sportspress_nonce();
}

function sportspress_list_details_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}
