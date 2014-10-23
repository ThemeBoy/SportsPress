<?php
/**
 * Adds settings to the permalinks admin settings page.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Permalink_Settings' ) ) :

/**
 * SP_Admin_Permalink_Settings Class
 */
class SP_Admin_Permalink_Settings {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		$this->slugs = apply_filters( 'sportspress_permalink_slugs', array(
			array( 'event', __( 'Events', 'sportspress' ) ),
			array( 'venue', __( 'Venues', 'sportspress' ) ),
			array( 'calendar', __( 'Calendars', 'sportspress' ) ),
			array( 'team', __( 'Teams', 'sportspress' ) ),
			array( 'league', __( 'Competitions', 'sportspress' ) ),
			array( 'season', __( 'Seasons', 'sportspress' ) ),
			array( 'table', __( 'League Tables', 'sportspress' ) ),
			array( 'player', __( 'Players', 'sportspress' ) ),
			array( 'position', __( 'Positions', 'sportspress' ) ),
			array( 'list', __( 'Player Lists', 'sportspress' ) ),
			array( 'staff', __( 'Staff', 'sportspress' ) ),
		) );

		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_action( 'admin_init', array( $this, 'settings_save' ) );
	}

	/**
	 * Init our settings
	 */
	public function settings_init() {
		// Add a section to the permalinks page
		add_settings_section( 'sportspress-permalink', __( 'SportsPress', 'sportspress' ), array( $this, 'settings' ), 'permalink' );

		// Add our settings
		foreach ( $this->slugs as $slug ):
			add_settings_field(	
				$slug[0],							// id
				$slug[1],							// setting title
				array( $this, 'slug_input' ),		// display callback
				'permalink',						// settings page
				'sportspress-permalink'				// settings section
			);
		endforeach;
	}

	/**
	 * Show a slug input box.
	 */
	public function slug_input() {
		$slug = array_shift( $this->slugs );
		$key = $slug[0];
		$text = get_option( 'sportspress_' . $key . '_slug', null );
		?><fieldset><input id="sportspress_<?php echo $key; ?>_slug" name="sportspress_<?php echo $key; ?>_slug" type="text" class="regular-text code" value="<?php echo $text; ?>" placeholder="<?php echo $key; ?>"></fieldset><?php
	}

	/**
	 * Show the settings
	 */
	public function settings() {
		echo wpautop( __( 'These settings control the permalinks used for SportsPress. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'sportspress' ) );
	}

	/**
	 * Save the settings
	 */
	public function settings_save() {
		if ( ! is_admin() )
			return;

		if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['sportspress_event_slug'] ) ):
			foreach ( $this->slugs as $slug ):
				$key = 'sportspress_' . $slug[0] . '_slug';
				$value = null;
				if ( isset( $_POST[ $key ] ) )
					$value = sanitize_text_field( $_POST[ $key ] );
				if ( empty( $value ) )
					delete_option( $key );
				else
					update_option( $key, $value );
			endforeach;
			sp_flush_rewrite_rules();
		endif;
	}
}

endif;

return new SP_Admin_Permalink_Settings();