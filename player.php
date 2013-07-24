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
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'rewrite' => array( 'slug' => 'player' ),
	);
	register_post_type( 'sp_player', $args );
}
add_action( 'init', 'sp_player_cpt_init' );

function sp_player_edit_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '&nbsp;',
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Position', 'sportspress' ),
		'sp_team' => __( 'Team', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsor', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_player_columns', 'sp_player_edit_columns' );

function sp_player_custom_columns( $column ) {
	global $post, $post_id, $typenow;
	if ( $typenow == 'sp_player' ):
		switch ($column):
			case 'sp_icon':
				if ( has_post_thumbnail() ) the_post_thumbnail( 'sp_icon' );
				break;
			case 'sp_position':
				the_terms( $post_id, 'sp_position' );
				break;
			case 'sp_league':
				the_terms( $post_id, 'sp_league' );
				break;
			case 'sp_season':
				the_terms( $post_id, 'sp_season' );
				break;
			case 'sp_sponsor':
				the_terms( $post_id, 'sp_sponsor' );
				break;
		endswitch;
	endif;
}
add_action( 'manage_posts_custom_column', 'sp_player_custom_columns' );

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