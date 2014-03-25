<?php
class SportsPressGeneralSettingsPage {
	public function __construct() {
		global $sportspress_options;
		$this->options =& $sportspress_options;
		add_action( 'admin_init', array( $this, 'page_init' ), 1 );
	}

	function page_init() {
		register_setting(
			'sportspress_general',
			'sportspress',
			'sportspress_options_validate'
		);
		
		add_settings_section(
			'general',
			__( 'General Options', 'sportspress' ),
			'',
			'sportspress_general'
		);
		
		add_settings_field(	
			'sport',
			__( 'Sport', 'sportspress' ),
			array( $this, 'sport_callback' ),
			'sportspress_general',
			'general'
		);
	}

	function sport_callback() {
		global $sportspress_options, $sportspress_sports;

		$selected = sportspress_array_value( $sportspress_options, 'sport', null );
		$custom_sport_name = sportspress_array_value( $sportspress_options, 'custom_sport_name', null );
		?>
		<fieldset>
			<select id="sportspress_sport" name="sportspress[sport]">
				<option value><?php _e( '&mdash; Select &mdash;', 'sportspress' ); ?></option>
				<?php foreach( $sportspress_sports as $slug => $sport ): ?>
					<option value="<?php echo $slug; ?>" <?php selected( $selected, $slug ); ?>><?php echo $sport['name']; ?></option>
				<?php endforeach; ?>
				<option value="custom" <?php selected( $selected, 'custom' ); ?>><?php _e( 'Custom', 'sportspress' ); ?></option>
			</select>
			<input id="sportspress_custom_sport_name" name="sportspress[custom_sport_name]" type="text" placeholder="<?php _e( 'Sport', 'sportspress' ); ?>" value="<?php echo $custom_sport_name; ?>"<?php if ( $selected != 'custom' ): ?> class="hidden"<?php endif; ?>>
		</fieldset>
		<?php
	}
}

if ( is_admin() )
	$sportspress_general_settings_page = new SportsPressGeneralSettingsPage();
