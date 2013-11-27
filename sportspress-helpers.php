<?php
if ( !function_exists( 'sp_get_array_depth' ) ) {
	function sp_get_array_depth( $array ) {
	    $max_depth = 1;
	    if ( is_array( $array ) ):
		    foreach ( $array as $value ):
		        if ( is_array( $value ) ):
		            $depth = sp_get_array_depth( $value ) + 1;
		            if ( $depth > $max_depth )
		                $max_depth = $depth;
		        endif;
		    endforeach;
	    	return $max_depth;
		else:
			return 0;
		endif;
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
		if ( is_array( $arr ) && array_key_exists( $key, $arr ) )
			$subset = $arr[ $key ];
		else
			$subset = $default;
		return $subset;
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

if ( !function_exists( 'sp_cpt_labels' ) ) {
	function sp_cpt_labels( $name, $singular_name, $lowercase_name = null, $is_submenu = false ) {
		if ( !$lowercase_name ) $lowercase_name = $name;
		$labels = array(
			'name' => $name,
			'singular_name' => $singular_name,
			'all_items' => $name,
			'add_new' => sprintf( __( 'Add %s', 'sportspress' ), $singular_name ),
			'add_new_item' => sprintf( __( 'Add New %s', 'sportspress' ), $singular_name ),
			'edit_item' => sprintf( __( 'Edit %s', 'sportspress' ), $singular_name ),
			'new_item' => sprintf( __( 'New %s', 'sportspress' ), $singular_name ),
			'view_item' => sprintf( __( 'View %s', 'sportspress' ), $singular_name ),
			'search_items' => sprintf( __( 'Search %s', 'sportspress' ), $name ),
			'not_found' => sprintf( __( 'No %s found', 'sportspress' ), $lowercase_name ),
			'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'sportspress' ), $lowercase_name ),
			'parent_item_colon' => sprintf( __( 'Parent %s', 'sportspress' ), $singular_name ) . ':'
		);
		return $labels;
	}
}

if ( !function_exists( 'sp_tax_labels' ) ) {
	function sp_tax_labels( $name, $singular_name, $lowercase_name = null ) {
		if ( !$lowercase_name ) $lowercase_name = $name;
		$labels = array(
			'name' => $name,
			'singular_name' => $singular_name,
			'all_items' => sprintf( __( 'All %s', 'sportspress' ), $name ),
			'edit_item' => sprintf( __( 'Edit %s', 'sportspress' ), $singular_name ),
			'view_item' => sprintf( __( 'View %s', 'sportspress' ), $singular_name ),
			'update_item' => sprintf( __( 'Update %s', 'sportspress' ), $singular_name ),
			'add_new_item' => sprintf( __( 'Add New %s', 'sportspress' ), $singular_name ),
			'new_item_name' => sprintf( __( 'New %s Name', 'sportspress' ), $singular_name ),
			'parent_item' => sprintf( __( 'Parent %s', 'sportspress' ), $singular_name ),
			'parent_item_colon' => sprintf( __( 'Parent %s', 'sportspress' ), $singular_name ) . ':',
			'search_items' =>  sprintf( __( 'Search %s', 'sportspress' ), $name ),
			'not_found' => sprintf( __( 'No %s found', 'sportspress' ), $lowercase_name )
		);
		return $labels;
	}
}

if ( !function_exists( 'sp_get_the_term_id' ) ) {
	function sp_get_the_term_id( $post_id, $taxonomy, $index ) {
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( is_array( $terms ) && array_key_exists( $index, $terms ) ):
			$term = $terms[0];
			if ( is_object( $term ) && property_exists( $term, 'term_id' ) )
				return $term->term_id;
			else
				return 0;
		else:
			return 0;
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
			'selected' => null
		);
		$args = array_merge( $defaults, $args ); 
		$terms = get_terms( $args['taxonomy'] );
		$name = ( $args['name'] ) ? $args['name'] : $args['taxonomy'];
		if ( $terms ) {
			printf( '<select name="%s" class="postform">', $name );
			if ( $args['show_option_all'] ) {
				printf( '<option value="0">%s</option>', $args['show_option_all'] );
			}
			if ( $args['show_option_none'] ) {
				printf( '<option value="-1">%s</option>', $args['show_option_none'] );
			}
			foreach ( $terms as $term ) {
				printf( '<option value="%s" %s>%s</option>', $term->term_id, selected( true, $args['selected'] == $term->term_id, false ), $term->name );
			}
			print( '</select>' );
		}
	}
}

if ( !function_exists( 'sp_the_posts' ) ) {
	function sp_the_posts( $post_id = null, $meta = 'post', $before = '', $sep = ', ', $after = '', $delimiter = ' - ' ) {
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
				if ( !empty( $before ) ):
					if ( is_array( $before ) && array_key_exists( $i, $before ) )
						echo $before[ $i ] . ' - ';
					else
						echo $before;
				endif;
				$parents = get_post_ancestors( $id );
				$parents = array_combine( array_keys( $parents ), array_reverse( array_values( $parents ) ) );
				foreach ( $parents as $parent ):
					if ( !in_array( $parent, $ids ) )
						edit_post_link( get_the_title( $parent ), '', '', $parent );
					echo $delimiter;
				endforeach;
				$title = get_the_title( $id );
				if ( empty( $title ) )
					$title = __( '(no title)', 'sportspress' );
				edit_post_link( $title, '', '', $id );
				if ( !empty( $after ) ):
					if ( is_array( $after ) ):
						if ( array_key_exists( $i, $after ) && $after[ $i ] != '' ):
							echo ' - ' . $after[ $i ];
						endif;
					else:
						echo $after;
					endif;
				endif;
				if ( ++$i !== $count )
					echo $sep;
			endforeach;
		endif;
	}
}

if ( !function_exists( 'sp_the_plain_terms' ) ) {
	function sp_the_plain_terms( $id, $taxonomy ) {
		$terms = get_the_terms( $id, $taxonomy );
		$arr = array();
		foreach( $terms as $term ):
			$arr[] = $term->name;
		endforeach;
		echo implode( ', ', $arr );
	}
}

if ( !function_exists( 'sp_post_checklist' ) ) {
	function sp_post_checklist( $post_id = null, $meta = 'post', $display = 'block', $filter = null, $index = null ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		?>
		<div id="<?php echo $meta; ?>-all" class="posttypediv wp-tab-panel sp-tab-panel" style="display: <?php echo $display; ?>;">
			<input type="hidden" value="0" name="<?php echo $meta; ?><?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" />
			<ul class="categorychecklist form-no-clear">
				<?php
				$selected = sp_array_between( (array)get_post_meta( $post_id, $meta, false ), 0, $index );
				$posts = get_pages( array( 'post_type' => $meta, 'number' => 0 ) );
				if ( empty( $posts ) )
					$posts = get_posts( array( 'post_type' => $meta, 'numberposts' => 0 ) );
				foreach ( $posts as $post ):
					$parents = get_post_ancestors( $post );
					if ( $filter ):
						$filter_values = (array)get_post_meta( $post->ID, $filter, false );
						$terms = (array)get_the_terms( $post->ID, 'sp_div' );
						foreach ( $terms as $term ):
							if ( is_object( $term ) && property_exists( $term, 'term_id' ) )
								$filter_values[] = $term->term_id;
						endforeach;
					endif;
					?>
					<li class="sp-post<?php
						if ( $filter ):
							echo ' sp-filter-0';
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
								$title = __( '(no title)' );
							echo $title;
							?>
						</label>
						<?php echo str_repeat( '</li></ul>', sizeof( $parents ) ); ?>
					</li>
					<?php
				endforeach;
				?>
			</ul>
		</div>
		<?php
	}
}

if ( !function_exists( 'sp_get_equation_optgroup_array' ) ) {
	function sp_get_equation_optgroup_array( $postid, $type = null, $variations = null, $defaults = null, $plain = true ) {
		$arr = array();

		// Get stats within the sports that the current stat is in ### TODO: should be for sport selected
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
				if ( $plain ) $arr[ '$' . $var->post_name ] = $var->post_title;
				foreach ( $variations as $key => $value ):
					$arr[ '$' . $var->post_name . '_' . $key ] = $var->post_title . ' ' . $value;
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

if ( !function_exists( 'sp_get_equation_selector' ) ) {
	function sp_get_equation_selector( $postid, $selected = null, $groups = array() ) {

		if ( ! isset( $postid ) )
			return;

		// Initialize options array
		$options = array();

		// Add groups to options
		foreach ( $groups as $group ):
			switch ( $group ):
				case 'event':
					$options[ __( 'Events', 'sportspress' ) ] = array( '$events_attended' => __( 'Attended', 'sportspress' ), '$events_played' => __( 'Played', 'sportspress' ) );
					break;
				case 'result':
					$options[ __( 'Results', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_result', array( 'for' => '&rarr;', 'against' => '&larr;' ), null, false );
					break;
				case 'outcome':
					$options[ __( 'Outcomes', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_outcome', array( 'max' => '&uarr;', 'min' => '&darr;' ) );
					break;
				case 'stat':
					$options[ __( 'Statistics', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_stat' );
					break;
				case 'metric':
					$options[ __( 'Metrics', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_metric' );
					break;
			endswitch;
		endforeach;

		// Create array of operators
		$operators = array( '+' => '&plus;', '-' => '&minus;', '*' => '&times;', '/' => '&divide;', '==' => '=', '!=' => '&ne;', '<' => '<', '<=' => '&le;', '>' => '>', '>=' => '&ge;', '(' => '(', ')' => ')' );

		// Add operators to options
		$options[ __( 'Operators', 'sportspress' ) ] = $operators;

		// Create array of constants
		$max = 10;
		$constants = array();
		for ( $i = 1; $i <= $max; $i ++ ):
			$constants[$i] = $i;
		endfor;

		// Add constants to options
		$options[ __( 'Constants', 'sportspress' ) ] = (array) $constants;

		?>
			<select name="sp_equation[]" data-remove-text="<?php _e( 'Remove', 'sportspress' ); ?>">
				<option value="">(<?php _e( 'Select', 'sportspress' ); ?>)</option>
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

if ( !function_exists( 'sp_get_eos_rows' ) ) {
	function sp_get_eos_rows( $raw ) {
		$raw = str_replace( array( "\r\n", ' ' ), array( "\n", '' ), $raw );
		$output = explode( "\n", $raw );
		return $output;
	}
}

if ( !function_exists( 'sp_get_eos_keys' ) ) {
	function sp_get_eos_keys( $raw ) {
		$raw = str_replace( array( "\r\n", ' :' ), array( "\n", ':' ), $raw );
		$arr = explode( "\n", $raw );
		$output = array();
		foreach ( $arr as $value ):
			$output[] = substr( $value, 0, strpos( $value, ':') );
		endforeach;
		return $output;
	}
}

if ( !function_exists( 'sp_get_stats_row' ) ) {
	function sp_get_stats_row( $post_id, $post_type = 'post', $args = array(), $static = false ) {
		$args = array_merge(
			array(
				'posts_per_page' => -1
			),
			(array)$args
		);
		$posts = (array)get_posts( $args );

		// Equation Operating System
		$eos = new eqEOS();

		$vars = array();

		$stats_settings = get_option( 'sportspress_stats' );

		// Get dynamic stats
		switch ( $post_type ):
			case 'sp_team':

				// All events attended by the team
				$vars['events_attended'] = $vars['events_played'] = sizeof( $posts );

				// Get result variables
				$args = array(
					'post_type' => 'sp_result',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC'
				);
				$results = (array)get_posts( $args );

				// Get outcome variables
				$args = array(
					'post_type' => 'sp_outcome',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC'
				);
				$outcomes = (array)get_posts( $args );

				// Initialize outcome variables
				foreach( $outcomes as $outcome ):
					$vars[ $outcome->post_name ] = 0;
					$vars[ $outcome->post_name . '_max' ] = 0;
					$vars[ $outcome->post_name . '_min' ] = 0;
				endforeach;

				// Populate each result variable
				foreach( $results as $result ):

					// Initialize and add for element to array
					if ( ! array_key_exists( $result->post_name, $vars . '_for' ) ):
						$vars[ $result->post_name . '_for' ] = 0;
					endif;

					// Initialize and add against element to array
					if ( ! array_key_exists( $result->post_name, $vars . '_against' ) ):
						$vars[ $result->post_name . '_against' ] = 0;
					endif;

					foreach( $posts as $event ):
						
						// Get match statistics
						$stats = get_post_meta( $event->ID, 'sp_stats', true );

						// Get value for the team in this match
						$value = (double) sp_array_value( $stats[ $post_id ][0], $result->post_name, 0 );

						// Add value for
						$vars[ $result->post_name . '_for' ] += $value;

						// Add values against
						foreach ( $stats as $team_post_id => $stat_array ):
							if ( $team_post_id != $post_id ):
								$vars[ $result->post_name . '_against' ] += sp_array_value( $stat_array[0], $result->post_name, 0 );
							endif;
						endforeach;

						// Calculate outcome
						// TODO

						// Check if max or min, and replace if it is
//						if ( $value > $vars[ $result->post_name . '_max' ] ) $vars[ $result->post_name . '_max' ] = $value;
//						elseif ( $value < $vars[ $result->post_name . '_min' ] ) $vars[ $result->post_name . '_min' ] = $value;

					endforeach;

				endforeach;

				// Get stats columns
				$args = array(
					'post_type' => 'sp_stat',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'orderby' => 'menu_order',
					'order' => 'ASC'
				);
				$columns = (array)get_posts( $args );

				break;

			case 'sp_player':

				// Get stats settings keys
				$keys = sp_get_eos_keys( get_option( 'sp_player_stats_columns' ) );

				// Add object properties needed for retreiving event stats
				foreach( $posts as $post ):
					$post->sp_player = get_post_meta( $post->ID, 'sp_team', false );
					$post->sp_player_index = array_search( $args['meta_query'][0]['value'], $post->sp_player );
				endforeach;

				// Create array of event stats columns
				$columns = sp_get_eos_rows( get_option( 'sp_event_stats_columns' ) );
				foreach ( $columns as $key => $value ):
					$row = explode( ':', $value );
					$var_name = strtolower( preg_replace( '~[^\p{L}]++~u', '', end( $row ) ) );
					$vars[ $var_name ] = 0;
					$stats_keys[ $key ] = $var_name;
				endforeach;

				// Populate columns with player stats from events
				foreach ( $posts as $post ):
					$team_stats = get_post_meta( $post->ID, 'sp_stats', true );
					foreach ( $team_stats as $team_id => $stat ):
						if ( array_key_exists( 1, $args['meta_query'] ) && $team_id != sp_array_value( $args['meta_query'][1], 'value', 0 ) ) continue;
						$player_id = sp_array_value( $args['meta_query'][0], 'value', 0 );
						if ( !array_key_exists( $player_id, $stat ) ) continue;
						foreach ( $stat[ $player_id ] as $key => $value ):
							if ( !array_key_exists( $key, $stats_keys ) || !array_key_exists( $stats_keys[ $key ], $vars ) ) continue;
							$vars[ $stats_keys[ $key ] ] += $value;
						endforeach;
					endforeach;
				endforeach;

				// Add appearances event count to vars
				$vars['appearances'] = sizeof( $posts );

				// Get EOS array
				$rows = sp_get_eos_rows( get_option( 'sp_player_stats_columns' ) );

				break;

			default:

				$columns = array();
				break;

		endswitch;

		// Get dynamic stats
		$dynamic = array();
		foreach ( $columns as $column ):
			$equation = get_post_meta( $column->ID, 'sp_equation', true );
			//$dynamic[ $column->post_name ] = $eos->solveIF( $equation, $vars );
		endforeach;

		echo '<pre>';
		print_r( $vars );
		echo '</pre>';

		if ( $static || true ):

			// Get static stats
			$static = (array)get_post_meta( $args['meta_query'][0]['value'], 'sp_stats', true );
			$table = sp_array_value( $static, 0, array() );
			if ( array_key_exists( 'tax_query',  $args ) )
				$row_id = $args['tax_query'][0]['terms'];
			else
				$row_id = 0;
			$static = sp_array_value( $table, $row_id, array() );

			// Combine static and dynamic stats
			$output = array_filter( $static ) + $dynamic;
			ksort( $output );

		else:

			$output = $dynamic;

		endif;

		return $output;
	}
}

if ( !function_exists( 'sp_stats_table' ) ) {
	function sp_stats_table( $stats = array(), $placeholders = array(), $index = 0, $columns = array(), $total = true, $rowtype = 'post', $slug = 'sp_stats' ) {
		global $pagenow;
		if ( !is_array( $stats ) )
			$stats = array( __( 'Name', 'sportspress' ) );
		?>
		<table class="widefat sp-stats-table">
			<thead>
				<tr>
					<?php foreach ( $columns as $column ): ?>
						<th><?php echo $column; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ( $stats as $key => $values ):
					if ( !$key ) continue;
					?>
					<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
						<td>
							<?php
							switch( $rowtype ):
								case 'post':
									$title = get_the_title( $key );
									break;
								default:
									$term = get_term( $key, $rowtype );
									$title = $term->name;
									break;
							endswitch;
							if ( empty( $title ) )
								$title = __( '(no title)' );
							echo $title;
							?>
						</td>
						<?php for ( $j = 0; $j < sizeof( $columns ) - 1; $j ++ ):
							$value = sp_array_value( $values, $j, '' );
							$placeholder = (int)sp_array_value( sp_array_value( $placeholders, $key, 0), $j, 0 );
							?>
							<td><input type="text" name="<?php echo $slug; ?>[<?php echo $index; ?>][<?php echo $key; ?>][]" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" /></td>
						<?php endfor; ?>
					</tr>
					<?php
					$i++;
				endforeach;
				if ( $total ):
					$values = sp_array_value( $stats, 0, array() );
					?>
					<tr class="sp-row sp-total<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
						<td><strong><?php _e( 'Total', 'sportspress' ); ?></strong></td>
						<?php for ( $j = 0; $j < sizeof( $columns ) - 1; $j ++ ):
							$value = sp_array_value( $values, $j, '' );
							?>
							<td><input type="text" name="<?php echo $slug; ?>[<?php echo $index; ?>][0][]" value="<?php echo $value; ?>" placeholder="0" /></td>
						<?php endfor; ?>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}
}

/*
if ( !function_exists( 'sp_team_stats_sport_choice' ) ) {
	function sp_team_stats_sport_choice( $selected = null ) {
		global $sportspress_sports;
		?>
			<select id="sp_team_stats_sport" name="sp_team_stats_sport">
				<?php foreach ( $sportspress_sports as $key => $value ): ?>
					<option value="<?php echo $key; ?>" data-sp-preset="<?php echo sp_array_value( $value, 'team' ); ?>"<?php if ( $selected == $key ) echo ' selected="selected"'; ?>><?php echo sp_array_value( $value, 'name' ); ?></option>
				<?php endforeach; ?>
			</select>
		<?php
	}
}
*/

if ( !function_exists( 'sp_post_adder' ) ) {
	function sp_post_adder( $meta = 'post' ) {
		$obj = get_post_type_object( $meta );
		?>
		<div id="<?php echo $meta; ?>-adder">
			<h4>
				<a title="<?php echo sprintf( esc_attr__( 'Add New %s', 'sportspress' ), esc_attr__( 'Team', 'sportspress' ) ); ?>" href="<?php echo admin_url( 'post-new.php?post_type=' . $meta ); ?>" target="_blank">
					+ <?php echo sprintf( __( 'Add New %s', 'sportspress' ), $obj->labels->singular_name ); ?>
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

if ( !function_exists( 'sportspress_render_option_field' ) ) {
	function sportspress_render_option_field( $group, $name, $type = 'text' ) {

		$options = get_option( $group );
		$value = '';
		if ( is_array( $options ) && array_key_exists( $name, $options ) ):
			$value = $options[ $name ];
		endif;

		switch ( $type ):
			case 'textarea':
				echo '<textarea id="' . $name . '" name="' . $group . '[' . $name . ']" rows="10" cols="50">' . $value . '</textarea>';
				break;
			case 'checkbox':
				echo '<input type="checkbox" id="' . $name . '" name="' . $group . '[' . $name . ']" value="1" ' . checked( 1, isset( $value ) ? $value : 0, false ) . '/>'; 
				break;
			default:
				echo '<input type="text" id="' . $name . '" name="' . $group . '[' . $name . ']" value="' . $value . '" />';
				break;
		endswitch;

	}
}
?>