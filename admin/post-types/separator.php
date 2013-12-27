<?php
function sp_separator_cpt_init() {
	$args = array(
		'public' => false,
		'show_ui' => true,
		'show_in_nav_menus' => false,
	);
	register_post_type( 'sp_separator', $args );
}
add_action( 'init', 'sp_separator_cpt_init' );
?>