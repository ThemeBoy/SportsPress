<?php
function sportspress_after_setup_theme() {
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'sportspress_after_setup_theme' );
