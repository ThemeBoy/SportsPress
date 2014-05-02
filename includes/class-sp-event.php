<?php
/**
 * Event Class
 *
 * The SportsPress event class handles individual event data.
 *
 * @class 		SP_Event
 * @version		0.8
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Event extends SP_Custom_Post{
	
	public function status() {
		$post_status = $this->post->post_status;
		$results = get_post_meta( $this->ID, 'sp_results', true );
		foreach( $results as $result ) {
			if ( count( array_filter( $result ) ) > 0 ) {
				return 'results';
			}
		}
		return $post_status;
	}

	public function lineup_filter( $v ) {
		return sp_array_value( $v, 'sub', false ) == false;
	}

	public function sub_filter( $v ) {
		return sp_array_value( $v, 'sub', false );
	}
}
