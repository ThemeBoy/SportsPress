<?php
/**
 * The template for displaying table content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-table.php
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
	 * sportspress_before_single_table hook
	 */
	 do_action( 'sportspress_before_single_table' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<?php do_action( 'sportspress_single_table_content' ); ?>