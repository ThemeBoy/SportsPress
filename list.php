<?php
function sp_list_cpt_init() {
	$name = __( 'Player Lists', 'sportspress' );
	$singular_name = __( 'Player List', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'rewrite' => array( 'slug' => 'list' )
	);
	register_post_type( 'sp_list', $args );
}
add_action( 'init', 'sp_list_cpt_init' );

function sp_list_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_list_columns', 'sp_list_edit_columns' );
?>