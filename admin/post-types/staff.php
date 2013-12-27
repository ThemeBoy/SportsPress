<?php
function sp_staff_cpt_init() {
	$name = __( 'Staff', 'sportspress' );
	$singular_name = __( 'Staff', 'sportspress' );
	$lowercase_name = __( 'staff', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sp_staff_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_staff_slug', 'staff' ) ),
		'capability_type' => 'sp_staff'
	);
	register_post_type( 'sp_staff', $args );
}
add_action( 'init', 'sp_staff_cpt_init' );

function sp_staff_meta_init() {
	remove_meta_box( 'submitdiv', 'sp_staff', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'sp_staff', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_staff', 'side' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_staff', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_staff_team_meta', 'sp_staff', 'side', 'high' );
	add_meta_box( 'sp_profilediv', __( 'Profile' ), 'sp_staff_profile_meta', 'sp_staff', 'normal', 'high' );
}
function sp_staff_team_meta( $post ) {
	sp_post_checklist( $post->ID, 'sp_team' );
	sp_post_adder( 'sp_team' );
	sp_nonce();
}

function sp_staff_profile_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sp_staff_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_staff_columns', 'sp_staff_edit_columns' );
?>