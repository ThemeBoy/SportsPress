<?php
if ( ! function_exists( 'sp_array_depth' ) ) {
	function sp_array_depth( $array ) {
	    $max_depth = 1;
	    if ( is_array( $array ) ):
		    foreach ( $array as $value ):
		        if ( is_array( $value ) ):
		            $depth = sp_array_depth( $value ) + 1;
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

if ( ! function_exists( 'sp_get_cpt_labels' ) ) {
	function sp_get_cpt_labels( $name, $singular_name ) {
		$labels = array(
			'name' => $name,
			'singular_name' => $singular_name,
			'all_items' => sprintf( __( 'All %s', 'sportspress' ), $name ),
			'add_new_item' => sprintf( __( 'Add New %s', 'sportspress' ), $singular_name ),
			'edit_item' => sprintf( __( 'Edit %s', 'sportspress' ), $singular_name ),
			'new_item' => sprintf( __( 'New %s', 'sportspress' ), $singular_name ),
			'view_item' => sprintf( __( 'View %s', 'sportspress' ), $singular_name ),
			'search_items' => sprintf( __( 'Search %s', 'sportspress' ), $name ),
			'not_found' => sprintf( __( 'No %s found', 'sportspress' ), $name ),
			'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'sportspress' ), $name ),
			'parent_item_colon' => sprintf( __( 'Parent %s', 'sportspress' ), $singular_name ) . ':'
		);
		return $labels;
	}
}

if ( ! function_exists( 'sp_get_tax_labels' ) ) {
	function sp_get_tax_labels( $name, $singular_name ) {
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
			'not_found' => sprintf( __( 'No %s found', 'sportspress' ), $name )
		);
		return $labels;
	}
}

if ( ! function_exists( 'sp_dropdown_taxonomies' ) ) {
	function sp_dropdown_taxonomies( $args = array() ) {
		$defaults = array(
			'show_option_all' => false,
			'show_option_none' => false,
			'taxonomy' => null,
			'name' => null,
			'selected' => null,
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
				printf( '<option value="%s" %s>%s</option>', $term->slug, selected( true, $args['selected'] == $term->slug ), $term->name );
			}
			print( '</select>' );
		}
	}
}

if ( ! function_exists( 'sp_the_posts' ) ) {
	function sp_the_posts( $post_id = null, $meta = 'post', $before = '', $sep = ', ', $after = '', $delimiter = ' - ' ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		$ids = get_post_meta( $post_id, $meta, false );
		$i = 0;
		$count = count( $ids );
		if ( isset( $ids ) && $ids && is_array( $ids ) ):
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
				edit_post_link( get_the_title( $id ), '', '', $id );
				if ( !empty( $after ) ):
					if ( is_array( $after ) && array_key_exists( $i, $after ) )
						echo ' - ' . $after[ $i ];
					else
						echo $after;
				endif;
				if ( ++$i !== $count )
					echo $sep;
			endforeach;
		endif;
	}
}

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

if ( ! function_exists( 'sp_post_checklist' ) ) {
	function sp_post_checklist( $post_id = null, $meta = 'post', $display = 'block', $filter = null, $index = null ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		?>
		<div id="<?php echo $meta; ?>-all" class="posttypediv wp-tab-panel sp-tab-panel" style="display: <?php echo $display; ?>;">
			<input type="hidden" value="0" name="sportspress[<?php echo $meta; ?>]<?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]" />
			<ul class="categorychecklist form-no-clear">
				<?php
				$selected = sp_array_between( (array)get_post_meta( $post_id, $meta, false ), 0, $index );
				$posts = get_pages( array( 'post_type' => $meta, 'number' => 0 ) );
				if ( empty( $posts ) )
					$posts = get_posts( array( 'post_type' => $meta, 'numberposts' => 0 ) );
				foreach ( $posts as $post ):
					$parents = get_post_ancestors( $post );
					if ( $filter )
						$filter_values = (array)get_post_meta( $post->ID, $filter, false )
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
							<input type="checkbox" value="<?php echo $post->ID; ?>" name="sportspress[<?php echo $meta; ?>]<?php if ( isset( $index ) ) echo '[' . $index . ']'; ?>[]"<?php if ( in_array( $post->ID, $selected ) ) echo ' checked="checked"'; ?>>
							<?php echo $post->post_title; ?>
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

if ( ! function_exists( 'sp_data_table' ) ) {
	function sp_data_table( $data = array(), $index = 0, $columns = array( 'Name' ), $total = true ) {
		if ( !is_array( $data ) )
			$data = array();
		?>
		<table class="widefat">
			<thead>
				<tr>
					<?php foreach ( $columns as $column ): ?>
						<th><?php echo $column; ?></th>
					<?php endforeach; ?>
					<th><?php _e( 'Auto', 'sportspress' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$i = 0;
				foreach ( $data as $key => $values ):
					if ( !$key ) continue;
					if ( array_key_exists( 'auto', $values ) )
						$auto = (int)$values[ 'auto' ];
					else
						$auto = 0;
					?>
					<tr class="sp-post<?php
						if ( $i % 2 == 0 )
							echo ' alternate';
					?>">
						<td><?php echo get_the_title( $key ); ?></td>
						<?php for ( $j = 0; $j < sizeof( $columns ) - 1; $j ++ ):
							if ( array_key_exists( $j, $values ) )
								$value = (int)$values[ $j ];
							else
								$value = 0;
							?>
							<td><input type="text" name="sportspress[sp_stats][<?php echo $index; ?>][<?php echo $key; ?>][]" value="<?php echo $value; ?>"<?php if ( $auto ) echo ' readonly="readonly"'; ?> /></td>
						<?php endfor; ?>
						<td><input type="checkbox" name="sportspress[sp_stats][<?php echo $index; ?>][<?php echo $key; ?>][auto]" value="1"<?php if ( $auto ) echo ' checked="checked"'; ?> /></td>
					</tr>
					<?php
					$i++;
				endforeach;
				if ( $total ):
					$values = array_key_exists( 0, $data ) ? $data[0] : array();
					$auto = array_key_exists( 'auto', $values ) ? (int)$values[ 'auto' ] : 0;
					?>
					<tr<?php
						if ( $i % 2 == 0 )
							echo ' class="alternate"';
					?>>
						<td><strong><?php _e( 'Total', 'sportspress' ); ?></strong></td>
						<?php for ( $j = 0; $j < sizeof( $columns ) - 1; $j ++ ):
								if ( array_key_exists( $j, $values ) )
									$value = (int)$values[ $j ];
								else
									$value = 0;
							?>
							<td><input type="text" name="sportspress[sp_stats][<?php echo $index; ?>][0][]" value="<?php echo $value; ?>"<?php if ( $auto ) echo ' readonly="readonly"'; ?> /></td>
						<?php endfor; ?>
						<td><input type="checkbox" name="sportspress[sp_stats][<?php echo $index; ?>][0][auto]" value="1"<?php if ( $auto ) echo ' checked="checked"'; ?> /></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}
}

if ( ! function_exists( 'sp_post_adder' ) ) {
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
?>