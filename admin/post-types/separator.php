<?php
function sportspress_separator_post_init() {
	$args = array(
		'label' => '',
		'public' => false,
		'show_ui' => true,
		'show_in_nav_menus' => false,
		'show_in_admin_bar' => false
	);
	register_post_type( 'sp_separator', $args );
}
add_action( 'init', 'sportspress_separator_post_init' );
