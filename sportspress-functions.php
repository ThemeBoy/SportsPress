<?php
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
	function sp_cpt_labels( $name, $singular_name, $lowercase_name = null ) {
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
			'selected' => null,
			'value' => 'slug'
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
				if ( $args['value'] == 'term_id' )
					printf( '<option value="%s" %s>%s</option>', $term->term_id, selected( true, $args['selected'] == $term->term_id, false ), $term->name );
				else
					printf( '<option value="%s" %s>%s</option>', $term->slug, selected( true, $args['selected'] == $term->slug, false ), $term->name );
			}
			print( '</select>' );
		}
	}
}

if ( !function_exists( 'sp_dropdown_pages' ) ) {
	function sp_dropdown_pages( $args = array() ) {
		$defaults = array(
			'show_option_all' => false,
			'show_option_none' => false,
			'name' => 'page_id',
			'selected' => null,
			'numberposts' => -1,
			'posts_per_page' => -1,
			'child_of' => 0,
			'sort_order' => 'ASC',
		    'sort_column'  => 'post_title',
		    'hierarchical' => 1,
		    'exclude'      => null,
		    'include'      => null,
		    'meta_key'     => null,
		    'meta_value'   => null,
		    'authors'      => null,
		    'exclude_tree' => null,
		    'post_type' => 'page'
		);
		$args = array_merge( $defaults, $args );
		$name = $args['name'];
		unset( $args['name'] );
		$posts = get_posts( $args );
		if ( $posts ) {
			printf( '<select name="%s" class="postform">', $name );
			if ( $args['show_option_all'] ) {
				printf( '<option value="0">%s</option>', $args['show_option_all'] );
			}
			if ( $args['show_option_none'] ) {
				printf( '<option value="-1">%s</option>', $args['show_option_none'] );
			}
			foreach ( $posts as $post ) {
				printf( '<option value="%s" %s>%s</option>', $post->post_name, selected( true, $args['selected'] == $post->post_name, false ), $post->post_title );
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

if ( !function_exists( 'sp_get_equation_selector' ) ) {
	function sp_get_equation_selector( $postid, $selected = null, $groups = array() ) {

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
					$options[ __( 'Outcomes', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_outcome', array( 'max' => '&uarr;', 'min' => '&darr;' ) );
					break;
				case 'column':
					$options[ __( 'Columns', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_column' );
					break;
				case 'statistic':
					$options[ __( 'Statistics', 'sportspress' ) ] = sp_get_equation_optgroup_array( $postid, 'sp_statistic' );
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

if ( !function_exists( 'sp_get_var_labels' ) ) {
	function sp_get_var_labels( $post_type, $independent = false ) {
		$args = array(
			'post_type' => $post_type,
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
		if ( $independent ):
			$args['meta_query'] = array(
				array(
					'key' => 'sp_equation',
					'value'=>''
				)
			);
		endif;

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
			$output[ $var->post_name ] = $equation;
		endforeach;

		return $output;
	}
}

if ( !function_exists( 'sp_league_table' ) ) {
	function sp_league_table( $columns = array(), $data = array(), $placeholders = array() ) {
		?>
		<table class="widefat sp-data-table">
			<thead>
				<tr>
					<th><?php _e( 'Team', 'sportspress' ); ?></th>
					<?php foreach ( $columns as $label ): ?>
						<th><?php echo $label; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ( $data as $team_id => $team_stats ):
					if ( !$team_id ) continue;
					$div = get_term( $team_id, 'sp_league' );
					?>
					<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
						<td>
							<?php echo get_the_title( $team_id ); ?>
						</td>
						<?php foreach( $columns as $column => $label ):
							$value = sp_array_value( $team_stats, $column, '' );
							$placeholder = sp_array_value( sp_array_value( $placeholders, $team_id, array() ), $column, 0 );
							?>
							<td><input type="text" name="sp_teams[<?php echo $team_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" /></td>
						<?php endforeach; ?>
					</tr>
					<?php
					$i++;
				endforeach;
				?>
			</tbody>
		</table>
		<?php
	}
}

if ( !function_exists( 'sp_player_table' ) ) {
	function sp_player_table( $columns = array(), $data = array(), $placeholders = array() ) {
		?>
		<table class="widefat sp-data-table">
			<thead>
				<tr>
					<th><?php _e( 'Player', 'sportspress' ); ?></th>
					<?php foreach ( $columns as $label ): ?>
						<th><?php echo $label; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ( $data as $player_id => $player_stats ):
					if ( !$player_id ) continue;
					$div = get_term( $player_id, 'sp_league' );
					?>
					<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
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
				?>
			</tbody>
		</table>
		<?php
	}
}

if ( !function_exists( 'sp_team_columns_table' ) ) {
	function sp_team_columns_table( $columns = array(), $data = array(), $placeholders = array() ) {
		?>
		<table class="widefat sp-data-table">
			<thead>
				<tr>
					<th><?php _e( 'League', 'sportspress' ); ?></th>
					<?php foreach ( $columns as $label ): ?>
						<th><?php echo $label; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				if ( empty( $data ) ):
					?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td><strong><?php printf( __( 'Select %s', 'sportspress' ), __( 'League', 'sportspress' ) ); ?></strong></td>
						</tr>
					<?php
				else:
					foreach ( $data as $div_id => $div_stats ):
						if ( !$div_id ) continue;
						$div = get_term( $div_id, 'sp_league' );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<?php echo $div->name; ?>
							</td>
							<?php foreach( $columns as $column => $label ):
								$value = sp_array_value( $div_stats, $column, '' );
								$placeholder = sp_array_value( sp_array_value( $placeholders, $div_id, array() ), $column, 0 );
								?>
								<td><input type="text" name="sp_columns[<?php echo $div_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" /></td>
							<?php endforeach; ?>
						</tr>
						<?php
						$i++;
					endforeach;
				endif;
				?>
			</tbody>
		</table>
		<?php
	}
}

if ( !function_exists( 'sp_player_statistics_table' ) ) {
	function sp_player_statistics_table( $columns = array(), $data = array(), $placeholders = array() ) {
		?>
		<table class="widefat sp-data-table">
			<thead>
				<tr>
					<th><?php _e( 'League', 'sportspress' ); ?></th>
					<?php foreach ( $columns as $label ): ?>
						<th><?php echo $label; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ( $data as $team_id => $team_stats ):
					if ( empty( $team_stats ) ):
						?>
							<td><strong><?php printf( __( 'Select %s', 'sportspress' ), __( 'Team', 'sportspress' ) ); ?></strong></td>
						<?php
						continue;
					endif;
					foreach ( $team_stats as $div_id => $div_stats ):
						if ( !$div_id ) continue;
						$div = get_term( $div_id, 'sp_league' );
						?>
						<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
							<td>
								<?php echo $div->name; ?>
							</td>
							<?php foreach( $columns as $column => $label ):
								$value = sp_array_value( $div_stats, $column, '' );
								$placeholder = sp_array_value( sp_array_value( sp_array_value( $placeholders, $team_id, array() ), $div_id, array() ), $column, 0 );
								?>
								<td><input type="text" name="sp_statistics[<?php echo $team_id; ?>][<?php echo $div_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" /></td>
							<?php endforeach; ?>
						</tr>
						<?php
						$i++;
					endforeach;
				endforeach;
				?>
			</tbody>
		</table>
		<?php
	}
}

if ( !function_exists( 'sp_event_results_table' ) ) {
	function sp_event_results_table( $columns = array(), $data = array() ) {
		?>
		<table class="widefat sp-data-table">
			<thead>
				<tr>
					<th><?php _e( 'Team', 'sportspress' ); ?></th>
					<?php foreach ( $columns as $label ): ?>
						<th><?php echo $label; ?></th>
					<?php endforeach; ?>
					<th><?php _e( 'Outcome', 'sportspress' ); ?></th>
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
							$value = sp_array_value( $team_results, 'outcome', '' );
							$args = array(
								'post_type' => 'sp_outcome',
								'name' => 'sp_results[' . $team_id . '][outcome]',
								'show_option_none' => __( '-- Not set --', 'sportspress' ),
								'option_none_value' => 0,
							    'sort_order'   => 'ASC',
							    'sort_column'  => 'menu_order',
								'selected' => $value
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
		<?php
	}
}

if ( !function_exists( 'sp_event_players_table' ) ) {
	function sp_event_players_table( $columns = array(), $data = array(), $team_id ) {
		?>
		<table class="widefat sp-data-table">
			<thead>
				<tr>
					<th><?php _e( 'Player', 'sportspress' ); ?></th>
					<?php foreach ( $columns as $label ): ?>
						<th><?php echo $label; ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ( $data as $player_id => $player_statistics ):
					if ( !$player_id ) continue;
					?>
					<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
						<td>
							<?php echo get_the_title( $player_id ); ?>
						</td>
						<?php foreach( $columns as $column => $label ):
							$value = sp_array_value( $player_statistics, $column, '' );							
							?>
							<td><input type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="0" /></td>
						<?php endforeach; ?>
					</tr>
					<?php
					$i++;
				endforeach;
				?>
				<tr class="sp-row sp-total<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
					<td><strong><?php _e( 'Total', 'sportspress' ); ?></strong></td>
					<?php foreach( $columns as $column => $label ):
						$player_id = 0;
						$player_statistics = $data[0];
						$value = sp_array_value( $player_statistics, $column, '' );
						?>
						<td><input type="text" name="sp_players[<?php echo $team_id; ?>][<?php echo $player_id; ?>][<?php echo $column; ?>]" value="<?php echo $value; ?>" placeholder="0" /></td>
					<?php endforeach; ?>
				</tr>
			</tbody>
		</table>
		<?php
	}
}

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

if ( !function_exists( 'sp_solve' ) ) {
	function sp_solve( $equation, $vars ) {

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
			$eos = new eqEOS();

			// Solve using EOS
			return $eos->solveIF( str_replace( ' ', '', $equation ), $vars );
		else:
			return 0;
		endif;

	}

}

if ( !function_exists( 'sp_get_table' ) ) {
	function sp_get_table( $post_id, $breakdown = false ) {
		$div_id = sp_get_the_term_id( $post_id, 'sp_league', 0 );
		$team_ids = (array)get_post_meta( $post_id, 'sp_team', false );
		$table_stats = (array)get_post_meta( $post_id, 'sp_teams', true );

		// Get labels from result variables
		$result_labels = (array)sp_get_var_labels( 'sp_result' );

		// Get labels from outcome variables
		$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

		// Get all leagues populated with stats where available
		$tempdata = sp_array_combine( $team_ids, $table_stats );

		// Create entry for each team in totals
		$totals = array();
		$placeholders = array();

		foreach ( $team_ids as $team_id ):
			if ( ! $team_id )
				continue;

			$totals[ $team_id ] = array( 'eventsplayed' => 0 );

			foreach ( $result_labels as $key => $value ):
				$totals[ $team_id ][ $key . 'for' ] = 0;
				$totals[ $team_id ][ $key . 'against' ] = 0;
			endforeach;

			foreach ( $outcome_labels as $key => $value ):
				$totals[ $team_id ][ $key ] = 0;
			endforeach;

			// Get static stats
			$static = get_post_meta( $team_id, 'sp_columns', true );

			// Create placeholders entry for the team
			$placeholders[ $team_id ] = array();

			// Add static stats to placeholders
			if ( array_key_exists( $div_id, $static ) ):
				$placeholders[ $team_id ] = $static[ $div_id ];
			endif;

		endforeach;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => $div_id
				)
			)
		);
		$events = get_posts( $args );

		// Event loop
		foreach( $events as $event ):

			$results = (array)get_post_meta( $event->ID, 'sp_results', true );

			foreach ( $results as $team_id => $team_result ):

				// Increment events played
				$totals[ $team_id ]['eventsplayed']++;

				foreach ( $team_result as $key => $value ):

					if ( $key == 'outcome' ):
						if ( array_key_exists( $value, $totals[ $team_id ] ) ):
							$totals[ $team_id ][ $value ]++;
						endif;
					else:
						if ( array_key_exists( $key . 'for', $totals[ $team_id ] ) ):
							$totals[ $team_id ][ $key . 'for' ] += $value;
						endif;
					endif;

				endforeach;

			endforeach;

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
		$priorities = array();

		foreach ( $stats as $stat ):

			// Get post meta
			$meta = get_post_meta( $stat->ID );

			// Add equation to object
			$stat->equation = sp_array_value( sp_array_value( $meta, 'sp_equation', array() ), 0, 0 );

			// Add column name to columns
			$columns[ $stat->post_name ] = $stat->post_title;

			// Add order to priorities if priority is set and does not exist in array already
			$priority = sp_array_value( sp_array_value( $meta, 'sp_priority', array() ), 0, 0 );
			if ( $priority && ! array_key_exists( $priorities, $priority ) ):
				$priorities[ $priority ] = array(
					'column' => $stat->post_name,
					'order' => sp_array_value( sp_array_value( $meta, 'sp_order', array() ), 0, 'DESC' )
				);
			endif;

		endforeach;

		// Sort priorities in descending order
		ksort( $priorities );

		// Fill in empty placeholder values for each team
		foreach ( $team_ids as $team_id ):
			if ( ! $team_id )
				continue;

			foreach ( $stats as $stat ):
				if ( sp_array_value( $placeholders[ $team_id ], $stat->post_name, '' ) == '' ):
					if ( sizeof( $events ) > 0 ):
						$placeholders[ $team_id ][ $stat->post_name ] = sp_solve( $stat->equation, sp_array_value( $totals, $team_id, array() ) );
					else:
						$placeholders[ $team_id ][ $stat->post_name ] = 0;
					endif;
				endif;
			endforeach;
		endforeach;

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $team_id => $team_data ):

			// Add team name to row
			$merged[ $team_id ] = array( 'name' => get_the_title( $team_id ) );

			foreach( $team_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $team_id, $tempdata ) && array_key_exists( $key, $tempdata[ $team_id ] ) && $tempdata[ $team_id ][ $key ] != '' ):
					$merged[ $team_id ][ $key ] = $tempdata[ $team_id ][ $key ];
				else:
					$merged[ $team_id ][ $key ] = $value;
				endif;

			endforeach;
		endforeach;

		uasort( $merged, function( $a, $b ) use ( $priorities ) {

			// Loop through priorities
			foreach( $priorities as $priority ):

				// Proceed if columns are not equal
				if ( sp_array_value( $a, $priority['column'], 0 ) != sp_array_value( $b, $priority['column'], 0 ) ):

					// Compare column values
					$output = sp_array_value( $a, $priority['column'], 0 ) - sp_array_value( $b, $priority['column'], 0 );

					// Flip value if descending order
					if ( $priority['order'] == 'DESC' ) $output = 0 - $output;

					return $output;

				endif;

			endforeach;

			// Default sort by alphabetical
			return strcmp( sp_array_value( $a, 'name', '' ), sp_array_value( $b, 'name', '' ) );
		});

		// Rearrange data array to reflect statistics
		$data = array();
		foreach( $merged as $key => $value ):
			$data[ $key ] = $tempdata[ $key ];
		endforeach;

		if ( $breakdown ):
			return array( $columns, $data, $placeholders, $merged );
		else:
			array_unshift( $columns, __( 'Team', 'sportspress' ) );
			$merged[0] = $columns;
			return $merged;
		endif;
	}
}

if ( !function_exists( 'sp_get_list' ) ) {
	function sp_get_list( $post_id, $breakdown = false ) {
		$div_id = sp_get_the_term_id( $post_id, 'sp_league', 0 );
		$team_id = get_post_meta( $post_id, 'sp_team', true );
		$player_ids = (array)get_post_meta( $post_id, 'sp_player', false );
		$stats = (array)get_post_meta( $post_id, 'sp_players', true );

		// Equation Operating System
		$eos = new eqEOS();

		// Get labels from result variables
		$columns = (array)sp_get_var_labels( 'sp_statistic' );

		// Get all leagues populated with stats where available
		$tempdata = sp_array_combine( $player_ids, $stats );

		// Get equations from statistics variables
		$equations = sp_get_var_equations( 'sp_statistic' );

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

			// Get static statistics
			$static = get_post_meta( $player_id, 'sp_statistics', true );

			// Create placeholders entry for the player
			$placeholders[ $player_id ] = array();

			// Add static statistics to placeholders
			if ( array_key_exists( $team_id, $static ) && array_key_exists( $div_id, $static[ $team_id ] ) ):
				$placeholders[ $player_id ] = $static[ $team_id ][ $div_id ];
			endif;
		endforeach;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => $div_id
				)
			),
			'meta_query' => array(
				array(
					'key' => 'sp_team',
					'value' => $team_id,
				)
			)
		);
		$events = get_posts( $args );

		// Event loop
		foreach( $events as $event ):

			$teams = (array)get_post_meta( $event->ID, 'sp_players', true );

			if ( ! array_key_exists( $team_id, $teams ) )
				continue;

			$players = sp_array_value( $teams, $team_id, array() );

			foreach ( $players as $player_id => $player_statistics ):

				// Increment events played
				$totals[ $player_id ]['eventsplayed']++;

				foreach ( $player_statistics as $key => $value ):

					if ( array_key_exists( $key, $totals[ $player_id ] ) ):
						$totals[ $player_id ][ $key ] += $value;
					endif;

				endforeach;

			endforeach;

		endforeach;

		$args = array(
			'post_type' => 'sp_statistic',
			'numberposts' => -1,
			'posts_per_page' => -1,
	  		'orderby' => 'menu_order',
	  		'order' => 'ASC'
		);
		$statistics = get_posts( $args );

		$columns = array();
		$priorities = array();

		foreach ( $statistics as $statistic ):

			// Get post meta
			$meta = get_post_meta( $statistic->ID );

			// Add equation to object
			$statistic->equation = sp_array_value( sp_array_value( $meta, 'sp_equation', array() ), 0, 0 );

			// Add column name to columns
			$columns[ $statistic->post_name ] = $statistic->post_title;

			// Add order to priorities if priority is set and does not exist in array already
			$priority = sp_array_value( sp_array_value( $meta, 'sp_priority', array() ), 0, 0 );
			if ( $priority && ! array_key_exists( $priorities, $priority ) ):
				$priorities[ $priority ] = array(
					'column' => $statistic->post_name,
					'order' => sp_array_value( sp_array_value( $meta, 'sp_order', array() ), 0, 'DESC' )
				);
			endif;

		endforeach;

		// Sort priorities in descending order
		ksort( $priorities );

		// Fill in empty placeholder values for each player
		foreach ( $player_ids as $player_id ):

			if ( ! $player_id )
				continue;

			foreach ( $statistics as $statistic ):
				if ( sp_array_value( $placeholders[ $player_id ], $statistic->post_name, '' ) == '' ):

					if ( empty( $statistic->equation ) ):

						// Reflect totals
						$placeholders[ $player_id ][ $statistic->post_name ] = sp_array_value( sp_array_value( $totals, $player_id, array() ), $key, 0 );

					else:

						// Calculate value
						if ( sizeof( $events ) > 0 ):
							$placeholders[ $player_id ][ $statistic->post_name ] = sp_solve( $statistic->equation, sp_array_value( $totals, $player_id, array() ) );
						else:
							$placeholders[ $player_id ][ $statistic->post_name ] = 0;
						endif;
					endif;
				endif;
			endforeach;
		endforeach;

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $player_id => $player_data ):

			// Add team name to row
			$merged[ $player_id ] = array( 'name' => get_the_title( $player_id ) );

			foreach( $player_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $player_id, $tempdata ) && array_key_exists( $key, $tempdata[ $player_id ] ) && $tempdata[ $player_id ][ $key ] != '' ):
					$merged[ $player_id ][ $key ] = $tempdata[ $player_id ][ $key ];
				else:
					$merged[ $player_id ][ $key ] = $value;
				endif;

			endforeach;
		endforeach;

		uasort( $merged, function( $a, $b ) use ( $priorities ) {

			// Loop through priorities
			foreach( $priorities as $priority ):

				// Proceed if columns are not equal
				if ( sp_array_value( $a, $priority['column'], 0 ) != sp_array_value( $b, $priority['column'], 0 ) ):

					// Compare column values
					$output = sp_array_value( $a, $priority['column'], 0 ) - sp_array_value( $b, $priority['column'], 0 );

					// Flip value if descending order
					if ( $priority['order'] == 'DESC' ) $output = 0 - $output;

					return $output;

				endif;

			endforeach;

			// Default sort by alphabetical
			return strcmp( sp_array_value( $a, 'name', '' ), sp_array_value( $b, 'name', '' ) );
		});

		// Rearrange data array to reflect statistics
		$data = array();
		foreach( $merged as $key => $value ):
			$data[ $key ] = $tempdata[ $key ];
		endforeach;

		if ( $breakdown ):
			return array( $columns, $data, $placeholders, $merged );
		else:
			array_unshift( $columns, __( 'Player', 'sportspress' ) );
			$merged[0] = $columns;
			return $merged;
		endif;
	}
}

if ( !function_exists( 'sp_get_table_html' ) ) {
	function sp_get_table_html( $id ) {

		$data = sp_get_table( $id );

		$output = '<table class="sp-league-table">' . '<thead>' . '<tr>';

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		foreach( $labels as $label ):
			$output .= '<th>' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</th>' . '</thead>' . '<tbody>';

		$i = 1;

		foreach( $data as $team_id => $row ):

			$output .= '<tr>';

			foreach( $row as $key => $value ):
				$output .= '<td>';
					if ( $key == 'name' ):
						$permalink = get_post_permalink( $team_id );
						$thumbnail = get_the_post_thumbnail( $team_id, 'sp_icon' );
						$output .= $i . ' ';
						if ( $thumbnail ):
							$output .= '<a href="' . $permalink . '">' . $thumbnail . '</a> ';
						endif;
						$output .= '<a href="' . $permalink . '">';
					endif;
				$output .= $value;
					if ( $key == 'name' ):
						$output .= '</a>';
					endif;
				$output .= '</td>';
			endforeach;

			$output .= '</tr>';

			$i++;

		endforeach;

		$output .= '</tbody>' . '</table>';

		return $output;

	}
}

if ( !function_exists( 'sp_get_list_html' ) ) {
	function sp_get_list_html( $id ) {

		$data = sp_get_list( $id );

		$output = '<table class="sp-player-list">' . '<thead>' . '<tr>';

		// The first row should be column labels
		$labels = $data[0];

		// Remove the first row to leave us with the actual data
		unset( $data[0] );

		foreach( $labels as $label ):
			$output .= '<th>' . $label . '</th>';
		endforeach;

		$output .= '</tr>' . '</th>' . '</thead>' . '<tbody>';

		foreach( $data as $player_id => $row ):

			$output .= '<tr>';

			foreach( $row as $key => $value ):
				$output .= '<td>';
					if ( $key == 'name' ):
						$permalink = get_post_permalink( $player_id );
						$output .= '<a href="' . $permalink . '">';
					endif;
				$output .= $value;
					if ( $key == 'name' ):
						$output .= '</a>';
					endif;
				$output .= '</td>';
			endforeach;

			$output .= '</tr>';

		endforeach;

		$output .= '</tbody>' . '</table>';

		return $output;

	}
}


if ( !function_exists( 'sp_highlight_admin_menu' ) ) {
	function sp_highlight_admin_menu() {
		global $parent_file, $submenu_file;
		$parent_file = 'options-general.php';
		$submenu_file = 'sportspress';
	}
}
?>