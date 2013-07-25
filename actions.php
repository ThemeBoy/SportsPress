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