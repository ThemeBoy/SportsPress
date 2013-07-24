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
		'rewrite' => array( 'slug' => 'venue' ),
	);
	register_post_type( 'sp_venue', $args );
}
add_action( 'init', 'sp_venue_cpt_init' );

function sp_venue_edit_columns( $columns ) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Venue', 'sportspress' ),
		'sp_address' => __( 'Address', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsor', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_venue_columns', 'sp_venue_edit_columns' );

function sp_venue_custom_columns( $column, $post_id ) {
	global $typenow;
	if ( $typenow == 'sp_venue' ):
		switch ( $column ):
			case 'sp_sponsor':
				if ( get_the_terms ( $post_id, 'sp_sponsor' ) )
					the_terms( $post_id, 'sp_sponsor' );
				else
					echo '—';
				break;
			case 'sp_address':
				echo get_post_meta( $post_id, 'sp_address', true );
				break;
		endswitch;
	endif;
}
add_action( 'manage_posts_custom_column', 'sp_venue_custom_columns', 10, 2 );

function sp_venue_request_filter_dropdowns() {
	global $typenow, $wp_query;
	if ( $typenow == 'sp_venue' ) {

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
add_action( 'restrict_manage_posts', 'sp_venue_request_filter_dropdowns' );
?>