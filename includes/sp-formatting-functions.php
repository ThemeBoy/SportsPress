<?php
/**
 * SportsPress Formatting
 *
 * Functions for formatting data.
 *
 * @author      ThemeBoy
 * @category    Core
 * @package     SportsPress/Functions
 * @version   2.7.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Sanitize taxonomy names. Slug format (no spaces, lowercase).
 *
 * Doesn't use sanitize_title as this destroys utf chars.
 *
 * @access public
 * @param mixed $taxonomy
 * @return string
 */
function sp_sanitize_taxonomy_name( $taxonomy ) {
	$filtered = strtolower( remove_accents( stripslashes( strip_tags( $taxonomy ) ) ) );
	$filtered = preg_replace( '/&.+?;/', '', $filtered ); // Kill entities
	$filtered = str_replace( array( '.', '\'', '"' ), '', $filtered ); // Kill quotes and full stops.
	$filtered = str_replace( array( ' ', '_' ), '-', $filtered ); // Replace spaces and underscores.

	return apply_filters( 'sanitize_taxonomy_name', $filtered, $taxonomy );
}

/**
 * Clean variables
 *
 * @access public
 * @param string $var
 * @return string
 */
function sp_clean( $var ) {
	return sanitize_text_field( $var );
}

/**
 * Merge two arrays
 *
 * @access public
 * @param array $a1
 * @param array $a2
 * @return array
 */
function sp_array_overlay( $a1, $a2 ) {
	foreach ( $a1 as $k => $v ) {
		if ( ! array_key_exists( $k, $a2 ) ) {
			continue;
		}
		if ( is_array( $v ) && is_array( $a2[ $k ] ) ) {
			$a1[ $k ] = sp_array_overlay( $v, $a2[ $k ] );
		} else {
			$a1[ $k ] = $a2[ $k ];
		}
	}
	return $a1;
}

/**
 * Array filter returns positive values only.
 *
 * @access public
 * @param int $var
 * @return bool
 */
function sp_filter_positive( $var = 0 ) {
	return $var > 0;
}

/**
 * Array filter returns non-empty array values.
 *
 * @access public
 * @param str $var
 * @return bool
 */
function sp_filter_non_empty( $var = '' ) {
	return strlen( $var ) > 0;
}

/**
 * Sort array randomly and maintain index association.
 *
 * @access public
 * @param array $array
 * @return bool
 */
function sp_sort_random() {
	return mt_rand( 0, 1 );
}

/**
 * Sort array by name field.
 *
 * @access public
 * @param array $array
 * @return bool
 */
function sp_sort_by_name( $a, $b ) {
	return strcmp( $a['name'], $b['name'] );
}

/**
 * let_to_num function.
 *
 * This function transforms the php.ini notation for numbers (like '2M') to an integer.
 *
 * @access public
 * @param $size
 * @return int
 */
function sp_let_to_num( $size ) {
	$l   = substr( $size, -1 );
	$ret = substr( $size, 0, -1 );
	switch ( strtoupper( $l ) ) {
		case 'P':
			$ret *= 1024;
		case 'T':
			$ret *= 1024;
		case 'G':
			$ret *= 1024;
		case 'M':
			$ret *= 1024;
		case 'K':
			$ret *= 1024;
	}
	return $ret;
}

/**
 * SportsPress Date Format - Allows to change date format for everything SportsPress
 *
 * @access public
 * @return string
 */
function sp_date_format() {
	return apply_filters( 'sportspress_date_format', get_option( 'date_format' ) );
}

/**
 * SportsPress Time Format - Allows to change time format for everything SportsPress
 *
 * @access public
 * @return string
 */
function sp_time_format() {
	return apply_filters( 'sportspress_time_format', get_option( 'time_format' ) );
}

if ( ! function_exists( 'sp_time_value' ) ) {

	/**
	 * Convert number of seconds to formatted time
	 *
	 * @access public
	 * @param mixed $value
	 * @return string
	 */
	function sp_time_value( $value = 0 ) {
		$intval  = intval( $value );
		$timeval = gmdate( 'i:s', $intval );
		$hours   = floor( $intval / 3600 );

		if ( '00' != $hours ) {
			$timeval = $hours . ':' . $timeval;
		}

		return preg_replace( '/^0/', '', $timeval );
	}
}

if ( ! function_exists( 'sp_value_time' ) ) {

	/**
	 * Convert a time string to the equivalent number of seconds.
	 *
	 * @param string $time_string The time string in the format 'hh:mm:ss'.
	 * @return int The total time in seconds.
	 */
	function sp_value_time( $time_string ) {
	    // Initialize variables for hours, minutes, and seconds
	    $hours   = 0;
	    $minutes = 0;
	    $seconds = 0;

	    // Split the time string into hours, minutes, and seconds
	    $time_parts = explode( ':', $time_string );

	    // Determine the number of components in the time string
	    $component_count = count( $time_parts );

	    // Set values based on the number of components
	    if ( 1 === $component_count ) {
	        $seconds = isset( $time_parts[0] ) ? $time_parts[0] : 0;
	    } elseif ( 2 === $component_count ) {
	        $minutes = isset( $time_parts[0] ) ? $time_parts[0] : 0;
	        $seconds = isset( $time_parts[1] ) ? $time_parts[1] : 0;
	    } elseif ( 2 < $component_count ) {
	        $hours = isset( $time_parts[0] ) ? $time_parts[0] : 0;
	        $minutes = isset( $time_parts[1] ) ? $time_parts[1] : 0;
	        $seconds = isset( $time_parts[2] ) ? $time_parts[2] : 0;
	    }

	    // Convert each part to seconds and calculate the total time in seconds
	    $total_seconds = ( $hours * 3600 ) + ( $minutes * 60 ) + $seconds;

	    return $total_seconds;
	}

}

if ( ! function_exists( 'sp_rgb_from_hex' ) ) {

	/**
	 * Hex darker/lighter/contrast functions for colours
	 *
	 * @access public
	 * @param mixed $color
	 * @return string
	 */
	function sp_rgb_from_hex( $color ) {
		$color = str_replace( '#', '', $color );
		// Convert shorthand colors to full format, e.g. "FFF" -> "FFFFFF"
		$color = preg_replace( '~^(.)(.)(.)$~', '$1$1$2$2$3$3', $color );

		$rgb['R'] = hexdec( $color[0] . $color[1] );
		$rgb['G'] = hexdec( $color[2] . $color[3] );
		$rgb['B'] = hexdec( $color[4] . $color[5] );
		return $rgb;
	}
}

if ( ! function_exists( 'sp_hex_darker' ) ) {

	/**
	 * Hex darker/lighter/contrast functions for colours
	 *
	 * @access public
	 * @param mixed $color
	 * @param int   $factor (default: 30)
	 * @return string
	 */
	function sp_hex_darker( $color, $factor = 30, $absolute = false ) {
		$base  = sp_rgb_from_hex( $color );
		$color = '#';

		foreach ( $base as $k => $v ) :
			if ( $absolute ) {
				$amount = $factor;
			} else {
				$amount = $v / 100;
				$amount = round( $amount * $factor );
			}
			$new_decimal = max( $v - $amount, 0 );

			$new_hex_component = dechex( $new_decimal );
			if ( strlen( $new_hex_component ) < 2 ) :
				$new_hex_component = '0' . $new_hex_component;
			endif;
			$color .= $new_hex_component;
		endforeach;

		return $color;
	}
}

if ( ! function_exists( 'sp_hex_lighter' ) ) {

	/**
	 * Hex darker/lighter/contrast functions for colours
	 *
	 * @access public
	 * @param mixed $color
	 * @param int   $factor (default: 30)
	 * @return string
	 */
	function sp_hex_lighter( $color, $factor = 30, $absolute = false ) {
		$base  = sp_rgb_from_hex( $color );
		$color = '#';

		foreach ( $base as $k => $v ) :
			if ( $absolute ) {
				$amount = $factor;
			} else {
				$amount = 255 - $v;
				$amount = $amount / 100;
				$amount = round( $amount * $factor );
			}
			$new_decimal = min( $v + $amount, 255 );

			$new_hex_component = dechex( $new_decimal );
			if ( strlen( $new_hex_component ) < 2 ) :
				$new_hex_component = '0' . $new_hex_component;
			endif;
			$color .= $new_hex_component;
		endforeach;

		return $color;
	}
}

if ( ! function_exists( 'sp_light_or_dark' ) ) {

	/**
	 * Detect if we should use a light or dark colour on a background colour
	 *
	 * @access public
	 * @param mixed  $color
	 * @param string $dark (default: '#000000')
	 * @param string $light (default: '#FFFFFF')
	 * @return string
	 */
	function sp_light_or_dark( $color, $dark = '#000000', $light = '#FFFFFF' ) {
		// return ( hexdec( $color ) > 0xffffff / 2 ) ? $dark : $light;
		$hex = str_replace( '#', '', $color );

		$c_r        = hexdec( substr( $hex, 0, 2 ) );
		$c_g        = hexdec( substr( $hex, 2, 2 ) );
		$c_b        = hexdec( substr( $hex, 4, 2 ) );
		$brightness = ( ( $c_r * 299 ) + ( $c_g * 587 ) + ( $c_b * 114 ) ) / 1000;

		return $brightness > 155 ? $dark : $light;
	}
}

if ( ! function_exists( 'sp_format_hex' ) ) {

	/**
	 * Format string as hex
	 *
	 * @access public
	 * @param string $hex
	 * @return string
	 */
	function sp_format_hex( $hex ) {

		$hex = preg_replace( '/[^A-Fa-f0-9]/', '', $hex );
		$hex = trim( str_replace( '#', '', $hex ) );

		if ( strlen( $hex ) == 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		$hex = substr( $hex, 0, 6 );

		if ( $hex ) {
			return '#' . $hex;
		}
	}
}
