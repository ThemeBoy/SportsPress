<?php
/**
 * The template for displaying trophy content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-trophy.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress Trophies
 * @version     2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! in_the_loop() ) return; // Return if not in main loop

/**
 * sportspress_before_single_trophy hook
 */
do_action( 'sportspress_before_single_trophy' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

do_action( 'sportspress_single_trophy_content' );

do_action( 'sportspress_after_single_trophy' );
