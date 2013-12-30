<?php
function sportspress_admin_menu() {

	add_options_page(
		__( 'SportsPress', 'sportspress' ),
		__( 'SportsPress', 'sportspress' ),
		'manage_options',
		'sportspress',
		'sportspress_settings'
	);

}
add_action( 'admin_menu', 'sportspress_admin_menu' );

function sportspress_settings() {

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

?>
	<div class="wrap">

		<h2 class="nav-tab-wrapper">
			<a href="?page=sportspress" class="nav-tab<?php echo $active_tab == 'general' ? ' nav-tab-active' : ''; ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=config" class="nav-tab<?php echo $active_tab == 'config' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Configure', 'sportspress' ); ?></a>
		</h2>

		<form method="post" action="options.php">
			<?php
				switch ( $active_tab ):
					case 'config':
						include 'config.php';
						break;
					default:
						include 'general.php';
				endswitch;
			?>
		</form>
		
	</div>
<?php
}

function sportspress_validate( $input ) {
	
	$original = get_option( 'sportspress' );

	if ( sp_array_value( $original, 'sport', null ) != sp_array_value( $input, 'sport', null ) ):

		global $sportspress_sports;

		$post_groups = sp_array_value( sp_array_value( $sportspress_sports, sp_array_value( $input, 'sport', null ), array() ), 'posts', array() );

		foreach( $post_groups as $post_type => $posts ):

			// Delete posts
			$old_posts = get_posts( array( 'post_type' => $post_type, 'numberposts' => -1, 'posts_per_page' => -1 ) );
			foreach( $old_posts as $post ):
				wp_delete_post( $post->ID, true);
			endforeach;

				// Add posts
			foreach( $posts as $index => $post ):
				$post['post_type'] = $post_type;
				if ( ! get_page_by_path( $post['post_name'], OBJECT, $post['post_type'] ) ):
					$post['menu_order'] = $index;
					$post['post_status'] = 'publish';
					$id = wp_insert_post( $post );
					if ( array_key_exists( 'meta', $post ) ):
						foreach ( $post['meta'] as $key => $value ):
							update_post_meta( $id, $key, $value );
						endforeach;
					endif;
				endif;
			endforeach;

		endforeach;

	endif;

	return $input;
}

function sportspress_register_settings() {
	
	register_setting(
		'sportspress_general',
		'sportspress',
		'sportspress_validate'
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
	
}
add_action( 'admin_init', 'sportspress_register_settings' );

function sportspress_sport_callback() {
	global $sportspress_sports;
	$options = get_option( 'sportspress' );
	?>
	<select id="sportspress_sport" name="sportspress[sport]">
		<?php foreach( $sportspress_sports as $slug => $sport ): ?>
			<option value="<?php echo $slug; ?>" <?php selected( $options['sport'], $slug ); ?>><?php echo $sport['name']; ?></option>
		<?php endforeach; ?>
	</select>
	<?
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

?>