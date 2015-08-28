<?php
/**
 * Staff Class
 *
 * The SportsPress staff class handles individual staff data.
 *
 * @class 		SP_Staff
 * @version		1.8.9
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Staff extends SP_Custom_Post {

	/**
	 * Returns current teams
	 *
	 * @access public
	 * @return array
	 */
	public function current_teams() {
		return get_post_meta( $this->ID, 'sp_current_team', false );
	}

	/**
	 * Returns past teams
	 *
	 * @access public
	 * @return array
	 */
	public function past_teams() {
		return get_post_meta( $this->ID, 'sp_past_team', false );
	}

	/**
	 * Returns nationalities
	 *
	 * @access public
	 * @return array
	 */
	public function nationalities() {
		return get_post_meta( $this->ID, 'sp_nationality', false );
	}

	/**
	 * Returns role
	 *
	 * @access public
	 * @return array
	 */
	public function role() {
		$roles = get_the_terms( $this->ID, 'sp_role' );
		if ( $roles && ! is_wp_error( $roles ) ):
			return array_shift( $roles );
		else:
			return false;
		endif;
	}
}
