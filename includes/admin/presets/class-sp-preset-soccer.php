<?php
/**
 * SportsPress Soccer Preset
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Preset_Soccer' ) ) :

/**
 * SP_Preset_Soccer
 */
class SP_Preset_Soccer extends SP_Preset_Sport {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'soccer';
		$this->label = __( 'Association Football (Soccer)', 'sportspress' );

		add_filter( 'sportspress_sport_presets_array', array( $this, 'add_sport_preset' ), 20 );
	}
}

endif;

return new SP_Preset_Soccer();
