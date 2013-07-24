<?php
function sp_tournament_cpt_init() {
	$name = __( 'Tournaments', 'sportspress' );
	$singular_name = __( 'Tournament', 'sportspress' );
	$labels = sp_get_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'page-attributes' ),
		'rewrite' => array( 'slug' => 'tournament' ),
	);
	register_post_type( 'sp_tournament', $args );
}
add_action( 'init', 'sp_tournament_cpt_init' );

function sp_tournament_edit_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_event' => __( 'Events', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsor', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_tournament_columns', 'sp_tournament_edit_columns' );

function sp_tournament_custom_columns( $column ) {
	global $post, $post_id, $typenow;
	if ( $typenow == 'sp_tournament' ):
		switch ($column):
			case 'sp_team':
				echo 'TEAMS';
				break;
			case 'sp_event':
				echo 'EVENTS';
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
add_action( 'manage_pages_custom_column', 'sp_tournament_custom_columns' );

function sp_tournament_request_filter_dropdowns() {
	global $typenow, $wp_query;
	if ( $typenow == 'sp_tournament' ) {

		// Sponsors
		$selected = isset( $_REQUEST['sp_sponsor'] ) ? $_REQUEST['sp_sponsor'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
			'taxonomy' => 'sp_sponsor',
			'name' => 'sp_sponsor',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		echo PHP_EOL;
		
	}
}
add_action( 'restrict_manage_posts', 'sp_tournament_request_filter_dropdowns' );
?>