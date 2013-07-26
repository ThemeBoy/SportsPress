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
	switch ( $column ):
		case 'sp_icon':
			the_post_thumbnail( 'sp_icon' );
			break;
		case 'sp_position':
			echo get_the_terms ( $post_id, 'sp_position' ) ? the_terms( $post_id, 'sp_position' ) : '—';
			break;
		case 'sp_team':
			echo get_post_meta ( $post_id, 'sp_team' ) ? sp_the_posts( $post_id, 'sp_team', '', '<br />' ) : '—';
			break;
		case 'sp_event':
			echo get_post_meta ( $post_id, 'sp_event' ) ? sizeof( get_post_meta ( $post_id, 'sp_event' ) ) : '—';
			break;
		case 'sp_league':
			echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : '—';
			break;
		case 'sp_season':
			echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : '—';
			break;
		case 'sp_sponsor':
			echo get_the_terms ( $post_id, 'sp_sponsor' ) ? the_terms( $post_id, 'sp_sponsor' ) : '—';
			break;
		case 'sp_kickoff':
			echo get_the_date ( get_option ( 'date_format' ) ) . '<br />' . get_the_time ( get_option ( 'time_format' ) );
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
	if ( in_array( $typenow, array( 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_calendar', 'sp_tournament' ) ) ):
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
	if ( in_array( $typenow, array( 'sp_team', 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_calendar' ) ) ):
		$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) ),
			'taxonomy' => 'sp_league',
			'name' => 'sp_league',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
	endif;
	if ( in_array( $typenow, array( 'sp_team', 'sp_event', 'sp_player', 'sp_staff', 'sp_table', 'sp_calendar' ) ) ):
		$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
			'taxonomy' => 'sp_season',
			'name' => 'sp_season',
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
	if ( isset( $_POST['sportspress'] ) ):
		$sportspress = (array)$_POST['sportspress'];
		if ( isset( $_POST ) && !empty( $sportspress ) ):
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
		    if ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;
			if ( !isset( $_POST['sportspress_nonce'] ) || ! wp_verify_nonce( $_POST['sportspress_nonce'], plugin_basename( __FILE__ ) ) ) return $post_id;
			foreach ( $sportspress as $key => $value ):
				delete_post_meta( $post_id, $key );
				if ( is_array( $value ) ):
					foreach ( $value as $single_value ):
						add_post_meta( $post_id, $key, $single_value, false );
					endforeach;
				else:
					update_post_meta( $post_id, $key, $value );
				endif;
			endforeach;
		endif;
	endif;
}
add_action( 'save_post', 'sp_save_post' );
?>