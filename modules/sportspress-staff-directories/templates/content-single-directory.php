<?php
/**
 * The template for displaying staff directory content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-directory.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Staff_Directories
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! in_the_loop() ) return; // Return if not in main loop

/**
 * sportspress_before_single_directory hook
 */
do_action( 'sportspress_before_single_directory' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

do_action( 'sportspress_single_directory_content' );

do_action( 'sportspress_after_single_directory' );
