<?php
/**
 * WPML Class
 *
 * The SportsPress WPML class handles all WPML-related localization hooks.
 *
 * @class 		SP_WPML
 * @version		1.8.2
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

	var $languages = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
		add_filter( 'the_title', array( $this, 'the_title' ), 5, 2 );
		add_filter( 'post_type_link', array( $this, 'post_type_link' ), 5, 3 );
		add_filter( 'icl_ls_languages', array( $this, 'ls' ) );
	}

	public function init() {
		if ( function_exists( 'icl_get_languages' ) )
			$this->languages = icl_get_languages();
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

	public static function post_type_link( $url, $post = null, $leavename = false, $sample = false ) {
		if ( self::can_localize( $post ) ):
			if ( ! $post ) global $post;

			// Get post ID
			$id = $post->ID;

			// Get translated post ID
			$translated_id = icl_object_id( $id, 'any', false, ICL_LANGUAGE_CODE );

			if ( $translated_id && $translated_id != $id && get_the_ID() != $translated_id ):
				return get_post_permalink( $translated_id, $leavename, $sample );
			endif;
		endif;

		return $url;
	}

	public function ls( $languages ) {
		if ( ! function_exists( 'icl_object_id' ) || ! is_singular( 'sp_event' ) ) return $languages;

		// Get post ID
		$id = get_the_ID();

		if ( get_post_status( $id ) != 'future' ) return $languages;

		$active_languages = array();

        foreach ( $this->languages as $k => $v ):
			global $wpdb;

			// Get language code
        	$code = sp_array_value( $v, 'code' );

        	// Get URL
			$translated_id = icl_object_id( $id, 'any', false, $code );
			if ( ! $translated_id ) continue;
			$url = get_post_permalink( $translated_id, false, true );

			// Get native name;
        	$native_name = sp_array_value( $v, 'native_name' );

        	// Get encode URL
			$encode_url = $wpdb->get_var($wpdb->prepare("SELECT encode_url FROM {$wpdb->prefix}icl_languages WHERE code=%s", $code));
			
			// Get flag
			$flag = $wpdb->get_row( "SELECT flag, from_template FROM {$wpdb->prefix}icl_flags WHERE lang_code='{$code}'" );
			if ( $flag->from_template ) {
				$wp_upload_dir = wp_upload_dir();
				$flag_url      = $wp_upload_dir[ 'baseurl' ] . '/flags/' . $flag->flag;
			} else {
				$flag_url = ICL_PLUGIN_URL . '/res/flags/' . $flag->flag;
			}

			// Add language
        	$active_languages[ $k ] = array_merge( $v, array(
				'language_code' => $code,
				'active' => ICL_LANGUAGE_CODE == $code ? '1' : 0,
				'translated_name' => $native_name,
				'encode_url' => $encode_url,
				'country_flag_url' => $flag_url,
				'url' => $url,
			) );
        endforeach;

        // Add if translations exist
        if ( sizeof( $active_languages ) > 1 )
        	$languages = array_merge( $languages, $active_languages );

		return $languages;
	}

	public static function can_localize( $post, $id = null ) {
		return function_exists( 'icl_object_id' ) && is_sp_post_type( get_post_type( $post ) );
	}
}

endif;

return new SP_WPML();
