<?php
function sportspress_sponsor_post_init() {
	$labels = array(
		'name' => __( 'Sponsors', 'sportspress' ),
		'singular_name' => __( 'Sponsor', 'sportspress' ),
		'add_new_item' => __( 'Add New', 'sportspress' ),
		'edit_item' => __( 'Edit', 'sportspress' ),
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
		'supports' => array( 'title', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sportspress_sponsor_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_sponsor_slug', 'sponsor' ) ),
		'menu_icon' => 'dashicons-star-filled',
		'capability_type' => 'sp_sponsor'
	);
	register_post_type( 'sp_sponsor', $args );
}
add_action( 'init', 'sportspress_sponsor_post_init' );

function sportspress_sponsor_meta_init() {
	remove_meta_box( 'submitdiv', 'sp_sponsor', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish', 'sportspress' ), 'post_submit_meta_box', 'sp_sponsor', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_sponsor', 'side' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_sponsor', 'side', 'low' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_sponsor_team_meta', 'sp_sponsor', 'side', 'high' );
	add_meta_box( 'sp_profilediv', __( 'Profile', 'sportspress' ), 'sportspress_sponsor_profile_meta', 'sp_sponsor', 'normal', 'high' );
}
function sportspress_sponsor_team_meta( $post ) {
	sportspress_post_checklist( $post->ID, 'sp_team' );
	sportspress_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
	sportspress_nonce();
}

function sportspress_sponsor_profile_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sportspress_sponsor_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '&nbsp;',
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_sponsor_columns', 'sportspress_sponsor_edit_columns' );
