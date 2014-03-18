<?php
class SportsPressTextSettingsPage {
	public function __construct() {
		global $sportspress_options;
		$this->options =& $sportspress_options;

		$this->strings = array(
			array( 'league', __( 'League', 'sportspress' ) ),
			array( 'season', __( 'Season', 'sportspress' ) ),
			array( 'venue', __( 'Venue', 'sportspress' ) ),
			array( 'rank', __( 'Rank', 'sportspress' ) ),
			array( 'hash', '#' ),
			array( 'player', __( 'Player', 'sportspress' ) ),
			array( 'team', __( 'Team', 'sportspress' ) ),
			array( 'pos', __( 'Pos', 'sportspress' ) ),
			array( 'current_team', __( 'Current Team', 'sportspress' ) ),
		);
		usort( $this->strings, array( $this, 'compare_label' ) );

		add_action( 'admin_init', array( $this, 'page_init' ), 1 );
	}

	private function compare_label( $a, $b ) {
		return strcmp( $a[1], $b[1] );
	}


	function page_init() {
		register_setting(
			'sportspress_text',
			'sportspress',
			'sportspress_options_validate'
		);
		
		add_settings_section(
			'text',
			__( 'Text Settings', 'sportspress' ),
			'',
			'sportspress_text'
		);

		foreach ( $this->strings as $string ):
			add_settings_field(	
				$string[0],
				$string[1],
				array( $this, 'text_callback' ),
				'sportspress_text',
				'text'
			);
		endforeach;
	}

	public function text_callback( $test ) {
		$string = array_shift( $this->strings );
		$key = $string[0];
		$placeholder = $string[1];
		$text = sportspress_array_value( $this->options, $key . '_string', null );
		?><fieldset><input id="sportspress_<?php echo $key; ?>_string" name="sportspress[<?php echo $key; ?>_string]" type="text" value="<?php echo $text; ?>" placeholder="<?php echo $placeholder; ?>"></fieldset><?php
	}
}

if ( is_admin() )
	$sportspress_text_settings_page = new SportsPressTextSettingsPage();
