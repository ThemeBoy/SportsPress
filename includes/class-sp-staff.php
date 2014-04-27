<?php
/**
 * Staff Class
 *
 * The SportsPress staff class handles individual staff data.
 *
 * @class 		SP_Staff
 * @version		0.8
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Staff extends SP_Custom_Post {

	/**
	 * Returns past teams
	 *
	 * @access public
	 * @return array
	 */
	public function past_teams() {
		return get_post_meta( $this->ID, 'sp_past_team', false );
	}
}
