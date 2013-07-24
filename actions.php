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

function sp_save_post() {
	global $post, $post_id, $typenow;
	if ( isset( $_POST['sportspress'] ) ):
		$sportspress = (array)$_POST['sportspress'];
		if ( isset( $_POST ) && !empty( $sportspress ) ):
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
	//		if ( ! isset( $_POST['sp_event_team_nonce'] ) || ! wp_verify_nonce( $_POST['sp_event_team_nonce'], plugin_basename( __FILE__ ) ) ) return $post_id;
			foreach ( $sportspress as $key => $value ):
				if ( is_array( $value ) )
					$value = serialize( $value );
				update_post_meta( $post_id, $key, $value );
			endforeach;
		endif;
	endif;
}
add_action( 'save_post', 'sp_save_post' );
?>