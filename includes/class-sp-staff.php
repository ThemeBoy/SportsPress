<?php
/**
 * Staff Class
 *
 * The SportsPress staff class handles individual staff data.
 *
 * @class 		SP_Staff
 * @version		2.5.1
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
		$roles = $this->get_roles();
		if ( $roles && ! is_wp_error( $roles ) ):
			return array_shift( $roles );
		else:
			return false;
		endif;
	}

	/**
	 * Returns roles
	 *
	 * @access public
	 * @return array
	 */
	public function roles() {
		$roles = $this->get_roles();
		if ( $roles && ! is_wp_error( $roles ) ):
			return (array) $roles;
		else:
			return array();
		endif;
	}

	public function get_roles() {
		$roles = get_the_terms( $this->ID, 'sp_role' );

		if ( ! is_array( $roles ) || ! sizeof( $roles ) ) return array();

		$include = wp_list_pluck( $roles, 'term_id' );

		return get_terms( array(
		  'taxonomy' => 'sp_role',
		  'hide_empty' => false,
			'orderby' => 'meta_value_num',
			'include' => $include,
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'sp_order',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key' => 'sp_order',
					'compare' => 'EXISTS'
				),
			),
		) );
	}
}
