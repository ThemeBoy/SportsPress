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

function sp_manage_posts_custom_column( $column, $post_id ) {
	global $post;
	switch ( $column ):
		case 'sp_icon':
			the_post_thumbnail( 'sp_icon' );
			break;
		case 'sp_position':
			echo get_the_terms ( $post_id, 'sp_position' ) ? the_terms( $post_id, 'sp_position' ) : '—';
			break;
		case 'sp_team':
			$result = get_post_meta( $post_id, 'sp_result', false );
			echo get_post_meta ( $post_id, 'sp_team' ) ? sp_the_posts( $post_id, 'sp_team', '', '<br />', $result, ( empty( $result ) ? ' — ' : ' ' ) ) : '—';
			break;
		case 'sp_player':
			echo sp_the_posts( $post_id, 'sp_player' );
			break;
		case 'sp_event':
			echo get_post_meta ( $post_id, 'sp_event' ) ? sizeof( get_post_meta ( $post_id, 'sp_event' ) ) : '—';
			break;
		case 'sp_league':
			echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : '—';
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
		wp_dropdown_pages( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_player', 'sp_staff' ) ) ):
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
		$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) ),
			'taxonomy' => 'sp_league',
			'name' => 'sp_league',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_team', 'sp_event', 'sp_player', 'sp_tournament', 'sp_venue' ) ) ):
		$selected = isset( $_REQUEST['sp_sponsor'] ) ? $_REQUEST['sp_sponsor'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Sponsors', 'sportspress' ) ),
			'taxonomy' => 'sp_sponsor',
			'name' => 'sp_sponsor',
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
			update_post_meta( $post_id, 'sp_stats', sp_array_value( $_POST, 'sp_stats', array() ) );
			delete_post_meta( $post_id, 'sp_result');
			$stats = (array)sp_array_value( $_POST, 'sp_stats', array() );
			foreach ( $stats as $table ) {
				$total = sp_array_value( sp_array_value( $table, 0, null ), 0, null );
				add_post_meta( $post_id, 'sp_result', $total );
			}
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
			sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );
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
			wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );
			sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
			break;
		case ( 'sp_list' ):
			update_post_meta( $post_id, 'sp_stats', sp_array_value( $_POST, 'sp_stats', array() ) );
			update_post_meta( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
			wp_set_post_terms( $post_id, sp_array_value( $_POST, 'sp_league', 0 ), 'sp_league' );
			sp_update_post_meta_recursive( $post_id, 'sp_player', sp_array_value( $_POST, 'sp_player', array() ) );
			break;
	endswitch;

	/*

		foreach ( $sportspress as $key => $value ):
			delete_post_meta( $post_id, $key );
			if ( is_array( $value ) ):
				if ( sp_get_array_depth( $value ) >= 3 ):
					add_post_meta( $post_id, $key, $value, false );
				else:
					$values = new RecursiveIteratorIterator( new RecursiveArrayIterator( $value ) );
					foreach ( $values as $value ):
						add_post_meta( $post_id, $key, $value, false );
					endforeach;
				endif;
			else:
				update_post_meta( $post_id, $key, $value );
			endif;
		endforeach;

	*/
}
add_action( 'save_post', 'sp_save_post' );
?>