<?php
function sportspress_options() {

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

?>
	<div class="wrap">

		<h2 class="nav-tab-wrapper">
			<a href="<?php echo remove_query_arg( 'tab' ); ?>" class="nav-tab<?php echo $active_tab == 'general' ? ' nav-tab-active' : ''; ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'events' ); ?>" class="nav-tab<?php echo $active_tab == 'events' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Schedule', 'sportspress' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'teams' ); ?>" class="nav-tab<?php echo $active_tab == 'teams' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Teams', 'sportspress' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'players' ); ?>" class="nav-tab<?php echo $active_tab == 'players' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Roster', 'sportspress' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'text' ); ?>" class="nav-tab<?php echo $active_tab == 'text' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Text', 'sportspress' ); ?></a>
		</h2>

		<form method="post" action="options.php">
			<?php
				switch ( $active_tab ):
					case 'events':
						settings_fields( 'sportspress_events' );
						do_settings_sections( 'sportspress_events' );
						submit_button();
						break;
					case 'teams':
						settings_fields( 'sportspress_teams' );
						do_settings_sections( 'sportspress_teams' );
						submit_button();
						break;
					case 'players':
						settings_fields( 'sportspress_players' );
						do_settings_sections( 'sportspress_players' );
						submit_button();
						break;
					case 'text':
						settings_fields( 'sportspress_text' );
						do_settings_sections( 'sportspress_text' );
						submit_button();
						break;
					default:
						settings_fields( 'sportspress_general' );
						do_settings_sections( 'sportspress_general' );
						submit_button();
						break;
				endswitch;
			?>
		</form>
		
	</div>
<?php
}

function sportspress_options_validate( $input ) {
	
	$options = (array)get_option( 'sportspress', array() );

	if ( isset( $input['sport'] ) && sportspress_array_value( $options, 'sport', null ) != sportspress_array_value( $input, 'sport', null ) ):

		// Get sports presets
		global $sportspress_sports;

		// Get array of taxonomies to insert
		$term_groups = sportspress_array_value( sportspress_array_value( $sportspress_sports, sportspress_array_value( $input, 'sport', null ), array() ), 'terms', array() );

		foreach( $term_groups as $taxonomy => $terms ):
			// Find empty terms and destroy
			$allterms = get_terms( $taxonomy, 'hide_empty=0' );

			foreach( $allterms as $term ):
				if ( $term->count == 0 )
					wp_delete_term( $term->term_id, $taxonomy );
			endforeach;

			// Insert terms
			foreach( $terms as $term ):
				wp_insert_term( $term['name'], $taxonomy, array( 'slug' => $term['slug'] ) );
			endforeach;
		endforeach;

		// Get array of post types to insert
		$post_groups = sportspress_array_value( sportspress_array_value( $sportspress_sports, sportspress_array_value( $input, 'sport', null ), array() ), 'posts', array() );

		// Loop through each post type
		foreach( $post_groups as $post_type => $posts ):

			$args = array(
				'post_type' => $post_type,
				'numberposts' => -1,
				'posts_per_page' => -1,
				'meta_query' => array(
					array(
						'key' => '_sp_preset',
						'value' => 1
					)
				)
			);

			// Delete posts
			$old_posts = get_posts( $args );

			foreach( $old_posts as $post ):
				wp_delete_post( $post->ID, true);
			endforeach;

			// Add posts
			foreach( $posts as $index => $post ):

				// Make sure post doesn't overlap
				if ( ! get_page_by_path( $post['post_name'], OBJECT, $post_type ) ):

					// Translate post title
					$post['post_title'] = __( $post['post_title'], 'sportspress' );

					// Set post type
					$post['post_type'] = $post_type;

					// Increment menu order by 2 and publish post
					$post['menu_order'] = $index * 2 + 2;
					$post['post_status'] = 'publish';
					$id = wp_insert_post( $post );

					// Flag as preset
					update_post_meta( $id, '_sp_preset', 1 );

					// Update meta
					if ( array_key_exists( 'meta', $post ) ):

						foreach ( $post['meta'] as $key => $value ):

							update_post_meta( $id, $key, $value );

						endforeach;

					endif;

					// Update terms
					if ( array_key_exists( 'tax_input', $post ) ):

						foreach ( $post['tax_input'] as $taxonomy => $terms ):

							wp_set_object_terms( $id, $terms, $taxonomy, false );

						endforeach;

					endif;

				endif;

			endforeach;

		endforeach;

	elseif ( isset( $input['text'] ) ):
		$input['text'] = array_filter( $input['text'] );
	endif;

	if ( ! is_array( $input ) )
		$input = array();

	// Merge with existing options
	return array_merge( $options, $input );
}

function sportspress_add_menu_page() {
	add_options_page(
		__( 'SportsPress', 'sportspress' ),
		__( 'SportsPress', 'sportspress' ),
		'manage_options',
		'sportspress',
		'sportspress_options'
	);
}
add_action( 'admin_menu', 'sportspress_add_menu_page' );
