<?php
function sportspress_admin_menu() {

	add_options_page(
		__( 'SportsPress Settings', 'sportspress' ),
		__( 'SportsPress', 'sportspress' ),
		'manage_options',
		'sportspress',
		'sportspress_settings'
	);

}
add_action( 'admin_menu', 'sportspress_admin_menu' );

function sportspress_settings() {
?>
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'SportsPress Settings', 'sportspress' ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'sportspress_stats' );
			do_settings_sections( 'sportspress_stats' );
			submit_button();
			?>
		</form>
		
	</div>
<?php
}

function sportspress_default_stats() {
	
	$defaults = array(

		'team'		=>	__( 'P', 'sportspress' ) . ': $appearances' . "\r\n" .
						__( 'W', 'sportspress' ) . ': $greater' . "\r\n" .
						__( 'D', 'sportspress' ) . ': $equal' . "\r\n" .
						__( 'L', 'sportspress' ) . ': $less' . "\r\n" .
						__( 'F', 'sportspress' ) . ': $for' . "\r\n" .
						__( 'A', 'sportspress' ) . ': $against' . "\r\n" .
						__( 'GD', 'sportspress' ) . ': $for - $against' . "\r\n" .
						__( 'PTS', 'sportspress' ) . ': 3 * $greater + $equal',

		'event'		=>	__( 'Goals', 'sportspress' ) . ': $goals' . "\r\n" .
						__( '1st Half', 'sportspress' ) . ': $firsthalf' . "\r\n" .
						__( '2nd Half', 'sportspress' ) . ': $secondhalf',

		'player'	=>	__( 'Goals', 'sportspress' ) . ': $goals' . "\r\n" .
						__( 'Assists', 'sportspress' ) . ': $assists' . "\r\n" .
						__( 'Yellow Cards', 'sportspress' ) . ': $yellowcards' . "\r\n" .
						__( 'Red Cards', 'sportspress' ) . ': $redcards'

	);
	
	return apply_filters( 'sportspress_default_stats', $defaults );
	
}

function sportspress_intialize_stats() {

	if( false == get_option( 'sportspress_stats' ) ) {	
		add_option( 'sportspress_stats', apply_filters( 'sportspress_default_stats', sportspress_default_stats() ) );
	}
	
	add_settings_section(
		'sportspress_stats',
		'',
		'',
		'sportspress_stats'
	);
	
	add_settings_field(	
		'sport',						
		__( 'Sport', 'sportspress' ),
		'sportspress_sport_callback',	
		'sportspress_stats',	
		'sportspress_stats'			
	);
	
	/*
	add_settings_field(	
		'team',						
		__( 'Teams',	'sportspress' ),
		'sportspress_team_stats_callback',	
		'sportspress_stats',	
		'sportspress_stats'			
	);
	
	add_settings_field(	
		'event',			
		__( 'Events', 'sportspress' ),			
		'sportspress_event_stats_callback',	
		'sportspress_stats',	
		'sportspress_stats'			
	);

	add_settings_field(	
		'player',	
		__( 'Players',	'sportspress' ),						
		'sportspress_player_stats_callback',	
		'sportspress_stats',	
		'sportspress_stats'			
	);
	*/
	
	register_setting(
		'sportspress_stats',
		'sportspress_stats'
	);
	
}
add_action( 'admin_init', 'sportspress_intialize_stats' );

function sportspress_sport_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'sport', 'sport' );
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