<?php
/**
 * SportsPress Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     1.9.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include core functions
include( 'sp-option-filters.php' );
include( 'sp-conditional-functions.php' );
include( 'sp-formatting-functions.php' );
include( 'sp-deprecated-functions.php' );
include( 'sp-api-functions.php' );

/**
 * Get template part.
 *
 * @access public
 * @param mixed $slug
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
 * @param mixed $template_name
 * @param array $args (default: array())
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
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '0.7' );
		return;
	}

	do_action( 'sportspress_before_template', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'sportspress_after_template', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @access public
 * @param mixed $template_name
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
			$template_name
		)
	);

	// Get default template
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found
	return apply_filters('sportspress_locate_template', $template, $template_name, $template_path);
}

/* deprecated functions below */

if( !function_exists( 'date_diff' ) ) {
	class DateInterval {
		public $y;
		public $m;
		public $d;
		public $h;
		public $i;
		public $s;
		public $invert;
		public $days;

		public function format($format) {
			$format = str_replace('%R%y', 
				($this->invert ? '-' : '+') . $this->y, $format);
			$format = str_replace('%R%m', 
				($this->invert ? '-' : '+') . $this->m, $format);
			$format = str_replace('%R%d', 
				($this->invert ? '-' : '+') . $this->d, $format);
			$format = str_replace('%R%h', 
				($this->invert ? '-' : '+') . $this->h, $format);
			$format = str_replace('%R%i', 
				($this->invert ? '-' : '+') . $this->i, $format);
			$format = str_replace('%R%s', 
				($this->invert ? '-' : '+') . $this->s, $format);

		$format = str_replace('%y', $this->y, $format);
		$format = str_replace('%m', $this->m, $format);
		$format = str_replace('%d', $this->d, $format);
		$format = str_replace('%h', $this->h, $format);
		$format = str_replace('%i', $this->i, $format);
		$format = str_replace('%s', $this->s, $format);

		return $format;
		}
	}

	function date_diff(DateTime $date1, DateTime $date2) {

		$diff = new DateInterval();

		if($date1 > $date2) {
			$tmp = $date1;
			$date1 = $date2;
			$date2 = $tmp;
			$diff->invert = 1;
		} else {
			$diff->invert = 0;
		}

		$diff->y = ((int) $date2->format('Y')) - ((int) $date1->format('Y'));
		$diff->m = ((int) $date2->format('n')) - ((int) $date1->format('n'));
		if($diff->m < 0) {
			$diff->y -= 1;
			$diff->m = $diff->m + 12;
		}
		$diff->d = ((int) $date2->format('j')) - ((int) $date1->format('j'));
		if($diff->d < 0) {
			$diff->m -= 1;
			$diff->d = $diff->d + ((int) $date1->format('t'));
		}
		$diff->h = ((int) $date2->format('G')) - ((int) $date1->format('G'));
		if($diff->h < 0) {
			$diff->d -= 1;
			$diff->h = $diff->h + 24;
		}
		$diff->i = ((int) $date2->format('i')) - ((int) $date1->format('i'));
		if($diff->i < 0) {
			$diff->h -= 1;
			$diff->i = $diff->i + 60;
		}
		$diff->s = ((int) $date2->format('s')) - ((int) $date1->format('s'));
		if($diff->s < 0) {
			$diff->i -= 1;
			$diff->s = $diff->s + 60;
		}

		$start_ts   = $date1->format('U');
		$end_ts   = $date2->format('U');
		$days     = $end_ts - $start_ts;
		$diff->days  = round($days / 86400);

		if (($diff->h > 0 || $diff->i > 0 || $diff->s > 0))
			$diff->days += ((bool) $diff->invert)
				? 1
				: -1;

		return $diff;
	}
}

if ( !function_exists( 'sp_flush_rewrite_rules' ) ) {
	function sp_flush_rewrite_rules() {
	    // Flush rewrite rules
	    $post_types = new SP_Post_types();
	    $post_types->register_taxonomies();
	    $post_types->register_post_types();
	    flush_rewrite_rules();
	}
}

if ( !function_exists( 'sp_add_link' ) ) {
	function sp_add_link( $string, $link = false, $active = true ) {
		if ( empty( $link ) || ! $active ) return $string;
		return '<a href="' . $link . '">' . $string . '</a>';
	}
}

if ( !function_exists( 'sp_nonce' ) ) {
	function sp_nonce() {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
	}
}

if ( !function_exists( 'sp_get_option' ) ) {
	function sp_get_option( $option, $default = null ) {
		if ( isset( $_POST[ $option ] ) )
			return $_POST[ $option ];
		else
			return get_option( $option, $default );
	}
}

if ( !function_exists( 'sp_array_between' ) ) {
	function sp_array_between ( $array = array(), $delimiter = 0, $index = 0 ) {
		$keys = array_keys( $array, $delimiter );
		if ( array_key_exists( $index, $keys ) ):
			$offset = $keys[ $index ];
			$end = sizeof( $array );
			if ( array_key_exists( $index + 1, $keys ) )
				$end = $keys[ $index + 1 ];
			$length = $end - $offset;
			$array = array_slice( $array, $offset, $length );
		endif;
		return $array;
	}
}

if ( !function_exists( 'sp_array_value' ) ) {
	function sp_array_value( $arr = array(), $key = 0, $default = null ) {
		return ( isset( $arr[ $key ] ) ? $arr[ $key ] : $default );
	}
}

if ( !function_exists( 'sp_array_combine' ) ) {
	function sp_array_combine( $keys = array(), $values = array(), $key_order = false ) {
		if ( ! is_array( $keys ) ) return array();
		if ( ! is_array( $values ) ) $values = array();

		$output = array();

		if ( $key_order ):
			foreach( $keys as $key ):
				if ( array_key_exists( $key, $values ) )
					$output[ $key ] = $values[ $key ];
				else
					$output[ $key ] = array();
			endforeach;
		else:
			foreach ( $values as $key => $value ):
				if ( in_array( $key, $keys ) ):
					$output[ $key ] = $value;
				endif;
			endforeach;

			foreach ( $keys as $key ):
				if ( $key !== false && ! array_key_exists( $key, $output ) )
					$output[ $key ] = array();
			endforeach;
		endif;
		return $output;
	}
}

if ( !function_exists( 'sp_numbers_to_words' ) ) {
	function sp_numbers_to_words( $str ) {
	    $output = str_replace( array( '%', '1st', '2nd', '3rd', '5th', '8th', '9th', '10', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ), array( 'percent', 'first', 'second', 'third', 'fifth', 'eight', 'ninth', 'ten', 'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine' ), $str );
	    return $output;
    }
}

if ( !function_exists( 'sp_column_active' ) ) {
	function sp_column_active( $array = null, $value = null ) {
		return $array == null || in_array( $value, $array );
	}
}

if ( !function_exists( 'sp_get_the_term_id' ) ) {
	function sp_get_the_term_id( $post_id, $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_array( $terms ) && sizeof( $terms ) > 0 ):
			$term = reset( $terms );
			if ( is_object( $term ) && property_exists( $term, 'term_id' ) )
				return $term->term_id;
			else
				return 0;
		else:
			return 0;
		endif;
	}
}

if ( !function_exists( 'sp_get_the_term_ids' ) ) {
	function sp_get_the_term_ids( $post_id, $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_array( $terms ) && sizeof( $terms ) > 0 ):
			return wp_list_pluck( $terms, 'term_id' );
		else:
			return array();
		endif;
	}
}

if ( !function_exists( 'sp_get_the_term_id_or_meta' ) ) {
	function sp_get_the_term_id_or_meta( $post_id, $taxonomy ) {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_array( $terms ) && sizeof( $terms ) > 0 ):
			$term = reset( $terms );
			if ( is_object( $term ) && property_exists( $term, 'term_id' ) )
				return $term->term_id;
			else
				return 0;
		else:
			return get_post_meta( $post_id, $taxonomy, true );
		endif;
	}
}

if ( !function_exists( 'sp_get_url' ) ) {
	function sp_get_url( $post_id ) {
		$url = get_post_meta( $post_id, 'sp_url', true );
		if ( ! $url ) return;
		return ' <a class="sp-link" href="' . $url . '" target="_blank" title="' . __( 'Visit Site', 'sportspress' ) . '">' . $url . '</a>';
	}
}

if ( !function_exists( 'sp_get_post_abbreviation' ) ) {
	function sp_get_post_abbreviation( $post_id ) {
		$abbreviation = get_post_meta ( $post_id, 'sp_abbreviation', true );
		if ( $abbreviation ):
			return $abbreviation;
		else:
			return substr( get_the_title( $post_id ), 0, 1 );
		endif;
	}
}

if ( !function_exists( 'sp_get_post_condition' ) ) {
	function sp_get_post_condition( $post_id ) {
		$condition = get_post_meta ( $post_id, 'sp_condition', true );
		$main_result = get_option( 'sportspress_primary_result', null );
		$result = get_page_by_path( $main_result, ARRAY_A, 'sp_result' );
		$label = sp_array_value( $result, 'post_title', __( 'Primary', 'sportspress' ) );
		if ( $condition ):
			$conditions = array(
				'0' => '&mdash;',
				'>' => sprintf( __( 'Most %s', 'sportspress' ), $label ),
				'<' => sprintf( __( 'Least %s', 'sportspress' ), $label ),
				'=' => sprintf( __( 'Equal %s', 'sportspress' ), $label ),
				'else' => sprintf( __( 'Default', 'sportspress' ), $label ),
			);
			return sp_array_value( $conditions, $condition, '&mdash;' );
		else:
			return '&mdash;';
		endif;
	}
}

if ( !function_exists( 'sp_get_post_precision' ) ) {
	function sp_get_post_precision( $post_id ) {
		$precision = get_post_meta ( $post_id, 'sp_precision', true );
		if ( $precision ):
			return $precision;
		else:
			return 0;
		endif;
	}
}

if ( !function_exists( 'sp_get_post_calculate' ) ) {
	function sp_get_post_calculate( $post_id ) {
		$calculate = get_post_meta ( $post_id, 'sp_calculate', true );
		if ( $calculate ):
			return str_replace(
				array( 'total', 'average' ),
				array( __( 'Total', 'sportspress' ), __( 'Average', 'sportspress' ) ),
				$calculate
			);
		else:
			return __( 'Total', 'sportspress' );
		endif;
	}
}

if ( !function_exists( 'sp_get_post_equation' ) ) {
	function sp_get_post_equation( $post_id ) {
		$equation = get_post_meta ( $post_id, 'sp_equation', true );
		if ( $equation ):
			$equation = str_replace(
				array( '/', '(', ')', '+', '-', '*', '_', '$' ),
				array( '&divide;', '(', ')', '&plus;', '&minus;', '&times;', '@', '' ),
				trim( $equation )
			);
			return '<code>' . implode( '</code> <code>', explode( ' ', $equation ) ) . '</code>';
		else:
			return '&mdash;';
		endif;
	}
}

if ( !function_exists( 'sp_get_post_order' ) ) {
	function sp_get_post_order( $post_id ) {
		$priority = get_post_meta ( $post_id, 'sp_priority', true );
		if ( $priority ):
			return $priority . ' ' . str_replace(
				array( 'DESC', 'ASC' ),
				array( '&darr;', '&uarr;' ),
				get_post_meta ( $post_id, 'sp_order', true )
			);
		else:
			return '&mdash;';
		endif;
	}
}

if ( !function_exists( 'sp_dropdown_statuses' ) ) {
	function sp_dropdown_statuses( $args = array() ) {
		$defaults = array(
			'show_option_default' => false,
			'name' => 'sp_status',
			'id' => null,
			'selected' => null,
		    'class' => null,
		);
		$args = array_merge( $defaults, $args ); 

		printf( '<select name="%s" class="postform %s">', $args['name'], $args['class'] );

		if ( $args['show_option_default'] ):
			printf( '<option value="default">%s</option>', $args['show_option_default'] );
		endif;

		$statuses = apply_filters( 'sportspress_statuses', array(
			'any' => __( 'All', 'sportspress' ),
			'publish' => __( 'Published', 'sportspress' ),
			'future' => __( 'Scheduled', 'sportspress' )
		));

		foreach ( $statuses as $value => $label ):
			printf( '<option value="%s" %s>%s</option>', $value, selected( $value, $args['selected'], false ), $label );
		endforeach;
		print( '</select>' );
		return true;
	}
}

if ( !function_exists( 'sp_dropdown_dates' ) ) {
	function sp_dropdown_dates( $args = array() ) {
		$defaults = array(
			'show_option_default' => false,
			'name' => 'sp_date',
			'id' => null,
			'selected' => null,
		    'class' => null,
		);
		$args = array_merge( $defaults, $args ); 

		printf( '<select name="%s" class="postform %s">', $args['name'], $args['class'] );

		if ( $args['show_option_default'] ):
			printf( '<option value="default">%s</option>', $args['show_option_default'] );
		endif;

		$dates = apply_filters( 'sportspress_dates', array(
			0 => __( 'All', 'sportspress' ),
			'w' => __( 'This week', 'sportspress' ),
			'day' => __( 'Today', 'sportspress' ),
			'range' => __( 'Date range:', 'sportspress' ),
		));

		foreach ( $dates as $value => $label ):
			printf( '<option value="%s" %s>%s</option>', $value, selected( $value, $args['selected'], false ), $label );
		endforeach;
		print( '</select>' );
		return true;
	}
}

if ( !function_exists( 'sp_dropdown_taxonomies' ) ) {
	function sp_dropdown_taxonomies( $args = array() ) {
		$defaults = array(
			'show_option_blank' => false,
			'show_option_all' => false,
			'show_option_none' => false,
			'taxonomy' => null,
			'name' => null,
			'id' => null,
			'selected' => null,
			'hide_empty' => false,
			'values' => 'slug',
			'class' => null,
			'property' => null,
			'placeholder' => null,
			'chosen' => false,
			'parent' => 0,
			'include_children' => true,
		);
		$args = array_merge( $defaults, $args ); 
		if ( ! $args['taxonomy'] ) return false;

		$name = ( $args['name'] ) ? $args['name'] : $args['taxonomy'];
		$id = ( $args['id'] ) ? $args['id'] : $name;

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

		printf( '<input type="hidden" name="tax_input[%s][]" value="0">', $args['taxonomy'] );

		if ( $terms ):
			printf( '<select name="%s" class="postform %s" %s>', $name, $class . ( $chosen ? ' chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' ) : '' ), ( $placeholder != null ? 'data-placeholder="' . $placeholder . '" ' : '' ) . $property );

			if ( strpos( $property, 'multiple' ) === false ):
				if ( $args['show_option_blank'] ):
					echo '<option></option>';
				endif;
				if ( $args['show_option_all'] ):
					printf( '<option value="0" ' . selected( '0', $selected, false ) . '>%s</option>', $args['show_option_all'] );
				endif;
				if ( $args['show_option_none'] ):
					printf( '<option value="-1" ' . selected( '-1', $selected, false ) . '>%s</option>', $args['show_option_none'] );
				endif;
			endif;

			foreach ( $terms as $term ):

				if ( $args['values'] == 'term_id' ):
					$this_value = $term->term_id;
				else:
					$this_value = $term->slug;
				endif;

				if ( strpos( $property, 'multiple' ) !== false ):
					$selected_prop = in_array( $this_value, $selected ) ? 'selected' : '';
				else:
					$selected_prop = selected( $this_value, $selected, false );
				endif;

				printf( '<option value="%s" %s>%s</option>', $this_value, $selected_prop, $term->name );

				if ( $args['include_children'] ):
					$term_children = get_term_children( $term->term_id, $args['taxonomy'] );

					foreach ( $term_children as $term_child_id ):
						$term_child = get_term_by( 'id', $term_child_id, $args['taxonomy'] );

						if ( $args['values'] == 'term_id' ):
							$this_value = $term_child->term_id;
						else:
							$this_value = $term_child->slug;
						endif;

						if ( strpos( $property, 'multiple' ) !== false ):
							$selected_prop = in_array( $this_value, $selected ) ? 'selected' : '';
						else:
							$selected_prop = selected( $this_value, $selected, false );
						endif;

						printf( '<option value="%s" %s>%s</option>', $this_value, $selected_prop, '— ' . $term_child->name );
					endforeach;
				endif;
			endforeach;
			print( '</select>' );
			return true;
		else:
			return false;
		endif;
	}
}

if ( !function_exists( 'sp_dropdown_pages' ) ) {
	function sp_dropdown_pages( $args = array() ) {
		$defaults = array(
			'prepend_options' => null,
			'append_options' => null,
			'show_option_blank' => false,
			'show_option_all' => false,
			'show_option_none' => false,
			'show_dates' => false,
			'option_all_value' => 0,
			'option_none_value' => -1,
			'name' => 'page_id',
			'id' => null,
			'selected' => null,
			'numberposts' => -1,
			'posts_per_page' => -1,
			'child_of' => 0,
			'order' => 'ASC',
		    'orderby' => 'title',
		    'hierarchical' => 1,
		    'exclude' => null,
		    'include' => null,
		    'meta_key' => null,
		    'meta_value' => null,
		    'authors' => null,
		    'exclude_tree' => null,
		    'post_type' => 'page',
			'post_status' => 'publish',
		    'values' => 'post_name',
		    'class' => null,
		    'property' => null,
		    'placeholder' => null,
		    'chosen' => false,
		    'filter' => false,
		);
		$args = array_merge( $defaults, $args );

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
		if ( $posts || $args['prepend_options'] || $args['append_options'] ):
			printf( '<select name="%s" id="%s" class="postform %s" %s>', $name, $id, $class . ( $chosen ? ' chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' ) : '' ), ( $placeholder != null ? 'data-placeholder="' . $placeholder . '" ' : '' ) . $property );

			if ( strpos( $property, 'multiple' ) === false ):
				if ( $args['show_option_blank'] ):
					printf( '<option value=""></option>' );
				endif;
				if ( $args['show_option_none'] ):
					printf( '<option value="%s" %s>%s</option>', $args['option_none_value'], selected( $selected, $args['option_none_value'], false ), ( $args['show_option_none'] === true ? '' : $args['show_option_none'] ) );
				endif;
				if ( $args['show_option_all'] ):
					printf( '<option value="%s" %s>%s</option>', $args['option_all_value'], selected( $selected, $args['option_all_value'], false ), $args['show_option_all'] );
				endif;
				if ( $args['prepend_options'] && is_array( $args['prepend_options'] ) ):
					foreach( $args['prepend_options'] as $slug => $label ):
						printf( '<option value="%s" %s>%s</option>', $slug, selected( $selected, $slug, false ), $label );
					endforeach;
				endif;
			endif;

			foreach ( $posts as $post ):
				setup_postdata( $post );

				if ( $values == 'ID' ):
					$this_value = $post->ID;
				else:
					$this_value = $post->post_name;
				endif;

				if ( strpos( $property, 'multiple' ) !== false ):
					$selected_prop = in_array( $this_value, $selected ) ? 'selected' : '';
				else:
					$selected_prop = selected( $this_value, $selected, false );
				endif;

				if ( $filter !== false ):
					$class = 'sp-post sp-filter-0';
					$filter_values = get_post_meta( $post->ID, $filter, false );
					foreach ( $filter_values as $filter_value ):
						$class .= ' sp-filter-' . $filter_value;
					endforeach;
				else:
					$class = '';
				endif;

				printf( '<option value="%s" class="%s" %s>%s</option>', $this_value, $class, $selected_prop, $post->post_title . ( $args['show_dates'] ? ' (' . $post->post_date . ')' : '' ) );
			endforeach;
			wp_reset_postdata();

			if ( strpos( $property, 'multiple' ) === false ):
				if ( $args['append_options'] && is_array( $args['append_options'] ) ):
					foreach( $args['append_options'] as $slug => $label ):
						printf( '<option value="%s" %s>%s</option>', $slug, selected( $selected, $slug, false ), $label );
					endforeach;
				endif;
			endif;
			print( '</select>' );
			return true;
		else:
			return false;
		endif;
	}
}

if ( !function_exists( 'sp_posts' ) ) {
	function sp_posts( $post_id = null, $meta = 'post' ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		$ids = get_post_meta( $post_id, $meta, false );
		if ( ( $key = array_search( 0, $ids ) ) !== false )
		    unset( $ids[ $key ] );
		$i = 0;
		$count = count( $ids );
		if ( isset( $ids ) && $ids && is_array( $ids ) && !empty( $ids ) ):
			foreach ( $ids as $id ):
				if ( !$id ) continue;
				$parents = get_post_ancestors( $id );
				$keys = array_keys( $parents );
				$values = array_reverse( array_values( $parents ) );
				if ( ! empty( $keys ) && ! empty( $values ) ):
					$parents = array_combine( $keys, $values );
					foreach ( $parents as $parent ):
						if ( !in_array( $parent, $ids ) )
							edit_post_link( get_the_title( $parent ), '', '', $parent );
						echo ' - ';
					endforeach;
				endif;
				$title = get_the_title( $id );
				if ( ! $title )
					continue;
				if ( empty( $title ) )
					$title = __( '(no title)', 'sportspress' );
				edit_post_link( $title, '', '', $id );
				if ( ++$i !== $count )
					echo ', ';
			endforeach;
		endif;
	}
}

if ( !function_exists( 'sp_post_checklist' ) ) {
	function sp_post_checklist( $post_id = null, $meta = 'post', $display = 'block', $filters = null, $index = null ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		?>
		<div id="<?php echo $meta; ?>-all" class="posttypediv wp-tab-panel sp-tab-panel sp-tab-filter-panel sp-select-all-range" style="display: <?php echo $display; ?>;">
			<input type="hidden" value="0" name="<?php echo $meta; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" />
			<ul class="categorychecklist form-no-clear">
				<li class="sp-select-all-container"><label class="selectit"><input type="checkbox" class="sp-select-all"> <strong><?php _e( 'Select All', 'sportspress' ); ?></strong></label></li>
				<?php
				$selected = sp_array_between( (array)get_post_meta( $post_id, $meta, false ), 0, $index );
				if ( empty( $posts ) ):
					$query = array( 'post_type' => $meta, 'numberposts' => -1, 'post_per_page' => -1, 'orderby' => 'menu_order' );
					if ( $meta == 'sp_player' ):
						$query['meta_key'] = 'sp_number';
						$query['orderby'] = 'meta_value_num';
						$query['order'] = 'ASC';
					endif;
					$posts = get_posts( $query );
				endif;
				foreach ( $posts as $post ):
					$parents = get_post_ancestors( $post );
					if ( $filters ):
						if ( is_array( $filters ) ):
							$filter_values = array();
							foreach ( $filters as $filter ):
								if ( get_taxonomy( $filter ) ):
									$terms = (array)get_the_terms( $post->ID, $filter );
									foreach ( $terms as $term ):
										if ( is_object( $term ) && property_exists( $term, 'term_id' ) )
											$filter_values[] = $term->term_id;
									endforeach;
								else:
									$filter_values = array_merge( $filter_values, (array)get_post_meta( $post->ID, $filter, false ) );
								endif;
							endforeach;
						else:
							$filter = $filters;
							if ( get_taxonomy( $filter ) ):
								$terms = (array)get_the_terms( $post->ID, $filter );
								foreach ( $terms as $term ):
									if ( is_object( $term ) && property_exists( $term, 'term_id' ) )
										$filter_values[] = $term->term_id;
								endforeach;
							else:
								$filter_values = (array)get_post_meta( $post->ID, $filter, false );
							endif;
						endif;
					endif;
					?>
					<li class="sp-post sp-filter-0<?php
						if ( $filters ):
							foreach ( $filter_values as $filter_value ):
								echo ' sp-filter-' . $filter_value;
							endforeach;
						endif;
					?>">
						<?php echo str_repeat( '<ul><li>', sizeof( $parents ) ); ?>
						<label class="selectit">
							<input type="checkbox" value="<?php echo $post->ID; ?>" name="<?php echo $meta; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]"<?php if ( in_array( $post->ID, $selected ) ) echo ' checked="checked"'; ?>>
							<?php echo sp_get_player_name_with_number( $post->ID ); ?>
						</label>
						<?php echo str_repeat( '</li></ul>', sizeof( $parents ) ); ?>
					</li>
					<?php
				endforeach;
				?>
				<li class="sp-not-found-container">
					<?php _e( 'No results found.', 'sportspress' ); ?>
					<?php if ( sizeof( $posts ) ): ?><a class="sp-show-all" href="#show-all-<?php echo $meta; ?>s"><?php _e( 'Show all', 'sportspress' ); ?></a><?php endif; ?>
				</li>
				<?php if ( sizeof( $posts ) ): ?>
					<li class="sp-show-all-container"><a class="sp-show-all" href="#show-all-<?php echo $meta; ?>s"><?php _e( 'Show all', 'sportspress' ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_column_checklist' ) ) {
	function sp_column_checklist( $post_id = null, $meta = 'post', $display = 'block', $selected = array(), $default_checked = false ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		?>
		<div id="<?php echo $meta; ?>-all" class="posttypediv wp-tab-panel sp-tab-panel sp-select-all-range" style="display: <?php echo $display; ?>;">
			<input type="hidden" value="0" name="sp_columns[]" />
			<ul class="categorychecklist form-no-clear">
				<li class="sp-select-all-container"><label class="selectit"><input type="checkbox" class="sp-select-all"> <strong><?php _e( 'Select All', 'sportspress' ); ?></strong></label></li>
				<?php
				$posts = get_pages( array( 'post_type' => $meta, 'number' => 0 ) );
				if ( empty( $posts ) ):
					$query = array( 'post_type' => $meta, 'numberposts' => -1, 'post_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order' );
					$posts = get_posts( $query );
				endif;
				if ( sizeof( $posts ) ):
					foreach ( $posts as $post ):
						?>
						<li class="sp-post">
							<label class="selectit">
								<input type="checkbox" value="<?php echo $post->post_name; ?>" name="sp_columns[]"<?php if ( ( ! is_array( $selected ) && $default_checked ) || in_array( $post->post_name, $selected ) ) echo ' checked="checked"'; ?>>
								<?php echo sp_draft_or_post_title( $post ); ?>
							</label>
						</li>
						<?php
					endforeach;
				else:
				?>
				<li class="sp-not-found-container"><?php _e( 'No results found.', 'sportspress' ); ?></li>
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
if ( !function_exists( 'sp_draft_or_post_title' ) ) {
	function sp_draft_or_post_title( $post = 0 ) {
		$title = get_the_title( $post );
		if ( empty( $title ) )
			$title = __( '(no title)', 'sportspress' );
		return $title;
	}
}

if ( !function_exists( 'sp_get_var_labels' ) ) {
	function sp_get_var_labels( $post_type, $neg = null ) {
		$args = array(
			'post_type' => $post_type,
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);

		$vars = get_posts( $args );

		$output = array();
		foreach ( $vars as $var ):
			if ( $neg === null || ( $neg && $var->menu_order < 0 ) || ( ! $neg && $var->menu_order >= 0 ) )
				$output[ $var->post_name ] = $var->post_title;
		endforeach;

		return $output;
	}
}

if ( !function_exists( 'sp_get_var_equations' ) ) {
	function sp_get_var_equations( $post_type ) {
		$args = array(
			'post_type' => $post_type,
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);

		$vars = get_posts( $args );

		$output = array();
		foreach ( $vars as $var ):
			$equation = get_post_meta( $var->ID, 'sp_equation', true );
			if ( ! $equation ) $equation = 0;
			$precision = get_post_meta( $var->ID, 'sp_precision', true );
			if ( ! $precision ) $precision = 0;
			$output[ $var->post_name ] = array(
				'equation' => $equation,
				'precision' => $precision,
			);
		endforeach;

		return $output;
	}
}

if ( !function_exists( 'sp_post_adder' ) ) {
	function sp_post_adder( $post_type = 'post', $label = null ) {
		$obj = get_post_type_object( $post_type );
		if ( $label == null )
			$label = __( 'Add New', 'sportspress' );
		?>
		<div id="<?php echo $post_type; ?>-adder">
			<h4>
				<a title="<?php echo esc_attr( $label ); ?>" href="<?php echo admin_url( 'post-new.php?post_type=' . $post_type ); ?>" target="_blank">
					+ <?php echo $label; ?>
				</a>
			</h4>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_taxonomy_adder' ) ) {
	function sp_taxonomy_adder( $taxonomy = 'category', $post_type = null, $label = null ) {
		$obj = get_taxonomy( $taxonomy );
		if ( $label == null )
			$label = __( 'Add New', 'sportspress' );
		?>
		<div id="<?php echo $taxonomy; ?>-adder">
			<h4>
				<a title="<?php echo esc_attr( $label ); ?>" href="<?php echo admin_url( 'edit-tags.php?taxonomy=' . $taxonomy . ( $post_type ? '&post_type=' . $post_type : '' ) ); ?>" target="_blank">
					+ <?php echo $label; ?>
				</a>
			</h4>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_update_post_meta' ) ) {
	function sp_update_post_meta( $post_id, $meta_key, $meta_value, $default = null ) {
		if ( !isset( $meta_value ) && isset( $default ) )
			$meta_value = $default;
		add_post_meta( $post_id, $meta_key, $meta_value, true );
	}
}

if ( !function_exists( 'sp_update_post_meta_recursive' ) ) {
	function sp_update_post_meta_recursive( $post_id, $meta_key, $meta_value ) {
		delete_post_meta( $post_id, $meta_key );
		$values = new RecursiveIteratorIterator( new RecursiveArrayIterator( $meta_value ) );
		foreach ( $values as $value ):
			add_post_meta( $post_id, $meta_key, $value, false );
		endforeach;
	}
}

if ( !function_exists( 'sp_update_user_meta_recursive' ) ) {
	function sp_update_user_meta_recursive( $user_id, $meta_key, $meta_value ) {
		delete_user_meta( $user_id, $meta_key );
		$values = new RecursiveIteratorIterator( new RecursiveArrayIterator( $meta_value ) );
		foreach ( $values as $value ):
			add_user_meta( $user_id, $meta_key, $value, false );
		endforeach;
	}
}

if ( !function_exists( 'sp_get_eos_safe_slug' ) ) {
	function sp_get_eos_safe_slug( $title, $post_id = 'var' ) {

		// String to lowercase
		$title = strtolower( $title );

		// Replace all numbers with words
		$title = sp_numbers_to_words( $title );

		// Remove all other non-alphabet characters
		$title = preg_replace( "/[^a-z_]/", '', $title );

		// Convert post ID to words if title is empty
		if ( $title == '' ):

			$title = sp_numbers_to_words( $post_id );

		endif;

		return $title;

	}
}

if ( !function_exists( 'sp_solve' ) ) {
	function sp_solve( $equation, $vars, $precision = 0, $default = '-' ) {

		if ( $equation == null )
			return $default;

		if ( strpos( $equation, '$gamesback' ) !== false ):

			// Return placeholder
			return $default;

		elseif ( strpos( $equation, '$streak' ) !== false ):

			// Return direct value
			return sp_array_value( $vars, 'streak', $default );

		elseif ( strpos( $equation, '$last5' ) !== false ):

			// Return imploded string
			$last5 = sp_array_value( $vars, 'last5', array( 0 ) );
			if ( array_sum( $last5 ) > 0 ):
				return implode( '-', $last5 );
			else:
				return $default;
			endif;

		elseif ( strpos( $equation, '$last10' ) !== false ):

			// Return imploded string
			$last10 = sp_array_value( $vars, 'last10', array( 0 ) );
			if ( array_sum( $last10 ) > 0 ):
				return implode( '-', $last10 );
			else:
				return $default;
			endif;

		elseif ( strpos( $equation, '$homerecord' ) !== false ):

			// Return imploded string
			$homerecord = sp_array_value( $vars, 'homerecord', array( 0 ) );
			return implode( '-', $homerecord );

		elseif ( strpos( $equation, '$awayrecord' ) !== false ):

			// Return imploded string
			$awayrecord = sp_array_value( $vars, 'awayrecord', array( 0 ) );
			return implode( '-', $awayrecord );

		endif;

		// Remove unnecessary variables from vars before calculating
		unset( $vars['gamesback'] );
		unset( $vars['streak'] );
		unset( $vars['last5'] );
		unset( $vars['last10'] );

		if ( sp_array_value( $vars, 'eventsplayed', 0 ) <= 0 )
			return $default;

		// Equation Operating System
        if ( ! class_exists( 'phpStack' ) )
            include_once( SP()->plugin_path() . '/includes/libraries/class-phpstack.php' );
        if ( ! class_exists( 'eqEOS' ) )
            include_once( SP()->plugin_path() . '/includes/libraries/class-eqeos.php' );
		$eos = new eqEOS();

		// Remove spaces from equation
		$equation = str_replace( ' ', '', $equation );

		// Create temporary equation replacing operators with spaces
		$temp = str_replace( array( '+', '-', '*', '/', '(', ')' ), ' ', $equation );

		// Check if each variable part is in vars
		$parts = explode( ' ', $temp );
		foreach( $parts as $key => $value ):
			if ( substr( $value, 0, 1 ) == '$' ):
				if ( ! array_key_exists( preg_replace( "/[^a-z0-9_]/", '', $value ), $vars ) )
					return 0;
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

if ( !function_exists( 'sp_sort_table_teams' ) ) {
	function sp_sort_table_teams ( $a, $b ) {

		global $sportspress_column_priorities;

		// Loop through priorities
		foreach( $sportspress_column_priorities as $priority ):

			// Proceed if columns are not equal
			if ( sp_array_value( $a, $priority['column'], 0 ) != sp_array_value( $b, $priority['column'], 0 ) ):

				// Compare column values
				$output = sp_array_value( $a, $priority['column'], 0 ) - sp_array_value( $b, $priority['column'], 0 );

				// Flip value if descending order
				if ( $priority['order'] == 'DESC' ) $output = 0 - $output;

				return ( $output > 0 );

			endif;

		endforeach;

		// Default sort by alphabetical
		return strcmp( sp_array_value( $a, 'name', '' ), sp_array_value( $b, 'name', '' ) );
	}
}

if ( !function_exists( 'sp_get_next_event' ) ) {
	function sp_get_next_event( $args = array() ) {
			$options = array(
				'post_type' => 'sp_event',
				'posts_per_page' => 1,
				'order' => 'ASC',
				'post_status' => 'future',
				'meta_query' => $args,
			);
			$posts = get_posts( $options );
			if ( $posts && is_array( $posts ) ) return array_pop( $posts );
			else return false;
	}
}

if ( !function_exists( 'sp_taxonomy_field' ) ) {
	function sp_taxonomy_field( $taxonomy = 'category', $post = null, $multiple = false, $trigger = false ) {
		$obj = get_taxonomy( $taxonomy );
		if ( $obj ) {
			$post_type = get_post_type( $post );
			?>
			<div class="<?php echo $post_type; ?>-<?php echo $taxonomy; ?>-field">
				<p><strong><?php echo $obj->labels->singular_name; ?></strong></p>
				<p>
					<?php
					$terms = get_the_terms( $post->ID, $taxonomy );
					$term_ids = array();
					if ( $terms ):
						foreach ( $terms as $term ):
							$term_ids[] = $term->term_id;
						endforeach;
					endif;
					$args = array(
						'taxonomy' => $taxonomy,
						'name' => 'tax_input[' . $taxonomy . '][]',
						'selected' => $term_ids,
						'values' => 'term_id',
						'class' => 'sp-has-dummy widefat' . ( $trigger ? ' sp-ajax-trigger' : '' ),
						'chosen' => true,
						'placeholder' => __( 'All', 'sportspress' ),
					);
					if ( $multiple ) {
						$args['property'] = 'multiple';
					}
					if ( ! sp_dropdown_taxonomies( $args ) ):
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
 * @return array
 */
function sp_get_text_options() {
	$strings = apply_filters( 'sportspress_text', array(
		__( 'Article', 'sportspress' ),
		__( 'Scorecard', 'sportspress' ),
		__( 'Career Total', 'sportspress' ),
		__( 'Current Team', 'sportspress' ),
		__( 'Current Teams', 'sportspress' ),
		__( 'Date', 'sportspress' ),
		__( 'Details', 'sportspress' ),
		__( 'Event', 'sportspress' ),
		__( 'Competition', 'sportspress' ),
		__( 'Nationality', 'sportspress' ),
		__( 'Outcome', 'sportspress' ),
		__( 'Past Teams', 'sportspress' ),
		__( 'Played', 'sportspress' ),
		__( 'Player', 'sportspress' ),
		__( 'Pos', 'sportspress' ),
		__( 'Position', 'sportspress' ),
		__( 'Preview', 'sportspress' ),
		__( 'Rank', 'sportspress' ),
		__( 'Recap', 'sportspress' ),
		__( 'Results', 'sportspress' ),
		__( 'Season', 'sportspress' ),
		__( 'Staff', 'sportspress' ),
		__( 'Substitutes', 'sportspress' ),
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
	));
	
	asort( $strings );
	return array_unique( $strings );
}

/**
 * Display a link to review SportsPress
 * @return null
 */
function sp_review_link() {
	?>
	<p>
		<a href="http://wordpress.org/support/view/plugin-reviews/sportspress#postform">
			<?php _e( 'Love SportsPress? Help spread the word by rating us 5★ on WordPress.org', 'sportspress' ); ?>
		</a>
	</p>
	<?php
}

/**
 * Return shortcode template for meta boxes
 * @return null
 */
function sp_get_shortcode_template( $shortcode, $id = null, $args = array() ) {
	$args = apply_filters( 'sportspress_shortcode_template_args', $args );
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
 * @return null
 */
function sp_shortcode_template( $shortcode, $id = null, $args = array() ) {
	echo sp_get_shortcode_template( $shortcode, $id, $args );
}
