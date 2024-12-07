<?php
/**
 * Conditional Equations
 *
 * @author    ThemeBoy
 * @category  Modules
 * @package   SportsPress/Modules
 * @version   2.7.23
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SportsPress_Conditional_Equations' ) ) :

	/**
	 * Main SportsPress Conditional Equations Class
	 *
	 * @class SportsPress_Conditional_Equations
	 * @version 2.7.23
	 */

	class SportsPress_Conditional_Equations {

		/**
		 * Constructor
		 */
		public function __construct() {
			// Define constants
			$this->define_constants();

			// Add hooks for filters
			add_filter( 'sportspress_equation_options', array( $this, 'add_options' ) );
			add_filter( 'sportspress_equation_alter', array( $this, 'alter_equation' ), 10, 2 );
		}

		/**
		 * Define constants.
		 */
		private function define_constants() {
			if ( ! defined( 'SP_CONDITIONAL_EQUATIONS_VERSION' ) ) {
				define( 'SP_CONDITIONAL_EQUATIONS_VERSION', '2.7.23' );
			}

			if ( ! defined( 'SP_CONDITIONAL_EQUATIONS_URL' ) ) {
				define( 'SP_CONDITIONAL_EQUATIONS_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( ! defined( 'SP_CONDITIONAL_EQUATIONS_DIR' ) ) {
				define( 'SP_CONDITIONAL_EQUATIONS_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

		/**
		 * Add additional options.
		 *
		 * @return array
		 */
		public function add_options( $options ) {
			$options['Operators']['>']  = '&gt;';
			$options['Operators']['<']  = '&lt;';
			$options['Operators']['=='] = '&equiv;';
			$options['Operators']['!='] = '&ne;';
			$options['Operators']['>='] = '&ge;';
			$options['Operators']['<='] = '&le;';
			return $options;
		}

		/**
		 * Alter the equation.
		 *
		 * @param string $equation The equation to alter.
		 * @param array  $vars     Variables to use in the equation.
		 * @return string
		 */
		public function alter_equation( $equation, $vars ) {
			// Check if the equation contains any conditional operators
			if ( ! preg_match( '/[><=!]/', $equation ) ) {
				// If no conditional operators, return the equation as-is
				return $equation;
			}
			// Replace all variables in the equation with their values
			foreach ( $vars as $var_name => $var_value ) {
				if ( is_null( $var_value ) || $var_value === '' ) {
					continue;
				}
		
				if ( is_array( $var_value ) ) {
					continue;
				}
		
				$var_value = (string) $var_value;
				$equation = str_replace( '$' . $var_name, $var_value, $equation );
			}
		
			// Remove spaces from the equation
			$equation = str_replace( ' ', '', $equation );
		
			// Evaluate sub-expressions in parentheses first
			while ( preg_match( '/\(([^()]+)\)/', $equation, $matches ) ) {
				$sub_expr = $matches[1]; // Extract the innermost sub-expression
		
				// Check for conditional operators in the sub-expression
				if ( preg_match( '/[><=!]/', $sub_expr ) ) {
					$evaluated = $this->evaluate_condition( $sub_expr ); // Evaluate the condition
				} else {
					$evaluated = $this->evaluate_expression( $sub_expr ); // Evaluate as a mathematical expression
				}
		
				// Replace the sub-expression with its evaluated value
				$equation = str_replace( '(' . $sub_expr . ')', $evaluated, $equation );
			}
		
			// Evaluate the fully reduced equation as a mathematical expression
			return $this->evaluate_expression( $equation );
		}
		
		/**
		 * Evaluate a conditional expression (e.g., "20 > 10").
		 *
		 * @param string $expression The conditional expression to evaluate.
		 * @return int 1 for true, 0 for false.
		 */
		private function evaluate_condition( $expression ) {
			try {
				// Parse the condition into left operand, operator, and right operand
				preg_match( '/(.+?)([><=!]+)(.+)/', $expression, $matches );
				$left_operand  = $this->evaluate_expression( trim( $matches[1] ) );
				$operator      = $matches[2];
				$right_operand = $this->evaluate_expression( trim( $matches[3] ) );
		
				// Evaluate the condition
				switch ( $operator ) {
					case '>':
						return (int) ( $left_operand > $right_operand );
					case '<':
						return (int) ( $left_operand < $right_operand );
					case '>=':
						return (int) ( $left_operand >= $right_operand );
					case '<=':
						return (int) ( $left_operand <= $right_operand );
					case '==':
						return (int) ( $left_operand == $right_operand );
					case '!=':
						return (int) ( $left_operand != $right_operand );
					default:
						return 0;
				}
			} catch ( Exception $e ) {
				return 0;
			}
		}
		
		/**
		 * Evaluate a mathematical expression safely.
		 *
		 * @param string $expression The expression to evaluate.
		 * @return float The result of the evaluation.
		 */
		private function evaluate_expression( $expression ) {
			try {
				// Include libraries if necessary (e.g., eqEOS)
				if ( ! class_exists( 'phpStack' ) ) {
					include_once SP()->plugin_path() . '/includes/libraries/class-phpstack.php';
				}
				if ( ! class_exists( 'eqEOS' ) ) {
					include_once SP()->plugin_path() . '/includes/libraries/class-eqeos.php';
				}
		
				// Use eqEOS to safely evaluate the expression
				$eos = new eqEOS();
				return $eos->solveIF( $expression );
			} catch ( Exception $e ) {
				return 0;
			}
		}
	}

endif;

new SportsPress_Conditional_Equations();
