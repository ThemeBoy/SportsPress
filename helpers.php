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
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'sportspress' ), $singular_name )
		);
		return $labels;
	}
}

if ( ! function_exists( 'sp_get_tax_labels' ) ) {
	function sp_get_tax_labels( $name, $singular_name ) {
		$labels = array(
			'name' => __( $name, 'sportspress' ),
			'singular_name' => __( $singular_name, 'sportspress' ),
			'all_items' => sprintf( __( 'All %s', 'sportspress' ), __( $name, 'sportspress' ) ),
			'edit_item' => sprintf( __( 'Edit %s', 'sportspress' ), __( $singular_name, 'sportspress' ) ),
			'view_item' => sprintf( __( 'View %s', 'sportspress' ), __( $singular_name, 'sportspress' ) ),
			'update_item' => sprintf( __( 'Update %s', 'sportspress' ), __( $singular_name, 'sportspress' ) ),
			'add_new_item' => sprintf( __( 'Add New %s', 'sportspress' ), __( $singular_name, 'sportspress' ) ),
			'new_item_name' => __( $singular_name, 'sportspress' ),
			'parent_item' => sprintf( __( 'Parent %s', 'sportspress' ), __( $singular_name, 'sportspress' ) ),
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'sportspress' ), __( $singular_name, 'sportspress' ) ),
			'search_items' =>  sprintf( __( 'Search %s', 'sportspress' ), __( $name, 'sportspress' ) ),
			'not_found' => sprintf( __( 'No %s found', 'sportspress' ), __( $name, 'sportspress' ) )
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
	function sp_the_posts( $post_id = null, $meta = 'post', $before = '', $sep = ',', $after = '' ) {
		echo $before;
		if ( ! isset( $post_id ) )
			global $post_id;
		$posts = get_post_meta( $post_id, $meta, false );
		if ( isset( $posts ) && $posts ):
			foreach ( $posts as $post ):
				$parents = get_post_ancestors( $post );
				$parents = array_combine( array_keys( $parents ), array_reverse( array_values( $parents ) ) );
				foreach ( $parents as $parent ):
					if ( !in_array( $parent, $posts ) )
						edit_post_link( get_the_title( $parent ), '', ' ', $parent );
					echo '— ';
				endforeach;
				edit_post_link( get_the_title( $post ), '', '', $post );
				if ( $post != end( $posts ) )
					echo $sep;
			endforeach;
		else:
			echo '—';
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

if ( ! function_exists( 'sp_team_checklist' ) ) {
	function sp_team_checklist( $post_id = null ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		$selected = (array)get_post_meta( $post_id, 'sp_team', false );
		$teams = get_pages( array( 'post_type' => 'sp_team') );
		foreach ( $teams as $team ):
			$parents = get_post_ancestors( $team );
			?>
			<li>
				<?php echo str_repeat( '<ul><li>', sizeof( $parents ) ); ?>
				<label class="selectit">
					<input type="checkbox" value="<?php echo $team->ID; ?>" name="sportspress[sp_team][]"<?php if ( in_array( $team->ID, $selected ) ) echo ' checked="checked"'; ?>>
					<?php echo $team->post_title; ?>
				</label>
				<?php echo str_repeat( '</li></ul>', sizeof( $parents ) ); ?>
			</li>
			<?php
		endforeach;
	}
}

if ( ! function_exists( 'sp_team_select_html' ) ) {
	function sp_team_select_html( $post_id = null ) {
		if ( ! isset( $post_id ) )
			global $post_id;
		?>
		<div id="posttype-sp_team" class="posttypediv">
			<div id="sp_team-all" class="wp-tab-panel">
				<input type="hidden" value="0" name="sportspress[sp_team]" />
				<ul class="categorychecklist form-no-clear">
					<?php sp_team_checklist( $post_id ); ?>
				</ul>
			</div>
			<div id="sp_team-adder">
				<h4>
					<a title="<?php echo sprintf( esc_attr__( 'Add New %s', 'sportspress' ), esc_attr__( 'Team', 'sportspress' ) ); ?>" href="<?php echo admin_url( 'post-new.php?post_type=sp_team' ); ?>" target="_blank">
						+ <?php echo sprintf( __( 'Add New %s', 'sportspress' ), __( 'Team', 'sportspress' ) ); ?>
					</a>
				</h4>
			</div>
		</div>
		<?php
	}
}
?>