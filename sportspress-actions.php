<?php
function sp_plugins_loaded() {
    load_plugin_textdomain ( 'sportspress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	add_image_size( 'sp_icon',  32, 32, false );
}
add_action( 'plugins_loaded', 'sp_plugins_loaded' );

function sp_after_theme_setup() {
	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_theme_setup', 'sp_after_theme_setup' );

function sp_admin_menu( $position ) {
	if ( ! current_user_can( 'manage_options' ) )
		return;
	
	global $menu, $submenu;

	// Find where our placeholder is in the menu
	foreach( $menu as $key => $data ) {
		if ( is_array( $data ) && array_key_exists( 2, $data ) && $data[2] == 'edit.php?post_type=sp_separator' )
			$position = $key;
	}

	// Swap our placeholder post type with a menu separator
	if ( $position ):
		$menu[ $position ] = array( '', 'read', 'separator-sportspress', '', 'wp-menu-separator sportspress' );
	endif;

    // Remove "Add Configuration" link under SportsPress
    unset( $submenu['edit.php?post_type=sp_config'][10] );

    // Remove "Seasons" link under Events
    unset( $submenu['edit.php?post_type=sp_event'][15] );

    // Remove "Seasons" link under Players
    unset( $submenu['edit.php?post_type=sp_player'][15] );

    // Remove "Seasons" link under Staff
    unset( $submenu['edit.php?post_type=sp_staff'][15] );
}
add_action( 'admin_menu', 'sp_admin_menu' );

function sp_manage_posts_custom_column( $column, $post_id ) {
	global $post;
	switch ( $column ):
		case 'sp_logo':
			edit_post_link( get_the_post_thumbnail( $post_id, 'sp_icon' ), '', '', $post_id );
			break;
		case 'sp_position':
			echo get_the_terms ( $post_id, 'sp_position' ) ? the_terms( $post_id, 'sp_position' ) : '—';
			break;
		case 'sp_team':
			$post_type = get_post_type( $post );
			$teams = get_post_meta ( $post_id, 'sp_team', false );
			if ( $post_type == 'sp_event' ):
				$results = get_post_meta( $post_id, 'sp_results', true );
				foreach( $teams as $team_id ):
					$team = get_post( $team_id );
					$outcome_slug = sp_array_value( sp_array_value( $results, $team_id, null ), 'outcome', null );

					$args=array(
						'name' => $outcome_slug,
						'post_type' => 'sp_outcome',
						'post_status' => 'publish',
						'posts_per_page' => 1
					);
					$outcomes = get_posts( $args );
					echo $team->post_title . ( $outcomes ? ' — ' . $outcomes[0]->post_title : '' ) . '<br>';
				endforeach;
			else:
				foreach( $teams as $team_id ):
					$team = get_post( $team_id );
					echo $team->post_title . '<br>';
				endforeach;
			endif;
			break;
		case 'sp_equation':
			echo sp_get_post_equation( $post_id );
			break;
		case 'sp_order':
			echo sp_get_post_order( $post_id );
			break;
		case 'sp_key':
			echo $post->post_name;
			break;
		case 'sp_format':
			echo sp_get_post_format( $post_id );
			break;
		case 'sp_player':
			echo sp_the_posts( $post_id, 'sp_player' );
			break;
		case 'sp_event':
			echo get_post_meta ( $post_id, 'sp_event' ) ? sizeof( get_post_meta ( $post_id, 'sp_event' ) ) : '—';
			break;
		case 'sp_season':
			echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : '—';
			break;
		case 'sp_sponsor':
			echo get_the_terms ( $post_id, 'sp_sponsor' ) ? the_terms( $post_id, 'sp_sponsor' ) : '—';
			break;
		case 'sp_kickoff':
			echo ( $post->post_status == 'future' ? __( 'Scheduled' ) : __( 'Played', 'sportspress' ) ) . '<br />' . date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) );
			break;
		case 'sp_address':
			echo get_post_meta( $post_id, 'sp_address', true ) ? get_post_meta( $post_id, 'sp_address', true ) : '—';
			break;
	endswitch;
}
add_action( 'manage_posts_custom_column', 'sp_manage_posts_custom_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'sp_manage_posts_custom_column', 10, 2 );

function sp_manage_posts_columns() {
	sp_highlight_admin_menu();
}
add_action( 'manage_posts_columns', 'sp_manage_posts_columns' );

function sp_restrict_manage_posts() {
	sp_highlight_admin_menu();
	global $typenow, $wp_query;
	if ( in_array( $typenow, array( 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_list' ) ) ):
		$selected = isset( $_REQUEST['sp_team'] ) ? $_REQUEST['sp_team'] : null;
		$args = array(
			'show_option_none' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ),
			'post_type' => 'sp_team',
			'name' => 'sp_team',
			'selected' => $selected
		);
		// wp_dropdown_pages( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_player' ) ) ):
		$selected = isset( $_REQUEST['sp_position'] ) ? $_REQUEST['sp_position'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Positions', 'sportspress' ) ),
			'taxonomy' => 'sp_position',
			'name' => 'sp_position',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_team', 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_list' ) ) ):
		$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
			'taxonomy' => 'sp_season',
			'name' => 'sp_season',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
	endif;
}
add_action( 'restrict_manage_posts', 'sp_restrict_manage_posts' );

function sp_nonce() {
	echo '<input type="hidden" name="sportspress_nonce" id="sportspress_nonce" value="' . wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';
}

function sp_save_post( $post_id ) {
	global $post, $typenow;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
    if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
	if ( !isset( $_POST['sportspress_nonce'] ) || ! wp_verify_nonce( $_POST['sportspress_nonce'], plugin_basename( __FILE__ ) ) ) return $post_id;
	switch ( $_POST['post_type'] ):
		case ( 'sp_team' ):

			// Update columns
			update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );

			break;

		case ( 'sp_event' ):

			// Get results
			$results = (array)sp_array_value( $_POST, 'sp_results', array() );

			// Update results
			update_post_meta( $post_id, 'sp_results', $results );

			// Update player statistics
			update_post_meta( $post_id, 'sp_players', sp_array_value( $_POST, 'sp_players', array() ) );

			// Update team array
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			// Update player array
			sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );

			// Update staff array
			sp_update_post_meta_recursive( $post_id, 'sp_staff', sp_array_value( $_POST, 'sp_staff', array() ) );

			break;

		case ( 'sp_column' ):

			// Update format as string
			update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'integer' ) );

			// Update precision as integer
			update_post_meta( $post_id, 'sp_precision', (int) sp_array_value( $_POST, 'sp_precision', 1 ) );

			// Update equation as string
			update_post_meta( $post_id, 'sp_equation', implode( ' ', sp_array_value( $_POST, 'sp_equation', array() ) ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_priority', sp_array_value( $_POST, 'sp_priority', '0' ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', 'DESC' ) );

			break;

		case ( 'sp_statistic' ):

			// Update format as string
			update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'integer' ) );

			// Update precision as integer
			update_post_meta( $post_id, 'sp_precision', (int) sp_array_value( $_POST, 'sp_precision', 1 ) );

			// Update equation as string
			update_post_meta( $post_id, 'sp_equation', implode( ' ', sp_array_value( $_POST, 'sp_equation', array() ) ) );
			
			// Update sort order as string
			update_post_meta( $post_id, 'sp_priority', sp_array_value( $_POST, 'sp_priority', '0' ) );

			// Update sort order as string
			update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', 'DESC' ) );

			break;

		case ( 'sp_result' ):

			// Update format as string
			update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'integer' ) );

			break;

		case ( 'sp_player' ):

			// Update player statistics
			update_post_meta( $post_id, 'sp_statistics', sp_array_value( $_POST, 'sp_statistics', array() ) );

			// Update team array
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			// Update player number
			update_post_meta( $post_id, 'sp_number', sp_array_value( $_POST, 'sp_number', '' ) );

			// Update player details array
			update_post_meta( $post_id, 'sp_details', sp_array_value( $_POST, 'sp_details', array() ) );

			break;

		case ( 'sp_staff' ):

			// Update team array
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			break;

		case ( 'sp_table' ):

			// Update teams array
			update_post_meta( $post_id, 'sp_teams', sp_array_value( $_POST, 'sp_teams', array() ) );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			// Update team array
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			break;

		case ( 'sp_list' ):

			// Update players array
			update_post_meta( $post_id, 'sp_players', sp_array_value( $_POST, 'sp_players', array() ) );

			// Update team array
			update_post_meta( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			// Update season taxonomy
			wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_season', 0 ), 'sp_season' );

			//Update player array
			sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );

			break;

	endswitch;
}
add_action( 'save_post', 'sp_save_post' );
?>