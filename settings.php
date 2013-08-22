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
		} else if( $tab == 'input_examples' ) {
			$tab = 'input_examples';
		} else {
			$tab = 'display_options';
		}
		?>
		
		<h2 class="nav-tab-wrapper">
			<a href="?page=sportspress&tab=display_options" class="nav-tab <?php echo $tab == 'display_options' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Display Options', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=stats" class="nav-tab <?php echo $tab == 'stats' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Statistics', 'sportspress' ); ?></a>
			<a href="?page=sportspress&tab=input_examples" class="nav-tab <?php echo $tab == 'input_examples' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Input Examples', 'sportspress' ); ?></a>
		</h2>
		
		<form method="post" action="options.php">
			<?php
			
				if( $tab == 'display_options' ) {
				
					settings_fields( 'sportspress_settings_options' );
					do_settings_sections( 'sportspress_settings_options' );
					
				} elseif( $tab == 'stats' ) {
				
					settings_fields( 'sportspress_stats' );
					do_settings_sections( 'sportspress_stats' );
					
				} else {
				
					settings_fields( 'sportspress_input_examples' );
					do_settings_sections( 'sportspress_input_examples' );
					
				} // end if/else
				
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
 * Provides default values for the Statistics.
 */
function sportspress_default_stats() {
	
	$defaults = array(
		'team'		=>	'',
		'event'	=>	'',
		'player'		=>	''
	);
	
	return apply_filters( 'sportspress_default_stats', $defaults );
	
} // end sportspress_default_stats

/**
 * Provides default values for the Display Options.
 */
function sportspress_default_display_options() {
	
	$defaults = array(
		'show_header'		=>	'',
		'show_content'		=>	'',
		'show_footer'		=>	''
	);
	
	return apply_filters( 'sportspress_default_display_options', $defaults );
	
} // end sportspress_default_display_options

/**
 * Provides default values for the Input Options.
 */
function sportspress_default_input_options() {
	
	$defaults = array(
		'input_example'		=>	'',
		'textarea_example'	=>	'',
		'checkbox_example'	=>	'',
		'radio_example'		=>	'',
		'time_options'		=>	'default'	
	);
	
	return apply_filters( 'sportspress_default_input_options', $defaults );
	
} // end sportspress_default_input_options

/**
 * Initializes the theme's display options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function sportspress_initialize_theme_options() {

	// If the theme options don't exist, create them.
	if ( false == get_option( 'sportspress_settings_options' ) ) {	
		add_option( 'sportspress_settings_options', apply_filters( 'sportspress_default_display_options', sportspress_default_display_options() ) );
	} // end if

	// First, we register a section. This is necessary since all future options must belong to a 
	add_settings_section(
		'general_settings_section',			// ID used to identify this section and with which to register options
		__( 'Display Options', 'sportspress' ),		// Title to be displayed on the administration page
		'',	// Callback used to render the description of the section
		'sportspress_settings_options'		// Page on which to add this section of options
	);
	
	// Next, we'll introduce the fields for toggling the visibility of content elements.
	add_settings_field(	
		'show_header',						// ID used to identify the field throughout the theme
		__( 'Header', 'sportspress' ),							// The label to the left of the option interface element
		'sportspress_toggle_header_callback',	// The name of the function responsible for rendering the option interface
		'sportspress_settings_options',	// The page on which this option will be displayed
		'general_settings_section',			// The name of the section to which this field belongs
		array(								// The array of arguments to pass to the callback. In this case, just a description.
			__( 'Activate this setting to display the header.', 'sportspress' ),
		)
	);
	
	add_settings_field(	
		'show_content',						
		__( 'Content', 'sportspress' ),				
		'sportspress_toggle_content_callback',	
		'sportspress_settings_options',					
		'general_settings_section',			
		array(								
			__( 'Activate this setting to display the content.', 'sportspress' ),
		)
	);
	
	add_settings_field(	
		'show_footer',						
		__( 'Footer', 'sportspress' ),				
		'sportspress_toggle_footer_callback',	
		'sportspress_settings_options',		
		'general_settings_section',			
		array(								
			__( 'Activate this setting to display the footer.', 'sportspress' ),
		)
	);
	
	// Finally, we register the fields with WordPress
	register_setting(
		'sportspress_settings_options',
		'sportspress_settings_options'
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

	add_settings_field(	
		'staff',
		__( 'Staff', 'sportspress' ),						
		'sportspress_staff_stats_callback',
		'sportspress_stats',
		'sportspress_stats'
	);
	
	register_setting(
		'sportspress_stats',
		'sportspress_stats'
	);
	
} // end sportspress_intialize_stats
add_action( 'admin_init', 'sportspress_intialize_stats' );

/**
 * Initializes the theme's input example by registering the Sections,
 * Fields, and Settings. This particular group of options is used to demonstration
 * validation and sanitization.
 *
 * This function is registered with the 'admin_init' hook.
 */ 
function sportspress_initialize_input_examples() {

	if( false == get_option( 'sportspress_input_examples' ) ) {	
		add_option( 'sportspress_input_examples', apply_filters( 'sportspress_default_input_options', sportspress_default_input_options() ) );
	} // end if

	add_settings_section(
		'input_examples_section',
		__( 'Input Examples', 'sportspress' ),
		'',
		'sportspress_input_examples'
	);
	
	add_settings_field(	
		'Input Element',						
		__( 'Input Element', 'sportspress' ),							
		'sportspress_input_element_callback',	
		'sportspress_input_examples',	
		'input_examples_section'			
	);
	
	add_settings_field(	
		'Textarea Element',						
		__( 'Textarea Element', 'sportspress' ),							
		'sportspress_textarea_element_callback',	
		'sportspress_input_examples',	
		'input_examples_section'			
	);
	
	add_settings_field(
		'Checkbox Element',
		__( 'Checkbox Element', 'sportspress' ),
		'sportspress_checkbox_element_callback',
		'sportspress_input_examples',
		'input_examples_section'
	);
	
	add_settings_field(
		'Radio Button Elements',
		__( 'Radio Button Elements', 'sportspress' ),
		'sportspress_radio_element_callback',
		'sportspress_input_examples',
		'input_examples_section'
	);
	
	add_settings_field(
		'Select Element',
		__( 'Select Element', 'sportspress' ),
		'sportspress_select_element_callback',
		'sportspress_input_examples',
		'input_examples_section'
	);
	
	register_setting(
		'sportspress_input_examples',
		'sportspress_input_examples',
		'sportspress_validate_input_examples'
	);

} // end sportspress_initialize_input_examples
add_action( 'admin_init', 'sportspress_initialize_input_examples' );

/* ------------------------------------------------------------------------ *
 * Field Callbacks
 * ------------------------------------------------------------------------ */ 

/**
 * This function renders the interface elements for toggling the visibility of the header element.
 * 
 * It accepts an array or arguments and expects the first element in the array to be the description
 * to be displayed next to the checkbox.
 */
function sportspress_toggle_header_callback($args) {
	
	// First, we read the options collection
	$options = get_option('sportspress_settings_options');
	
	// Next, we update the name attribute to access this element's ID in the context of the display options array
	// We also access the show_header element of the options collection in the call to the checked() helper function
	$html = '<input type="checkbox" id="show_header" name="sportspress_settings_options[show_header]" value="1" ' . checked( 1, isset( $options['show_header'] ) ? $options['show_header'] : 0, false ) . '/>'; 
	
	// Here, we'll take the first argument of the array and add it to a label next to the checkbox
	$html .= '<label for="show_header">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end sportspress_toggle_header_callback

function sportspress_toggle_content_callback($args) {

	$options = get_option('sportspress_settings_options');
	
	$html = '<input type="checkbox" id="show_content" name="sportspress_settings_options[show_content]" value="1" ' . checked( 1, isset( $options['show_content'] ) ? $options['show_content'] : 0, false ) . '/>'; 
	$html .= '<label for="show_content">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end sportspress_toggle_content_callback

function sportspress_toggle_footer_callback($args) {
	
	$options = get_option('sportspress_settings_options');
	
	$html = '<input type="checkbox" id="show_footer" name="sportspress_settings_options[show_footer]" value="1" ' . checked( 1, isset( $options['show_footer'] ) ? $options['show_footer'] : 0, false ) . '/>'; 
	$html .= '<label for="show_footer">&nbsp;'  . $args[0] . '</label>'; 
	
	echo $html;
	
} // end sportspress_toggle_footer_callback

function sportspress_team_stats_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'team', 'textarea' );
}

function sportspress_event_stats_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'event', 'textarea' );
}

function sportspress_player_stats_callback() {
	sportspress_render_option_field( 'sportspress_stats', 'player', 'textarea' );
}

function sportspress_input_element_callback() {
	
	$options = get_option( 'sportspress_input_examples' );
	
	// Render the output
	echo '<input type="text" id="input_example" name="sportspress_input_examples[input_example]" value="' . $options['input_example'] . '" />';
	
} // end sportspress_input_element_callback

function sportspress_textarea_element_callback() {
	
	$options = get_option( 'sportspress_input_examples' );
	
	// Render the output
	echo '<textarea id="textarea_example" name="sportspress_input_examples[textarea_example]" rows="5" cols="50">' . $options['textarea_example'] . '</textarea>';
	
} // end sportspress_textarea_element_callback

function sportspress_checkbox_element_callback() {

	$options = get_option( 'sportspress_input_examples' );
	
	$html = '<input type="checkbox" id="checkbox_example" name="sportspress_input_examples[checkbox_example]" value="1"' . checked( 1, $options['checkbox_example'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="checkbox_example">This is an example of a checkbox</label>';
	
	echo $html;

} // end sportspress_checkbox_element_callback

function sportspress_radio_element_callback() {

	$options = get_option( 'sportspress_input_examples' );
	
	$html = '<input type="radio" id="radio_example_one" name="sportspress_input_examples[radio_example]" value="1"' . checked( 1, $options['radio_example'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="radio_example_one">Option One</label>';
	$html .= '&nbsp;';
	$html .= '<input type="radio" id="radio_example_two" name="sportspress_input_examples[radio_example]" value="2"' . checked( 2, $options['radio_example'], false ) . '/>';
	$html .= '&nbsp;';
	$html .= '<label for="radio_example_two">Option Two</label>';
	
	echo $html;

} // end sportspress_radio_element_callback

function sportspress_select_element_callback() {

	$options = get_option( 'sportspress_input_examples' );
	
	$html = '<select id="time_options" name="sportspress_input_examples[time_options]">';
		$html .= '<option value="default">' . __( 'Select a time option...', 'sportspress' ) . '</option>';
		$html .= '<option value="never"' . selected( $options['time_options'], 'never', false) . '>' . __( 'Never', 'sportspress' ) . '</option>';
		$html .= '<option value="sometimes"' . selected( $options['time_options'], 'sometimes', false) . '>' . __( 'Sometimes', 'sportspress' ) . '</option>';
		$html .= '<option value="always"' . selected( $options['time_options'], 'always', false) . '>' . __( 'Always', 'sportspress' ) . '</option>';	$html .= '</select>';
	
	echo $html;

} // end sportspress_radio_element_callback

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

function sportspress_validate_input_examples( $input ) {

	// Create our array for storing the validated options
	$output = array();
	
	// Loop through each of the incoming options
	foreach( $input as $key => $value ) {
		
		// Check to see if the current option has a value. If so, process it.
		if( isset( $input[$key] ) ) {
		
			// Strip all HTML and PHP tags and properly handle quoted strings
			$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
			
		} // end if
		
	} // end foreach
	
	// Return the array processing any additional functions filtered by this action
	return apply_filters( 'sportspress_validate_input_examples', $output, $input );

} // end sportspress_validate_input_examples

?>