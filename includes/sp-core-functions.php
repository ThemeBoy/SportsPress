<?php
/**
 * SportsPress Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include core functions
include( 'sp-conditional-functions.php' );
include( 'sp-formatting-functions.php' );
include( 'sp-deprecated-functions.php' );

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
	$template = apply_filters( 'sp_get_template_part', $template, $slug, $name );

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
				array( '/', '(', ')', '+', '-', '*', '$' ),
				array( '<code>&divide;</code>', '<code>(</code>', '<code>)</code>', '<code>&plus;</code>', '<code>&minus;</code>', '<code>&times;</code>', '' ),
				trim( $equation )
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
							<?php echo sp_draft_or_post_title( $post ); ?>
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

		if ( $equation == null )
			return '-';

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
			$post = array_pop( $posts );
			return $post;
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
						'post_name'  => 'yellospards',
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
	$strings = apply_filters( 'sportspress_text', array(
		'article' => __( 'Article', 'sportspress' ),
		'current_team' => __( 'Current Team', 'sportspress' ),
		'date' => __( 'Date', 'sportspress' ),
		'details' => __( 'Details', 'sportspress' ),
		'event' => __( 'Event', 'sportspress' ),
		'league' => __( 'League', 'sportspress' ),
		'nationality' => __( 'Nationality', 'sportspress' ),
		'past_teams' => __( 'Past Teams', 'sportspress' ),
		'played' => __( 'Played', 'sportspress' ),
		'player' => __( 'Player', 'sportspress' ),
		'pos' => __( 'Pos', 'sportspress' ),
		'position' => __( 'Position', 'sportspress' ),
		'preview' => __( 'Preview', 'sportspress' ),
		'rank' => __( 'Rank', 'sportspress' ),
		'recap' => __( 'Recap', 'sportspress' ),
		'results' => __( 'Team Results', 'sportspress' ),
		'season' => __( 'Season', 'sportspress' ),
		'staff' => __( 'Staff', 'sportspress' ),
		'substitutes' => __( 'Substitutes', 'sportspress' ),
		'team' => __( 'Team', 'sportspress' ),
		'teams' => __( 'Teams', 'sportspress' ),
		'time' => __( 'Time', 'sportspress' ),
		'timeresults' => __( 'Time/Results', 'sportspress' ),
		'total' => __( 'Total', 'sportspress' ),
		'venue' => __( 'Venue', 'sportspress' ),
		'view_all_events' => __( 'View all events', 'sportspress' ),
		'view_all_players' => __( 'View all players', 'sportspress' ),
		'view_full_table' => __( 'View full table', 'sportspress' ),
	));
	asort( $strings );
	return $strings;
}
