<?php
/**
 * REST API Class
 *
 * The SportsPress REST API class handles all API-related hooks.
 *
 * @class 		SP_REST_API
 * @version		2.0
 * @package		SportsPress/Classes
 * @category	Class
 * @package 	SportsPress/API
 * @author 		ThemeBoy
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_REST_API' ) ) :

/**
 * SP_REST_API Class
 */
class SP_REST_API {

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Include required files
		$this->includes();
	}

	/**
	 * Include required files.
	 */
	private function includes() {
		require_once dirname( __FILE__ ) . '/api/class-sp-rest-teams-controller.php';
	}
}

endif;

return new SP_REST_API();
