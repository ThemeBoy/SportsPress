<?php
function sp_player_cpt_init() {
	$name = __( 'Players', 'sportspress' );
	$singular_name = __( 'Player', 'sportspress' );
	$labels = sp_get_cpt_labels( $name, $singular_name );
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
	add_meta_box( 'submitdiv', __( 'Publish', 'sportspress' ), 'post_submit_meta_box', 'sp_player', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_player', 'side' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_player_team_meta', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_profilediv', __( 'Profile', 'sportspress' ), 'sp_player_profile_meta', 'sp_player', 'normal', 'high' );
}
function sp_player_team_meta( $post, $metabox ) {
	global $post_id;
	sp_team_select_html( $post_id );
	sp_nonce();
}

function sp_player_profile_meta( $post, $metabox ) {
	wp_editor( $post->post_content, 'content' );
}

function sp_player_edit_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsors', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_player_columns', 'sp_player_edit_columns' );

function sp_player_custom_columns( $column, $post_id ) {
	global $post, $typenow;
	if ( $typenow == 'sp_player' ):
		switch ($column):
			case 'sp_position':
				if ( get_the_terms ( $post_id, 'sp_position' ) )
					the_terms( $post_id, 'sp_position' );
				else
					echo '—';
				break;
			case 'sp_team':
				sp_unserialized_posts( $post_id, 'sp_teams', '', '<br />' );
				break;
			case 'sp_league':
				if ( get_the_terms ( $post_id, 'sp_league' ) )
					the_terms( $post_id, 'sp_league' );
				else
					echo '—';
				break;
			case 'sp_season':
				if ( get_the_terms ( $post_id, 'sp_season' ) )
					the_terms( $post_id, 'sp_season' );
				else
					echo '—';
				break;
			case 'sp_sponsor':
				if ( get_the_terms ( $post_id, 'sp_sponsor' ) )
					the_terms( $post_id, 'sp_sponsor' );
				else
					echo '—';
				break;
		endswitch;
	endif;
}
add_action( 'manage_posts_custom_column', 'sp_player_custom_columns', 10, 2 );

function sp_player_request_filter_dropdowns() {
	global $typenow, $wp_query;
	if ( $typenow == 'sp_player' ) {

		// Positions
		$selected = isset( $_REQUEST['sp_position'] ) ? $_REQUEST['sp_position'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Positions', 'sportspress' ) ),
			'taxonomy' => 'sp_position',
			'name' => 'sp_position',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		echo PHP_EOL;

		// Leagues
		$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) ),
			'taxonomy' => 'sp_league',
			'name' => 'sp_league',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		echo PHP_EOL;

		// Seasons
		$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
			'taxonomy' => 'sp_season',
			'name' => 'sp_season',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		echo PHP_EOL;

		// Sponsors
		$selected = isset( $_REQUEST['sp_sponsor'] ) ? $_REQUEST['sp_sponsor'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Sponsors', 'sportspress' ) ),
			'taxonomy' => 'sp_sponsor',
			'name' => 'sp_sponsor',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		
	}
}
add_action( 'restrict_manage_posts', 'sp_player_request_filter_dropdowns' );
?>