<?php
function sportspress_settings() {

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

?>
	<div class="wrap">

		<h2 class="nav-tab-wrapper">
			<a href="?page=sportspress" class="nav-tab<?php echo $active_tab == 'general' ? ' nav-tab-active' : ''; ?>"><?php _e( 'SportsPress', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=events" class="nav-tab<?php echo $active_tab == 'events' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Events', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=teams" class="nav-tab<?php echo $active_tab == 'teams' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Teams', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=players" class="nav-tab<?php echo $active_tab == 'players' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Players', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=staff" class="nav-tab<?php echo $active_tab == 'staff' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Staff', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=config" class="nav-tab<?php echo $active_tab == 'config' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Configure', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=advanced" class="nav-tab<?php echo $active_tab == 'advanced' ? ' nav-tab-active' : ''; ?>"><?php _e( 'Advanced', 'sportspress' ); ?></a>
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

function sportspress_sport_callback() {
	global $sportspress_sports;
	$options = get_option( 'sportspress' );
	?>
	<select id="sportspress_sport" name="sportspress[sport]">
		<?php foreach( $sportspress_sports as $slug => $sport ): ?>
			<option value="<?php echo $slug; ?>" <?php selected( $options['sport'], $slug ); ?>><?php _e( $sport['name'], 'sportspress' ); ?></option>
		<?php endforeach; ?>
	</select>
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
