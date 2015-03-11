<?php
/**
 * SportsPress Option Filters
 *
 * Filters for SportsPress options.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     1.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sportspress_primary_performance_filter( $option ) {
	if ( $option ) return $option;
	$performance_posts = get_posts( array( 'posts_per_page' => 1, 'orderby' => 'menu_order', 'order' => 'ASC', 'post_type' => 'sp_performance' ) );
	if ( ! $performance_posts ) return $option;
	$post = reset( $performance_posts );
	return $post->post_name;
}
add_filter( 'option_sportspress_primary_performance', 'sportspress_primary_performance_filter' );
