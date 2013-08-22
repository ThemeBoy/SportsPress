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

function sportspress_settings( $tab = '' ) {
?>
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2><?php _e( 'SportsPress Settings', 'sportspress' ); ?></h2>
		<?php settings_errors(); ?>
		
		<?php
		if( isset( $_GET[ 'tab' ] ) ) {
			$tab = $_GET[ 'tab' ];
		} else if( $tab == 'stats' ) {
			$tab = 'stats';
		} else {
			$tab = 'display_options';
		}
		?>
		
		<h2 class="nav-tab-wrapper">
			<a href="?page=sportspress&tab=display_options" class="nav-tab <?php echo $tab == 'display_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Display Options', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=stats" class="nav-tab <?php echo $tab == 'stats' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Statistics', 'sportspress' ); ?></a>
		</h2>
		
		<form method="post" action="options.php">
			<?php
			
				if( $tab == 'display_options' ) {
				
					settings_fields( 'sportspress_options' );
					do_settings_sections( 'sportspress_options' );
					
				} else {
				
					settings_fields( 'sportspress_stats' );
					do_settings_sections( 'sportspress_stats' );
					
				}
				
				submit_button();
			
			?>
		</form>
		
	</div><!-- /.wrap -->
<?php
} // end sportspress_settings

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */ 

/**
 * Provides default values for the Display Options.
 */
function sportspress_default_display_options() {
	
	$defaults = array(
		'staff'		=>	'',
		'table'		=>	'',
		'list'		=>	''
	);
	
	return apply_filters( 'sportspress_default_display_options', $defaults );
	
} // end sportspress_default_display_options

/**
 * Provides default values for the Statistics.
 */
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
	
} // end sportspress_default_stats

/**
 * Initializes the theme's display options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function sportspress_initialize_theme_options() {

	// If the theme options don't exist, create them.
	if ( false == get_option( 'sportspress_options' ) ) {	
		add_option( 'sportspress_options', apply_filters( 'sportspress_default_display_options', sportspress_default_display_options() ) );
	} // end if

	// First, we register a section. This is necessary since all future options must belong to a 
	add_settings_section(
		'general_settings_section',			// ID used to identify this section and with which to register options
		__( 'Display Options', 'sportspress' ),		// Title to be displayed on the administration page
		'',	// Callback used to render the description of the section
		'sportspress_options'		// Page on which to add this section of options
	);
	
	// Next, we'll introduce the fields for toggling the visibility of content elements.
	add_settings_field(	
		'staff',						// ID used to identify the field throughout the theme
		__( 'Staff', 'sportspress' ),							// The label to the left of the option interface element
		'sportspress_toggle_staff_callback',	// The name of the function responsible for rendering the option interface
		'sportspress_options',	// The page on which this option will be displayed
		'general_settings_section'
	);
	
	add_settings_field(	
		'table',						
		__( 'League Tables', 'sportspress' ),				
		'sportspress_toggle_table_callback',	
		'sportspress_options',					
		'general_settings_section'
	);
	
	add_settings_field(	
		'list',						
		__( 'Player Lists', 'sportspress' ),				
		'sportspress_toggle_list_callback',	
		'sportspress_options',		
		'general_settings_section'
	);
	
	// Finally, we register the fields with WordPress
	register_setting(
		'sportspress_options',
		'sportspress_options'
	);
	
} // end sportspress_initialize_theme_options
add_action( 'admin_init', 'sportspress_initialize_theme_options' );

/**
 * Initializes the theme's social options by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function sportspress_intialize_stats() {

	if( false == get_option( 'sportspress_stats' ) ) {	
		add_option( 'sportspress_stats', apply_filters( 'sportspress_default_stats', sportspress_default_stats() ) );
	} // end if
	
	add_settings_section(
		'sportspress_stats',			// ID used to identify this section and with which to register options
		'',		// Title to be displayed on the administration page
		'',	// Callback used to render the description of the section
		'sportspress_stats'		// Page on which to add this section of options
	);
	
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
	
	register_setting(
		'sportspress_stats',
		'sportspress_stats'
	);
	
} // end sportspress_intialize_stats
add_action( 'admin_init', 'sportspress_intialize_stats' );

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function renders the interface elements for toggling the visibility of the header element.
 * 
 * It accepts an array or arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */
function sportspress_toggle_staff_callback() {
	
	$options = get_option('sportspress_options');
	
	$html = '<input type="checkbox" id="staff" name="sportspress_options[staff]" value="1" ' . checked( 1, isset( $options['staff'] ) ? $options['staff'] : 0, false ) . '/>'; 
	
	echo $html;
	
} // end sportspress_toggle_staff_callback

function sportspress_toggle_table_callback() {

	$options = get_option('sportspress_options');
	
	$html = '<input type="checkbox" id="show_content" name="sportspress_options[show_content]" value="1" ' . checked( 1, isset( $options['show_content'] ) ? $options['show_content'] : 0, false ) . '/>'; 
	
	echo $html;
	
} // end sportspress_toggle_table_callback

function sportspress_toggle_list_callback() {
	
	$options = get_option('sportspress_options');
	
	$html = '<input type="checkbox" id="show_footer" name="sportspress_options[show_footer]" value="1" ' . checked( 1, isset( $options['show_footer'] ) ? $options['show_footer'] : 0, false ) . '/>'; 
	
	echo $html;
	
} // end sportspress_toggle_list_callback

function sportspress_team_stats_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'team', 'textarea' );
}

function sportspress_event_stats_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'event', 'textarea' );
}

function sportspress_player_stats_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'player', 'textarea' );
}

/* ------------------------------------------------------------------------ *
 * Setting Callbacks
 * ------------------------------------------------------------------------ */ 
 
/**
 * Sanitization callback for the social options. Since each of the social options are text inputs,
 * this function loops through the incoming option and strips all tags and slashes from the value
 * before serializing it.
 *	
 * @params	$input	The unsanitized collection of options.
 *
 * @returns			The collection of sanitized values.
 */
function sportspress_sanitize_stats( $input ) {
	
	// Define the array for the updated options
	$output = array();

	// Loop through each of the options sanitizing the data
	foreach( $input as $key => $val ) {
	
		if( isset ( $input[$key] ) ) {
			$output[$key] = esc_url_raw( strip_tags( stripslashes( $input[$key] ) ) );
		} // end if	
	
	} // end foreach
	
	// Return the new collection
	return apply_filters( 'sportspress_sanitize_stats', $output, $input );

} // end sportspress_sanitize_stats

?>