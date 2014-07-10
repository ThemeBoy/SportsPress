<?php
/**
 * WPML Class
 *
 * The SportsPress WPML class handles all WPML-related localization hooks.
 *
 * @class 		SP_WPML
 * @version		1.1.9
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
		add_filter( 'post_type_link', array( $this, 'post_type_link' ), 5, 2 );
	}

	public static function the_title( $title, $id = null ) {
		if ( self::can_localize( $id, get_post_type( $id ) ) ):
			// Get translated post ID
			$translated_id = icl_object_id( $id, 'any', false, ICL_LANGUAGE_CODE );
			
			if ( $translated_id ):
				$post = get_post( $translated_id );
				$title = $post->post_title;
			endif;
		endif;

		return $title;
	}

	public static function post_type_link( $url, $post ) {
		if ( self::can_localize( $post->ID, $post->post_type ) ):
			// Get translated post ID
			$translated_id = icl_object_id( $post->ID, 'any', false, ICL_LANGUAGE_CODE );

			if ( $translated_id ):
				$url .= '?lang=ja';
				//$url = get_permalink( $translated_id );
			endif;
		endif;

		return $url;
	}

	public static function can_localize( $id, $post_type ) {
		return ( function_exists( 'icl_object_id' ) && is_sp_post_type( $post_type ) && $id != get_the_ID() );
	}
}

endif;

return new SP_WPML();
