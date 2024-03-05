<?php
/**
 * Adds settings to the permalinks admin settings page.
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.7.20
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Admin_Permalink_Settings' ) ) :

	/**
	 * SP_Admin_Permalink_Settings Class
	 */
	class SP_Admin_Permalink_Settings {

		/**
		 * @var array
		 */
		public $slugs = array();
		
		/**
		 * Hook in tabs.
		 */
		public function __construct() {
			$this->slugs = apply_filters(
				'sportspress_permalink_slugs',
				array(
					array( 'event', esc_attr__( 'Events', 'sportspress' ) ),
					array( 'venue', esc_attr__( 'Venues', 'sportspress' ) ),
					array( 'calendar', esc_attr__( 'Calendars', 'sportspress' ) ),
					array( 'team', esc_attr__( 'Teams', 'sportspress' ) ),
					array( 'league', esc_attr__( 'Leagues', 'sportspress' ) ),
					array( 'season', esc_attr__( 'Seasons', 'sportspress' ) ),
					array( 'table', esc_attr__( 'League Tables', 'sportspress' ) ),
					array( 'player', esc_attr__( 'Players', 'sportspress' ) ),
					array( 'position', esc_attr__( 'Positions', 'sportspress' ) ),
					array( 'list', esc_attr__( 'Player Lists', 'sportspress' ) ),
					array( 'staff', esc_attr__( 'Staff', 'sportspress' ) ),
				)
			);

			add_action( 'admin_init', array( $this, 'settings_init' ) );
			add_action( 'admin_init', array( $this, 'settings_save' ) );
		}

		/**
		 * Init our settings
		 */
		public function settings_init() {
			// Add a section to the permalinks page
			add_settings_section( 'sportspress-permalink', esc_attr__( 'SportsPress', 'sportspress' ), array( $this, 'settings' ), 'permalink' );

			// Add our settings
			foreach ( $this->slugs as $slug ) :
				add_settings_field(
					$slug[0],                           // id
					$slug[1],                           // setting title
					array( $this, 'slug_input' ),       // display callback
					'permalink',                        // settings page
					'sportspress-permalink'             // settings section
				);
			endforeach;
		}

		/**
		 * Show a slug input box.
		 */
		public function slug_input() {
			$slug = array_shift( $this->slugs );
			$key  = $slug[0];
			$text = get_option( 'sportspress_' . $key . '_slug', null );
			?><fieldset><input id="sportspress_<?php echo esc_attr( $key ); ?>_slug" name="sportspress_<?php echo esc_attr( $key ); ?>_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $text ); ?>" placeholder="<?php echo esc_attr( $key ); ?>"></fieldset>
			<?php
		}

		/**
		 * Show the settings
		 */
		public function settings() {
			echo wp_kses_post( wpautop( __( 'These settings control the permalinks used for SportsPress. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'sportspress' ) ) );
        wp_nonce_field( plugin_basename( __FILE__ ), 'sp_permalink_nonce' );
		}

		/**
		 * Save the settings
		 */
		public function settings_save() {
			if ( ! is_admin() ) :
				return;
			endif;

			if ( ! is_user_logged_in() ) :
				return;
			endif;

			if ( ! current_user_can( 'manage_sportspress' ) ) :
				return;
			endif;

			if ( isset( $_POST['permalink_structure'] ) || isset( $_POST['sportspress_event_slug'] ) ) :
        if ( ! isset( $_POST['sp_permalink_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['sp_permalink_nonce'] ), plugin_basename( __FILE__ ) ) ) :
          return;
        endif;

				foreach ( $this->slugs as $slug ) :
					$key   = 'sportspress_' . $slug[0] . '_slug';
					$value = null;
					if ( isset( $_POST[ $key ] ) ) {
						$value = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
					}
					if ( empty( $value ) ) {
						delete_option( $key );
					} else {
						update_option( $key, $value );
					}
				endforeach;
				sp_flush_rewrite_rules();
			endif;
		}
	}

endif;

return new SP_Admin_Permalink_Settings();
