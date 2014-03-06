<?php
function sportspress_settings() {

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

?>
	<div class="wrap">

		<h2 class="nav-tab-wrapper">
			<a href="<?php echo remove_query_arg( 'tab' ); ?>" class="nav-tab<?php echo $active_tab == 'general' ? ' nav-tab-active' : ''; ?>"><?php _e( 'General', 'sportspress' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'events' ); ?>" class="nav-tab<?php echo $active_tab == 'events' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Events', 'sportspress' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'tables' ); ?>" class="nav-tab<?php echo $active_tab == 'tables' ? ' nav-tab-active' : ''; ?>"><?php _e( 'League Tables', 'sportspress' ); ?></a>
			<a href="<?php echo add_query_arg( 'tab', 'players' ); ?>" class="nav-tab<?php echo $active_tab == 'players' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Players', 'sportspress' ); ?></a>
		</h2>

		<form method="post" action="options.php">
			<?php
				switch ( $active_tab ):
					case 'events':
						include 'events.php';
						break;
					case 'tables':
						include 'tables.php';
						break;
					case 'players':
						include 'players.php';
						break;
					default:
						include 'general.php';
				endswitch;
			?>
		</form>
		
	</div>
<?php
}

function sportspress_sport_callback() {
	global $sportspress_sports;
	$options = get_option( 'sportspress' );

	$selected = sportspress_array_value( $options, 'sport', null );
	$custom_sport_name = sportspress_array_value( $options, 'custom_sport_name', null );
	?>
	<fieldset>
		<select id="sportspress_sport" name="sportspress[sport]">
			<option value><?php _e( '&mdash; Select &mdash;', 'sportspress' ); ?></option>
			<?php foreach( $sportspress_sports as $slug => $sport ): ?>
				<option value="<?php echo $slug; ?>" <?php selected( $selected, $slug ); ?>><?php echo $sport['name']; ?></option>
			<?php endforeach; ?>
			<option value="custom" <?php selected( $selected, 'custom' ); ?>><?php _e( 'Custom', 'sportspress' ); ?></option>
		</select>
		<input id="sportspress_custom_sport_name" name="sportspress[custom_sport_name]" type="text" placeholder="<?php _e( 'Sport', 'sportspress' ); ?>" value="<?php echo $custom_sport_name; ?>"<?php if ( $selected != 'custom' ): ?> class="hidden"<?php endif; ?>>
	</fieldset>
	<?php
}

function sportspress_result_callback() {
	$options = get_option( 'sportspress' );

	$selected = sportspress_array_value( $options, 'main_result', null );
	$args = array(
		'post_type' => 'sp_result',
		'name' => 'sportspress[main_result]',
		'show_option_all' => __( '(Auto)', 'sportspress' ),
		'selected' => $selected,
		'values' => 'slug',
	);
	?>
	<fieldset>
		<? sportspress_dropdown_pages( $args ); ?>
	</fieldset>
	<?php
}

function sportspress_team_logos_callback() {
	$options = get_option( 'sportspress' );

	$show_team_logo = sportspress_array_value( $options, 'league_table_show_team_logo', false );
	?>
	<fieldset>
		<label for="sportspress_league_table_show_team_logo">
			<input id="sportspress_league_table_show_team_logo_default" name="sportspress[league_table_show_team_logo]" type="hidden" value="0">
			<input id="sportspress_league_table_show_team_logo" name="sportspress[league_table_show_team_logo]" type="checkbox" value="1" <?php checked( $show_team_logo ); ?>>
			<?php _e( 'Display logos', 'sportspress' ); ?>
		</label>
	</fieldset>
	<?php
}

function sportspress_team_stats_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'team', 'textarea' );
}

function sportspress_event_stats_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'event', 'textarea' );
}

function sportspress_player_stats_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'player', 'textarea' );
}

function sportspress_settings_init() {

    $installed = get_option( 'sportspress_installed', false );
	
	// General settings
	register_setting(
		'sportspress_general',
		'sportspress',
		'sportspress_options_validate'
	);
	
	add_settings_section(
		'general',
		'',
		'',
		'sportspress_general'
	);
	
	add_settings_field(	
		'sport',
		__( 'Sport', 'sportspress' ),
		'sportspress_sport_callback',	
		'sportspress_general',
		'general'
	);

	// Event Settings
	register_setting(
		'sportspress_events',
		'sportspress',
		'sportspress_options_validate'
	);
	
	add_settings_section(
		'events',
		'',
		'',
		'sportspress_events'
	);
	
	add_settings_field(	
		'result',
		__( 'Main Result', 'sportspress' ),
		'sportspress_result_callback',	
		'sportspress_events',
		'events'
	);

	// League Table Settings
	register_setting(
		'sportspress_tables',
		'sportspress',
		'sportspress_options_validate'
	);
	
	add_settings_section(
		'tables',
		'',
		'',
		'sportspress_tables'
	);
	
	add_settings_field(	
		'result',
		__( 'Teams', 'sportspress' ),
		'sportspress_team_logos_callback',	
		'sportspress_tables',
		'tables'
	);

	// Player Settings
	register_setting(
		'sportspress_players',
		'sportspress',
		'sportspress_options_validate'
	);
	
	add_settings_section(
		'players',
		'',
		'',
		'sportspress_players'
	);
	
	add_settings_field(	
		'result',
		__( 'Main Result', 'sportspress' ),
		'sportspress_result_callback',	
		'sportspress_players',
		'players'
	);
	
}
add_action( 'admin_init', 'sportspress_settings_init', 1 );

function sportspress_options_validate( $input ) {
	
	$options = (array)get_option( 'sportspress' );

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
		'sportspress_settings'
	);
}
add_action( 'admin_menu', 'sportspress_add_menu_page' );
