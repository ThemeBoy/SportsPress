<?php
/**
 * Countdowns
 *
 * @author    ThemeBoy
 * @category  Modules
 * @package   SportsPress/Modules
 * @version   2.7.9
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SportsPress_Countdowns' ) ) :

	/**
	 * Main SportsPress Countdowns Class
	 *
	 * @class SportsPress_Countdowns
	 * @version 2.7
	 */
	class SportsPress_Countdowns {

		/**
		 * Constructor
		 */
		public function __construct() {
			// Define constants
			$this->define_constants();

			// Actions
			add_action( 'sportspress_widgets', array( $this, 'include_widgets' ) );

			// Filters
			add_filter( 'sportspress_shortcodes', array( $this, 'add_shortcodes' ) );
			add_filter( 'sportspress_event_settings', array( $this, 'add_settings' ) );
			add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		}

		/**
		 * Define constants.
		 */
		private function define_constants() {
			if ( ! defined( 'SP_COUNTDOWNS_VERSION' ) ) {
				define( 'SP_COUNTDOWNS_VERSION', '2.7' );
			}

			if ( ! defined( 'SP_COUNTDOWNS_URL' ) ) {
				define( 'SP_COUNTDOWNS_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( ! defined( 'SP_COUNTDOWNS_DIR' ) ) {
				define( 'SP_COUNTDOWNS_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

		/**
		 * Add widgets.
		 *
		 * @return array
		 */
		public function include_widgets() {
			include_once SP()->plugin_path() . '/includes/widgets/class-sp-widget-countdown.php';
		}

		/**
		 * Add shortcodes.
		 *
		 * @return array
		 */
		public function add_shortcodes( $shortcodes ) {
			$shortcodes['event'][] = 'countdown';
			return $shortcodes;
		}

		/**
		 * Add settings.
		 *
		 * @return array
		 */
		public function add_settings( $settings ) {
			$settings = array_merge(
				$settings,
				array(
					array(
						'title' => esc_attr__( 'Countdown', 'sportspress' ),
						'type'  => 'title',
						'id'    => 'countdown_options',
					),
				),
				apply_filters(
					'sportspress_countdown_options',
					array(
						array(
							'title'         => esc_attr__( 'Display', 'sportspress' ),
							'desc'          => esc_attr__( 'Logos', 'sportspress' ),
							'id'            => 'sportspress_countdown_show_logos',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => 'start',
						),
						array(
							'desc'          => esc_attr__( 'Featured Image', 'sportspress' ),
							'id'            => 'sportspress_countdown_show_thumbnail',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => 'end',
						),
					)
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'countdown_options',
					),
				)
			);
			return $settings;
		}

		/**
		 * Add text options
		 */
		public function add_text_options( $options = array() ) {
			return array_merge(
				$options,
				array(
					__( 'days', 'sportspress' ),
					__( 'hrs', 'sportspress' ),
					__( 'mins', 'sportspress' ),
					__( 'secs', 'sportspress' ),
				)
			);
		}
	}

endif;

new SportsPress_Countdowns();
