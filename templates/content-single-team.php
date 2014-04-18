<?php
/**
 * The template for displaying team content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-team.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * sportspress_before_single_team hook
	 */
	 do_action( 'sportspress_before_single_team' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<?php do_action( 'sportspress_single_team_content' ); ?>