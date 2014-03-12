<?php
function sportspress_sponsor_post_init() {
	$labels = array(
		'name' => __( 'Sponsors', 'sportspress' ),
		'singular_name' => __( 'Sponsor', 'sportspress' ),
		'add_new_item' => __( 'Add New Sponsor', 'sportspress' ),
		'edit_item' => __( 'Edit Sponsor', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Sponsors', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sportspress_sponsor_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_sponsor_slug', 'sponsor' ) ),
		'menu_icon' => 'dashicons-star-filled',
		'capability_type' => 'sp_sponsor'
	);
	register_post_type( 'sp_sponsor', $args );
}
add_action( 'init', 'sportspress_sponsor_post_init' );

function sportspress_sponsor_meta_init() {
}

function sportspress_sponsor_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '&nbsp;',
		'title' => __( 'Name', 'sportspress' ),
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_sponsor_columns', 'sportspress_sponsor_edit_columns' );
