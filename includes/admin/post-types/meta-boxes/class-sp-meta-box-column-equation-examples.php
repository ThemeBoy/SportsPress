<?php
/**
 * Column Equation Examples
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Meta_Box_Equation_Examples' ) )
	include( 'class-sp-meta-box-equation-examples.php' );

/**
 * SP_Meta_Box_Column_Equation_Examples
 */
class SP_Meta_Box_Column_Equation_Examples extends SP_Meta_Box_Equation_Examples {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$equations = array(
            'Games Played' => 'Played',
            'Pts' => 'Win &times; 3 + Draw',
            'Win %' => 'Win &divide; Played',
            'Diff' => 'Points&nbsp;(for) - Points&nbsp;(against)',
            'GB' => 'Games&nbsp;Back',
            'Home Goals' => 'Goals&nbsp;(for) @Home',
            'Strk' => 'Streak',
            'L10' => 'Last&nbsp;10',
        );
		self::examples( $equations );
	}
}
