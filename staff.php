<?php
function sp_staff_cpt_init() {
	$name = __( 'Staff', 'sportspress' );
	$singular_name = __( 'Staff', 'sportspress' );
	$labels = sp_get_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'rewrite' => array( 'slug' => 'staff' ),
	);
	register_post_type( 'sp_staff', $args );
}
add_action( 'init', 'sp_staff_cpt_init' );

function sp_staff_edit_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '&nbsp;',
		'title' => __( 'Name', 'sportspress' ),
		'sp_team' => __( 'Team', 'sportspress' ),
		'sp_position' => __( 'Position', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_staff_columns', 'sp_staff_edit_columns' );

function sp_staff_custom_columns( $column ) {
	global $post, $post_id, $typenow;
	if ( $typenow == 'sp_staff' ):
		switch ($column):
			case 'sp_icon':
				if ( has_post_thumbnail() ) the_post_thumbnail( 'sp_icon' );
				break;
			case 'sp_position':
				if ( get_the_terms ( $post_id, 'sp_position' ) )
					the_terms( $post_id, 'sp_position' );
				else
					echo '—';
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
		endswitch;
	endif;
}
add_action( 'manage_posts_custom_column', 'sp_staff_custom_columns' );

function sp_staff_request_filter_dropdowns() {
	global $typenow, $wp_query;
	if ( $typenow == 'sp_staff' ) {

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
		
	}
}
add_action( 'restrict_manage_posts', 'sp_staff_request_filter_dropdowns' );
?>