<?php
/**
 * The template for displaying team content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-team.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! in_the_loop() ) return; // Return if not in main loop

/**
 * sportspress_before_single_team hook
 */
do_action( 'sportspress_before_single_team' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

do_action( 'sportspress_single_team_content' );

do_action( 'sportspress_after_single_team' );
