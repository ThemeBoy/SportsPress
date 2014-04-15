<?php
/**
 * SportsPress Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include core functions
include( 'sp-conditional-functions.php' );
include( 'sp-formatting-functions.php' );
include( 'sp-deprecated-functions.php' );

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

	do_action( 'sportspress_before_template_part', $template_name, $template_path, $located, $args );

	include( $located );

	do_action( 'sportspress_after_template_part', $template_name, $template_path, $located, $args );
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

if ( !function_exists( 'sp_nonce' ) ) {
	function sp_nonce() {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
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
	function sp_array_combine( $keys = array(), $values = array() ) {
		$output = array();
		foreach ( $keys as $key ):
			if ( is_array( $values ) && array_key_exists( $key, $values ) )
				$output[ $key ] = $values[ $key ];
			else
				$output[ $key ] = array();
		endforeach;
		return $output;
	}
}

if ( !function_exists( 'sp_numbers_to_words' ) ) {
	function sp_numbers_to_words( $str ) {
	    $output = str_replace( array( '1st', '2nd', '3rd', '5th', '8th', '9th', '10', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ), array( 'first', 'second', 'third', 'fifth', 'eight', 'ninth', 'ten', 'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine' ), $str );
	    return $output;
    }
}

if ( !function_exists( 'sp_get_the_term_id' ) ) {
	function sp_get_the_term_id( $post_id, $taxonomy, $index ) {
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

if ( !function_exists( 'sp_get_post_views' ) ) {
	function sp_get_post_views( $post_id ) {
	    $count_key = 'sp_views';
	    $count = get_post_meta( $post_id, $count_key, true );
	    if ( $count == '' ):
	    	$count = 0;
	        delete_post_meta( $post_id, $count_key );
	        add_post_meta( $post_id, $count_key, '0' );
	    endif;
	    if ( isset( $views ) && $views == 1 )
	    	return __( '1 view', 'sportspress' );
	    else
	    	return sprintf( __( '%s views', 'sportspress' ), $count );
	}
}

if ( !function_exists( 'sp_set_post_views' ) ) {
	function sp_set_post_views( $post_id ) {
		if ( is_preview() )
			return;
		
	    $count_key = 'sp_views';
	    $count = get_post_meta( $post_id, $count_key, true );
	    if ( $count == '' ):
	        $count = 0;
	        delete_post_meta( $post_id, $count_key );
	        add_post_meta( $post_id, $count_key, '0' );
	    else:
	        $count++;
	        update_post_meta( $post_id, $count_key, $count );
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
			return str_replace(
				array( '$', '+', '-', '*', '/' ),
				array( '&Sigma; ', '&plus;', '&minus;', '&times;', '&divide' ),
				$equation
			);
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

if ( !function_exists( 'sp_dropdown_taxonomies' ) ) {
	function sp_dropdown_taxonomies( $args = array() ) {
		$defaults = array(
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
		);
		$args = array_merge( $defaults, $args ); 
		$terms = get_terms( $args['taxonomy'], $args );
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

		if ( $terms ):
			printf( '<select name="%s" class="postform %s" %s>', $name, $class . ( $chosen ? ' chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' ) : '' ), ( $placeholder != null ? 'data-placeholder="' . $placeholder . '" ' : '' ) . $property );

			if ( strpos( $property, 'multiple' ) === false ):
				if ( $args['show_option_all'] ):
					printf( '<option value="0">%s</option>', $args['show_option_all'] );
				endif;
				if ( $args['show_option_none'] ):
					printf( '<option value="-1">%s</option>', $args['show_option_none'] );
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
		
		$posts = get_posts( $args );
		if ( $posts || $args['prepend_options'] || $args['append_options'] ):
			printf( '<select name="%s" id="%s" class="postform %s" %s>', $name, $id, $class . ( $chosen ? ' chosen-select' . ( is_rtl() ? ' chosen-rtl' : '' ) : '' ), ( $placeholder != null ? 'data-placeholder="' . $placeholder . '" ' : '' ) . $property );

			if ( strpos( $property, 'multiple' ) === false ):
				if ( $args['show_option_blank'] ):
					printf( '<option value=""></option>' );
				endif;
				if ( $args['show_option_all'] ):
					printf( '<option value="%s" %s>%s</option>', $args['option_all_value'], selected( $selected, $args['option_all_value'], false ), $args['show_option_all'] );
				endif;
				if ( $args['show_option_none'] ):
					printf( '<option value="%s" %s>%s</option>', $args['option_none_value'], selected( $selected, $args['option_none_value'], false ), ( $args['show_option_none'] === true ? '' : $args['show_option_none'] ) );
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

				printf( '<option value="%s" %s>%s</option>', $this_value, $selected_prop, $post->post_title . ( $args['show_dates'] ? ' (' . $post->post_date . ')' : '' ) );
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
	function sp_post_checklist( $post_id = null, $meta = 'post', $display = 'block', $filter = null, $index = null ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		?>
		<div id="<?php echo $meta; ?>-all" class="posttypediv wp-tab-panel sp-tab-panel sp-select-all-range" style="display: <?php echo $display; ?>;">
			<input type="hidden" value="0" name="<?php echo $meta; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" />
			<ul class="categorychecklist form-no-clear">
				<li class="sp-select-all-container"><label class="selectit"><input type="checkbox" class="sp-select-all"> <strong><?php _e( 'Select All', 'sportspress' ); ?></strong></label></li>
				<?php
				$selected = sp_array_between( (array)get_post_meta( $post_id, $meta, false ), 0, $index );
				$posts = get_pages( array( 'post_type' => $meta, 'number' => 0 ) );
				if ( empty( $posts ) ):
					$query = array( 'post_type' => $meta, 'numberposts' => -1, 'post_per_page' => -1 );
					if ( $meta == 'sp_player' ):
						$query['meta_key'] = 'sp_number';
						$query['orderby'] = 'meta_value_num';
						$query['order'] = 'ASC';
					endif;
					$posts = get_posts( $query );
				endif;
				foreach ( $posts as $post ):
					$parents = get_post_ancestors( $post );
					if ( $filter ):
						$filter_values = (array)get_post_meta( $post->ID, $filter, false );
						$terms = (array)get_the_terms( $post->ID, 'sp_season' );
						foreach ( $terms as $term ):
							if ( is_object( $term ) && property_exists( $term, 'term_id' ) )
								$filter_values[] = $term->term_id;
						endforeach;
					endif;
					?>
					<li class="sp-post sp-filter-0<?php
						if ( $filter ):
							foreach ( $filter_values as $filter_value ):
								echo ' sp-filter-' . $filter_value;
							endforeach;
						endif;
					?>">
						<?php echo str_repeat( '<ul><li>', sizeof( $parents ) ); ?>
						<label class="selectit">
							<input type="checkbox" value="<?php echo $post->ID; ?>" name="<?php echo $meta; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]"<?php if ( in_array( $post->ID, $selected ) ) echo ' checked="checked"'; ?>>
							<?php
							$title = $post->post_title;
							if ( empty( $title ) )
								$title = __( '(no title)', 'sportspress' );
							echo $title;
							?>
						</label>
						<?php echo str_repeat( '</li></ul>', sizeof( $parents ) ); ?>
					</li>
					<?php
				endforeach;
				?>
				<li class="sp-not-found-container"><?php _e( 'No results found.', 'sportspress' ); ?></li>
			</ul>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_calculate_selector' ) ) {
	function sp_calculate_selector( $postid, $selected = null ) {
		$options = array(
			'total' => __( 'Total', 'sportspress' ),
			'average' => __( 'Average', 'sportspress' ),
		);
		?>
		<select name="sp_calculate">
		<?php foreach( $options as $key => $name ): ?>
			<option value="<?php echo $key; ?>" <?php selected ( $key, $selected ); ?>><?php echo $name; ?></option>
		<?php endforeach; ?>
		</select>
		<?php
	}
}

if ( !function_exists( 'sp_get_equation_optgroup_array' ) ) {
	function sp_get_equation_optgroup_array( $postid, $type = null, $variations = null, $defaults = null, $totals = true ) {
		$arr = array();

		// Get posts
		$args = array(
			'post_type' => $type,
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'exclude' => $postid
		);
		$vars = get_posts( $args );

		// Add extra vars to the array
		if ( isset( $defaults ) && is_array( $defaults ) ):
			foreach ( $defaults as $key => $value ):
				$arr[ $key ] = $value;
			endforeach;
		endif;

		// Add vars to the array
		if ( isset( $variations ) && is_array( $variations ) ):
			foreach ( $vars as $var ):
				if ( $totals ) $arr[ '$' . $var->post_name ] = $var->post_title;
				foreach ( $variations as $key => $value ):
					$arr[ '$' . $var->post_name . $key ] = $var->post_title . ' ' . $value;
				endforeach;
			endforeach;
		else:
			foreach ( $vars as $var ):
				'$' . $arr[ $var->post_name ] = $var->post_title;
			endforeach;
		endif;

		return (array) $arr;
	}
}

if ( !function_exists( 'sp_equation_selector' ) ) {
	function sp_equation_selector( $postid, $selected = null, $groups = array() ) {

		if ( ! isset( $postid ) )
			return;

		// Initialize options array
		$options = array();

		// Add groups to options
		foreach ( $groups as $group ):
			switch ( $group ):
				case 'player_event':
					$options[ __( 'Events', 'sportspress' ) ] = array( '$eventsattended' => __( 'Attended', 'sportspress' ), '$eventsplayed' => __( 'Played', 'sportspress' ) );
					break;
				case 'team_event':
					$options[ __( 'Events', 'sportspress' ) ] = array( '$eventsplayed' => __( 'Played', 'sportspress' ) );
					break;
				case 'result':
					$options[ __( 'Results', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_result', array( 'for' => '&rarr;', 'against' => '&larr;' ), null, false );
					break;
				case 'outcome':
					$options[ __( 'Outcomes', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_outcome', array() );
					$options[ __( 'Outcomes', 'sportspress' ) ]['$streak'] = __( 'Streak', 'sportspress' );
					$options[ __( 'Outcomes', 'sportspress' ) ]['$last5'] = __( 'Last 5', 'sportspress' );
					$options[ __( 'Outcomes', 'sportspress' ) ]['$last10'] = __( 'Last 10', 'sportspress' );
					break;
				case 'column':
					$options[ __( 'Columns', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_column' );
					break;
				case 'performance':
					$options[ __( 'Performance', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_performance' );
					break;
			endswitch;
		endforeach;

		// Create array of operators
		$operators = array( '+' => '&plus;', '-' => '&minus;', '*' => '&times;', '/' => '&divide;', '(' => '(', ')' => ')' );

		// Add operators to options
		$options[ __( 'Operators', 'sportspress' ) ] = $operators;

		// Create array of constants
		$max = 10;
		$constants = array();
		for ( $i = 1; $i <= $max; $i ++ ):
			$constants[$i] = $i;
		endfor;

		// Add 100 to constants
		$constants[100] = 100;

		// Add constants to options
		$options[ __( 'Constants', 'sportspress' ) ] = (array) $constants;

		?>
			<select name="sp_equation[]">
				<option value=""><?php _e( '&mdash; Select &mdash;', 'sportspress' ); ?></option>
				<?php

				foreach ( $options as $label => $option ):
					printf( '<optgroup label="%s">', $label );

					foreach ( $option as $key => $value ):
						printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $selected, false ), $value );
					endforeach;
				
					echo '</optgroup>';
				endforeach;

				?>
			</select>
		<?php
	}
}

if ( !function_exists( 'sp_get_var_labels' ) ) {
	function sp_get_var_labels( $post_type ) {
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

if ( !function_exists( 'sp_edit_calendar_table' ) ) {
	function sp_edit_calendar_table( $data = array(), $usecolumns = null ) {
		if ( is_array( $usecolumns ) )
			$usecolumns = array_filter( $usecolumns );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-calendar-table">
				<thead>
					<tr>
						<th class="column-date">
							<?php _e( 'Date', 'sportspress' ); ?>
						</th>
						<th class="column-event">
							<label for="sp_columns_event">
								<input type="checkbox" name="sp_columns[]" value="event" id="sp_columns_event" <?php checked( ! is_array( $usecolumns ) || in_array( 'event', $usecolumns ) ); ?>>
								<?php _e( 'Event', 'sportspress' ); ?>
							</label>
						</th>
						<th class="column-teams">
							<label for="sp_columns_teams">
								<input type="checkbox" name="sp_columns[]" value="teams" id="sp_columns_teams" <?php checked( ! is_array( $usecolumns ) || in_array( 'teams', $usecolumns ) ); ?>>
								<?php _e( 'Teams', 'sportspress' ); ?>
							</label>
						</th>
						<th class="column-time">
							<label for="sp_columns_time">
								<input type="checkbox" name="sp_columns[]" value="time" id="sp_columns_time" <?php checked( ! is_array( $usecolumns ) || in_array( 'time', $usecolumns ) ); ?>>
								<?php _e( 'Time', 'sportspress' ); ?>
							</label>
						</th>
						<th class="column-venue">
							<label for="sp_columns_venue">
								<input type="checkbox" name="sp_columns[]" value="venue" id="sp_columns_venue" <?php checked( ! is_array( $usecolumns ) || in_array( 'venue', $usecolumns ) ); ?>>
								<?php _e( 'Venue', 'sportspress' ); ?>
							</label>
						</th>
						<th class="column-article">
							<label for="sp_columns_article">
								<input type="checkbox" name="sp_columns[]" value="article" id="sp_columns_article" <?php checked( ! is_array( $usecolumns ) || in_array( 'article', $usecolumns ) ); ?>>
								<?php _e( 'Article', 'sportspress' ); ?>
							</label>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) && sizeof( $data ) > 0 ):
						$main_result = get_option( 'sportspress_primary_result', null );
						$i = 0;
						foreach ( $data as $event ):
							$teams = get_post_meta( $event->ID, 'sp_team' );
							$results = get_post_meta( $event->ID, 'sp_results', true );
							$video = get_post_meta( $event->ID, 'sp_video', true );
							?>
							<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
								<td><?php echo get_post_time( get_option( 'date_format' ), false, $event ); ?></td>
								<td><?php echo $event->post_title; ?></td>
								<td>
									<?php
									foreach ( $teams as $team ):
										$name = get_the_title( $team );
										if ( $name ):
											$team_results = sp_array_value( $results, $team, null );

											if ( $main_result ):
												$team_result = sp_array_value( $team_results, $main_result, null );
											else:
												if ( is_array( $team_results ) ):
													end( $team_results );
													$team_result = prev( $team_results );
												else:
													$team_result = null;
												endif;
											endif;

											if ( $team_result != null ):
												unset( $team_results['outcome'] );
												$team_results = implode( ' | ', $team_results );
												echo '<a class="result tips" title="' . $team_results . '" href="' . get_edit_post_link( $event->ID ) . '">' . $team_result . '</a> ';
											endif;

											echo $name . '<br>';
										endif;
									endforeach;
									?>
								</td>
								<td><?php echo get_post_time( get_option( 'time_format' ), false, $event ); ?></td>
								<td><?php the_terms( $event->ID, 'sp_venue' ); ?></td>
								<td>
									<a href="<?php echo get_edit_post_link( $event->ID ); ?>#sp_articlediv">
										<?php if ( $video ): ?>
											<div class="dashicons dashicons-video-alt"></div>
										<?php elseif ( has_post_thumbnail( $event->ID ) ): ?>
											<div class="dashicons dashicons-camera"></div>
										<?php endif; ?>
										<?php
										if ( $event->post_content == null ):
											_e( 'None', 'sportspress' );
										elseif ( $event->post_status == 'publish' ):
											_e( 'Recap', 'sportspress' );
										else:
											_e( 'Preview', 'sportspress' );
										endif;
										?>
									</a>
								</td>
							</tr>
							<?php
							$i++;
						endforeach;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="6">
							<?php printf( __( 'Select %s', 'sportspress' ), __( 'Details', 'sportspress' ) ); ?>
						</td>
					</tr>
					<?php
					endif;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_edit_league_table' ) ) {
	function sp_edit_league_table( $columns = array(), $usecolumns = null, $data = array(), $placeholders = array(), $adjustments = array() ) {
		if ( is_array( $usecolumns ) )
			$usecolumns = array_filter( $usecolumns );
			$show_team_logo = get_option( 'sportspress_table_show_logos', false );
		?>
		<ul class="subsubsub sp-table-bar">
			<li><a href="#sp-table-values" class="current"><?php _e( 'Values', 'sportspress' ); ?></a></li> | 
			<li><a href="#sp-table-adjustments" class=""><?php _e( 'Adjustments', 'sportspress' ); ?></a></li>
		</ul>
		<div class="sp-data-table-container sp-table-panel sp-table-values" id="sp-table-values">
			<table class="widefat sp-data-table sp-league-table">
				<thead>
					<tr>
						<th><?php _e( 'Team', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $key => $label ): ?>
							<th><label for="sp_columns_<?php echo $key; ?>">
								<input type="checkbox" name="sp_columns[]" value="<?php echo $key; ?>" id="sp_columns_<?php echo $key; ?>" <?php checked( ! is_array( $usecolumns ) || in_array( $key, $usecolumns ) ); ?>>
								<?php echo $label; ?>
							</label></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) && sizeof( $data ) > 0 ):
						$i = 0;
						foreach ( $data as $team_id => $team_stats ):
							if ( !$team_id )
								continue;

							$default_name = sp_array_value( $team_stats, 'name', '' );
							if ( $default_name == null )
								$default_name = get_the_title( $team_id );
							?>
							<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
								<td>
									<?php if ( $show_team_logo ) echo get_the_post_thumbnail( $team_id, 'sportspress-fit-icon' ); ?>
									<span class="sp-default-value">
										<span class="sp-default-value-input"><?php echo $default_name; ?></span>
										<a class="dashicons dashicons-edit sp-edit" title="<?php _e( 'Edit', 'sportspress' ); ?>"></a>
									</span>
									<span class="hidden sp-custom-value">
										<input type="text" name="sp_teams[<?php echo $team_id; ?>][name]" class="name sp-custom-value-input" value="<?php echo sp_array_value( $team_stats, 'name', '' ); ?>" placeholder="<?php echo get_the_title( $team_id ); ?>" size="6">
										<a class="button button-secondary sp-cancel"><?php _e( 'Cancel', 'sportspress' ); ?></a>
										<a class="button button-primary sp-save"><?php _e( 'Save', 'sportspress' ); ?></a>
									</span>
								</td>
								<?php foreach( $columns as $column => $label ):
									$value = sp_array_value( $team_stats, $column, '' );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $team_id, array() ), $column, 0 );
									?>
									<td><input type="text" name="sp_teams[<?php echo $team_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" data-placeholder="<?php echo $placeholder; ?>" data-matrix="<?php echo $team_id; ?>_<?php echo $column; ?>" data-adjustment="<?php echo sp_array_value( sp_array_value( $adjustments, $team_id, array() ), $column, 0 ); ?>" /></td>
								<?php endforeach; ?>
							</tr>
							<?php
							$i++;
						endforeach;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="<?php $colspan = sizeof( $columns ) + 1; echo $colspan; ?>">
							<?php printf( __( 'Select %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ); ?>
						</td>
					</tr>
					<?php
					endif;
					?>
				</tbody>
			</table>
		</div>
		<div class="sp-data-table-container sp-table-panel sp-table-adjustments hidden" id="sp-table-adjustments">
			<table class="widefat sp-data-table sp-league-table">
				<thead>
					<tr>
						<th><?php _e( 'Team', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $key => $label ): ?>
							<th><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) && sizeof( $data ) > 0 ):
						$i = 0;
						foreach ( $data as $team_id => $team_stats ):
							if ( !$team_id )
								continue;
							?>
							<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
								<td>
									<?php echo get_the_title( $team_id ); ?>
								</td>
								<?php foreach( $columns as $column => $label ):
									$value = sp_array_value( sp_array_value( $adjustments, $team_id, array() ), $column, '' );
									?>
									<td><input type="text" name="sp_adjustments[<?php echo $team_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="0" data-matrix="<?php echo $team_id; ?>_<?php echo $column; ?>" /></td>
								<?php endforeach; ?>
							</tr>
							<?php
							$i++;
						endforeach;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="<?php $colspan = sizeof( $columns ) + 1; echo $colspan; ?>">
							<?php printf( __( 'Select %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ); ?>
						</td>
					</tr>
					<?php
					endif;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_edit_player_list_table' ) ) {
	function sp_edit_player_list_table( $columns = array(), $usecolumns = null, $data = array(), $placeholders = array() ) {
		if ( is_array( $usecolumns ) )
			$usecolumns = array_filter( $usecolumns );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-player-list-table">
				<thead>
					<tr>
						<th>#</th>
						<th><?php _e( 'Player', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $key => $label ): ?>
							<th><label for="sp_columns_<?php echo $key; ?>">
								<input type="checkbox" name="sp_columns[]" value="<?php echo $key; ?>" id="sp_columns_<?php echo $key; ?>" <?php checked( ! is_array( $usecolumns ) || in_array( $key, $usecolumns ) ); ?>>
								<?php echo $label; ?>
							</label></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					if ( is_array( $data ) && sizeof( $data ) > 0 ):
						$i = 0;
						foreach ( $data as $player_id => $player_stats ):
							if ( !$player_id ) continue;
							$div = get_term( $player_id, 'sp_season' );
							$number = get_post_meta( $player_id, 'sp_number', true );
							?>
							<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
								<td><?php echo ( $number ? $number : '&nbsp;' ); ?></td>
								<td>
									<?php echo get_the_title( $player_id ); ?>
								</td>
								<?php foreach( $columns as $column => $label ):
									$value = sp_array_value( $player_stats, $column, '' );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $player_id, array() ), $column, 0 );
									?>
									<td><input type="text" name="sp_players[<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" /></td>
								<?php endforeach; ?>
							</tr>
							<?php
							$i++;
						endforeach;
					else:
					?>
					<tr class="sp-row alternate">
						<td colspan="<?php $colspan = sizeof( $columns ) + 1; echo $colspan; ?>">
							<?php printf( __( 'Select %s', 'sportspress' ), __( 'Players', 'sportspress' ) ); ?>
						</td>
					</tr>
					<?php
					endif;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_edit_team_columns_table' ) ) {
	function sp_edit_team_columns_table( $league_id, $columns = array(), $data = array(), $placeholders = array(), $merged = array(), $seasons = array(), $readonly = true ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-select-all-range">
				<thead>
					<tr>
						<th class="check-column"><input class="sp-select-all" type="checkbox"></th>
						<th><?php _e( 'Season', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $label ): ?>
							<th><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $data as $div_id => $div_stats ):
						if ( !$div_id ) continue;
						$div = get_term( $div_id, 'sp_season' );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<input type="checkbox" name="sp_leagues_seasons[<?php echo $league_id; ?>][<?php echo $div_id; ?>]" id="sp_leagues_seasons_<?php echo $league_id; ?>_<?php echo $div_id; ?>" value="1" <?php checked( sp_array_value( $seasons, $div_id, 0 ), 1 ); ?>>
							</td>
							<td>
								<label for="sp_leagues_seasons_<?php echo $league_id; ?>_<?php echo $div_id; ?>"><?php echo $div->name; ?></label>
							</td>
							<?php foreach( $columns as $column => $label ):
								$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, 0 );
								?>
								<td><?php
									$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, null );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $div_id, array() ), $column, 0 );
									echo '<input type="text" name="sp_columns[' . $league_id . '][' . $div_id . '][' . $column . ']" value="' . $value . '" placeholder="' . $placeholder . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . ' />';
								?></td>
							<?php endforeach; ?>
						</tr>
						<?php
						$i++;
					endforeach;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_edit_player_performance_table' ) ) {
	function sp_edit_player_performance_table( $id = null, $league_id, $columns = array(), $data = array(), $placeholders = array(), $merged = array(), $seasons_teams = array(), $readonly = true ) {
		if ( ! $id )
			$id = get_the_ID();

		$teams = array_filter( get_post_meta( $id, 'sp_team', false ) );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table">
				<thead>
					<tr>
						<th><?php _e( 'Season', 'sportspress' ); ?></th>
						<th><?php _e( 'Team', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $label ): ?>
							<th><?php echo $label; ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $data as $div_id => $div_stats ):
						if ( !$div_id || $div_id == 'performance' ) continue;
						$div = get_term( $div_id, 'sp_season' );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<?php echo $div->name; ?>
							</td>
							<td>
								<?php
								$value = sp_array_value( $seasons_teams, $div_id, '-1' );
								$args = array(
									'post_type' => 'sp_team',
									'name' => 'sp_leagues[' . $league_id . '][' . $div_id . ']',
									'show_option_none' => __( '&mdash; None &mdash;', 'sportspress' ),
								    'sort_order'   => 'ASC',
								    'sort_column'  => 'menu_order',
									'selected' => $value,
									'values' => 'ID',
									'include' => $teams,
									'tax_query' => array(
										'relation' => 'AND',
										array(
											'taxonomy' => 'sp_league',
											'terms' => $league_id,
											'field' => 'id',
										),
										array(
											'taxonomy' => 'sp_season',
											'terms' => $div_id,
											'field' => 'id',
										),
									),
								);
								if ( ! sp_dropdown_pages( $args ) ):
									_e( 'No results found.', 'sportspress' );
								endif;
								?>
							</td>
							<?php foreach( $columns as $column => $label ):
								?>
								<td><?php
									$value = sp_array_value( sp_array_value( $data, $div_id, array() ), $column, null );
									$placeholder = sp_array_value( sp_array_value( $placeholders, $div_id, array() ), $column, 0 );
									echo '<input type="text" name="sp_performance[' . $league_id . '][' . $div_id . '][' . $column . ']" value="' . $value . '" placeholder="' . $placeholder . '"' . ( $readonly ? ' disabled="disabled"' : '' ) . '  />';
								?></td>
							<?php endforeach; ?>
						</tr>
						<?php
						$i++;
					endforeach;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_edit_event_results_table' ) ) {
	function sp_edit_event_results_table( $columns = array(), $data = array() ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table">
				<thead>
					<tr>
						<th class="column-team"><?php _e( 'Team', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $key => $label ): ?>
							<th class="outcome-<?php echo $key; ?>"><?php echo $label; ?></th>
						<?php endforeach; ?>
						<th class="column-outcome"><?php _e( 'Outcome', 'sportspress' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $data as $team_id => $team_results ):
						if ( !$team_id ) continue;
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<?php echo get_the_title( $team_id ); ?>
							</td>
							<?php foreach( $columns as $column => $label ):
								$value = sp_array_value( $team_results, $column, '' );
								?>
								<td><input type="text" name="sp_results[<?php echo $team_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" /></td>
							<?php endforeach; ?>
							<td>
								<?php
								$values = sp_array_value( $team_results, 'outcome', '' );
								if ( ! is_array( $values ) )
									$values = array( $values );

								$args = array(
									'post_type' => 'sp_outcome',
									'name' => 'sp_results[' . $team_id . '][outcome][]',
									'option_none_value' => '',
								    'sort_order'   => 'ASC',
								    'sort_column'  => 'menu_order',
									'selected' => $values,
									'class' => 'sp-outcome',
									'property' => 'multiple',
									'chosen' => true,
								);
								sp_dropdown_pages( $args );
								?>
							</td>
						</tr>
						<?php
						$i++;
					endforeach;
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_event_player_status_selector' ) ) {
	function sp_event_player_status_selector( $team_id, $player_id, $value = null ) {

		if ( ! $team_id || ! $player_id )
			return '&mdash;';

		$options = array(
			'lineup' => __( 'Starting Lineup', 'sportspress' ),
			'sub' => __( 'Substitute', 'sportspress' ),
		);

		$output = '<select name="sp_players[' . $team_id . '][' . $player_id . '][status]">';

		foreach( $options as $key => $name ):
			$output .= '<option value="' . $key . '"' . ( $key == $value ? ' selected' : '' ) . '>' . $name . '</option>';
		endforeach;

		$output .= '</select>';

		return $output;

	}
}

if ( !function_exists( 'sp_event_player_sub_selector' ) ) {
	function sp_event_player_sub_selector( $team_id, $player_id, $value, $data = array() ) {

		if ( ! $team_id || ! $player_id )
			return '&mdash;';

		$output = '<select name="sp_players[' . $team_id . '][' . $player_id . '][sub]" style="display: none;">';

		$output .= '<option value="0">' . __( 'None', 'sportspress' ) . '</option>';

		// Add players as selectable options
		foreach( $data as $id => $performance ):
			if ( ! $id || $id == $player_id ) continue;
			$number = get_post_meta( $id, 'sp_number', true );
			$output .= '<option value="' . $id . '"' . ( $id == $value ? ' selected' : '' ) . '>' . ( $number ? $number . '. ' : '' ) . get_the_title( $id ) . '</option>';
		endforeach;

		$output .= '</select>';

		return $output;

	}
}

if ( !function_exists( 'sp_edit_event_players_table' ) ) {
	function sp_edit_event_players_table( $columns = array(), $data = array(), $team_id ) {
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-performance-table">
				<thead>
					<tr>
						<th>#</th>
						<th><?php _e( 'Player', 'sportspress' ); ?></th>
						<?php foreach ( $columns as $label ): ?>
							<th><?php echo $label; ?></th>
						<?php endforeach; ?>
						<th><?php _e( 'Status', 'sportspress' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 0;
					foreach ( $data as $player_id => $player_performance ):
						if ( !$player_id ) continue;
						$number = get_post_meta( $player_id, 'sp_number', true );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>" data-player="<?php echo $player_id; ?>">
							<td><?php echo ( $number ? $number : '&nbsp;' ); ?></td>
							<td><?php echo get_the_title( $player_id ); ?></td>
							<?php foreach( $columns as $column => $label ):
								$value = sp_array_value( $player_performance, $column, '' );
								?>
								<td>
									<input class="sp-player-<?php echo $column; ?>-input" type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="0" />
								</td>
							<?php endforeach; ?>
							<td class="sp-status-selector">
								<?php echo sp_event_player_status_selector( $team_id, $player_id, sp_array_value( $player_performance, 'status', null ) ); ?>
								<?php echo sp_event_player_sub_selector( $team_id, $player_id, sp_array_value( $player_performance, 'sub', null ), $data ); ?>
							</td>
						</tr>
						<?php
						$i++;
					endforeach;
					?>
					<tr class="sp-row sp-total<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
						<td>&nbsp;</td>
						<td><strong><?php _e( 'Total', 'sportspress' ); ?></strong></td>
						<?php foreach( $columns as $column => $label ):
							$player_id = 0;
							$player_performance = sp_array_value( $data, 0, array() );
							$value = sp_array_value( $player_performance, $column, '' );
							?>
							<td><input type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="0" /></td>
						<?php endforeach; ?>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
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
	function sp_taxonomy_adder( $taxonomy = 'category', $post_type = 'post', $label = null ) {
		$obj = get_taxonomy( $taxonomy );
		if ( $label == null )
			$label = __( 'Add New', 'sportspress' );
		?>
		<div id="<?php echo $taxonomy; ?>-adder">
			<h4>
				<a title="<?php echo esc_attr( $label ); ?>" href="<?php echo admin_url( 'edit-tags.php?taxonomy=' . $taxonomy . '&post_type=' . $post_type ); ?>" target="_blank">
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

if ( !function_exists( 'sp_get_eos_safe_slug' ) ) {
	function sp_get_eos_safe_slug( $title, $post_id = 'var' ) {

		// String to lowercase
		$title = strtolower( $title );

		// Replace all numbers with words
		$title = sp_numbers_to_words( $title );

		// Remove all other non-alphabet characters
		$title = preg_replace( "/[^a-z]/", '', $title );

		// Convert post ID to words if title is empty
		if ( $title == '' ):

			$title = sp_numbers_to_words( $post_id );

		endif;

		return $title;

	}
}

if ( !function_exists( 'sp_solve' ) ) {
	function sp_solve( $equation, $vars, $precision = 0 ) {

		if ( strpos( $equation, '$streak' ) !== false ):

			// Return direct value
			return sp_array_value( $vars, 'streak', '-' );

		elseif ( strpos( $equation, '$last5' ) !== false ):

			// Return imploded string
			$last5 = sp_array_value( $vars, 'last5', array( 0 ) );
			if ( array_sum( $last5 ) > 0 ):
				return implode( '-', $last5 );
			else:
				return '-';
			endif;

		elseif ( strpos( $equation, '$last10' ) !== false ):

			// Return imploded string
			$last10 = sp_array_value( $vars, 'last10', array( 0 ) );
			if ( array_sum( $last10 ) > 0 ):
				return implode( '-', $last10 );
			else:
				return '-';
			endif;

		endif;

		// Remove unnecessary variables from vars before calculating
		unset( $vars['streak'] );
		unset( $vars['last5'] );
		unset( $vars['last10'] );

		if ( sp_array_value( $vars, 'eventsplayed', 0 ) <= 0 )
			return '-';

		// Clearance to begin calculating remains true if all equation variables are in vars
		$clearance = true;

		// Check if each variable part is in vars
		$parts = explode( ' ', $equation );
		foreach( $parts as $key => $value ):
			if ( substr( $value, 0, 1 ) == '$' ):
				if ( ! array_key_exists( preg_replace( "/[^a-z]/", '', $value ), $vars ) )
					$clearance = false;
			endif;
		endforeach;

		if ( $clearance ):
			// Equation Operating System
	        if ( ! class_exists( 'phpStack' ) )
	            include_once( SP()->plugin_path() . '/includes/libraries/class-phpstack.php' );
	        if ( ! class_exists( 'eqEOS' ) )
	            include_once( SP()->plugin_path() . '/includes/libraries/class-eqeos.php' );

			$eos = new eqEOS();

			// Solve using EOS
			return round( $eos->solveIF( str_replace( ' ', '', $equation ), $vars ), $precision );
		else:
			return 0;
		endif;

	}
}


if ( !function_exists( 'sp_get_calendar_data' ) ) {
	function sp_get_calendar_data( $post_id = null, $admin = false ) {
		global $pagenow;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'post_date',
			'order' => 'ASC',
			'post_status' => 'any',
			'tax_query' => array(
				'relation' => 'AND'
			),
		);

		if ( $pagenow != 'post-new.php' ):
			if ( $post_id ):
				$leagues = get_the_terms( $post_id, 'sp_league' );
				$seasons = get_the_terms( $post_id, 'sp_season' );
				$venues = get_the_terms( $post_id, 'sp_venue' );
				$team = get_post_meta( $post_id, 'sp_team', true );
				$usecolumns = get_post_meta( $post_id, 'sp_columns', true );

				if ( $leagues ):
					$league_ids = array();
					foreach( $leagues as $league ):
						$league_ids[] = $league->term_id;
					endforeach;
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_league',
						'field' => 'id',
						'terms' => $league_ids
					);
				endif;

				if ( $seasons ):
					$season_ids = array();
					foreach( $seasons as $season ):
						$season_ids[] = $season->term_id;
					endforeach;
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_season',
						'field' => 'id',
						'terms' => $season_ids
					);
				endif;

				if ( $venues ):
					$venue_ids = array();
					foreach( $venues as $venue ):
						$venue_ids[] = $venue->term_id;
					endforeach;
					$args['tax_query'][] = array(
						'taxonomy' => 'sp_venue',
						'field' => 'id',
						'terms' => $venue_ids
					);
				endif;

				if ( $team ):
					$args['meta_query']	= array(
						array(
							'key' => 'sp_team',
							'value' => $team,
						),
					);
				endif;

			else:
				$usecolumns = null;
			endif;
			
			$events = get_posts( $args );

		else:
			$usecolumns = null;
			$events = array();
		endif;

		if ( $admin ):
			return array( $events, $usecolumns );
		else:
			return $events;
		endif;

	}
}

if ( !function_exists( 'sp_get_team_columns_data' ) ) {
	function sp_get_team_columns_data( $post_id, $league_id, $admin = false ) {

		$seasons = (array)get_the_terms( $post_id, 'sp_season' );
		$columns = (array)get_post_meta( $post_id, 'sp_columns', true );
		$leagues_seasons = sp_array_value( (array)get_post_meta( $post_id, 'sp_leagues_seasons', true ), $league_id, array() );

		// Get labels from result variables
		$result_labels = (array)sp_get_var_labels( 'sp_result' );

		// Get labels from outcome variables
		$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

		// Generate array of all season ids and season names
		$div_ids = array();
		$season_names = array();
		foreach ( $seasons as $season ):
			if ( is_object( $season ) && property_exists( $season, 'term_id' ) && property_exists( $season, 'name' ) ):
				$div_ids[] = $season->term_id;
				$season_names[ $season->term_id ] = $season->name;
			endif;
		endforeach;

		$data = array();

		// Get all seasons populated with data where available
		$data = sp_array_combine( $div_ids, sp_array_value( $columns, $league_id, array() ) );

		// Get equations from column variables
		$equations = sp_get_var_equations( 'sp_column' );

		// Initialize placeholders array
		$placeholders = array();

		foreach ( $div_ids as $div_id ):

			$totals = array( 'eventsplayed' => 0, 'streak' => 0, 'last5' => null, 'last10' => null );

			foreach ( $result_labels as $key => $value ):
				$totals[ $key . 'for' ] = 0;
				$totals[ $key . 'against' ] = 0;
			endforeach;

			foreach ( $outcome_labels as $key => $value ):
				$totals[ $key ] = 0;
			endforeach;

			// Initialize streaks counter
			$streak = array( 'name' => '', 'count' => 0, 'fire' => 1 );

			// Initialize last counters
			$last5 = array();
			$last10 = array();

			// Add outcome types to last counters
			foreach( $outcome_labels as $key => $value ):
				$last5[ $key ] = 0;
				$last10[ $key ] = 0;
			endforeach;

			// Get all events involving the team in current season
			$args = array(
				'post_type' => 'sp_event',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'order' => 'ASC',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'sp_team',
						'value' => $post_id
					),
					array(
						'key' => 'sp_format',
						'value' => 'league'
					)
				),
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'sp_league',
						'field' => 'id',
						'terms' => $league_id
					),
					array(
						'taxonomy' => 'sp_season',
						'field' => 'id',
						'terms' => $div_id
					),
				)
			);
			$events = get_posts( $args );

			foreach( $events as $event ):
				$results = (array)get_post_meta( $event->ID, 'sp_results', true );
				foreach ( $results as $team_id => $team_result ):
					foreach ( $team_result as $key => $value ):
						if ( $team_id == $post_id ):
							if ( $key == 'outcome' ):

								// Convert to array
								if ( ! is_array( $value ) ):
									$value = array( $value );
								endif;

								foreach( $value as $outcome ):

									// Increment events played and outcome count
									if ( array_key_exists( $outcome, $totals ) ):
										$totals['eventsplayed']++;
										$totals[ $outcome ]++;
									endif;

									if ( $outcome && $outcome != '-1' ):

										// Add to streak counter
										if ( $streak['fire'] && ( $streak['name'] == '' || $streak['name'] == $outcome ) ):
											$streak['name'] = $outcome;
											$streak['count'] ++;
										else:
											$streak['fire'] = 0;
										endif;

										// Add to last 5 counter if sum is less than 5
										if ( array_key_exists( $outcome, $last5 ) && array_sum( $last5 ) < 5 ):
											$last5[ $outcome ] ++;
										endif;

										// Add to last 10 counter if sum is less than 10
										if ( array_key_exists( $outcome, $last10 ) && array_sum( $last10 ) < 10 ):
											$last10[ $outcome ] ++;
										endif;

									endif;

								endforeach;

							else:
								if ( array_key_exists( $key . 'for', $totals ) ):
									$totals[ $key . 'for' ] += $value;
								endif;
							endif;
						else:
							if ( $key != 'outcome' ):
								if ( array_key_exists( $key . 'against', $totals ) ):
									$totals[ $key . 'against' ] += $value;
								endif;
							endif;
						endif;
					endforeach;
				endforeach;
			endforeach;

			// Compile streaks counter and add to totals
			$args=array(
				'name' => $streak['name'],
				'post_type' => 'sp_outcome',
				'post_status' => 'publish',
				'posts_per_page' => 1
			);
			$outcomes = get_posts( $args );

			if ( $outcomes ):
				$outcome = reset( $outcomes );
				$totals['streak'] = $outcome->post_title . $streak['count'];
			endif;

			// Add last counters to totals
			$totals['last5'] = $last5;
			$totals['last10'] = $last10;

			// Generate array of placeholder values for each league
			$placeholders[ $div_id ] = array();
			foreach ( $equations as $key => $value ):
				$placeholders[ $div_id ][ $key ] = sp_solve( $value['equation'], $totals, $value['precision'] );
			endforeach;

		endforeach;

		// Get columns from column variables
		$columns = sp_get_var_labels( 'sp_column' );

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $season_id => $season_data ):

			if ( ! sp_array_value( $leagues_seasons, $season_id, 0 ) )
				continue;

			$season_name = sp_array_value( $season_names, $season_id, '&nbsp;' );

			// Add season name to row
			$merged[ $season_id ] = array(
				'name' => $season_name
			);

			foreach( $season_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $season_id, $data ) && array_key_exists( $key, $data[ $season_id ] ) && $data[ $season_id ][ $key ] != '' ):
					$merged[ $season_id ][ $key ] = $data[ $season_id ][ $key ];
				else:
					$merged[ $season_id ][ $key ] = $value;
				endif;

			endforeach;

		endforeach;

		if ( $admin ):
			return array( $columns, $data, $placeholders, $merged, $leagues_seasons );
		else:
			$labels = array_merge( array( 'name' => __( 'Season', 'sportspress' ) ), $columns );
			$merged[0] = $labels;
			return $merged;
		endif;

	}

}

if ( !function_exists( 'sp_get_league_table_data' ) ) {
	function sp_get_league_table_data( $post_id, $breakdown = false ) {
		$league_id = sp_get_the_term_id( $post_id, 'sp_league', 0 );
		$div_id = sp_get_the_term_id( $post_id, 'sp_season', 0 );
		$team_ids = (array)get_post_meta( $post_id, 'sp_team', false );
		$table_stats = (array)get_post_meta( $post_id, 'sp_teams', true );
		$usecolumns = get_post_meta( $post_id, 'sp_columns', true );
		$adjustments = get_post_meta( $post_id, 'sp_adjustments', true );

		// Get labels from result variables
		$result_labels = (array)sp_get_var_labels( 'sp_result' );

		// Get labels from outcome variables
		$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

		// Get all leagues populated with stats where available
		$tempdata = sp_array_combine( $team_ids, $table_stats );

		// Create entry for each team in totals
		$totals = array();
		$placeholders = array();

		// Initialize streaks counter
		$streaks = array();

		// Initialize last counters
		$last5s = array();
		$last10s = array();

		foreach ( $team_ids as $team_id ):
			if ( ! $team_id )
				continue;

			// Initialize team streaks counter
			$streaks[ $team_id ] = array( 'name' => '', 'count' => 0, 'fire' => 1 );

			// Initialize team last counters
			$last5s[ $team_id ] = array();
			$last10s[ $team_id ] = array();

			// Add outcome types to team last counters
			foreach( $outcome_labels as $key => $value ):
				$last5s[ $team_id ][ $key ] = 0;
				$last10s[ $team_id ][ $key ] = 0;
			endforeach;

			// Initialize team totals
			$totals[ $team_id ] = array( 'eventsplayed' => 0, 'streak' => 0 );

			foreach ( $result_labels as $key => $value ):
				$totals[ $team_id ][ $key . 'for' ] = 0;
				$totals[ $team_id ][ $key . 'against' ] = 0;
			endforeach;

			foreach ( $outcome_labels as $key => $value ):
				$totals[ $team_id ][ $key ] = 0;
			endforeach;

			// Get static stats
			$static = get_post_meta( $team_id, 'sp_columns', true );

			// Add static stats to placeholders
			$placeholders[ $team_id ] = sp_array_value( $static, $div_id, array() );

		endforeach;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'order' => 'ASC',
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => $league_id
				),
				array(
					'taxonomy' => 'sp_season',
					'field' => 'id',
					'terms' => $div_id
				)
			)
		);
		$events = get_posts( $args );

		// Event loop
		foreach ( $events as $event ):

			$results = (array)get_post_meta( $event->ID, 'sp_results', true );

			foreach ( $results as $team_id => $team_result ):

				if ( ! in_array( $team_id, $team_ids ) )
					continue;

				foreach ( $team_result as $key => $value ):

					if ( $key == 'outcome' ):

						if ( ! is_array( $value ) ):
							$value = array( $value );
						endif;

						foreach ( $value as $outcome ):

							// Increment events played and outcome count
							if ( array_key_exists( $team_id, $totals ) && is_array( $totals[ $team_id ] ) && array_key_exists( $outcome, $totals[ $team_id ] ) ):
								$totals[ $team_id ]['eventsplayed']++;
								$totals[ $team_id ][ $outcome ]++;
							endif;

							if ( $outcome && $outcome != '-1' ):

								// Add to streak counter
								if ( $streaks[ $team_id ]['fire'] && ( $streaks[ $team_id ]['name'] == '' || $streaks[ $team_id ]['name'] == $outcome ) ):
									$streaks[ $team_id ]['name'] = $outcome;
									$streaks[ $team_id ]['count'] ++;
								else:
									$streaks[ $team_id ]['fire'] = 0;
								endif;

								// Add to last 5 counter if sum is less than 5
								if ( array_key_exists( $team_id, $last5s ) && array_key_exists( $outcome, $last5s[ $team_id ] ) && array_sum( $last5s[ $team_id ] ) < 5 ):
									$last5s[ $team_id ][ $outcome ] ++;
								endif;

								// Add to last 10 counter if sum is less than 10
								if ( array_key_exists( $team_id, $last10s ) && array_key_exists( $outcome, $last10s[ $team_id ] ) && array_sum( $last10s[ $team_id ] ) < 10 ):
									$last10s[ $team_id ][ $outcome ] ++;
								endif;

							endif;

						endforeach;

					else:
						if ( array_key_exists( $team_id, $totals ) && is_array( $totals[ $team_id ] ) && array_key_exists( $key . 'for', $totals[ $team_id ] ) ):
							$totals[ $team_id ][ $key . 'for' ] += $value;
							foreach( $results as $other_team_id => $other_result ):
								if ( $other_team_id != $team_id && array_key_exists( $key . 'against', $totals[ $team_id ] ) ):
									$totals[ $team_id ][ $key . 'against' ] += sp_array_value( $other_result, $key, 0 );
								endif;
							endforeach;
						endif;
					endif;

				endforeach;

			endforeach;

		endforeach;

		foreach ( $streaks as $team_id => $streak ):
			// Compile streaks counter and add to totals
			if ( $streak['name'] ):
				$args = array(
					'name' => $streak['name'],
					'post_type' => 'sp_outcome',
					'post_status' => 'publish',
					'posts_per_page' => 1
				);
				$outcomes = get_posts( $args );

				if ( $outcomes ):
					$outcome = reset( $outcomes );
					$totals[ $team_id ]['streak'] = $outcome->post_title . $streak['count'];
				else:
					$totals[ $team_id ]['streak'] = null;
				endif;
			else:
				$totals[ $team_id ]['streak'] = null;
			endif;
		endforeach;

		foreach ( $last5s as $team_id => $last5 ):
			// Add last 5 to totals
			$totals[ $team_id ]['last5'] = $last5;
		endforeach;

		foreach ( $last10s as $team_id => $last10 ):
			// Add last 10 to totals
			$totals[ $team_id ]['last10'] = $last10;
		endforeach;

		$args = array(
			'post_type' => 'sp_column',
			'numberposts' => -1,
			'posts_per_page' => -1,
	  		'orderby' => 'menu_order',
	  		'order' => 'ASC'
		);
		$stats = get_posts( $args );

		$columns = array();
		global $sportspress_column_priorities;
		$sportspress_column_priorities = array();

		foreach ( $stats as $stat ):

			// Get post meta
			$meta = get_post_meta( $stat->ID );

			// Add equation to object
			$stat->equation = sp_array_value( sp_array_value( $meta, 'sp_equation', array() ), 0, 0 );
			$stat->precision = sp_array_value( sp_array_value( $meta, 'sp_precision', array() ), 0, 0 );

			// Add column name to columns
			$columns[ $stat->post_name ] = $stat->post_title;

			// Add order to priorities if priority is set and does not exist in array already
			$priority = sp_array_value( sp_array_value( $meta, 'sp_priority', array() ), 0, 0 );
			if ( $priority && ! array_key_exists( $priority, $sportspress_column_priorities ) ):
				$sportspress_column_priorities[ $priority ] = array(
					'column' => $stat->post_name,
					'order' => sp_array_value( sp_array_value( $meta, 'sp_order', array() ), 0, 'DESC' )
				);
			endif;

		endforeach;

		// Sort priorities in descending order
		ksort( $sportspress_column_priorities );

		// Fill in empty placeholder values for each team
		foreach ( $team_ids as $team_id ):
			if ( ! $team_id )
				continue;

			foreach ( $stats as $stat ):
				if ( sp_array_value( $placeholders[ $team_id ], $stat->post_name, '' ) == '' ):

					// Solve
					$placeholder = sp_solve( $stat->equation, sp_array_value( $totals, $team_id, array() ), $stat->precision );

					// Adjustments
					$placeholder += sp_array_value( sp_array_value( $adjustments, $team_id, array() ), $stat->post_name, 0 );

					$placeholders[ $team_id ][ $stat->post_name ] = $placeholder;
				endif;
			endforeach;
		endforeach;

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $team_id => $team_data ):

			// Add team name to row
			$merged[ $team_id ] = array();

			$team_data['name'] = get_the_title( $team_id );

			foreach( $team_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $team_id, $tempdata ) && array_key_exists( $key, $tempdata[ $team_id ] ) && $tempdata[ $team_id ][ $key ] != '' ):
					$merged[ $team_id ][ $key ] = $tempdata[ $team_id ][ $key ];
				else:
					$merged[ $team_id ][ $key ] = $value;
				endif;

			endforeach;
		endforeach;

		uasort( $merged, 'sp_sort_table_teams' );

		// Rearrange data array to reflect values
		$data = array();
		foreach( $merged as $key => $value ):
			$data[ $key ] = $tempdata[ $key ];
		endforeach;
		
		if ( $breakdown ):
			return array( $columns, $usecolumns, $data, $placeholders, $merged );
		else:
			if ( ! is_array( $usecolumns ) )
				$usecolumns = array();
			foreach ( $columns as $key => $label ):
				if ( ! in_array( $key, $usecolumns ) ):
					unset( $columns[ $key ] );
				endif;
			endforeach;
			$labels = array_merge( array( 'name' => SP()->text->string('Team', 'team') ), $columns );
			$merged[0] = $labels;
			return $merged;
		endif;
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

if ( !function_exists( 'sp_get_player_list_data' ) ) {
	function sp_get_player_list_data( $post_id, $admin = false ) {
		$league_id = sp_get_the_term_id( $post_id, 'sp_league', 0 );
		$div_id = sp_get_the_term_id( $post_id, 'sp_season', 0 );
		$team_id = get_post_meta( $post_id, 'sp_team', true );
		$player_ids = (array)get_post_meta( $post_id, 'sp_player', false );
		$stats = (array)get_post_meta( $post_id, 'sp_players', true );
		$orderby = get_post_meta( $post_id, 'sp_orderby', true );
		$order = get_post_meta( $post_id, 'sp_order', true );
		$usecolumns = get_post_meta( $post_id, 'sp_columns', true );

		// Get labels from result variables
		$columns = (array)sp_get_var_labels( 'sp_performance' );

		// Get all leagues populated with stats where available
		$tempdata = sp_array_combine( $player_ids, $stats );

		// Create entry for each player in totals
		$totals = array();
		$placeholders = array();

		foreach ( $player_ids as $player_id ):
			if ( ! $player_id )
				continue;

			$totals[ $player_id ] = array( 'eventsattended' => 0, 'eventsplayed' => 0 );

			foreach ( $columns as $key => $value ):
				$totals[ $player_id ][ $key ] = 0;
			endforeach;

			// Get static performance
			$static = get_post_meta( $player_id, 'sp_performance', true );

			// Create placeholders entry for the player
			$placeholders[ $player_id ] = array( 'eventsplayed' => 0 );

			// Add static performance to placeholders
			if ( is_array( $static ) && array_key_exists( $league_id, $static ) && array_key_exists( $div_id, $static[ $league_id ] ) ):
				$placeholders[ $player_id ] = array_merge( $placeholders[ $player_id ], $static[ $league_id ][ $div_id ] );
			endif;
		endforeach;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => $league_id
				),
				array(
					'taxonomy' => 'sp_season',
					'field' => 'id',
					'terms' => $div_id
				),
			),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'sp_team',
					'compare' => 'EXISTS',
				),
			),
		);

		if ( $team_id ):
			$args['meta_query'] = array(
				array(
					'key' => 'sp_team',
					'value' => $team_id,
				),
			);
		endif;

		$events = get_posts( $args );

		// Event loop
		foreach( $events as $event ):

			$teams = (array)get_post_meta( $event->ID, 'sp_players', true );

			if ( $team_id ):

				if ( ! array_key_exists( $team_id, $teams ) )
					continue;

				$players = sp_array_value( $teams, $team_id, array() );

				foreach ( $players as $player_id => $player_performance ):

					if ( ! $player_id || ! in_array( $player_id, $player_ids ) )
						continue;

					// Increment events played
					if ( sp_array_value( $player_performance, 'status' ) != 'sub' || sp_array_value( $player_performance, 'sub', 0 ) ): 
						$totals[ $player_id ]['eventsplayed']++;
					endif;

					foreach ( $player_performance as $key => $value ):

						if ( array_key_exists( $key, $totals[ $player_id ] ) ):
							$totals[ $player_id ][ $key ] += $value;
						endif;

					endforeach;

				endforeach;

			else:

				foreach ( $teams as $players ):

					foreach ( $players as $player_id => $player_performance ):

						if ( ! $player_id || ! in_array( $player_id, $player_ids ) )
							continue;

						// Increment events played
						if ( sp_array_value( $player_performance, 'status' ) != 'sub' || sp_array_value( $player_performance, 'sub', 0 ) ): 
							$totals[ $player_id ]['eventsplayed']++;
						endif;

						foreach ( $player_performance as $key => $value ):

							if ( array_key_exists( $key, $totals[ $player_id ] ) ):
								$totals[ $player_id ][ $key ] += $value;
							endif;

						endforeach;

					endforeach;

				endforeach;

			endif;

		endforeach;

		$args = array(
			'post_type' => 'sp_performance',
			'numberposts' => -1,
			'posts_per_page' => -1,
	  		'orderby' => 'menu_order',
	  		'order' => 'ASC',
		);
		$performances = get_posts( $args );

		$columns = array( 'eventsplayed' => SP()->text->string('Played', 'player') );

		foreach ( $performances as $performance ):

			// Get post meta
			$meta = get_post_meta( $performance->ID );

			// Add equation to object
			$performance->equation = sp_array_value( sp_array_value( $meta, 'sp_equation', array() ), 0, 0 );

			// Add column name to columns
			$columns[ $performance->post_name ] = $performance->post_title;

		endforeach;

		// Fill in empty placeholder values for each player
		foreach ( $player_ids as $player_id ):

			if ( ! $player_id )
				continue;

			// Add events played as an object to performance for placeholder calculations
			$epstat = new stdClass();
			$epstat->post_name = 'eventsplayed';
			array_unshift( $performances, $epstat );

			foreach ( $performances as $performance ):
				if ( sp_array_value( $placeholders[ $player_id ], $performance->post_name, '' ) == '' ):

					if ( $performance->post_name == 'eventsplayed' ):
						$calculate = 'total';
					else:
						$calculate = get_post_meta( $performance->ID, 'sp_calculate', true );
					endif;

					if ( $calculate && $calculate == 'average' ):

						// Reflect average
						$eventsplayed = (int)sp_array_value( $totals[ $player_id ], 'eventsplayed', 0 );
						if ( ! $eventsplayed ):
							$placeholders[ $player_id ][ $performance->post_name ] = 0;
						else:
							$placeholders[ $player_id ][ $performance->post_name ] = sp_array_value( sp_array_value( $totals, $player_id, array() ), $performance->post_name, 0 ) / $eventsplayed;
						endif;

					else:

						// Reflect total
						$placeholders[ $player_id ][ $performance->post_name ] = sp_array_value( sp_array_value( $totals, $player_id, array() ), $performance->post_name, 0 );

					endif;

				endif;
			endforeach;
		endforeach;

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $player_id => $player_data ):

			// Add player name to row
			$merged[ $player_id ] = array( 'number' => get_post_meta( $player_id, 'sp_number', true ), 'name' => get_the_title( $player_id ), 'eventsplayed' => 0 );

			foreach( $player_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $player_id, $tempdata ) && array_key_exists( $key, $tempdata[ $player_id ] ) && $tempdata[ $player_id ][ $key ] != '' ):
					$merged[ $player_id ][ $key ] = $tempdata[ $player_id ][ $key ];
				else:
					$merged[ $player_id ][ $key ] = $value;
				endif;

			endforeach;
		endforeach;

		if ( $orderby != 'number' || $order != 'ASC' ):
			global $sportspress_performance_priorities;
			$sportspress_performance_priorities = array(
				array(
					'key' => $orderby,
					'order' => $order,
				),
			);
			uasort( $merged, 'sp_sort_list_players' );
		endif;

		// Rearrange data array to reflect performance
		$data = array();
		foreach( $merged as $key => $value ):
			$data[ $key ] = $tempdata[ $key ];
		endforeach;

		if ( $admin ):
			return array( $columns, $usecolumns, $data, $placeholders, $merged );
		else:
			if ( ! is_array( $usecolumns ) )
				$usecolumns = array();
			foreach ( $columns as $key => $label ):
				if ( ! in_array( $key, $usecolumns ) ):
					unset( $columns[ $key ] );
				endif;
			endforeach;
			$labels = array_merge( array( 'name' => SP()->text->string('Player', 'player') ), $columns );
			$merged[0] = $labels;
			return $merged;
		endif;
	}
}

if ( !function_exists( 'sp_get_player_roster_data' ) ) {
	function sp_get_player_roster_data( $post_id, $admin = false ) {
		$league_id = sp_get_the_term_id( $post_id, 'sp_league', 0 );
		$div_id = sp_get_the_term_id( $post_id, 'sp_season', 0 );
		$team_id = get_post_meta( $post_id, 'sp_team', true );
		$player_ids = (array)get_post_meta( $post_id, 'sp_player', false );
		$stats = (array)get_post_meta( $post_id, 'sp_players', true );
		$orderby = get_post_meta( $post_id, 'sp_orderby', true );
		$order = get_post_meta( $post_id, 'sp_order', true );

		// Get labels from result variables
		$columns = (array)sp_get_var_labels( 'sp_performance' );

		// Get all leagues populated with stats where available
		$tempdata = sp_array_combine( $player_ids, $stats );

		// Create entry for each player in totals
		$totals = array();
		$placeholders = array();

		foreach ( $player_ids as $player_id ):
			if ( ! $player_id )
				continue;

			$positions = get_the_terms( $player_id, 'sp_position' );
			$position_ids = array();
			foreach ( $positions as $position ):
				$position_ids[] = $position->term_id;
			endforeach;

			$totals[ $player_id ] = array( 'eventsattended' => 0, 'eventsplayed' => 0 );

			foreach ( $columns as $key => $value ):
				$totals[ $player_id ][ $key ] = 0;
			endforeach;

			// Get static performance
			$static = get_post_meta( $player_id, 'sp_performance', true );

			// Create placeholders entry for the player
			$placeholders[ $player_id ] = array( 'eventsplayed' => 0, 'positions' => $position_ids );

			// Add static performance to placeholders
			if ( is_array( $static ) && array_key_exists( $league_id, $static ) && array_key_exists( $div_id, $static[ $league_id ] ) ):
				$placeholders[ $player_id ] = array_merge( $placeholders[ $player_id ], $static[ $league_id ][ $div_id ] );
			endif;
		endforeach;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => $league_id
				),
				array(
					'taxonomy' => 'sp_season',
					'field' => 'id',
					'terms' => $div_id
				),
			),
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'sp_team',
					'compare' => 'EXISTS',
				),
			),
		);

		if ( $team_id ):
			$args['meta_query'] = array(
				array(
					'key' => 'sp_team',
					'value' => $team_id,
				),
			);
		endif;

		$events = get_posts( $args );

		// Event loop
		foreach( $events as $event ):

			$teams = (array)get_post_meta( $event->ID, 'sp_players', true );

			if ( $team_id ):

				if ( ! array_key_exists( $team_id, $teams ) )
					continue;

				$players = sp_array_value( $teams, $team_id, array() );

				foreach ( $players as $player_id => $player_performance ):

					if ( ! $player_id || ! in_array( $player_id, $player_ids ) )
						continue;

					// Increment events played
					if ( sp_array_value( $player_performance, 'status' ) != 'sub' || sp_array_value( $player_performance, 'sub', 0 ) ): 
						$totals[ $player_id ]['eventsplayed']++;
					endif;

					foreach ( $player_performance as $key => $value ):

						if ( array_key_exists( $key, $totals[ $player_id ] ) ):
							$totals[ $player_id ][ $key ] += $value;
						endif;

					endforeach;

				endforeach;

			else:

				foreach ( $teams as $players ):

					foreach ( $players as $player_id => $player_performance ):

						if ( ! $player_id || ! in_array( $player_id, $player_ids ) )
							continue;

						// Increment events played
						if ( sp_array_value( $player_performance, 'status' ) != 'sub' || sp_array_value( $player_performance, 'sub', 0 ) ): 
							$totals[ $player_id ]['eventsplayed']++;
						endif;

						foreach ( $player_performance as $key => $value ):

							if ( array_key_exists( $key, $totals[ $player_id ] ) ):
								$totals[ $player_id ][ $key ] += $value;
							endif;

						endforeach;

					endforeach;

				endforeach;

			endif;

		endforeach;

		$args = array(
			'post_type' => 'sp_performance',
			'numberposts' => -1,
			'posts_per_page' => -1,
	  		'orderby' => 'menu_order',
	  		'order' => 'ASC',
		);
		$performances = get_posts( $args );

		$columns = array( 'eventsplayed' => SP()->text->string('Played', 'player') );

		foreach ( $performances as $performance ):

			// Get post meta
			$meta = get_post_meta( $performance->ID );

			// Add equation to object
			$performance->equation = sp_array_value( sp_array_value( $meta, 'sp_equation', array() ), 0, 0 );

			// Add column name to columns
			$columns[ $performance->post_name ] = $performance->post_title;

		endforeach;

		// Fill in empty placeholder values for each player
		foreach ( $player_ids as $player_id ):

			if ( ! $player_id )
				continue;

			// Add events played as an object to performance for placeholder calculations
			$epstat = new stdClass();
			$epstat->post_name = 'eventsplayed';
			array_unshift( $performances, $epstat );

			foreach ( $performances as $performance ):
				if ( sp_array_value( $placeholders[ $player_id ], $performance->post_name, '' ) == '' ):

					if ( $performance->post_name == 'eventsplayed' ):
						$calculate = 'total';
					else:
						$calculate = get_post_meta( $performance->ID, 'sp_calculate', true );
					endif;

					if ( $calculate && $calculate == 'average' ):

						// Reflect average
						$eventsplayed = (int)sp_array_value( $totals[ $player_id ], 'eventsplayed', 0 );
						if ( ! $eventsplayed ):
							$placeholders[ $player_id ][ $performance->post_name ] = 0;
						else:
							$placeholders[ $player_id ][ $performance->post_name ] = sp_array_value( sp_array_value( $totals, $player_id, array() ), $performance->post_name, 0 ) / $eventsplayed;
						endif;

					else:

						// Reflect total
						$placeholders[ $player_id ][ $performance->post_name ] = sp_array_value( sp_array_value( $totals, $player_id, array() ), $performance->post_name, 0 );

					endif;

				endif;
			endforeach;
		endforeach;

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $player_id => $player_data ):

			// Add player name to row
			$merged[ $player_id ] = array( 'number' => get_post_meta( $player_id, 'sp_number', true ), 'name' => get_the_title( $player_id ), 'eventsplayed' => 0 );

			foreach( $player_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $player_id, $tempdata ) && array_key_exists( $key, $tempdata[ $player_id ] ) && $tempdata[ $player_id ][ $key ] != '' ):
					$merged[ $player_id ][ $key ] = $tempdata[ $player_id ][ $key ];
				else:
					$merged[ $player_id ][ $key ] = $value;
				endif;

			endforeach;
		endforeach;

		if ( $orderby != 'number' || $order != 'ASC' ):
			global $sportspress_performance_priorities;
			$sportspress_performance_priorities = array(
				array(
					'key' => $orderby,
					'order' => $order,
				),
			);
			uasort( $merged, 'sp_sort_list_players' );
		endif;

		// Rearrange data array to reflect performance
		$data = array();
		foreach( $merged as $key => $value ):
			$data[ $key ] = $tempdata[ $key ];
		endforeach;

		if ( $admin ):
			return array( $columns, $data, $placeholders, $merged );
		else:
			$labels = array_merge( array( 'name' => SP()->text->string('Player', 'player') ), $columns );
			$merged[0] = $labels;
			return $merged;
		endif;
	}
}

if ( !function_exists( 'sp_sort_list_players' ) ) {
	function sp_sort_list_players ( $a, $b ) {

		global $sportspress_performance_priorities;

		// Loop through priorities
		foreach( $sportspress_performance_priorities as $priority ):

			// Proceed if columns are not equal
			if ( sp_array_value( $a, $priority['key'], 0 ) != sp_array_value( $b, $priority['key'], 0 ) ):

				if ( $priority['key'] == 'name' ):

					$output = strcmp( sp_array_value( $a, 'name', null ), sp_array_value( $b, 'name', null ) );

				else:

					// Compare performance values
					$output = sp_array_value( $a, $priority['key'], 0 ) - sp_array_value( $b, $priority['key'], 0 );

				endif;

				// Flip value if descending order
				if ( $priority['order'] == 'DESC' ) $output = 0 - $output;

				return ( $output > 0 );

			endif;

		endforeach;

		// Default sort by number
		return sp_array_value( $a, 'number', 0 ) - sp_array_value( $b, 'number', 0 );
	}
}

if ( !function_exists( 'sp_get_player_metrics_data' ) ) {
	function sp_get_player_metrics_data( $post_id ) {

		$metrics = (array)get_post_meta( $post_id, 'sp_metrics', true );

		// Get labels from metric variables
		$metric_labels = (array)sp_get_var_labels( 'sp_metric' );

		$data = array();

		foreach( $metric_labels as $key => $value ):

			$metric = sp_array_value( $metrics, $key, null );
			if ( $metric == null )
				continue;

			$data[ $value ] = sp_array_value( $metrics, $key, '&nbsp;' );

		endforeach;

		return $data;
		
	}
}

if ( !function_exists( 'sp_get_player_performance_data' ) ) {
	function sp_get_player_performance_data( $post_id, $league_id, $admin = false ) {

		$seasons = (array)get_the_terms( $post_id, 'sp_season' );
		$positions = get_the_terms( $post_id, 'sp_position' );
		$stats = (array)get_post_meta( $post_id, 'sp_performance', true );
		$seasons_teams = sp_array_value( (array)get_post_meta( $post_id, 'sp_leagues', true ), $league_id, array() );

		$args = array(
			'post_type' => 'sp_performance',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);

		if ( $positions ):
			$position_ids = array();
			foreach( $positions as $position ):
				$position_ids[] = $position->term_id;
			endforeach;
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'sp_position',
					'field' => 'id',
					'terms' => $position_ids,
				),
			);
		endif;

		$performances = get_posts( $args );

		$performance_labels = array();
		$equations = array( 'eventsplayed' => 'total' );
		foreach ( $performances as $performance ):
			$performance_labels[ $performance->post_name ] = $performance->post_title;
			$equations[ $performance->post_name ] = get_post_meta( $performance->ID, 'sp_calculate', true );
		endforeach;
		$columns = array_merge( array( 'eventsplayed' => SP()->text->string('Played', 'player') ), $performance_labels );

		// Generate array of all season ids and season names
		$div_ids = array();
		$season_names = array();
		foreach ( $seasons as $season ):
			if ( is_object( $season ) && property_exists( $season, 'term_id' ) && property_exists( $season, 'name' ) ):
				$div_ids[] = $season->term_id;
				$season_names[ $season->term_id ] = $season->name;
			endif;
		endforeach;

		$tempdata = array();

		// Get all seasons populated with stats where available
		$tempdata = sp_array_combine( $div_ids, sp_array_value( $stats, $league_id, array() ) );

		foreach ( $div_ids as $div_id ):

			$team_id = sp_array_value( $seasons_teams, $div_id, '-1' );

			$totals = array( 'eventsattended' => 0, 'eventsplayed' => 0 );

			foreach ( $performance_labels as $key => $value ):
				$totals[ $key ] = 0;
			endforeach;
		
			$args = array(
				'post_type' => 'sp_event',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key' => 'sp_player',
						'value' => $post_id
					)
				),
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'sp_league',
						'field' => 'id',
						'terms' => $league_id
					),
					array(
						'taxonomy' => 'sp_season',
						'field' => 'id',
						'terms' => $div_id
					)
				)
			);
			$events = get_posts( $args );

			foreach( $events as $event ):
				$totals['eventsattended']++;
				$team_performance = (array)get_post_meta( $event->ID, 'sp_players', true );

				// Add all team performance
				foreach ( $team_performance as $players ):
					if ( array_key_exists( $post_id, $players ) ):
						$player_performance = sp_array_value( $players, $post_id, array() );
						if ( sp_array_value( $player_performance, 'status' ) != 'sub' || sp_array_value( $player_performance, 'sub', 0 ) ): 
							$totals['eventsplayed']++;
						endif;
						foreach ( $player_performance as $key => $value ):
							if ( array_key_exists( $key, $totals ) ):
								$totals[ $key ] += $value;
							endif;
						endforeach;
					endif;
				endforeach;
			endforeach;

			// Generate array of placeholder values for each league
			$placeholders[ $div_id ] = array();
			foreach ( $equations as $key => $value ):

				if ( $value == 'average' ):

					// Reflect average
					$eventsplayed = (int)sp_array_value( $totals, 'eventsplayed', 0 );
					if ( ! $eventsplayed ):
						$placeholders[ $div_id ][ $key ] = 0;
					else:
						$placeholders[ $div_id ][ $key ] = sp_array_value( $totals, $key, 0 ) / $eventsplayed;
					endif;

				else:

					// Reflect total
					$placeholders[ $div_id ][ $key ] = sp_array_value( $totals, $key, 0 );

				endif;

			endforeach;

		endforeach;

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $season_id => $season_data ):

			$team_id = sp_array_value( $seasons_teams, $season_id, array() );

			if ( ! $team_id || $team_id == '-1' )
				continue;

			$team_name = get_the_title( $team_id );
			$team_permalink = get_permalink( $team_id );

			$season_name = sp_array_value( $season_names, $season_id, '&nbsp;' );

			// Add season name to row
			$merged[ $season_id ] = array(
				'name' => $season_name,
				'team' => '<a href="' . $team_permalink . '">' . $team_name . '</a>'
			);

			foreach( $season_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $season_id, $tempdata ) && array_key_exists( $key, $tempdata[ $season_id ] ) && $tempdata[ $season_id ][ $key ] != '' ):
					$merged[ $season_id ][ $key ] = $tempdata[ $season_id ][ $key ];
				else:
					$merged[ $season_id ][ $key ] = $value;
				endif;

			endforeach;

		endforeach;

		if ( $admin ):
			return array( $columns, $tempdata, $placeholders, $merged, $seasons_teams );
		else:
			$labels = array_merge( array( 'name' => SP()->text->string('Season'), 'team' => SP()->text->string('Team', 'player'), 'eventsplayed' => SP()->text->string('Played', 'player') ), $columns );
			$merged[0] = $labels;
			return $merged;
		endif;

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
			$post = array_pop( $posts );
			return $post;
	}
}

if ( !function_exists( 'sp_delete_duplicate_post' ) ) {
	function sp_delete_duplicate_post( &$post ) {
		global $wpdb;

		$key = isset( $post['sp_key'] ) ? $post['sp_key'] : null;
		if ( ! $key ) $key = $post['post_title'];
		$id = sp_array_value( $post, 'post_ID', 'var' );
		$title = sp_get_eos_safe_slug( $key, $id );

		$check_sql = "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = %s AND ID != %d LIMIT 1";
		$post_name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $title, $post['post_type'], $id ) );

		if ( $post_name_check ):
			wp_delete_post( $post_name_check, true );
			$post['post_status'] = 'draft';
		endif;

		return $post_name_check;
	}
}

/**
 * Get an array of sport options and settings.
 * @return array
 */
function sp_get_sport_presets() {
	return apply_filters( 'sportspress_sports', array(
		'baseball' => array(
			'name' => __( 'Baseball', 'sportspress' ),
			'posts' => array(
				// Columns
				'sp_column' => array(
					array(
						'post_title' => 'W',
						'post_name' => 'w',
						'meta'       => array(
							'sp_equation'     => '$w',
							'sp_format'       => 'integer',
							'sp_precision'    => 0,
							'sp_priority'     => 1,
							'sp_order'        => 'DESC',
						),
					),
					array(
						'post_title' => 'L',
						'post_name' => 'l',
						'meta'       => array(
							'sp_equation'     => '$l',
							'sp_format'       => 'integer',
							'sp_precision'    => 0,
							'sp_priority'     => 2,
							'sp_order'        => 'ASC',
						),
					),
					array(
						'post_title' => 'Pct',
						'post_name' => 'pct',
						'meta'       => array(
							'sp_equation'     => '$w / $eventsplayed',
							'sp_format'       => 'decimal',
							'sp_precision'    => 2,
						),
					),
					array(
						'post_title' => 'RS',
						'post_name' => 'rs',
						'meta'       => array(
							'sp_equation'     => '$rfor',
							'sp_format'       => 'integer',
							'sp_precision'    => 0,
							'sp_priority'     => 3,
							'sp_order'        => 'DESC',
						),
					),
					array(
						'post_title' => 'RA',
						'post_name' => 'ra',
						'meta'       => array(
							'sp_equation'     => '$ragainst',
							'sp_format'       => 'integer',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'Strk',
						'post_name' => 'strk',
						'meta'       => array(
							'sp_equation'     => '$streak',
							'sp_format'       => 'integer',
							'sp_precision'    => 0,
						),
					),
				),
				// Statistics
				'sp_statistic' => array(
				),
				// Results
				'sp_result' => array(
					array(
						'post_title' => '1',
						'post_name' => 'first',
						'meta'       => array(
						),
					),
					array(
						'post_title' => '2',
						'post_name' => 'second',
						'meta'       => array(
						),
					),
					array(
						'post_title' => '3',
						'post_name' => 'third',
						'meta'       => array(
						),
					),
					array(
						'post_title' => '4',
						'post_name' => 'fourth',
						'meta'       => array(
						),
					),
					array(
						'post_title' => '5',
						'post_name' => 'fifth',
						'meta'       => array(
						),
					),
					array(
						'post_title' => '6',
						'post_name' => 'sixth',
						'meta'       => array(
						),
					),
					array(
						'post_title' => '7',
						'post_name' => 'seventh',
						'meta'       => array(
						),
					),
					array(
						'post_title' => '8',
						'post_name' => 'eighth',
						'meta'       => array(
						),
					),
					array(
						'post_title' => '9',
						'post_name' => 'ninth',
						'meta'       => array(
						),
					),
					array(
						'post_title' => '&nbsp;',
						'post_name' => 'extra',
						'meta'       => array(
						),
					),
					array(
						'post_title' => 'R',
						'post_name' => 'r',
						'meta'       => array(
						),
					),
					array(
						'post_title' => 'H',
						'post_name' => 'h',
						'meta'       => array(
						),
					),
					array(
						'post_title' => 'E',
						'post_name' => 'e',
						'meta'       => array(
						),
					),
				),
				// Outcomes
				'sp_outcome' => array(
					array(
						'post_title' => 'Win',
						'post_name' => 'w',
						'meta'       => array(
						),
					),
					array(
						'post_title' => 'Loss',
						'post_name' => 'l',
						'meta'       => array(
						),
					),
				),
			),
		),
		'basketball' => array(
			'name' => __( 'Basketball', 'sportspress' ),
			'terms' => array(
				// Positions
				'sp_position' => array(
					array(
						'name' => 'Point Guard',
						'slug' => 'pointguard',
					),
					array(
						'name' => 'Shooting Guard',
						'slug' => 'shootingguard',
					),
					array(
						'name' => 'Small Forward',
						'slug' => 'smallforward',
					),
					array(
						'name' => 'Power Forward',
						'slug' => 'powerforward',
					),
					array(
						'name' => 'Center',
						'slug' => 'center',
					),
				),
			),
			'posts' => array(
				// Results
				'sp_result' => array(
					array(
						'post_title' => '1',
						'post_name' => 'one',
					),
					array(
						'post_title' => '2',
						'post_name' => 'two',
					),
					array(
						'post_title' => '3',
						'post_name' => 'three',
					),
					array(
						'post_title' => '4',
						'post_name' => 'four',
					),
					array(
						'post_title' => 'OT',
						'post_name' => 'ot',
					),
					array(
						'post_title' => 'T',
						'post_name' => 't',
					),
				),
				// Outcomes
				'sp_outcome' => array(
					array(
						'post_title' => 'W',
						'post_name' => 'w',
					),
					array(
						'post_title' => 'L',
						'post_name' => 'l',
					),
				),
				// Table Columns
				'sp_column' => array(
					array(
						'post_title' => 'W',
						'post_name' => 'w',
						'meta' => array(
							'sp_equation' => '$w',
						),
					),
					array(
						'post_title' => 'L',
						'post_name' => 'l',
						'meta' => array(
							'sp_equation' => '$l',
						),
					),
					array(
						'post_title' => 'Pct',
						'post_name' => 'pct',
						'meta' => array(
							'sp_equation' => '$w / $eventsplayed * 100',
						),
					),
					array(
						'post_title' => 'GB',
						'post_name' => 'gb',
						'meta' => array(
							'sp_equation' => '( $wmax + $l - $w - $lmax ) / 2',
						),
					),
					array(
						'post_title' => 'L10',
						'post_name' => 'lten',
						'meta' => array(
							'sp_equation' => '$last10',
						),
					),
					array(
						'post_title' => 'Streak',
						'post_name' => 'streak',
						'meta' => array(
							'sp_equation' => '$streak',
						),
					),
					array(
						'post_title' => 'PF',
						'post_name' => 'pf',
						'meta' => array(
							'sp_equation' => '$tfor',
						),
					),
					array(
						'post_title' => 'PA',
						'post_name' => 'pa',
						'meta' => array(
							'sp_equation' => '$tagainst',
						),
					),
					array(
						'post_title' => 'DIFF',
						'post_name' => 'diff',
						'meta' => array(
							'sp_equation' => '$tfor - $tagainst',
						),
					),
				),
				// Player Metrics
				'sp_metric' => array(
					array(
						'post_title' => 'Height',
						'post_name' => 'height',
					),
					array(
						'post_title' => 'Weight',
						'post_name' => 'weight',
					),
					array(
						'post_title' => 'Experience',
						'post_name' => 'experience',
					),
				),
				// Player Statistics
				'sp_statistic' => array(
					array(
						'post_title' => 'MIN',
						'post_name' => 'min',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'FGM',
						'post_name' => 'fgm',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'FGA',
						'post_name' => 'fga',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => '3PM',
						'post_name' => '3pm',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => '3PA',
						'post_name' => '3pa',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'FTM',
						'post_name' => 'ftm',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'FTA',
						'post_name' => 'fta',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'OFF',
						'post_name' => 'off',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'DEF',
						'post_name' => 'def',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'REB',
						'post_name' => 'reb',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'AST',
						'post_name' => 'ast',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'STL',
						'post_name' => 'stl',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'BLK',
						'post_name' => 'blk',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'TO',
						'post_name' => 'to',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'PF',
						'post_name' => 'pf',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
					array(
						'post_title' => 'PTS',
						'post_name' => 'pts',
						'tax_input' => array(
							'sp_position' => array(
								'slug' => 'pointguard',
								'slug' => 'shootingguard',
								'slug' => 'smallforward',
								'slug' => 'powerforward',
								'slug' => 'center',
							),
						),
						'meta' => array(
							'sp_calculate' => 'average',
						),
					),
				),
			),
		),
		'cricket' => array(
			'name' => __( 'Cricket', 'sportspress' ),
			'posts' => array(
				// Table Columns
				'sp_column' => array(
					array(
						'post_title' => 'M',
						'post_name'  => 'm',
						'meta'       => array(
							'sp_equation'     => '$eventsplayed',
						),
					),
					array(
						'post_title' => 'W',
						'post_name'  => 'w',
						'meta'       => array(
							'sp_equation'     => '$w',
						),
					),
					array(
						'post_title' => 'L',
						'post_name'  => 'l',
						'meta'       => array(
							'sp_equation'     => '$l',
						),
					),
					array(
						'post_title' => 'T',
						'post_name'  => 't',
						'meta'       => array(
							'sp_equation'     => '$t',
						),
					),
					array(
						'post_title' => 'N/R',
						'post_name'  => 'nr',
						'meta'       => array(
							'sp_equation'     => '$nr',
						),
					),
					array(
						'post_title' => 'Pts',
						'post_name'  => 'pts',
						'meta'       => array(
							'sp_equation'     => '$w * 2 + $nr',
							'sp_priority'     => '1',
							'sp_order'        => 'DESC',
						),
					),
					array(
						'post_title' => 'RR',
						'post_name'  => 'rr',
						'meta'       => array(
							'sp_equation'     => '( $rfor / $oagainst ) - ( $ragainst / $ofor )',
						),
					),
				),
				// Statistics
				'sp_statistic' => array(
				),
				// Results
				'sp_result' => array(
				),
				// Outcomes
				'sp_outcome' => array(
				),
			),
		),
		'football' => array(
			'name' => __( 'American Football', 'sportspress' ),
			'terms' => array(
				// Positions
				'sp_position' => array(
					array(
						'name' => 'Quarterback',
						'slug' => 'quarterback',
					),
					array(
						'name' => 'Running Back',
						'slug' => 'runningback',
					),
					array(
						'name' => 'Wide Receiver',
						'slug' => 'widereceiver',
					),
					array(
						'name' => 'Tight End',
						'slug' => 'tightend',
					),
					array(
						'name' => 'Defensive Lineman',
						'slug' => 'defensivelineman',
					),
					array(
						'name' => 'Linebacker',
						'slug' => 'linebacker',
					),
					array(
						'name' => 'Defensive Back',
						'slug' => 'defensiveback',
					),
					array(
						'name' => 'Kickoff Kicker',
						'slug' => 'kickoffkicker',
					),
					array(
						'name' => 'Kick Returner',
						'slug' => 'kickreturner',
					),
					array(
						'name' => 'Punter',
						'slug' => 'punter',
					),
					array(
						'name' => 'Punt Returner',
						'slug' => 'puntreturner',
					),
					array(
						'name' => 'Field Goal Kicker',
						'slug' => 'fieldgoalkicker',
					),
				),
			),
			'posts' => array(
				// Results
				'sp_result' => array(
					array(
						'post_title' => '1',
						'post_name'  => 'one',
					),
					array(
						'post_title' => '2',
						'post_name'  => 'two',
					),
					array(
						'post_title' => '3',
						'post_name'  => 'three',
					),
					array(
						'post_title' => '4',
						'post_name'  => 'four',
					),
					array(
						'post_title' => 'TD',
						'post_name'  => 'td',
					),
					array(
						'post_title' => 'T',
						'post_name'  => 't',
					),
				),
				// Outcomes
				'sp_outcome' => array(
					array(
						'post_title' => 'Win',
						'post_name'  => 'w',
					),
					array(
						'post_title' => 'Loss',
						'post_name'  => 'l',
					),
					array(
						'post_title' => 'Tie',
						'post_name'  => 't',
					),
				),
				// Table Columns
				'sp_column' => array(
					array(
						'post_title' => 'W',
						'post_name' => 'w',
						'meta' => array(
							'sp_equation'     => '$w',
						),
					),
					array(
						'post_title' => 'L',
						'post_name' => 'l',
						'meta' => array(
							'sp_equation'     => '$l',
						),
					),
					array(
						'post_title' => 'T',
						'post_name' => 't',
						'meta' => array(
							'sp_equation'     => '$t',
						),
					),
					array(
						'post_title' => 'Pct',
						'post_name' => 'pct',
						'meta' => array(
							'sp_equation'     => '$w / $eventsplayed',
						),
					),
					array(
						'post_title' => 'PF',
						'post_name' => 'pf',
						'meta' => array(
							'sp_equation'     => '$tfor',
						),
					),
					array(
						'post_title' => 'PA',
						'post_name' => 'pa',
						'meta' => array(
							'sp_equation'     => '$tagainst',
						),
					),
					array(
						'post_title' => 'Net Pts',
						'post_name' => 'netpts',
						'meta' => array(
							'sp_equation'     => '$tfor - $tagainst',
						),
					),
					array(
						'post_title' => 'TD',
						'post_name' => 'td',
						'meta' => array(
							'sp_equation'     => '$td',
						),
					),
					array(
						'post_title' => 'Strk',
						'post_name' => 'strk',
						'meta' => array(
							'sp_equation'     => '$streak',
						),
					),
					array(
						'post_title' => 'Last 5',
						'post_name' => 'last5',
						'meta' => array(
							'sp_equation'     => '$last5',
						),
					),
				),
				// Player Metrics
				'sp_metric' => array(
					array(
						'post_title' => 'Height',
						'post_name'  => 'height',
					),
					array(
						'post_title' => 'Weight',
						'post_name'  => 'weight',
					),
				),
				// Player Statistics
				'sp_statistic' => array(
					array(
						'post_title' => 'Comp',
						'post_name' => 'comp',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
							),
						),
					),
					array(
						'post_title' => 'Att',
						'post_name' => 'att',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
							),
						),
					),
					array(
						'post_title' => 'Pct',
						'post_name' => 'pct',
						'meta' => array(
							'sp_calculate' => 'average',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'kickoffkicker',
							),
						),
					),
					array(
						'post_title' => 'Att/G',
						'post_name' => 'attg',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
							),
						),
					),
					array(
						'post_title' => 'Rec',
						'post_name' => 'rec',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'widereceiver',
								'tightend',
							),
						),
					),
					array(
						'post_title' => 'Comb',
						'post_name' => 'comb',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => 'Total',
						'post_name' => 'total',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => 'Ast',
						'post_name' => 'ast',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => 'Sck',
						'post_name' => 'scktackles',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => 'SFTY',
						'post_name' => 'sfty',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => 'PDef',
						'post_name' => 'pdef',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => 'TDs',
						'post_name' => 'tds',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => 'KO',
						'post_name' => 'ko',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickoffkicker',
							),
						),
					),
					array(
						'post_title' => 'Ret',
						'post_name' => 'ret',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'Punts',
						'post_name' => 'punts',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'punter',
							),
						),
					),
					array(
						'post_title' => 'Yds',
						'post_name' => 'yds',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
								'widereceiver',
								'tightend',
								'defensivelineman',
								'linebacker',
								'defensiveback',
								'kickoffkicker',
								'kickreturner',
								'punter',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'Net Yds',
						'post_name' => 'netyds',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'punter',
							),
						),
					),
					array(
						'post_title' => 'Avg',
						'post_name' => 'avg',
						'meta' => array(
							'sp_calculate' => 'average',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
								'widereceiver',
								'tightend',
								'kickoffkicker',
								'kickreturner',
								'punter',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'Net Avg',
						'post_name' => 'netavg',
						'meta' => array(
							'sp_calculate' => 'average',
						),
						'tax_input' => array(
							'sp_position' => array(
								'punter',
							),
						),
					),
					array(
						'post_title' => 'Blk',
						'post_name' => 'blk',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'punter',
							),
						),
					),
					array(
						'post_title' => 'OOB',
						'post_name' => 'oob',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickoffkicker',
								'punter',
							),
						),
					),
					array(
						'post_title' => 'Dn',
						'post_name' => 'dn',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'punter',
							),
						),
					),
					array(
						'post_title' => 'IN 20',
						'post_name' => 'in20',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'punter',
							),
						),
					),
					array(
						'post_title' => 'TB',
						'post_name' => 'tb',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'punter',
							),
						),
					),
					array(
						'post_title' => 'FC',
						'post_name' => 'fc',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickreturner',
								'punter',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'Ret',
						'post_name' => 'retpunt',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickoffkicker',
								'kickreturner',
								'punter',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'RetY',
						'post_name' => 'rety',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickreturner',
								'punter',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'Yds/G',
						'post_name' => 'ydsg',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
								'widereceiver',
								'tightend',
							),
						),
					),
					array(
						'post_title' => 'TD',
						'post_name' => 'TD',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
								'widereceiver',
								'tightend',
								'defensivelineman',
								'linebacker',
								'defensiveback',
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'Int',
						'post_name' => 'int',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => '1st',
						'post_name' => 'first',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
								'widereceiver',
								'tightend',
							),
						),
					),
					array(
						'post_title' => '1st%',
						'post_name' => 'firstpct',
						'meta' => array(
							'sp_calculate' => 'average',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
								'widereceiver',
								'tightend',
							),
						),
					),
					array(
						'post_title' => 'Lng',
						'post_name' => 'lng',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
								'widereceiver',
								'tightend',
								'defensivelineman',
								'linebacker',
								'defensiveback',
								'kickreturner',
								'punter',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => '20+',
						'post_name' => 'twentyplus',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
								'widereceiver',
								'tightend',
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => '40+',
						'post_name' => 'fourtyplus',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
								'runningback',
								'widereceiver',
								'tightend',
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'Sck',
						'post_name' => 'sck',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
							),
						),
					),
					array(
						'post_title' => 'Rate',
						'post_name' => 'rate',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'quarterback',
							),
						),
					),
					array(
						'post_title' => 'FUM',
						'post_name' => 'fum',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'runningback',
								'widereceiver',
								'tightend',
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'FF',
						'post_name' => 'ff',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => 'Rec',
						'post_name' => 'recfum',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'defensivelineman',
								'linebacker',
								'defensiveback',
							),
						),
					),
					array(
						'post_title' => 'TD',
						'post_name' => 'tdfum',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickoffkicker',
							),
						),
					),
					array(
						'post_title' => 'Avg',
						'post_name' => 'avgpunt',
						'meta' => array(
							'sp_calculate' => 'average',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickoffkicker',
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'Lng',
						'post_name' => 'lngpunt',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'TD',
						'post_name' => 'tdpunt',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickoffkicker',
								'kickreturner',
								'punter',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => '20+',
						'post_name' => 'twentypluspunt',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => '40+',
						'post_name' => 'fourtypluspunt',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'FC',
						'post_name' => 'fcpunt',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'FUM',
						'post_name' => 'fumpunt',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickreturner',
								'puntreturner',
							),
						),
					),
					array(
						'post_title' => 'OSK',
						'post_name' => 'osk',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickoffkicker',
							),
						),
					),
					array(
						'post_title' => 'OSKR',
						'post_name' => 'oskr',
						'meta' => array(
							'sp_calculate' => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'kickoffkicker',
							),
						),
					),
				),
			),
		),
		'footy' => array(
			'name' => __( 'Australian Rules Football', 'sportspress' ),
			'posts' => array(
				// Table Columns
				'sp_column' => array(
					array(
						'post_title' => 'P',
						'post_name' => 'p',
						'meta'       => array(
							'sp_equation'     => '$eventsplayed',
						)
					),
					array(
						'post_title' => 'W',
						'post_name' => 'w',
						'meta'       => array(
							'sp_equation'     => '$w',
						)
					),
					array(
						'post_title' => 'L',
						'post_name' => 'l',
						'meta'       => array(
							'sp_equation'     => '$l',
						)
					),
					array(
						'post_title' => 'D',
						'post_name' => 'd',
						'meta'       => array(
							'sp_equation'     => '$d',
						)
					),
					array(
						'post_title' => 'F',
						'post_name' => 'f',
						'meta'       => array(
							'sp_equation'     => '$ptsfor',
						)
					),
					array(
						'post_title' => 'A',
						'post_name' => 'a',
						'meta'       => array(
							'sp_equation'     => '$ptsagainst',
						)
					),
					array(
						'post_title' => 'Pct',
						'post_name' => 'pct',
						'meta'       => array(
							'sp_equation'     => '( $w / $eventsplayed ) * 10 * 10',
						)
					),
					array(
						'post_title' => 'Pts',
						'post_name' => 'pts',
						'meta'       => array(
							'sp_equation'     => '$pts',
						)
					)
				),
				// Statistics
				'sp_statistic' => array(
				),
				// Results
				'sp_result' => array(
				),
				// Outcomes
				'sp_outcome' => array(
				),
			),
		),
		'gaming' => array(
			'name' => __( 'Competitive Gaming', 'sportspress' ),
			'posts' => array(
				// Table Columns
				'sp_column' => array(
					array(
						'post_title' => 'W',
						'post_name' => 'w',
						'meta'       => array(
							'sp_equation'     => '$w',
						),
					),
					array(
						'post_title' => 'L',
						'post_name' => 'l',
						'meta'       => array(
							'sp_equation'     => '$l',
						),
					),
					array(
						'post_title' => 'Pct',
						'post_name' => 'pct',
						'meta'       => array(
							'sp_equation'     => '$w / $eventsplayed',
						),
					),
					array(
						'post_title' => 'Strk',
						'post_name' => 'strk',
						'meta'       => array(
							'sp_equation'     => '$strk',
						),
					),
					array(
						'post_title' => 'XP',
						'post_name' => 'xp',
						'meta'       => array(
							'sp_equation'     => '$xp',
						),
					),
					array(
						'post_title' => 'Rep',
						'post_name' => 'rep',
						'meta'       => array(
							'sp_equation'     => '$rep / $eventsplayed',
						),
					),
					array(
						'post_title' => 'Ping',
						'post_name' => 'ping',
						'meta'       => array(
							'sp_equation'     => '$ping / $eventsplayed',
						),
					),
				),
				// Statistics
				'sp_statistic' => array(
				),
				// Results
				'sp_result' => array(
				),
				// Outcomes
				'sp_outcome' => array(
				),
			),
		),
		'golf' => array(
			'name' => __( 'Golf', 'sportspress' ),
			'posts' => array(
				// Table Columns
				'sp_column' => array(
				),
				// Statistics
				'sp_statistic' => array(
					array(
						'post_title' => 'Events',
						'post_name' => 'events',
						'meta'       => array(
							'sp_equation'     => '$eventsplayed',
						),
					),
					array(
						'post_title' => 'Avg',
						'post_name' => 'avg',
						'meta'       => array(
							'sp_equation'     => '$ptsfor / $eventsplayed',
						),
					),
					array(
						'post_title' => 'Total',
						'post_name' => 'total',
						'meta'       => array(
							'sp_equation'     => '$ptsfor',
						),
					),
					array(
						'post_title' => 'PL',
						'post_name' => 'lost',
						'meta'       => array(
							'sp_equation'     => '$ptsagainst',
						),
					),
					array(
						'post_title' => 'PG',
						'post_name' => 'gained',
						'meta'       => array(
							'sp_equation'     => '$ptsfor',
						),
					),
				),
				// Results
				'sp_result' => array(
				),
				// Outcomes
				'sp_outcome' => array(
				),
			),
		),
		'hockey' => array(
			'name' => __( 'Hockey', 'sportspress' ),
			'posts' => array(
				// Table Columns
				'sp_column' => array(
					array(
						'post_title' => 'GP',
						'post_name'  => 'gp',
						'meta'       => array(
							'sp_equation'     => '$eventsplayed',
						),
					),
					array(
						'post_title' => 'W',
						'post_name'  => 'w',
						'meta'       => array(
							'sp_equation'     => '$w',
						),
					),
					array(
						'post_title' => 'L',
						'post_name'  => 'l',
						'meta'       => array(
							'sp_equation'     => '$l',
						),
					),
					array(
						'post_title' => 'OT',
						'post_name'  => 'ot',
						'meta'       => array(
							'sp_equation'     => '$ot',
						),
					),
					array(
						'post_title' => 'P',
						'post_name'  => 'p',
						'meta'       => array(
							'sp_equation'     => '$w * 2 + $ot',
						),
					),
					array(
						'post_title' => 'GF',
						'post_name'  => 'gf',
						'meta'       => array(
							'sp_equation'     => '$gfor',
						),
					),
					array(
						'post_title' => 'GA',
						'post_name'  => 'ga',
						'meta'       => array(
							'sp_equation'     => '$gagainst',
						),
					),
					array(
						'post_title' => 'Strk',
						'post_name'  => 'strk',
						'meta'       => array(
							'sp_equation'     => '$streak',
						),
					),
				),
				// Statistics
				'sp_statistic' => array(
				),
				// Results
				'sp_result' => array(
				),
				// Outcomes
				'sp_outcome' => array(
					array(
						'post_title' => 'Win',
						'post_name'  => 'w'
					),
					array(
						'post_title' => 'Loss',
						'post_name'  => 'l'
					),
					array(
						'post_title' => 'Overtime',
						'post_name'  => 'ot'
					),
				),
			),
		),
		'racing' => array(
			'name' => __( 'Racing', 'sportspress' ),
			'posts' => array(
				// Table Columns
				'sp_column' => array(
				),
				// Statistics
				'sp_statistic' => array(
					array(
						'post_title' => 'Pts',
						'post_name' => 'pts',
						'meta'       => array(
							'sp_equation'     => '$ptsfor',
						),
					),
					array(
						'post_title' => 'B',
						'post_name' => 'b',
						'meta'       => array(
							'sp_equation'     => '$ptsmax - $ptsfor',
						),
					),
					array(
						'post_title' => 'S',
						'post_name' => 's',
						'meta'       => array(
							'sp_equation'     => '$eventsplayed',
						),
					),
					array(
						'post_title' => 'W',
						'post_name' => 'w',
						'meta'       => array(
							'sp_equation'     => '$w',
							'sp_priority'     => '1',
							'sp_order'        => 'DESC',
						),
					),
					array(
						'post_title' => 'DNF',
						'post_name' => 'dnf',
						'meta'       => array(
							'sp_equation'     => '$dnf',
						),
					),
				),
				// Results
				'sp_result' => array(
				),
				// Outcomes
				'sp_outcome' => array(
				),
			),
		),
		'rugby' => array(
			'name' => __( 'Rugby', 'sportspress' ),
			'posts' => array(
				// Results
				'sp_result' => array(
					array(
						'post_title' => 'Points',
						'post_name'  => 'points',
					),
					array(
						'post_title' => 'Bonus',
						'post_name'  => 'bonus',
					),
				),
				// Outcomes
				'sp_outcome' => array(
					array(
						'post_title' => 'Win',
						'post_name'  => 'w',
					),
					array(
						'post_title' => 'Draw',
						'post_name'  => 'd',
					),
					array(
						'post_title' => 'Loss',
						'post_name'  => 'l',
					),
				),
				// Table Columns
				'sp_column' => array(
					array(
						'post_title' => 'P',
						'post_name'  => 'p',
						'meta'       => array(
							'sp_equation'     => '$eventsplayed',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'W',
						'post_name'  => 'w',
						'meta'       => array(
							'sp_equation'     => '$w',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'D',
						'post_name'  => 'd',
						'meta'       => array(
							'sp_equation'     => '$d',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'L',
						'post_name'  => 'l',
						'meta'       => array(
							'sp_equation'     => '$l',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'B',
						'post_name'  => 'b',
						'meta'       => array(
							'sp_equation'     => '$bonus',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'F',
						'post_name'  => 'f',
						'meta'       => array(
							'sp_equation'     => '$pointsfor',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'A',
						'post_name'  => 'a',
						'meta'       => array(
							'sp_equation'     => '$pointsagainst',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => '+/-',
						'post_name'  => 'pd',
						'meta'       => array(
							'sp_equation'     => '$pointsfor - $pointsagainst',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'Pts',
						'post_name'  => 'pts',
						'meta'       => array(
							'sp_equation'     => '( $w + $bonus ) * 2 + $d',
							'sp_precision'    => 0,
							'sp_priority'     => '1',
							'sp_order'        => 'DESC',
						),
					),
				),
				// Player Metrics
				'sp_metric' => array(
					array(
						'post_title' => 'Height',
						'post_name'  => 'height',
					),
					array(
						'post_title' => 'Weight',
						'post_name'  => 'weight',
					),
				),
				// Player Statistics
				'sp_statistic' => array(
					array(
						'post_title' => 'Points',
						'post_name'  => 'points',
						'meta'       => array(
							'sp_calculate'     => 'total',
						),
					),
					array(
						'post_title' => 'Tries',
						'post_name'  => 'tries',
						'meta'       => array(
							'sp_calculate'     => 'total',
						),
					),
					array(
						'post_title' => 'Conversions',
						'post_name'  => 'conversions',
						'meta'       => array(
							'sp_calculate'     => 'total',
						),
					),
					array(
						'post_title' => 'Penalty Goals',
						'post_name'  => 'penaltygoals',
						'meta'       => array(
							'sp_calculate'     => 'total',
						),
					),
					array(
						'post_title' => 'Drop Goals',
						'post_name'  => 'dropgoals',
						'meta'       => array(
							'sp_calculate'     => 'total',
						),
					),
				),
			),
		),
		'soccer' => array(
			'name' => __( 'Soccer (Association Football)', 'sportspress' ),
			'terms' => array(
				// Positions
				'sp_position' => array(
					array(
						'name' => 'Goalkeeper',
						'slug' => 'goalkeeper',
					),
					array(
						'name' => 'Defender',
						'slug' => 'defender',
					),
					array(
						'name' => 'Midfielder',
						'slug' => 'midfielder',
					),
					array(
						'name' => 'Forward',
						'slug' => 'forward',
					),
				),
			),
			'posts' => array(
				// Results
				'sp_result' => array(
					array(
						'post_title' => '1st Half',
						'post_name'  => 'firsthalf',
					),
					array(
						'post_title' => '2nd Half',
						'post_name'  => 'secondhalf',
					),
					array(
						'post_title' => 'Goals',
						'post_name'  => 'goals',
					),
				),
				// Outcomes
				'sp_outcome' => array(
					array(
						'post_title' => 'Win',
						'post_name'  => 'w',
					),
					array(
						'post_title' => 'Draw',
						'post_name'  => 'd',
					),
					array(
						'post_title' => 'Loss',
						'post_name'  => 'l',
					),
				),
				// Table Columns
				'sp_column' => array(
					array(
						'post_title' => 'P',
						'post_name'  => 'p',
						'meta'       => array(
							'sp_equation'     => '$eventsplayed',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'W',
						'post_name'  => 'w',
						'meta'       => array(
							'sp_equation'     => '$w',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'D',
						'post_name'  => 'd',
						'meta'       => array(
							'sp_equation'     => '$d',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'L',
						'post_name'  => 'l',
						'meta'       => array(
							'sp_equation'     => '$l',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'F',
						'post_name'  => 'f',
						'meta'       => array(
							'sp_equation'     => '$goalsfor',
							'sp_precision'    => 0,
							'sp_priority'     => '3',
							'sp_order'        => 'DESC',
						),
					),
					array(
						'post_title' => 'A',
						'post_name'  => 'a',
						'meta'       => array(
							'sp_equation'     => '$goalsagainst',
							'sp_precision'    => 0,
						),
					),
					array(
						'post_title' => 'GD',
						'post_name'  => 'gd',
						'meta'       => array(
							'sp_equation'     => '$goalsfor - $goalsagainst',
							'sp_precision'    => 0,
							'sp_priority'     => '2',
							'sp_order'        => 'DESC',
						),
					),
					array(
						'post_title' => 'Pts',
						'post_name'  => 'pts',
						'meta'       => array(
							'sp_equation'     => '$w * 3 + $d',
							'sp_precision'    => 0,
							'sp_priority'     => '1',
							'sp_order'        => 'DESC',
						),
					),
				),
				// Player Metrics
				'sp_metric' => array(
					array(
						'post_title' => 'Height',
						'post_name'  => 'height',
						'tax_input' => array(
							'sp_position' => array(
								'goalkeeper',
								'defender',
								'midfielder',
								'forward',
							),
						),
					),
					array(
						'post_title' => 'Weight',
						'post_name'  => 'weight',
						'tax_input' => array(
							'sp_position' => array(
								'goalkeeper',
								'defender',
								'midfielder',
								'forward',
							),
						),
					),
				),
				// Player Statistics
				'sp_statistic' => array(
					array(
						'post_title' => 'Goals',
						'post_name'  => 'goals',
						'meta'       => array(
							'sp_calculate'     => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'goalkeeper',
								'defender',
								'midfielder',
								'forward',
							),
						),
					),
					array(
						'post_title' => 'Assists',
						'post_name'  => 'assists',
						'meta'       => array(
							'sp_calculate'     => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'goalkeeper',
								'defender',
								'midfielder',
								'forward',
							),
						),
					),
					array(
						'post_title' => 'Yellow Cards',
						'post_name'  => 'yellowcards',
						'meta'       => array(
							'sp_calculate'     => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'goalkeeper',
								'defender',
								'midfielder',
								'forward',
							),
						),
					),
					array(
						'post_title' => 'Red Cards',
						'post_name'  => 'redcards',
						'meta'       => array(
							'sp_calculate'     => 'total',
						),
						'tax_input' => array(
							'sp_position' => array(
								'goalkeeper',
								'defender',
								'midfielder',
								'forward',
							),
						),
					),
				),
			),
		),
	));
}

function sp_get_sport_options() {
	$sports = sp_get_sport_presets();
	$options = array();
	foreach ( $sports as $slug => $data ):
		$options[ $slug ] = $data['name'];
	endforeach;
	return $options;
}

/**
 * Get an array of text options per context.
 * @return array
 */
function sp_get_text_options() {
	return apply_filters( 'sportspress_text', array(
		'general' => array(
			'league' => __( 'League', 'sportspress' ),
			'season' => __( 'Season', 'sportspress' ),
		),
		'event' => array(
			'event' => __( 'Event', 'sportspress' ),
			'date' => __( 'Date', 'sportspress' ),
			'time' => __( 'Time', 'sportspress' ),
			'results' => __( 'Results', 'sportspress' ),
			'team' => __( 'Team', 'sportspress' ),
			'teams' => __( 'Teams', 'sportspress' ),
			'details' => __( 'Details', 'sportspress' ),
			'venue' => __( 'Venue', 'sportspress' ),
			'player' => __( 'Player', 'sportspress' ),
			'substitutes' => __( 'Substitutes', 'sportspress' ),
			'total' => __( 'Total', 'sportspress' ),
			'article' => __( 'Article', 'sportspress' ),
			'preview' => __( 'Preview', 'sportspress' ),
			'recap' => __( 'Recap', 'sportspress' ),
			'view_all_events' => __( 'View all events', 'sportspress' ),
		),
		'team' => array(
			'team' => __( 'Team', 'sportspress' ),
			'teams' => __( 'Teams', 'sportspress' ),
			'pos' => __( 'Pos', 'sportspress' ),
			'view_full_table' => __( 'View full table', 'sportspress' ),
		),
		'player' => array(
			'player' => __( 'Player', 'sportspress' ),
			'position' => __( 'Position', 'sportspress' ),
			'nationality' => __( 'Nationality', 'sportspress' ),
			'current_team' => __( 'Current Team', 'sportspress' ),
			'past_teams' => __( 'Past Teams', 'sportspress' ),
			'rank' => __( 'Rank', 'sportspress' ),
			'played' => __( 'Played', 'sportspress' ),
			'view_all_players' => __( 'View all players', 'sportspress' ),
		),
		'staff' => array(
			'staff' => __( 'Staff', 'sportspress' ),
		),
	));
}

function sp_is_config_type( $typenow = null ) {
	if ( $typenow == null ) global $typenow;
	
	$post_types = array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_performance', 'sp_metric' );

	if ( in_array( $typenow, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_performance', 'sp_metric' ) ) )
		return true;
	return false;
}
