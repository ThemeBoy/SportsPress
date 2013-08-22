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

if ( !function_exists( 'sp_num_to_letter' ) ) {
	function sp_num_to_letter( $num, $uppercase = false ) {
		$num -= 0;
		$letter = 	chr( ( $num % 26 ) + 97 );
		$letter .= 	( floor( $num / 26 ) > 0 ) ? str_repeat( $letter, floor( $num / 26 ) ) : '';
		return 		( $uppercase ? strtoupper( $letter ) : $letter );
	}
}

if ( !function_exists( 'sp_cpt_labels' ) ) {
	function sp_cpt_labels( $name, $singular_name, $lowercase_name = null ) {
		if ( !$lowercase_name ) $lowercase_name = $name;
		$labels = array(
			'name' => $name,
			'singular_name' => $singular_name,
			'all_items' => sprintf( __( 'All %s', 'sportspress' ), $name ),
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
					$title = __( '(no title)' );
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
						$terms = (array)get_the_terms( $post->ID, 'sp_league' );
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

if ( !function_exists( 'sp_get_eos_array' ) ) {
	function sp_get_eos_array( $raw ) {
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
	function sp_get_stats_row( $post_type = 'post', $args = array(), $static = false ) {
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
		switch ($post_type):
			case 'sp_team':

				// Add object properties needed for retreiving event stats
				foreach( $posts as $post ):
					$post->sp_team = get_post_meta( $post->ID, 'sp_team', false );
					$post->sp_team_index = array_search( $args['meta_query'][0]['value'], $post->sp_team );
					$post->sp_result = get_post_meta( $post->ID, 'sp_result', false );
				endforeach;

				// Get team stats from all attended events
				$vars['appearances'] = sizeof( $posts );
				$vars['wins'] = sizeof( array_filter( $posts, function( $post ) { return array_count_values( $post->sp_result ) > 1 && max( $post->sp_result ) == $post->sp_result[ $post->sp_team_index ];	} ) );
				$vars['ties'] = sizeof( array_filter( $posts, function( $post ) { return array_count_values( $post->sp_result ) == 1; } ) );
				$vars['losses'] = sizeof( array_filter( $posts, function( $post ) { return array_count_values( $post->sp_result ) > 1 && min( $post->sp_result ) == $post->sp_result[ $post->sp_team_index ]; } ) );
				$vars['for'] = 0; foreach( $posts as $post ): $vars['for'] += $post->sp_result[ $post->sp_team_index ]; endforeach;
				$vars['against'] = 0; foreach( $posts as $post ): $result = $post->sp_result; unset( $result[ $post->sp_team_index ] ); $vars['against'] += array_sum( $result ); endforeach;
				
				// Get EOS array
				$rows = sp_get_eos_array( $stats_settings['team'] );
				break;

			case 'sp_player':

				// Create array of event stats columns
				$columns = sp_get_eos_array( get_option( 'sp_event_stats_columns' ) );
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
				$rows = sp_get_eos_array( get_option( 'sp_player_stats_columns' ) );
				break;

			default:

				$rows = array();
				break;

		endswitch;

		// Get dynamic stats
		$dynamic = array();
		foreach ( $rows as $key => $value ):
			$row = explode( ':', $value );
			$dynamic[ $key ] = $eos->solveIF( sp_array_value( $row, 1, '$appearances'), $vars );
		endforeach;

		if ( $static ):

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
				echo '<textarea id="' . $group . '" name="' . $group . '[' . $name . ']" rows="10" cols="50">' . $value . '</textarea>';
				break;
			default:
				echo '<input type="text" id="' . $name . '" name="' . $group . '[' . $name . ']" value="' . $value . '" />';
				break;
		endswitch;

	}
}
?>