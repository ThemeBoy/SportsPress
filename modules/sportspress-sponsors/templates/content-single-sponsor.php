<?php
/**
 * The template for displaying sponsor content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-sponsor.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress Sponsors
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! in_the_loop() ) return; // Return if not in main loop

/**
 * sportspress_before_single_sponsor hook
 */
do_action( 'sportspress_before_single_sponsor' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

do_action( 'sportspress_single_sponsor_content' );

do_action( 'sportspress_after_single_sponsor' );
