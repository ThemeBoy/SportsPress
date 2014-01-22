<?php
function sportspress_staff_post_init() {
	$name = __( 'Staff', 'sportspress' );
	$singular_name = __( 'Staff', 'sportspress' );
	$lowercase_name = __( 'staff', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sportspress_staff_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_staff_slug', 'staff' ) ),
		'menu_icon' => 'dashicons-businessman',
		'capability_type' => 'sp_staff'
	);
	register_post_type( 'sp_staff', $args );
}
add_action( 'init', 'sportspress_staff_post_init' );

function sportspress_staff_meta_init() {
	remove_meta_box( 'submitdiv', 'sp_staff', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish', 'sportspress' ), 'post_submit_meta_box', 'sp_staff', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_staff', 'side' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_staff', 'side', 'low' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_staff_team_meta', 'sp_staff', 'side', 'high' );
	add_meta_box( 'sp_profilediv', __( 'Profile', 'sportspress' ), 'sportspress_staff_profile_meta', 'sp_staff', 'normal', 'high' );
}
function sportspress_staff_team_meta( $post ) {
	sportspress_post_checklist( $post->ID, 'sp_team' );
	sportspress_post_adder( 'sp_team' );
	sportspress_nonce();
}

function sportspress_staff_profile_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sportspress_staff_edit_columns() {
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
add_filter( 'manage_edit-sp_staff_columns', 'sportspress_staff_edit_columns' );
