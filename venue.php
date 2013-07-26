<?php
function sp_venue_cpt_init() {
	$name = __( 'Venues', 'sportspress' );
	$singular_name = __( 'Venue', 'sportspress' );
	$labels = sp_get_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'rewrite' => array( 'slug' => 'venue' )
	);
	register_post_type( 'sp_venue', $args );
}
add_action( 'init', 'sp_venue_cpt_init' );

function sp_venue_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Venue', 'sportspress' ),
		'sp_address' => __( 'Address', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsors', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_venue_columns', 'sp_venue_edit_columns' );
?>