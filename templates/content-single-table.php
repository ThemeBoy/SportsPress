<?php
/**
 * The template for displaying table content.
 *
 * Override this template by copying it to yourtheme/sportspress/content-single-table.php
 *
 * @author 		ThemeBoy
 * @package 	SportsPress/Templates
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
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