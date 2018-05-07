<?php
/*
Plugin Name: SportsPress Conditional Equations
Plugin URI: http://themeboy.com/
Description: Add conditional equations to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Conditional_Equations' ) ) :

/**
 * Main SportsPress Conditional Equations Class
 *
 * @class SportsPress_Conditional_Equations
 * @version	2.6
 */
 
 class SportsPress_Conditional_Equations {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions

		// Filters
		add_filter( 'sportspress_equation_options', array( $this, 'add_options' ) );
		add_filter( 'sportspress_equation_alter', array( $this, 'alter_equation' ), 10, 2 );

	}
	
	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_CONDITIONAL_EQUATIONS_VERSION' ) )
			define( 'SP_CONDITIONAL_EQUATIONS_VERSION', '2.6' );

		if ( !defined( 'SP_CONDITIONAL_EQUATIONS_URL' ) )
			define( 'SP_CONDITIONAL_EQUATIONS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_CONDITIONAL_EQUATIONS_DIR' ) )
			define( 'SP_CONDITIONAL_EQUATIONS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Add additional options.
	 *
	 * @return array
	 */
	public function add_options( $options ) {
		$options[ 'Operators' ]['>'] = '&gt;';
		$options[ 'Operators' ]['<'] = '&lt;';
		$options[ 'Operators' ]['=='] = '&equiv;';
		$options[ 'Operators' ]['!='] = '&ne;';
		$options[ 'Operators' ]['>='] = '&ge;';
		$options[ 'Operators' ]['<='] = '&le;';
		return $options;
	}
	
	/**
	 * Alter.
	 *
	 * @return array
	 */
	public function alter_equation( $equation, $vars ) {
		
		// Remove space between equation parts
		$equation = str_replace( ' ', '', $equation );
		
		// Find all parentheses with conditional operators
		$re = '/([^[\(|\)]*[<=>][^[\(|\)]*)/';
		if ( preg_match_all( $re, $equation, $matches ) ) {

			foreach ( $matches[1] as $match ) {
				
				// Find which Conditional Operator is used
				preg_match ( '/[\!\>\=\<]+/' ,$match, $conop );
				$conop = $conop[0];
				
				//preg_match ( '/.+?(?=[\>\=\<])/' ,$match, $leftvar );
				preg_match ( '/.+?(?='.$conop.')/' ,$match, $leftvar );
				
				//preg_match ( '/(?<=[\>\=\<]).*/' ,$match, $rightvar );
				preg_match ( '/(?<='.$conop.').*/' ,$match, $rightvar );

				// Check if it is a variable or a number
				if ( strpos ( $leftvar[0], '$' ) !== FALSE ) {
					$leftvar = str_replace ( '$', '', $leftvar[0] );
					$leftvar = $vars[$leftvar];
				} else {
					$leftvar = $leftvar[0];
				}
				
				// Check if it is a variable or a number
				if ( strpos ( $rightvar[0], '$' ) !== FALSE ) {
					$rightvar = str_replace ( '$', '', $rightvar[0] );
					$rightvar = $vars[$rightvar];
				} else {
					$rightvar = $rightvar[0];
				}
				
				// Select the correct conditional operator
				switch ( $conop ) {
					case '>':
						$solution = (int) ( $leftvar > $rightvar );
						break;
					case '<':
						$solution = (int) ( $leftvar < $rightvar );
						break;
					case '==':
						$solution = (int) ( $leftvar == $rightvar );
						break;
					case '!=':
						$solution = (int) ( $leftvar != $rightvar );
						break;
					case '>=':
						$solution = (int) ( $leftvar >= $rightvar );
						break;
					case '<=':
						$solution = (int) ( $leftvar <= $rightvar );
						break;
				}
				
				// Replace the result of the conditional sub-equation to the equation
				$equation = str_replace ( $match, $solution, $equation );
			}
			
		}
		return $equation;
	}
			
}

endif;

new SportsPress_Conditional_Equations();
