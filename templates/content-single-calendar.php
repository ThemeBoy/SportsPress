<?php
/**
 * The template for displaying calendar content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-calendar.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! in_the_loop() ) return; // Return if not in main loop

/**
 * sportspress_before_single_calendar hook
 */
do_action( 'sportspress_before_single_calendar' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

do_action( 'sportspress_single_calendar_content' );

do_action( 'sportspress_after_single_calendar' );
