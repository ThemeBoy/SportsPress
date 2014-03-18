<?php
class SportsPressPermalinkSettingsSection {
	public function __construct() {
		$this->slugs = array(
			array( 'events', __( 'Events', 'sportspress' ) ),
			array( 'venue', __( 'Venues', 'sportspress' ) ),
			array( 'calendar', __( 'Calendars', 'sportspress' ) ),
			array( 'teams', __( 'Teams', 'sportspress' ) ),
			array( 'league', __( 'Leagues', 'sportspress' ) ),
			array( 'season', __( 'Seasons', 'sportspress' ) ),
			array( 'table', __( 'League Tables', 'sportspress' ) ),
			array( 'players', __( 'Players', 'sportspress' ) ),
			array( 'position', __( 'Positions', 'sportspress' ) ),
			array( 'list', __( 'Player Lists', 'sportspress' ) ),
			array( 'staff', __( 'Staff', 'sportspress' ) ),
		);

		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'admin_init', array( $this, 'settings_save' ) );
	}

	function settings_init() {		
		add_settings_section(
			'sportspress',
			__( 'SportsPress', 'sportspress' ),
			array( $this, 'settings' ),
			'permalink'
		);

		foreach ( $this->slugs as $slug ):
			add_settings_field(	
				$slug[0],
				$slug[1],
				array( $this, 'slug_callback' ),
				'permalink',
				'sportspress'
			);
		endforeach;
	}

	public function settings() {
		echo wpautop( __( 'These settings control the permalinks used for SportsPress. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'sportspress' ) );
	}

	public function slug_callback( $test ) {
		$slug = array_shift( $this->slugs );
		$key = $slug[0];
		$text = get_option( 'sportspress_' . $key . '_slug', null );
		?><fieldset><input id="sportspress_<?php echo $key; ?>_slug" name="sportspress_<?php echo $key; ?>_slug" type="text" class="regular-text code" value="<?php echo $text; ?>" placeholder="<?php echo $key; ?>"></fieldset><?php
	}

	public function settings_save() {
		if ( ! is_admin() )
			return;

		if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['category_base'] ) && isset( $_POST['product_permalink'] ) ):
			foreach ( $this->slugs as $slug ):
				$key = 'sportspress_' . $slug[0] . '_slug';
				$value = sanitize_text_field( $_POST[ $key ] );
				if ( empty( $value ) )
					delete_option( $key );
				else
					update_option( $key, $value );
			endforeach;
			sportspress_flush_rewrite_rules();
		endif;
	}
}

if ( is_admin() )
	$sportspress_permalink_settings_section = new SportsPressPermalinkSettingsSection();
