<?php
function sportspress_staff_post_init() {
	$labels = array(
		'name' => __( 'Staff', 'sportspress' ),
		'singular_name' => __( 'Staff', 'sportspress' ),
		'add_new_item' => __( 'Add New Staff', 'sportspress' ),
		'edit_item' => __( 'Edit Staff', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Staff', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sportspress_staff_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sportspress_staff_slug', 'staff' ) ),
		'show_in_menu' => 'edit.php?post_type=sp_player',
		'capability_type' => 'sp_staff'
	);
	register_post_type( 'sp_staff', $args );
}
add_action( 'init', 'sportspress_staff_post_init' );

function sportspress_staff_meta_init() {
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_staff_team_meta', 'sp_staff', 'side', 'default' );
	add_meta_box( 'sp_profilediv', __( 'Profile', 'sportspress' ), 'sportspress_staff_profile_meta', 'sp_staff', 'normal', 'high' );
}
function sportspress_staff_team_meta( $post ) {
	sportspress_post_checklist( $post->ID, 'sp_team' );
	sportspress_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
	sportspress_nonce();
}

function sportspress_staff_profile_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sportspress_staff_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_staff_columns', 'sportspress_staff_edit_columns' );
