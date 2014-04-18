<?php
/**
 * The template for displaying list content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-list.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * sportspress_before_single_list hook
	 */
	 do_action( 'sportspress_before_single_list' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<?php do_action( 'sportspress_single_list_content' ); ?>