<?php
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
	function sp_the_posts( $post_id = null, $meta = 'post', $before = '', $sep = ', ', $after = '', $delimiter = '— ' ) {
		echo $before;
		if ( ! isset( $post_id ) )
			global $post_id;
		$posts = get_post_meta( $post_id, $meta, false );
		$i = 0;
		$count = count( $posts );
		if ( isset( $posts ) && $posts && is_array( $posts ) ):
			foreach ( $posts as $post ):
				$parents = get_post_ancestors( $post );
				$parents = array_combine( array_keys( $parents ), array_reverse( array_values( $parents ) ) );
				foreach ( $parents as $parent ):
					if ( !in_array( $parent, $posts ) )
						edit_post_link( get_the_title( $parent ), '', ' ', $parent );
					echo $delimiter;
				endforeach;
				edit_post_link( get_the_title( $post ), '', '', $post );
				if ( ++$i !== $count ) {
					echo $sep;
				  }
			endforeach;
		endif;
		echo $after;
	}
}

if ( ! function_exists( 'sp_team_logo' ) ) {
	function sp_team_logo( $post_id = null ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		if ( has_post_thumbnail( $post_id ) ):
			the_post_thumbnail( 'sp_icon' );
		else:
			$parents = get_post_ancestors( $post_id );
			foreach ( $parents as $parent ) {
				if( has_post_thumbnail( $parent ) ) {
					echo get_the_post_thumbnail( $parent, 'sp_icon');
					break;
				}
			}
		endif;
	}
}

if ( ! function_exists( 'sp_post_checklist' ) ) {
	function sp_post_checklist( $post_id = null, $meta = 'post', $add_new_item = true ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		$obj = get_post_type_object( $meta );
		?>
		<div id="posttype-<?php echo $meta; ?>" class="posttypediv">
			<div id="<?php echo $meta; ?>-all" class="wp-tab-panel">
				<input type="hidden" value="0" name="sportspress[<?php echo $meta; ?>]" />
				<ul class="categorychecklist form-no-clear">
					<?php
					$selected = (array)get_post_meta( $post_id, $meta, false );
					$posts = get_pages( array( 'post_type' => $meta, 'number' => 0 ) );
					if ( empty( $posts ) )
						$posts = get_posts( array( 'post_type' => $meta, 'numberposts' => 0 ) );
					foreach ( $posts as $post ):
						$parents = get_post_ancestors( $post );
						?>
						<li>
							<?php echo str_repeat( '<ul><li>', sizeof( $parents ) ); ?>
							<label class="selectit">
								<input type="checkbox" value="<?php echo $post->ID; ?>" name="sportspress[<?php echo $meta; ?>][]"<?php if ( in_array( $post->ID, $selected ) ) echo ' checked="checked"'; ?>>
								<?php echo $post->post_title; ?>
							</label>
							<?php echo str_repeat( '</li></ul>', sizeof( $parents ) ); ?>
						</li>
						<?php
					endforeach;
					?>
				</ul>
			</div>
			<?php if ( $add_new_item ): ?>
				<div id="<?php echo $meta; ?>-adder">
					<h4>
						<a title="<?php echo sprintf( esc_attr__( 'Add New %s', 'sportspress' ), esc_attr__( 'Team', 'sportspress' ) ); ?>" href="<?php echo admin_url( 'post-new.php?post_type=' . $meta ); ?>" target="_blank">
							+ <?php echo sprintf( __( 'Add New %s', 'sportspress' ), $obj->labels->singular_name ); ?>
						</a>
					</h4>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
?>