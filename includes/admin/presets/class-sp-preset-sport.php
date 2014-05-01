<?php
/**
 * SportsPress Sport Preset
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Preset_Sport' ) ) :

/**
 * SP_Preset_Sport
 */
class SP_Preset_Sport {

	protected $id    = '';
	protected $label = '';

	/**
	 * Add this page to settings
	 */
	public function add_sport_preset( $presets ) {
		$presets[ $this->id ] = $this->label;

		return $presets;
	}
}

endif;
