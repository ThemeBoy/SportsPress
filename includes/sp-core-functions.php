<?php
/**
 * SportsPress Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author      ThemeBoy
 * @category    Core
 * @package     SportsPress/Functions
 * @version   2.7.18
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Include core functions
require 'sp-option-filters.php';
require 'sp-conditional-functions.php';
require 'sp-formatting-functions.php';
require 'sp-deprecated-functions.php';
require 'sp-api-functions.php';

/**
 * Get template part.
 *
 * @access public
 * @param mixed  $slug
 * @param string $name (default: '')
 * @return void
 */
function sp_get_template_part( $slug, $name = '' ) {
	$template = '';

	// Look in yourtheme/slug-name.php and yourtheme/sportspress/slug-name.php
	if ( $name ) {
		$template = locate_template( array( "{$slug}-{$name}.php", SP()->template_path() . "{$slug}-{$name}.php" ) );
	}

	// Get default slug-name.php
	if ( ! $template && $name && file_exists( SP()->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
		$template = SP()->plugin_path() . "/templates/{$slug}-{$name}.php";
	}

	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/sportspress/slug.php
	if ( ! $template ) {
		$template = locate_template( array( "{$slug}.php", SP()->template_path() . "{$slug}.php" ) );
	}

	// Allow 3rd party plugin filter template file from their plugin
	$template = apply_filters( 'sportspress_get_template_part', $template, $slug, $name );

	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Get templates passing attributes and including the file.
 *
 * @access public
 * @param mixed  $template_name
 * @param array  $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return void
 */
function sp_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}

	$located = sp_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $located ) ), '0.7' );
		return;
	}

	do_action( 'sportspress_before_template', $template_name, $template_path, $located, $args );

	include $located;

	do_action( 'sportspress_after_template', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *      yourtheme       /   $template_path  /   $template_name
 *      yourtheme       /   $template_name
 *      $default_path   /   $template_name
 *
 * @access public
 * @param mixed  $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function sp_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = SP()->template_path();
	}

	if ( ! $default_path ) {
		$default_path = SP()->plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found
	return apply_filters( 'sportspress_locate_template', $template, $template_name, $template_path );
}

function sp_substr( $string = '', $start = 0, $length = null ) {
	if ( function_exists( 'mb_substr' ) ) {
		return mb_substr( $string, $start, $length );
	} else {
		return substr( $string, $start, $length );
	}
}

function sp_strtoupper( $string = '' ) {
	if ( function_exists( 'mb_strtoupper' ) ) {
		return mb_strtoupper( $string );
	} else {
		return strtoupper( $string );
	}
}

/**
 * Get the timezone string.
 *
 * @access public
 * @return string
 */
function sp_get_timezone() {
	$tzstring = get_option( 'timezone_string' );

	// Remove old Etc mappings. Fallback to gmt_offset.
	if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
		$tzstring = '';
	}

	if ( empty( $tzstring ) ) { // Create a UTC+- zone if no timezone string exists
		$current_offset = get_option( 'gmt_offset' );

		if ( 0 == $current_offset ) {
			$tzstring = 'UTC+0';
		} elseif ( $current_offset < 0 ) {
			$tzstring = 'UTC' . $current_offset;
		} else {
			$tzstring = 'UTC+' . $current_offset;
		}
	}

	return $tzstring;
}

/* deprecated functions below */

if ( ! function_exists( 'date_diff' ) ) {
	class DateInterval {
		public $y;
		public $m;
		public $d;
		public $h;
		public $i;
		public $s;
		public $invert;
		public $days;

		public function format( $format ) {
			$format = str_replace(
				'%R%y',
				( $this->invert ? '-' : '+' ) . $this->y,
				$format
			);
			$format = str_replace(
				'%R%m',
				( $this->invert ? '-' : '+' ) . $this->m,
				$format
			);
			$format = str_replace(
				'%R%d',
				( $this->invert ? '-' : '+' ) . $this->d,
				$format
			);
			$format = str_replace(
				'%R%h',
				( $this->invert ? '-' : '+' ) . $this->h,
				$format
			);
			$format = str_replace(
				'%R%i',
				( $this->invert ? '-' : '+' ) . $this->i,
				$format
			);
			$format = str_replace(
				'%R%s',
				( $this->invert ? '-' : '+' ) . $this->s,
				$format
			);

			$format = str_replace( '%y', $this->y, $format );
			$format = str_replace( '%m', $this->m, $format );
			$format = str_replace( '%d', $this->d, $format );
			$format = str_replace( '%h', $this->h, $format );
			$format = str_replace( '%i', $this->i, $format );
			$format = str_replace( '%s', $this->s, $format );

			return $format;
		}
	}

	function date_diff( DateTime $date1, DateTime $date2 ) {

		$diff = new DateInterval();

		if ( $date1 > $date2 ) {
			$tmp          = $date1;
			$date1        = $date2;
			$date2        = $tmp;
			$diff->invert = 1;
		} else {
			$diff->invert = 0;
		}

		$diff->y = ( (int) $date2->format( 'Y' ) ) - ( (int) $date1->format( 'Y' ) );
		$diff->m = ( (int) $date2->format( 'n' ) ) - ( (int) $date1->format( 'n' ) );
		if ( $diff->m < 0 ) {
			$diff->y -= 1;
			$diff->m  = $diff->m + 12;
		}
		$diff->d = ( (int) $date2->format( 'j' ) ) - ( (int) $date1->format( 'j' ) );
		if ( $diff->d < 0 ) {
			$diff->m -= 1;
			$diff->d  = $diff->d + ( (int) $date1->format( 't' ) );
		}
		$diff->h = ( (int) $date2->format( 'G' ) ) - ( (int) $date1->format( 'G' ) );
		if ( $diff->h < 0 ) {
			$diff->d -= 1;
			$diff->h  = $diff->h + 24;
		}
		$diff->i = ( (int) $date2->format( 'i' ) ) - ( (int) $date1->format( 'i' ) );
		if ( $diff->i < 0 ) {
			$diff->h -= 1;
			$diff->i  = $diff->i + 60;
		}
		$diff->s = ( (int) $date2->format( 's' ) ) - ( (int) $date1->format( 's' ) );
		if ( $diff->s < 0 ) {
			$diff->i -= 1;
			$diff->s  = $diff->s + 60;
		}

		$start_ts   = $date1->format( 'U' );
		$end_ts     = $date2->format( 'U' );
		$days       = $end_ts - $start_ts;
		$diff->days = round( $days / 86400 );

		if ( ( $diff->h > 0 || $diff->i > 0 || $diff->s > 0 ) ) {
			$diff->days += ( (bool) $diff->invert )
				? 1
				: -1;
		}

		return $diff;
	}
}

if ( ! function_exists( 'sp_flush_rewrite_rules' ) ) {
	function sp_flush_rewrite_rules() {
		// Flush rewrite rules
		$post_types = new SP_Post_types();
		$post_types->register_taxonomies();
		$post_types->register_post_types();
		flush_rewrite_rules();
	}
}

if ( ! function_exists( 'sp_add_link' ) ) {
	function sp_add_link( $string, $link = false, $active = true ) {
		if ( empty( $link ) || ! $active ) {
			return $string;
		}
		return '<a href="' . $link . '" itemprop="url" content="' . $link . '">' . $string . '</a>';
	}
}

if ( ! function_exists( 'sp_nonce' ) ) {
	function sp_nonce() {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
	}
}

if ( ! function_exists( 'sp_get_option' ) ) {
	function sp_get_option( $option, $default = null ) {
		if ( isset( $_POST[ $option ] ) ) {
			sanitize_text_field( wp_unslash( $_POST[ $option ] ) );
		} else {
			return get_option( $option, $default );
		}
	}
}

if ( ! function_exists( 'sp_array_between' ) ) {
	function sp_array_between( $array = array(), $delimiter = 0, $index = 0 ) {
		$keys = array_keys( $array, $delimiter );
		if ( array_key_exists( $index, $keys ) ) :
			$offset = $keys[ $index ];
			$end    = sizeof( $array );
			if ( array_key_exists( $index + 1, $keys ) ) {
				$end = $keys[ $index + 1 ];
			}
			$length = $end - $offset;
			$array  = array_slice( $array, $offset, $length );
		endif;
		return $array;
	}
}

if ( ! function_exists( 'sp_array_map_recursive' ) ) {
	function sp_array_map_recursive( callable $func, array $arr ) {
		array_walk_recursive(
			$arr,
			function( &$v ) use ( $func ) {
				$v = $func( $v );
			}
		);
		return $arr;
	}
}

if ( ! function_exists( 'sp_array_value' ) ) {
	function sp_array_value( $arr = array(), $key = 0, $default = null, $sanitize = false ) {
		$value = ( isset( $arr[ $key ] ) ? $arr[ $key ] : $default );

		if ( $sanitize ) :
			if ( is_array( $value ) ) :
				switch ( $sanitize ) :
					case 'int':
						$value = sp_array_map_recursive( 'intval', $value );
						break;
					case 'title':
						$value = sp_array_map_recursive( 'sanitize_title', $value );
						break;
					case 'text':
						$value = sp_array_map_recursive( 'wp_kses_post', $value );
						break;
					case 'key':
						$value = sp_array_map_recursive( 'sanitize_key', $value );
						break;
			endswitch;
		  else :
			  switch ( $sanitize ) :
				  case 'int':
					  if ( empty( $value ) ) {
						  $value = $value;
					  }else{
						$value = intval( $value );
					  }
					  break;
				  case 'title':
					  $value = sanitize_title( $value );
					  break;
				  case 'text':
					  $value = sanitize_text_field( $value );
					  break;
				  case 'key':
					  $value = sanitize_key( $value );
					  break;
			endswitch;
		  endif;
	endif;

		return $value;
	}
}

if ( ! function_exists( 'sp_array_combine' ) ) {
	function sp_array_combine( $keys = array(), $values = array(), $key_order = false ) {
		if ( ! is_array( $keys ) ) {
			return array();
		}
		if ( ! is_array( $values ) ) {
			$values = array();
		}

		$output = array();

		if ( $key_order ) :
			foreach ( $keys as $key ) :
				if ( array_key_exists( $key, $values ) ) {
					$output[ $key ] = $values[ $key ];
				} else {
					$output[ $key ] = array();
				}
			endforeach;
		else :
			foreach ( $values as $key => $value ) :
				if ( in_array( $key, $keys ) ) :
					$output[ $key ] = $value;
				endif;
			endforeach;

			foreach ( $keys as $key ) :
				if ( $key !== false && ! array_key_exists( $key, $output ) ) {
					$output[ $key ] = array();
				}
			endforeach;
		endif;
		return $output;
	}
}

if ( ! function_exists( 'sp_numbers_to_words' ) ) {
	function sp_numbers_to_words( $str ) {
		$output = str_replace( array( '%', '1st', '2nd', '3rd', '5th', '8th', '9th', '10', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ), array( 'percent', 'first', 'second', 'third', 'fifth', 'eight', 'ninth', 'ten', 'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine' ), $str );
		return $output;
	}
}

if ( ! function_exists( 'sp_column_active' ) ) {
	function sp_column_active( $array = null, $value = null ) {
		return $array == null || in_array( $value, $array );
	}
}

if ( ! function_exists( 'sp_get_the_term_id' ) ) {
	function sp_get_the_term_id( $post_id, $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_array( $terms ) && sizeof( $terms ) > 0 ) :
			$term = reset( $terms );
			if ( is_object( $term ) && property_exists( $term, 'term_id' ) ) {
				return $term->term_id;
			} else {
				return 0;
			} else :
				return 0;
		endif;
	}
}

if ( ! function_exists( 'sp_get_the_term_ids' ) ) {
	function sp_get_the_term_ids( $post_id, $taxonomy ) {
		$terms    = get_the_terms( $post_id, $taxonomy );
		$term_ids = array();

		if ( is_array( $terms ) && sizeof( $terms ) > 0 ) {
			$term_ids = wp_list_pluck( $terms, 'term_id' );
		}

		$term_ids = sp_add_auto_term( $term_ids, $post_id, $taxonomy );

		return $term_ids;
	}
}

if ( ! function_exists( 'sp_get_the_term_id_or_meta' ) ) {
	function sp_get_the_term_id_or_meta( $post_id, $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_array( $terms ) && sizeof( $terms ) > 0 ) :
			$term = reset( $terms );
			if ( is_object( $term ) && property_exists( $term, 'term_id' ) ) {
				return $term->term_id;
			} else {
				return 0;
			} else :
				return get_post_meta( $post_id, $taxonomy, true );
		endif;
	}
}

if ( ! function_exists( 'sp_add_auto_term' ) ) {
	function sp_add_auto_term( $term_ids, $post_id, $taxonomy ) {
		switch ( $taxonomy ) {
			case 'sp_league':
				if ( get_post_meta( $post_id, 'sp_main_league', true ) ) {
					$term_id = get_option( 'sportspress_league', false );
					if ( $term_id ) {
						$term_ids[] = $term_id;
					}
				}
				break;
			case 'sp_season':
				if ( get_post_meta( $post_id, 'sp_current_season', true ) ) {
					$term_id = get_option( 'sportspress_season', false );
					if ( $term_id ) {
						$term_ids[] = $term_id;
					}
				}
				break;
		}

		return $term_ids;
	}
}

if ( ! function_exists( 'sp_get_url' ) ) {
	function sp_get_url( $post_id ) {
		$url = get_post_meta( $post_id, 'sp_url', true );
		if ( ! $url ) {
			return;
		}
		return ' <a class="sp-link" href="' . $url . '" target="_blank" title="' . esc_attr__( 'Visit Site', 'sportspress' ) . '">' . $url . '</a>';
	}
}

if ( ! function_exists( 'sp_get_post_abbreviation' ) ) {
	function sp_get_post_abbreviation( $post_id ) {
		$abbreviation = get_post_meta( $post_id, 'sp_abbreviation', true );
		if ( $abbreviation ) :
			return $abbreviation;
		else :
			return mb_substr( get_the_title( $post_id ), 0, 1 );
		endif;
	}
}

if ( ! function_exists( 'sp_get_post_condition' ) ) {
	function sp_get_post_condition( $post_id ) {
		$condition   = get_post_meta( $post_id, 'sp_condition', true );
		$main_result = get_option( 'sportspress_primary_result', null );
		$result      = get_page_by_path( $main_result, ARRAY_A, 'sp_result' );
		$label       = sp_array_value( $result, 'post_title', esc_attr__( 'Primary', 'sportspress' ) );
		if ( $condition ) :
			$conditions = array(
				'0'    => '&mdash;',
				'>'    => sprintf( esc_attr__( 'Most %s', 'sportspress' ), $label ),
				'<'    => sprintf( esc_attr__( 'Least %s', 'sportspress' ), $label ),
				'='    => sprintf( esc_attr__( 'Equal %s', 'sportspress' ), $label ),
				'else' => sprintf( esc_attr__( 'Default', 'sportspress' ), $label ),
			);
			return sp_array_value( $conditions, wp_specialchars_decode( $condition ), '&mdash;' );
		else :
			return '&mdash;';
		endif;
	}
}

if ( ! function_exists( 'sp_get_post_precision' ) ) {
	function sp_get_post_precision( $post_id ) {
		$precision = get_post_meta( $post_id, 'sp_precision', true );
		if ( $precision ) :
			return $precision;
		else :
			return 0;
		endif;
	}
}

if ( ! function_exists( 'sp_get_post_calculate' ) ) {
	function sp_get_post_calculate( $post_id ) {
		$calculate = get_post_meta( $post_id, 'sp_calculate', true );
		if ( $calculate ) :
			return str_replace(
				array( 'total', 'average' ),
				array( esc_attr__( 'Total', 'sportspress' ), esc_attr__( 'Average', 'sportspress' ) ),
				$calculate
			);
		else :
			return esc_attr__( 'Total', 'sportspress' );
		endif;
	}
}

if ( ! function_exists( 'sp_get_post_equation' ) ) {
	function sp_get_post_equation( $post_id ) {
		$equation = get_post_meta( $post_id, 'sp_equation', true );
		if ( $equation ) :
			$equation = str_replace(
				array( '/', '(', ')', '+', '-', '*', '_', '$' ),
				array( '&divide;', '(', ')', '&#43;', '&minus;', '&times;', '@', '' ),
				trim( $equation )
			);
			return '<code>' . implode( '</code> <code>', explode( ' ', $equation ) ) . '</code>';
		else :
			return '&mdash;';
		endif;
	}
}

if ( ! function_exists( 'sp_get_post_order' ) ) {
	function sp_get_post_order( $post_id ) {
		$priority = get_post_meta( $post_id, 'sp_priority', true );
		if ( $priority ) :
			return $priority . ' ' . str_replace(
				array( 'DESC', 'ASC' ),
				array( '&darr;', '&uarr;' ),
				get_post_meta( $post_id, 'sp_order', true )
			);
		else :
			return '&mdash;';
		endif;
	}
}

if ( ! function_exists( 'sp_get_post_section' ) ) {
	function sp_get_post_section( $post_id ) {
		$section = get_post_meta( $post_id, 'sp_section', true );
		if ( isset( $section ) ) :
			$options = apply_filters(
				'sportspress_performance_sections',
				array(
					-1 => esc_attr__( 'All', 'sportspress' ),
					0  => esc_attr__( 'Offense', 'sportspress' ),
					1  => esc_attr__(
						'Defense',
						'sportspress'
					),
				)
			);
			return sp_array_value( $options, $section, esc_attr__( 'All', 'sportspress' ) );
		else :
			return esc_attr__( 'All', 'sportspress' );
		endif;
	}
}

if ( ! function_exists( 'sp_get_post_format' ) ) {
	function sp_get_post_format( $post_id ) {
		$format = get_post_meta( $post_id, 'sp_format', true );
		if ( isset( $format ) ) :
			$options = apply_filters(
				'sportspress_performance_formats',
				array(
					'number'   => esc_attr__( 'Number', 'sportspress' ),
					'time'     => esc_attr__( 'Time', 'sportspress' ),
					'text'     => esc_attr__( 'Text', 'sportspress' ),
					'equation' => esc_attr__( 'Equation', 'sportspress' ),
					'checkbox' => esc_attr__(
						'Checkbox',
						'sportspress'
					),
				)
			);
			return sp_array_value( $options, $format, esc_attr__( 'Number', 'sportspress' ) );
		else :
			return esc_attr__( 'Number', 'sportspress' );
		endif;
	}
}

if ( ! function_exists( 'sp_get_format_placeholder' ) ) {
	function sp_get_format_placeholder( $key = 'number' ) {
		$placeholders = apply_filters(
			'sportspress_format_placeholders',
			array(
				'number'   => 0,
				'time'     => '0:00',
				'text'     => '&nbsp;',
				'checkbox' => '&nbsp;',
			)
		);
		return sp_array_value( $placeholders, $key, 0 );
	}
}

if ( ! function_exists( 'sp_get_term_sections' ) ) {
	function sp_get_term_sections( $t_id ) {
		$term_meta = get_option( "taxonomy_$t_id" );
		if ( isset( $term_meta['sp_sections'] ) ) {
			$sections = $term_meta['sp_sections'];
		} else {
			$sections = apply_filters(
				'sportspress_performance_sections',
				array(
					0 => esc_attr__( 'Offense', 'sportspress' ),
					1 => esc_attr__(
						'Defense',
						'sportspress'
					),
				)
			);
			$sections = array_keys( $sections );
		}

		if ( '' === $sections ) {
			$sections = array();
		}

		return $sections;
	}
}

if ( ! function_exists( 'sp_get_default_mode' ) ) {
	function sp_get_default_mode() {
		$mode = get_option( 'sportspress_mode', 'team' );

		if ( empty( $mode ) ) {
			$mode = 'team';
		}

		return $mode;
	}
}

if ( ! function_exists( 'sp_get_post_mode' ) ) {
	function sp_get_post_mode( $post_id ) {
		$mode = get_post_meta( $post_id, 'sp_mode', true );

		if ( empty( $mode ) ) {
			$mode = sp_get_default_mode();
		}

		return $mode;
	}
}

if ( ! function_exists( 'sp_get_post_mode_type' ) ) {
	function sp_get_post_mode_type( $post_id ) {
		$mode = sp_get_post_mode( $post_id );

		$post_type = "sp_$mode";

		if ( ! in_array( $post_type, sp_primary_post_types() ) ) {
			$post_type = sp_get_default_mode();
		}

		return $post_type;
	}
}

if ( ! function_exists( 'sp_get_post_mode_label' ) ) {
	function sp_get_post_mode_label( $post_id, $singular = false ) {
		$labels = array(
			'team'   => array(
				__( 'Teams', 'sportspress' ),
				__( 'Team', 'sportspress' ),
			),
			'player' => array(
				__( 'Players', 'sportspress' ),
				__( 'Player', 'sportspress' ),
			),
		);

		$mode = sp_get_post_mode( $post_id );

		if ( ! array_key_exists( $mode, $labels ) ) {
			$mode = 'team';
		}

		$index = intval( $singular );

		return $labels[ $mode ][ $index ];
	}
}

if ( ! function_exists( 'sp_dropdown_statuses' ) ) {
	function sp_dropdown_statuses( $args = array() ) {
		$defaults = array(
			'show_option_default' => false,
			'name'                => 'sp_status',
			'id'                  => null,
			'selected'            => null,
			'class'               => null,
		);
		$args     = array_merge( $defaults, $args );

		printf( '<select name="%s" class="postform %s">', esc_attr( $args['name'] ), esc_attr( $args['class'] ) );

		if ( $args['show_option_default'] ) :
			printf( '<option value="default">%s</option>', esc_attr( $args['show_option_default'] ) );
		endif;

		$statuses = apply_filters(
			'sportspress_statuses',
			array(
				'any'     => esc_attr__( 'All', 'sportspress' ),
				'publish' => esc_attr__( 'Published', 'sportspress' ),
				'future'  => esc_attr__( 'Scheduled', 'sportspress' ),
			)
		);

		foreach ( $statuses as $value => $label ) :
			printf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $value, $args['selected'], false ), esc_attr( $label ) );
		endforeach;
		print( '</select>' );
		return true;
	}
}

if ( ! function_exists( 'sp_dropdown_dates' ) ) {
	function sp_dropdown_dates( $args = array() ) {
		$defaults = array(
			'show_option_default' => false,
			'name'                => 'sp_date',
			'id'                  => null,
			'selected'            => null,
			'class'               => null,
		);
		$args     = array_merge( $defaults, $args );

		printf( '<select name="%s" class="postform %s">', esc_attr( $args['name'] ), esc_attr( $args['class'] ) );

		if ( $args['show_option_default'] ) :
			printf( '<option value="default">%s</option>', esc_attr( $args['show_option_default'] ) );
		endif;

		$dates = apply_filters(
			'sportspress_dates',
			array(
				0       => esc_attr__( 'All', 'sportspress' ),
				'-day'  => esc_attr__( 'Yesterday', 'sportspress' ),
				'day'   => esc_attr__( 'Today', 'sportspress' ),
				'+day'  => esc_attr__( 'Tomorrow', 'sportspress' ),
				'-w'    => esc_attr__( 'Last week', 'sportspress' ),
				'w'     => esc_attr__( 'This week', 'sportspress' ),
				'+w'    => esc_attr__( 'Next week', 'sportspress' ),
				'range' => esc_attr__( 'Date range:', 'sportspress' ),
			)
		);

		foreach ( $dates as $value => $label ) :
			printf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $value, $args['selected'], false ), esc_attr( $label ) );
		endforeach;
		print( '</select>' );
		return true;
	}
}

if ( ! function_exists( 'sp_dropdown_taxonomies' ) ) {
	function sp_dropdown_taxonomies( $args = array() ) {
		$defaults = array(
			'show_option_blank' => false,
			'show_option_all'   => false,
			'show_option_none'  => false,
			'show_option_auto'  => false,
			'taxonomy'          => null,
			'name'              => null,
			'id'                => null,
			'selected'          => null,
			'hide_empty'        => false,
			'values'            => 'slug',
			'class'             => null,
			'property'          => 'none',
			'placeholder'       => null,
			'chosen'            => false,
			'parent'            => 0,
			'include_children'  => true,
		);
		$args     = array_merge( $defaults, $args );
		if ( ! $args['taxonomy'] ) {
			return false;
		}

		$name = ( $args['name'] ) ? $args['name'] : $args['taxonomy'];
		$id   = ( $args['id'] ) ? $args['id'] : $name;

		unset( $args['name'] );
		unset( $args['id'] );

		$class = $args['class'];
		unset( $args['class'] );

		$property = $args['property'];
		unset( $args['property'] );

		$placeholder = $args['placeholder'];
		unset( $args['placeholder'] );

		$selected = $args['selected'];
		unset( $args['selected'] );

		$chosen = $args['chosen'];
		unset( $args['chosen'] );

		$terms = get_terms( $args['taxonomy'], $args );

		printf( '<input type="hidden" name="tax_input[%s][]" value="0">', esc_attr( $args['taxonomy'] ) );

		if ( $terms ) :
			printf( '<select name="%s" class="postform %s" %s>', esc_attr( $name ), esc_attr( $class ) . ( $chosen ? ' chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' ) : '' ), ( $placeholder != null ? 'data-placeholder="' . esc_attr( $placeholder ) . '" ' : '' ) . esc_attr( $property ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( $property && strpos( $property, 'multiple' ) === false ) :
				if ( $args['show_option_blank'] ) :
					echo '<option value="">' . ( is_bool( $args['show_option_blank'] ) ? '' : esc_attr( $args['show_option_blank'] ) ) . '</option>';
				endif;
				if ( $args['show_option_all'] ) :
					printf( '<option value="0" ' . selected( '0', $selected, false ) . '>%s</option>', esc_attr( $args['show_option_all'] ) );
				endif;
				if ( $args['show_option_none'] ) :
					printf( '<option value="-1" ' . selected( '-1', $selected, false ) . '>%s</option>', esc_attr( $args['show_option_none'] ) );
				endif;
			endif;

			if ( $args['show_option_auto'] ) :
				if ( $property && strpos( $property, 'multiple' ) !== false ) :
					$selected_prop = in_array( 'auto', $selected ) ? 'selected' : '';
				else :
					$selected_prop = selected( 'auto', $selected, false );
				endif;
				printf( '<option value="auto" ' . esc_attr( $selected_prop ) . '>%s</option>', esc_attr( $args['show_option_auto'] ) . ' ' . esc_attr__( '(Auto)', 'sportspress' ) );
			endif;

			foreach ( $terms as $term ) :

				if ( $args['values'] == 'term_id' ) :
					$this_value = $term->term_id;
				else :
					$this_value = $term->slug;
				endif;

				if ( $property && strpos( $property, 'multiple' ) !== false ) :
					$selected_prop = in_array( $this_value, $selected ) ? 'selected' : '';
				else :
					$selected_prop = selected( $this_value, $selected, false );
				endif;

				printf( '<option value="%s" %s>%s</option>', esc_attr( $this_value ), esc_attr( $selected_prop ), esc_attr( $term->name ) );

				if ( $args['include_children'] ) :
					sp_dropdown_hierarchical_taxonomies( $args, $property, $selected, $term->term_id );
				endif;
			endforeach;
			print( '</select>' );
			return true;
		else :
			return false;
		endif;
	}
}

function sp_dropdown_hierarchical_taxonomies( $args, $property, $selected, $current_term_id = 0, $depth = 1 ) {
    $term_children = get_terms([
        'taxonomy'   => $args['taxonomy'],
        'hide_empty' => false,
        'parent'     => $current_term_id,
    ]);

    if ( $term_children ) {
		$indent = str_repeat('-', $depth);

		foreach ( $term_children as $term_child ) {
			if ( $args['values'] == 'term_id' ) :
				$this_value = $term_child->term_id;
			else :
				$this_value = $term_child->slug;
			endif;

			if ( $property && strpos( $property, 'multiple' ) !== false ) :
				$selected_prop = in_array( $this_value, $selected ) ? 'selected' : '';
			else :
				$selected_prop = selected( $this_value, $selected, false );
			endif;

			printf( '<option value="%s" %s>%s</option>', esc_attr( $this_value ), esc_attr( $selected_prop ), $indent . ' ' . esc_attr( $term_child->name ) );

			sp_dropdown_hierarchical_taxonomies( $args, $property, $selected, $term_child->term_id, $depth + 1 );
		}
	}
}

if ( ! function_exists( 'sp_dropdown_pages' ) ) {
	function sp_dropdown_pages( $args = array() ) {
		$defaults = array(
			'prepend_options'   => null,
			'append_options'    => null,
			'show_option_blank' => false,
			'show_option_all'   => false,
			'show_option_none'  => false,
			'show_dates'        => false,
			'option_all_value'  => 0,
			'option_none_value' => -1,
			'name'              => 'page_id',
			'id'                => null,
			'selected'          => null,
			'numberposts'       => -1,
			'posts_per_page'    => -1,
			'child_of'          => 0,
			'order'             => 'ASC',
			'orderby'           => 'title',
			'hierarchical'      => 1,
			'exclude'           => null,
			'include'           => null,
			'meta_key'          => null,
			'meta_value'        => null,
			'authors'           => null,
			'exclude_tree'      => null,
			'post_type'         => 'page',
			'post_status'       => 'publish',
			'values'            => 'post_name',
			'class'             => null,
			'property'          => 'none',
			'placeholder'       => null,
			'chosen'            => false,
			'filter'            => false,
		);
		$args     = array_merge( $defaults, $args );

		$name = $args['name'];
		unset( $args['name'] );

		$id = ( $args['id'] ) ? $args['id'] : $name;
		unset( $args['id'] );

		$values = $args['values'];
		unset( $args['values'] );

		$class = $args['class'];
		unset( $args['class'] );

		$property = $args['property'];
		unset( $args['property'] );

		$placeholder = $args['placeholder'];
		unset( $args['placeholder'] );

		$selected = $args['selected'];
		unset( $args['selected'] );

		$chosen = $args['chosen'];
		unset( $args['chosen'] );

		$filter = $args['filter'];
		unset( $args['filter'] );

		$posts = get_posts( $args );
		if ( $posts || $args['prepend_options'] || $args['append_options'] ) :
			printf( '<select name="%s" id="%s" class="postform %s" %s>', esc_attr( $name ), esc_attr( $id ), esc_attr( $class ) . ( $chosen ? ' chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' ) : '' ), ( $placeholder != null ? 'data-placeholder="' . esc_attr( $placeholder ) . '" ' : '' ) . esc_attr( $property ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( $property && strpos( $property, 'multiple' ) === false ) :
				if ( $args['show_option_blank'] ) :
					printf( '<option value=""></option>' );
				endif;
				if ( $args['show_option_none'] ) :
					printf( '<option value="%s" %s>%s</option>', esc_attr( $args['option_none_value'] ), selected( $selected, esc_attr( $args['option_none_value'] ), false ), ( $args['show_option_none'] === true ? '' : esc_attr( $args['show_option_none'] ) ) );
				endif;
				if ( $args['show_option_all'] ) :
					printf( '<option value="%s" %s>%s</option>', esc_attr( $args['option_all_value'] ), selected( $selected, esc_attr( $args['option_all_value'] ), false ), esc_attr( $args['show_option_all'] ) );
				endif;
				if ( $args['prepend_options'] && is_array( $args['prepend_options'] ) ) :
					foreach ( $args['prepend_options'] as $slug => $label ) :
						printf( '<option value="%s" %s>%s</option>', esc_attr( $slug ), selected( $selected, $slug, false ), esc_attr( $label ) );
					endforeach;
				endif;
			endif;

			foreach ( $posts as $post ) :
				setup_postdata( $post );

				if ( $values == 'ID' ) :
					$this_value = $post->ID;
				else :
					$this_value = $post->post_name;
				endif;

				if ( $property && strpos( $property, 'multiple' ) !== false ) :
					$selected_prop = in_array( $this_value, $selected ) ? 'selected' : '';
				else :
					$selected_prop = selected( $this_value, $selected, false );
				endif;

				if ( $filter !== false ) :
					$class         = 'sp-post sp-filter-0';
					$filter_values = get_post_meta( $post->ID, $filter, false );
					foreach ( $filter_values as $filter_value ) :
						$class .= ' sp-filter-' . $filter_value;
					endforeach;
				else :
					$class = '';
				endif;

				printf( '<option value="%s" class="%s" %s>%s</option>', esc_attr( $this_value ), esc_attr( $class ), esc_attr( $selected_prop ), esc_attr( $post->post_title ) . ( $args['show_dates'] ? ' (' . esc_attr( $post->post_date ) . ')' : '' ) );
			endforeach;
			wp_reset_postdata();

			if ( $property && strpos( $property, 'multiple' ) === false ) :
				if ( $args['append_options'] && is_array( $args['append_options'] ) ) :
					foreach ( $args['append_options'] as $slug => $label ) :
						printf( '<option value="%s" %s>%s</option>', esc_attr( $slug ), selected( $selected, $slug, false ), esc_attr( $label ) );
					endforeach;
				endif;
			endif;
			print( '</select>' );
			return true;
		else :
			return false;
		endif;
	}
}

if ( ! function_exists( 'sp_posts' ) ) {
	function sp_posts( $post_id = null, $meta = 'post' ) {
		if ( ! isset( $post_id ) ) {
			global $post_id;
		}
		$ids = get_post_meta( $post_id, $meta, false );
		if ( ( $key = array_search( 0, $ids ) ) !== false ) {
			unset( $ids[ $key ] );
		}
		$i     = 0;
		$count = count( $ids );
		if ( isset( $ids ) && $ids && is_array( $ids ) && ! empty( $ids ) ) :
			foreach ( $ids as $id ) :
				if ( ! $id ) {
					continue;
				}
				$parents = get_post_ancestors( $id );
				$keys    = array_keys( $parents );
				$values  = array_reverse( array_values( $parents ) );
				if ( ! empty( $keys ) && ! empty( $values ) ) :
					$parents = array_combine( $keys, $values );
					foreach ( $parents as $parent ) :
						if ( ! in_array( $parent, $ids ) ) {
							edit_post_link( get_the_title( $parent ), '', '', $parent );
						}
						echo ' - ';
					endforeach;
				endif;
				$title = get_the_title( $id );
				if ( ! $title ) {
					continue;
				}
				if ( empty( $title ) ) {
					$title = esc_attr__( '(no title)', 'sportspress' );
				}
				edit_post_link( $title, '', '', $id );
				if ( ++$i !== $count ) {
					echo ', ';
				}
			endforeach;
		endif;
	}
}

if ( ! function_exists( 'sp_post_checklist' ) ) {
	function sp_post_checklist( $post_id = null, $meta = 'post', $display = 'block', $filters = null, $index = null, $slug = null ) {
		if ( ! isset( $post_id ) ) {
			global $post_id;
		}
		if ( ! isset( $slug ) ) {
			$slug = $meta;
		}
		?>
		<div id="<?php echo esc_attr( $slug ); ?>-all" class="posttypediv tabs-panel wp-tab-panel sp-tab-panel sp-tab-filter-panel sp-select-all-range" style="display: <?php echo esc_attr( $display ); ?>;">
			<input type="hidden" value="0" name="<?php echo esc_attr( $slug ); ?><?php if ( isset( $index ) ) { echo '[' . esc_attr( $index ) . ']';} ?>[]" />
			<ul class="categorychecklist form-no-clear">
				<li class="sp-select-all-container"><label class="selectit"><input type="checkbox" class="sp-select-all"> <strong><?php esc_attr_e( 'Select All', 'sportspress' ); ?></strong></label></li>
				<?php
				$selected = (array) get_post_meta( $post_id, $slug, false );
				if ( ! sizeof( $selected ) ) {
					$selected = (array) get_post_meta( $post_id, $meta, false );
				}
				$selected = sp_array_between( $selected, 0, $index );
				if ( empty( $posts ) ) :
					$query = array(
						'post_type'     => $meta,
						'numberposts'   => -1,
						'post_per_page' => -1,
						'orderby'       => 'menu_order',
					);
					if ( $meta == 'sp_player' ) :
						$query['meta_key'] = 'sp_number';
						$query['orderby']  = 'meta_value_num';
						$query['order']    = 'ASC';
					endif;
					// Add a hook to alter $query args.
					$query = apply_filters( 'sportspress_sp_post_checklist_args', $query, $meta );
					$posts = get_posts( $query );
				endif;
				foreach ( $posts as $post ) :
					$parents = get_post_ancestors( $post );
					if ( $filters ) :
						if ( is_array( $filters ) ) :
							$filter_values = array();
							foreach ( $filters as $filter ) :
								if ( get_taxonomy( $filter ) ) :
									$terms = (array) get_the_terms( $post->ID, $filter );
									foreach ( $terms as $term ) :
										if ( is_object( $term ) && property_exists( $term, 'term_id' ) ) {
											$filter_values[] = $term->term_id;
										}
									endforeach;
								else :
									$filter_values = array_merge( $filter_values, (array) get_post_meta( $post->ID, $filter, false ) );
								endif;
							endforeach;
						else :
							$filter = $filters;
							if ( get_taxonomy( $filter ) ) :
								$terms = (array) get_the_terms( $post->ID, $filter );
								foreach ( $terms as $term ) :
									if ( is_object( $term ) && property_exists( $term, 'term_id' ) ) {
										$filter_values[] = $term->term_id;
									}
								endforeach;
							else :
								$filter_values = (array) get_post_meta( $post->ID, $filter, false );
							endif;
						endif;
					endif;
					?>
					<li class="sp-post sp-filter-0
					<?php
					if ( $filters ) :
						foreach ( $filter_values as $filter_value ) :
							echo ' sp-filter-' . esc_attr( $filter_value );
							endforeach;
						endif;
					?>
					">
						<?php echo str_repeat( '<ul><li>', sizeof( $parents ) ); ?>
						<label class="selectit">
							<input type="checkbox" value="<?php echo esc_attr( $post->ID ); ?>" name="<?php echo esc_attr( $slug ); ?><?php if ( isset( $index ) ) { echo '[' . esc_attr( $index ) . ']';} ?>[]"
							<?php
							if ( in_array( $post->ID, $selected ) ) {
								echo ' checked="checked"';}
							?>
>
							<?php echo esc_html( sp_get_player_name_with_number( $post->ID ) ); ?>
						</label>
						<?php echo str_repeat( '</li></ul>', sizeof( $parents ) ); ?>
					</li>
					<?php
				endforeach;
				?>
				<li class="sp-not-found-container">
					<?php esc_attr_e( 'No results found.', 'sportspress' ); ?>
					<?php
					if ( sizeof( $posts ) ) :
						?>
						<a class="sp-show-all" href="#show-all-<?php echo esc_attr( $slug ); ?>s"><?php esc_attr_e( 'Show all', 'sportspress' ); ?></a><?php endif; ?>
				</li>
				<?php if ( sizeof( $posts ) ) : ?>
					<li class="sp-show-all-container"><a class="sp-show-all" href="#show-all-<?php echo esc_attr( $slug ); ?>s"><?php esc_attr_e( 'Show all', 'sportspress' ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
		<?php
	}
}

if ( ! function_exists( 'sp_column_checklist' ) ) {
	function sp_column_checklist( $post_id = null, $meta = 'post', $display = 'block', $selected = array(), $default_checked = false ) {
		if ( ! isset( $post_id ) ) {
			global $post_id;
		}
		?>
		<div id="<?php echo esc_attr( $meta ); ?>-all" class="posttypediv tabs-panel wp-tab-panel sp-tab-panel sp-select-all-range" style="display: <?php echo esc_attr( $display ); ?>;">
			<input type="hidden" value="0" name="sp_columns[]" />
			<ul class="categorychecklist form-no-clear">
				<li class="sp-select-all-container"><label class="selectit"><input type="checkbox" class="sp-select-all"> <strong><?php esc_attr_e( 'Select All', 'sportspress' ); ?></strong></label></li>
				<?php
				$posts = get_pages(
					array(
						'post_type' => $meta,
						'number'    => 0,
					)
				);
				if ( empty( $posts ) ) :
					$query = array(
						'post_type'     => $meta,
						'numberposts'   => -1,
						'post_per_page' => -1,
						'order'         => 'ASC',
						'orderby'       => 'menu_order',
						'meta_query'    => array(
							'relation' => 'OR',
							array(
								'key'     => 'sp_format',
								'value'   => 'number',
								'compare' => 'NOT EXISTS',
							),
							array(
								'key'     => 'sp_format',
								'value'   => array( 'equation', 'text' ),
								'compare' => 'NOT IN',
							),
						),
					);
					$posts = get_posts( $query );
				endif;
				if ( sizeof( $posts ) ) :
					foreach ( $posts as $post ) :
						if ( 'sp_performance' == $meta ) {
							$format = get_post_meta( $post->ID, 'sp_format', true );
							if ( 'text' === $format ) {
								continue;
							}
						}
						?>
						<li class="sp-post">
							<label class="selectit">
								<input type="checkbox" value="<?php echo esc_attr( $post->post_name ); ?>" name="sp_columns[]"
																		 <?php
																			if ( ( ! is_array( $selected ) && $default_checked ) || in_array( $post->post_name, $selected ) ) {
																				echo ' checked="checked"';}
																			?>
								>
								<?php echo esc_html( sp_draft_or_post_title( $post ) ); ?>
							</label>
						</li>
						<?php
					endforeach;
				else :
					?>
				<li class="sp-not-found-container"><?php esc_attr_e( 'No results found.', 'sportspress' ); ?></li>
				<?php endif; ?>
			</ul>
		</div>
		<?php
	}
}


/**
 * Get the post title.
 *
 * The post title is fetched and if it is blank then a default string is
 * returned.
 *
 * @since 2.7.0
 * @param mixed $post Post id or object. If not supplied the global $post is used.
 * @return string The post title if set
 */
if ( ! function_exists( 'sp_draft_or_post_title' ) ) {
	function sp_draft_or_post_title( $post = 0 ) {
		$title = get_the_title( $post );
		if ( empty( $title ) ) {
			$title = esc_attr__( '(no title)', 'sportspress' );
		}
		return $title;
	}
}

if ( ! function_exists( 'sp_get_var_labels' ) ) {
	function sp_get_var_labels( $post_type, $neg = null, $all = true ) {
		$args = array(
			'post_type'      => $post_type,
			'numberposts'    => -1,
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);

		if ( ! $all ) {
			$args['meta_query'] = array(
				array(
					'key'   => 'sp_visible',
					'value' => 1,
				),
				array(
					'key'     => 'sp_visible',
					'value'   => 1,
					'compare' => 'NOT EXISTS',
				),
				'relation' => 'OR',
			);
		}

		$vars = get_posts( $args );

		$output = array();
		foreach ( $vars as $var ) :
			if ( $neg === null || ( $neg && $var->menu_order < 0 ) || ( ! $neg && $var->menu_order >= 0 ) ) {
				$output[ $var->post_name ] = $var->post_title;
			}
		endforeach;

		return $output;
	}
}

if ( ! function_exists( 'sp_get_var_equations' ) ) {
	function sp_get_var_equations( $post_type ) {
		$args = array(
			'post_type'      => $post_type,
			'numberposts'    => -1,
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		);

		$vars = get_posts( $args );

		$output = array();
		foreach ( $vars as $var ) :
			$equation = get_post_meta( $var->ID, 'sp_equation', true );
			if ( ! $equation ) {
				$equation = 0;
			}
			$precision = get_post_meta( $var->ID, 'sp_precision', true );
			if ( ! $precision ) {
				$precision = 0;
			}
			$output[ $var->post_name ] = array(
				'equation'  => $equation,
				'precision' => $precision,
			);
		endforeach;

		return $output;
	}
}

if ( ! function_exists( 'sp_post_adder' ) ) {
	function sp_post_adder( $post_type = 'post', $label = null, $attributes = array() ) {
		$obj = get_post_type_object( $post_type );
		if ( $label == null ) {
			$label = esc_attr__( 'Add New', 'sportspress' );
		}
		?>
		<div id="<?php echo esc_attr( $post_type ); ?>-adder">
			<h4>
				<a title="<?php echo esc_attr( $label ); ?>" href="<?php echo esc_url( admin_url( add_query_arg( $attributes, 'post-new.php?post_type=' . $post_type ) ) ); ?>" target="_blank">
					+ <?php echo esc_html( $label ); ?>
				</a>
			</h4>
		</div>
		<?php
	}
}

if ( ! function_exists( 'sp_taxonomy_adder' ) ) {
	function sp_taxonomy_adder( $taxonomy = 'category', $post_type = null, $label = null ) {
		$obj = get_taxonomy( $taxonomy );
		if ( $label == null ) {
			$label = esc_attr__( 'Add New', 'sportspress' );
		}
		?>
		<div id="<?php echo esc_attr( $taxonomy ); ?>-adder">
			<h4>
				<a title="<?php echo esc_attr( $label ); ?>" href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=' . $taxonomy . ( $post_type ? '&post_type=' . $post_type : '' ) ) ); ?>" target="_blank">
					+ <?php echo esc_html( $label ); ?>
				</a>
			</h4>
		</div>
		<?php
	}
}

if ( ! function_exists( 'sp_update_post_meta' ) ) {
	function sp_update_post_meta( $post_id, $meta_key, $meta_value, $default = null ) {
		if ( ! isset( $meta_value ) && isset( $default ) ) {
			$meta_value = $default;
		}
		add_post_meta( $post_id, $meta_key, $meta_value, true );
	}
}

if ( ! function_exists( 'sp_add_post_meta_recursive' ) ) {
	function sp_add_post_meta_recursive( $post_id, $meta_key, $meta_value ) {
		$values = new RecursiveIteratorIterator( new RecursiveArrayIterator( $meta_value ) );
		foreach ( $values as $value ) :
			add_post_meta( $post_id, $meta_key, $value, false );
		endforeach;
	}
}

if ( ! function_exists( 'sp_update_post_meta_recursive' ) ) {
	function sp_update_post_meta_recursive( $post_id, $meta_key, $meta_value ) {
		delete_post_meta( $post_id, $meta_key );
		sp_add_post_meta_recursive( $post_id, $meta_key, $meta_value );
	}
}

if ( ! function_exists( 'sp_update_user_meta_recursive' ) ) {
	function sp_update_user_meta_recursive( $user_id, $meta_key, $meta_value ) {
		delete_user_meta( $user_id, $meta_key );
		$values = new RecursiveIteratorIterator( new RecursiveArrayIterator( $meta_value ) );
		foreach ( $values as $value ) :
			add_user_meta( $user_id, $meta_key, $value, false );
		endforeach;
	}
}

if ( ! function_exists( 'sp_get_eos_safe_slug' ) ) {
	function sp_get_eos_safe_slug( $title, $post_id = 'var' ) {

		// String to lowercase
		$title = strtolower( $title );

		// Replace all numbers with words
		$title = sp_numbers_to_words( $title );

		// Remove all other non-alphabet characters
		$title = preg_replace( '/[^a-z_]/', '', $title );

		// Convert post ID to words if title is empty
		if ( $title == '' ) :

			$title = sp_numbers_to_words( $post_id );

		endif;

		return $title;

	}
}

if ( ! function_exists( 'sp_solve' ) ) {
	function sp_solve( $equation, $vars, $precision = 0, $default = 0, $post_id = 0 ) {

		// Add a hook to alter $equation
		$equation = apply_filters( 'sportspress_equation_alter', $equation, $vars, $precision, $default );

		if ( $equation == null ) {
			return $default;
		}

		if ( strpos( $equation, '$gamesback' ) !== false ) :

			// Return placeholder
			return $default;

		elseif ( strpos( $equation, '$streak' ) !== false ) :

			// Return direct value
			return sp_array_value( $vars, 'streak', $default );

		elseif ( strpos( $equation, '$form' ) !== false ) :

			// Return direct value
			return sp_array_value( $vars, 'form', $default );

		elseif ( strpos( $equation, '$last5' ) !== false ) :

			// Return imploded string
			$last5 = sp_array_value( $vars, 'last5', array( 0 ) );
			if ( array_sum( $last5 ) > 0 ) :
				return implode( '-', $last5 );
			else :
				return $default;
			endif;

		elseif ( strpos( $equation, '$last10' ) !== false ) :

			// Return imploded string
			$last10 = sp_array_value( $vars, 'last10', array( 0 ) );
			if ( array_sum( $last10 ) > 0 ) :
				return implode( '-', $last10 );
			else :
				return $default;
			endif;

		elseif ( strpos( $equation, '$homerecord' ) !== false ) :

			// Return imploded string
			$homerecord = sp_array_value( $vars, 'homerecord', array( 0 ) );
			return implode( '-', $homerecord );

		elseif ( strpos( $equation, '$awayrecord' ) !== false ) :

			// Return imploded string
			$awayrecord = sp_array_value( $vars, 'awayrecord', array( 0 ) );
			return implode( '-', $awayrecord );

		endif;

		if ( $solution = apply_filters( 'sportspress_equation_solve_for_presets', null, $equation, $post_id ) ) :
			return $solution;
		endif;

		// Remove unnecessary variables from vars before calculating
		unset( $vars['gamesback'] );
		unset( $vars['streak'] );
		unset( $vars['last5'] );
		unset( $vars['last10'] );

		// Equation Operating System
		if ( ! class_exists( 'phpStack' ) ) {
			include_once SP()->plugin_path() . '/includes/libraries/class-phpstack.php';
		}
		if ( ! class_exists( 'eqEOS' ) ) {
			include_once SP()->plugin_path() . '/includes/libraries/class-eqeos.php';
		}
		$eos = new eqEOS();

		// Remove spaces from equation
		$equation = str_replace( ' ', '', $equation );

		// Create temporary equation replacing operators with spaces
		$temp = str_replace( array( '+', '-', '*', '/', '(', ')' ), ' ', $equation );

		// Check if each variable part is in vars
		$parts = explode( ' ', $temp );
		foreach ( $parts as $key => $value ) :
			if ( substr( $value, 0, 1 ) == '$' ) :
				if ( ! array_key_exists( preg_replace( '/[^a-z0-9_]/', '', $value ), $vars ) ) {
					return 0;
				}
			endif;
		endforeach;

		// Remove space between equation parts
		$equation = str_replace( ' ', '', $equation );

		// Initialize Subequations
		$subequations = array( $equation );

		// Find all equation parts contained in parentheses
		if ( preg_match_all( '~\((.*?)\)~', $equation, $results ) ) {
			foreach ( sp_array_value( $results, 1, array() ) as $result ) {
				if ( ! empty( $result ) ) {
					$subequations[] = $result;
				}
			}
		}

		// Initialize subequation
		$subequation = $equation;

		// Check each subequation separated by division
		foreach ( $subequations as $subequation ) {
			while ( $pos = strpos( $subequation, '/' ) ) {
				$subequation = substr( $subequation, $pos + 1 );

				// Make sure paretheses match
				if ( substr_count( $subequation, '(' ) === substr_count( $subequation, ')' ) ) {

					// Return zero if denominator is zero
					if ( $eos->solveIF( $subequation, $vars ) == 0 ) {
						return 0;
					}
				}
			}
		}

		// Return solution
		return number_format( $eos->solveIF( str_replace( ' ', '', $equation ), $vars ), $precision, '.', '' );

	}
}

if ( ! function_exists( 'sp_sort_table_teams' ) ) {
	function sp_sort_table_teams( $a, $b ) {

		global $sportspress_column_priorities;

		// Loop through priorities
		foreach ( $sportspress_column_priorities as $priority ) :

			// Proceed if columns are not equal
			if ( sp_array_value( $a, $priority['column'], 0 ) != sp_array_value( $b, $priority['column'], 0 ) ) :

				// Compare column values
				$output = sp_array_value( $a, $priority['column'], 0 ) - sp_array_value( $b, $priority['column'], 0 );

				// Flip value if descending order
				if ( $priority['order'] == 'DESC' ) {
					$output = 0 - $output;
				}

				return ( $output > 0 );

			endif;

		endforeach;

		// Default sort by alphabetical
		return strcmp( sp_array_value( $a, 'name', '' ), sp_array_value( $b, 'name', '' ) );
	}
}

if ( ! function_exists( 'sp_sort_terms' ) ) {

	/**
	 * Sorts terms by `sp_order`.
	 *
	 * @param  int|object $a Term ID or term.
	 * @param  int|object $b Term ID or term.
	 * @return int    Sorting order.
	 */
	function sp_sort_terms( $a, $b ) {
		if ( is_numeric( $a ) ) {
			$a = intval( $a );
			$a = get_term( $a );
		}
		if ( is_numeric( $b ) ) {
			$b = intval( $b );
			$b = get_term( $b );
		}
		$term_meta_a = get_term_meta( $a->term_id, 'sp_order', true );
		$term_meta_b = get_term_meta( $b->term_id, 'sp_order', true );
		return $term_meta_a == $term_meta_b ? 0 : ( $term_meta_a > $term_meta_b ? 1 : -1 );
	}
}

if ( ! function_exists( 'sp_get_next_event' ) ) {
	function sp_get_next_event( $args = array() ) {
		$options = array(
			'post_type'      => 'sp_event',
			'posts_per_page' => 1,
			'order'          => 'ASC',
			'post_status'    => 'future',
		);
		$options = array_merge( $options, $args );
		$posts   = get_posts( $options );
		if ( $posts && is_array( $posts ) ) {
			return array_pop( $posts );
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'sp_taxonomy_field' ) ) {
	function sp_taxonomy_field( $taxonomy = 'category', $post = null, $multiple = false, $trigger = false, $placeholder = null ) {
		$obj = get_taxonomy( $taxonomy );
		if ( $obj && $obj->public ) {
			$post_type = get_post_type( $post );
			?>
			<div class="<?php echo esc_attr( $post_type ); ?>-<?php echo esc_attr( $taxonomy ); ?>-field">
				<p><strong><?php echo esc_attr( $obj->labels->singular_name ); ?></strong></p>
				<p>
					<?php
					$terms    = get_the_terms( $post->ID, $taxonomy );
					$term_ids = array();
					if ( $terms ) :
						foreach ( $terms as $term ) :
							$term_ids[] = $term->term_id;
						endforeach;
					endif;

					// Set auto option
					$auto = false;
					if ( in_array( $post_type, sp_secondary_post_types() ) ) {
						switch ( $taxonomy ) {
							case 'sp_league':
								$auto = esc_attr__( 'Main League', 'sportspress' );
								if ( get_post_meta( $post->ID, 'sp_main_league', true ) ) {
									$term_ids[] = 'auto';
								}
								break;
							case 'sp_season':
								$auto = esc_attr__( 'Current Season', 'sportspress' );
								if ( get_post_meta( $post->ID, 'sp_current_season', true ) ) {
									$term_ids[] = 'auto';
								}
								break;
						}
					}

					$args = array(
						'show_option_auto' => $auto,
						'taxonomy'         => $taxonomy,
						'name'             => 'tax_input[' . $taxonomy . '][]',
						'selected'         => $term_ids,
						'values'           => 'term_id',
						'class'            => 'sp-has-dummy widefat' . ( $trigger ? ' sp-ajax-trigger' : '' ),
						'chosen'           => true,
						'placeholder'      => $placeholder ? $placeholder : esc_attr__( 'All', 'sportspress' ),
					);
					if ( $multiple ) {
						$args['property'] = 'multiple';
					}
					if ( ! sp_dropdown_taxonomies( $args ) ) :
						sp_taxonomy_adder( $taxonomy, $post_type, $obj->labels->add_new_item );
					endif;
					?>
				</p>
			</div>
			<?php
		}
	}
}

/**
 * Get an array of text options per context.
 *
 * @return array
 */
function sp_get_text_options() {
	$strings = apply_filters(
		'sportspress_text',
		array(
			__( 'Article', 'sportspress' ),
			__( 'Away', 'sportspress' ),
			__( 'Box Score', 'sportspress' ),
			__( 'Canceled', 'sportspress' ),
			__( 'Career Total', 'sportspress' ),
			__( 'Current Team', 'sportspress' ),
			__( 'Date', 'sportspress' ),
			__( 'Defense', 'sportspress' ),
			__( 'Details', 'sportspress' ),
			__( 'Event', 'sportspress' ),
			__( 'Events', 'sportspress' ),
			__( 'Excerpt', 'sportspress' ),
			__( 'Fixtures', 'sportspress' ),
			__( 'Full Time', 'sportspress' ),
			__( 'Home', 'sportspress' ),
			__( 'League', 'sportspress' ),
			__( 'Leagues', 'sportspress' ),
			__( 'League Table', 'sportspress' ),
			__( 'Match Day', 'sportspress' ),
			__( 'Nationality', 'sportspress' ),
			__( 'Offense', 'sportspress' ),
			__( 'Outcome', 'sportspress' ),
			__( 'Past Teams', 'sportspress' ),
			__( 'Photo', 'sportspress' ),
			__( 'Player', 'sportspress' ),
			__( 'Player of the Match', 'sportspress' ),
			__( 'Players', 'sportspress' ),
			__( 'Pos', 'sportspress' ),
			__( 'Position', 'sportspress' ),
			__( 'Postponed', 'sportspress' ),
			__( 'Preview', 'sportspress' ),
			__( 'Profile', 'sportspress' ),
			__( 'Rank', 'sportspress' ),
			__( 'Recap', 'sportspress' ),
			__( 'Results', 'sportspress' ),
			__( 'Season', 'sportspress' ),
			__( 'Seasons', 'sportspress' ),
			__( 'Staff', 'sportspress' ),
			__( 'Statistics', 'sportspress' ),
			__( 'TBD', 'sportspress' ),
			__( 'Team', 'sportspress' ),
			__( 'Teams', 'sportspress' ),
			__( 'Time', 'sportspress' ),
			__( 'Time/Results', 'sportspress' ),
			__( 'Total', 'sportspress' ),
			__( 'Venue', 'sportspress' ),
			__( 'Video', 'sportspress' ),
			__( 'View all events', 'sportspress' ),
			__( 'View all players', 'sportspress' ),
			__( 'View full table', 'sportspress' ),
			__( 'Visit Site', 'sportspress' ),
		)
	);

	asort( $strings );
	return array_unique( $strings );
}

/**
 * Display a link to review SportsPress
 *
 * @return null
 */
function sp_review_link() {
	?>
	<p>
		<a href="https://wordpress.org/support/plugin/sportspress/reviews/?rate=5#new-post">
			<?php esc_attr_e( 'Love SportsPress? Help spread the word by rating us 5★ on WordPress.org', 'sportspress' ); ?>
		</a>
	</p>
	<?php
}

/**
 * Return shortcode template for meta boxes
 *
 * @return null
 */
function sp_get_shortcode_template( $shortcode, $id = null, $args = array() ) {
	$args   = apply_filters( 'sportspress_shortcode_template_args', $args );
	$output = '[' . $shortcode;
	if ( $id ) {
		$output .= ' ' . $id;
	}
	if ( sizeof( $args ) ) {
		foreach ( $args as $key => $value ) {
			$output .= ' ' . $key . '="' . $value . '"';
		}
	}
	$output .= ']';
	return esc_attr( $output );
}

/**
 * Display shortcode template for meta boxes
 *
 * @return null
 */
function sp_shortcode_template( $shortcode, $id = null, $args = array() ) {
	echo esc_attr( sp_get_shortcode_template( $shortcode, $id, $args ) );
}

if ( ! function_exists( 'array_replace' ) ) {
	/**
	 * array_replace for PHP version earlier than 5.3
	 *
	 * @link   http://be2.php.net/manual/fr/function.array-replace.php#115215
	 */
	function array_replace() {
		$args     = func_get_args();
		$num_args = func_num_args();
		$res      = array();
		for ( $i = 0; $i < $num_args; $i++ ) {
			if ( is_array( $args[ $i ] ) ) {
				foreach ( $args[ $i ] as $key => $val ) {
					$res[ $key ] = $val;
				}
			} else {
				trigger_error( __FUNCTION__ . '(): Argument #' . esc_html( $i + 1 ) . ' is not an array', E_USER_WARNING );
				return null;
			}
		}
		return $res;
	}
}

/**
 * Check if a shortcode is shown on content
 *
 * @return bool
 */
function sp_has_shortcodes( $content, $tags ) {
	if ( is_array( $tags ) ) {
		foreach ( $tags as $tag ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
			if ( empty( $matches ) ) {
				return false;
			}
			foreach ( $matches as $shortcode ) {
				if ( $tag === $shortcode[2] ) {
					return true;
				}
			}
		}
	} else {
		if ( shortcode_exists( $tags ) ) {
			preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
			if ( empty( $matches ) ) {
				return false;
			}
			foreach ( $matches as $shortcode ) {
				if ( $tags === $shortcode[2] ) {
					return true;
				}
			}
		}
	}
	return false;
}

/**
 * Check if a custom flag was uploaded from the user
 *
 * @return string
 */
function sp_flags( $nationality ) {
	$nationality = strtolower( $nationality );
	$flag        = '';
	$custom_flag_post_id = false;
	$args = array(
		'post_type' => 'attachment',
		'title' => $nationality,
		'posts_per_page' => 1,
		'fields' => 'ids',
	);
	$custom_flag = get_posts( $args );
	if( $custom_flag ){
		$custom_flag_post_id = $custom_flag[0];
	}
	if ( $custom_flag_post_id ) {
		$flag_src = wp_get_attachment_image_url( $custom_flag_post_id, array( 23, 15 ), false );
		$flag     = '<img src="' . $flag_src . '" alt="' . $nationality . '">';
	} else {
		$flag = '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/images/flags/' . $nationality . '.png" alt="' . $nationality . '">';
	}

	return $flag;
}
