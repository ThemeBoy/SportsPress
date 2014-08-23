<?php
/**
 * WPML Class
 *
 * The SportsPress WPML class handles all WPML-related localization hooks.
 *
 * @class 		SP_WPML
 * @version		1.3
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_WPML' ) ) :

/**
 * SP_WPML Class
 */
class SP_WPML {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'the_title', array( $this, 'the_title' ), 5, 2 );
		add_filter( 'post_type_link', array( $this, 'post_type_link' ), 5, 3 );
	}

	public static function the_title( $title, $id = null ) {
		if ( self::can_localize( $id, $id ) ):
			// Get translated post ID
			$translated_id = icl_object_id( $id, 'any', false, ICL_LANGUAGE_CODE );
			
			if ( $translated_id ):
				$post = get_post( $translated_id );
				if ( $post ) $title = $post->post_title;
			endif;
		endif;

		return $title;
	}

	public static function post_type_link( $url, $post, $leavename ) {
		if ( self::can_localize( $post ) ):
			// Get post ID
			$id = $post->ID;

			// Get translated post ID
			$translated_id = icl_object_id( $id, 'any', false, ICL_LANGUAGE_CODE );

			if ( $translated_id && $translated_id != $id ):
				return get_permalink( $translated_id, $leavename );
			endif;
		endif;

		return $url;
	}

	public static function can_localize( $post, $id = null ) {
		return function_exists( 'icl_object_id' ) && is_sp_post_type( get_post_type( $post ) ) && $id != get_the_ID();
	}
}

endif;

return new SP_WPML();
