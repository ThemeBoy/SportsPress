<?php
/**
 * Statistic Equation Examples
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
 * SP_Meta_Box_Statistic_Equation_Examples
 */
class SP_Meta_Box_Statistic_Equation_Examples extends SP_Meta_Box_Equation_Examples {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$equations = array(
            'Appearances' => 'Played',
            'Win Ratio' => '( Win &divide; Played ) &times; 100',
            'Batting Avg' => 'Hits &divide; At&nbsp;Bats',
            'Minutes / Game' => 'Min &divide; Played',
            'Goal %' => 'Goals &divide; Attempts',
            'Blocks / Set' => 'Blocks &divide; Sets',
        );
		self::examples( $equations );
	}
}
