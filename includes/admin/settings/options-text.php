<?php
class SportsPressTextSettingsPage {
	private $strings = null;

	public function __construct() {
		global $sportspress_options;
		$this->options =& $sportspress_options;
		add_action( 'admin_init', array( $this, 'page_init' ), 1 );
	}

	function page_init() {
		register_setting(
			'sportspress_text',
			'sportspress',
			'sportspress_options_validate'
		);
		
		add_settings_section(
			'text',
			__( 'Text', 'sportspress' ),
			'',
			'sportspress_text'
		);

		$this->strings =& SP()->text->strings;
		foreach ( $this->strings as $string ):
			add_settings_field(	
				sanitize_title( $string ),
				$string,
				array( $this, 'text_callback' ),
				'sportspress_text',
				'text'
			);
		endforeach;
	}

	public function text_callback( $test ) {
		$string = array_shift( $this->strings );
		$key = sanitize_title( $string );
		$text = sportspress_array_value( sportspress_array_value( $this->options, 'text', array() ), $string, null );
		?><fieldset><input id="sportspress_text_<?php echo $key; ?>" name="sportspress[text][<?php echo $string; ?>]" type="text" class="regular-text" value="<?php echo $text; ?>" placeholder="<?php echo $string; ?>"></fieldset><?php
	}
}

if ( is_admin() )
	$sportspress_text_settings_page = new SportsPressTextSettingsPage();
