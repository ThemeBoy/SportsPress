<?php
/**
 * The template for displaying event content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-event.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! in_the_loop() ) return; // Return if not in main loop
?>

<?php
	/**
	 * sportspress_before_single_event hook
	 */
	 do_action( 'sportspress_before_single_event' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<?php do_action( 'sportspress_single_event_content' ); ?>