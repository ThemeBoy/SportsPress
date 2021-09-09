<?php
/**
 * SportsPress Trophies Functions
 *
 * General trophies functions available on both the front-end and admin.
 *
 * @author 		ThemeBoy
 * @category 	Functions
 * @package 	SportsPress Trophies
 * @version     2.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Sort array by counting subarray fields.
 *
 * @access public
 * @param array $array
 * @return bool
 */
if ( !function_exists( 'sp_sort_by_count' ) ) {
	function sp_sort_by_count( $a, $b ) {
		$a = count( $a );
		$b = count( $b );
		return ( $a == $b ) ? 0 : ( ( $a < $b ) ? 1 : - 1 );
	}
}
