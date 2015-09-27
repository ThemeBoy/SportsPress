<?php
/**
 * Column Equation
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Meta_Box_Equation' ) )
	include( 'class-sp-meta-box-equation.php' );

/**
 * SP_Meta_Box_Column_Equation
 */
class SP_Meta_Box_Column_Equation extends SP_Meta_Box_Equation {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$equation = get_post_meta( $post->ID, 'sp_equation', true );
		self::builder( $post->post_title, $equation, array( 'team_event', 'outcome', 'result', 'subset', 'preset' ) );
	}
}