<?php
/**
 * The template for displaying tournament content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-tournament.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Tournaments
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! in_the_loop() ) return; // Return if not in main loop

/**
 * sportspress_before_single_tournament hook
 */
do_action( 'sportspress_before_single_tournament' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

do_action( 'sportspress_single_tournament_content' );

do_action( 'sportspress_after_single_tournament' );
