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

function sp_admin_menu_separator() {
	if ( ! current_user_can( 'manage_options' ) )
		return;
	global $menu;
	$menu[41] = array( '', 'read', 'separator-sportspress', '', 'wp-menu-separator sportspress' );
}
add_action( 'admin_menu', 'sp_admin_menu_separator' );

function sp_manage_posts_custom_column( $column, $post_id ) {
	global $post;
	switch ( $column ):
		case 'sp_logo':
			edit_post_link( get_the_post_thumbnail( $post_id, 'sp_icon' ), '', '', $post_id );
			break;
		case 'sp_pos':
			echo get_the_terms ( $post_id, 'sp_pos' ) ? the_terms( $post_id, 'sp_pos' ) : '—';
			break;
		case 'sp_team':
			$result = get_post_meta( $post_id, 'sp_result', false );
			echo get_post_meta ( $post_id, 'sp_team' ) ? sp_the_posts( $post_id, 'sp_team', '', '<br />', $result, ( empty( $result ) ? ' — ' : ' ' ) ) : '—';
			break;
		case 'sp_sport':
			echo get_the_terms ( $post_id, 'sp_sport' ) ? preg_replace('#<a.*?>.*?</a>#i', '', sp_the_plain_terms( $post_id, 'sp_sport' ) ) : '—';
			break;
		case 'sp_equation':
			echo get_post_meta ( $post_id, 'sp_equation', true );
			break;
		case 'sp_player':
			echo sp_the_posts( $post_id, 'sp_player' );
			break;
		case 'sp_event':
			echo get_post_meta ( $post_id, 'sp_event' ) ? sizeof( get_post_meta ( $post_id, 'sp_event' ) ) : '—';
			break;
		case 'sp_div':
			echo get_the_terms ( $post_id, 'sp_div' ) ? the_terms( $post_id, 'sp_div' ) : '—';
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

function sp_restrict_manage_posts() {
	global $typenow, $wp_query;
	if ( in_array( $typenow, array( 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_list', 'sp_tournament' ) ) ):
		$selected = isset( $_REQUEST['sp_team'] ) ? $_REQUEST['sp_team'] : null;
		$args = array(
			'show_option_none' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ),
			'post_type' => 'sp_team',
			'name' => 'sp_team',
			'selected' => $selected
		);
		// wp_dropdown_pages( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_player', 'sp_staff' ) ) ):
		$selected = isset( $_REQUEST['sp_pos'] ) ? $_REQUEST['sp_pos'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Positions', 'sportspress' ) ),
			'taxonomy' => 'sp_pos',
			'name' => 'sp_pos',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_team', 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_list' ) ) ):
		$selected = isset( $_REQUEST['sp_div'] ) ? $_REQUEST['sp_div'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Divisions', 'sportspress' ) ),
			'taxonomy' => 'sp_div',
			'name' => 'sp_div',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_stat', 'sp_metric' ) ) ):
		$selected = isset( $_REQUEST['sp_sport'] ) ? $_REQUEST['sp_sport'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Sports', 'sportspress' ) ),
			'taxonomy' => 'sp_sport',
			'name' => 'sp_sport',
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
			update_post_meta( $post_id, 'sp_stats', sp_array_value( $_POST, 'sp_stats', array() ) );
			break;
		case ( 'sp_event' ):

			// Get results
			$results = (array)sp_array_value( $_POST, 'sp_results', array() );

			// Update results
			update_post_meta( $post_id, 'sp_results', $results );

			// Delete result values
			delete_post_meta( $post_id, 'sp_result');

			// Add result values for each team (first column of results table)
			$teams = (array)sp_array_value( $results, 0, null );
			foreach ( $teams as $team ):
				add_post_meta( $post_id, 'sp_result', sp_array_value( $team, 0, null ) );
			endforeach;

			// Update stats
			update_post_meta( $post_id, 'sp_stats', sp_array_value( $_POST, 'sp_stats', array() ) );

			// Update team array
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );

			// Update player array
			sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );

			break;

		case ( 'sp_stat' ):

			// Update equation as string
			update_post_meta( $post_id, 'sp_equation', implode( ' ', sp_array_value( $_POST, 'sp_equation', array() ) ) );

			break;

		case ( 'sp_player' ):
			update_post_meta( $post_id, 'sp_stats', sp_array_value( $_POST, 'sp_stats', array() ) );
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
			break;
		case ( 'sp_staff' ):
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
			break;
		case ( 'sp_table' ):
			update_post_meta( $post_id, 'sp_stats', sp_array_value( $_POST, 'sp_stats', array() ) );
			wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_div', 0 ), 'sp_div' );
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
			break;
		case ( 'sp_list' ):
			update_post_meta( $post_id, 'sp_stats', sp_array_value( $_POST, 'sp_stats', array() ) );
			update_post_meta( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
			wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_div', 0 ), 'sp_div' );
			sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );
			break;
	endswitch;
}
add_action( 'save_post', 'sp_save_post' );
?>