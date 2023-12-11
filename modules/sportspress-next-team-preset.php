<?php
/**
 * Next Team Preset
 *
 * @author    ThemeBoy
 * @category  Modules
 * @package   SportsPress/Modules
 * @version   2.7.17
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SportsPress_Next_Team_Preset' ) ) :

	/**
	 * Main SportsPress Next Team Preset Class
	 *
	 * @class SportsPress_Next_Team_Preset
	 * @version 2.7.17
	 */

	class SportsPress_Next_Team_Preset {

		/** @var bool The link events setting. */
		public $link_events = true;

		/**
		 * Constructor
		 */
		public function __construct() {
			// Define constants
			$this->define_constants();

			$this->link_events = get_option( 'sportspress_link_events', 'yes' ) === 'yes' ? true : false;

			// Filters
			add_filter( 'sportspress_equation_options', array( $this, 'add_options' ) );
			add_filter( 'sportspress_equation_presets', array( $this, 'presets' ) );
			add_filter( 'sportspress_equation_solve_for_presets', array( $this, 'solve' ), 10, 3 );
			add_filter( 'sportspress_table_options', array( $this, 'add_settings' ) );

		}

		/**
		 * Define constants.
		 */
		private function define_constants() {
			if ( ! defined( 'SP_NEXT_TEAM_PRESET_VERSION' ) ) {
				define( 'SP_NEXT_TEAM_PRESET_VERSION', '2.7.17' );
			}

			if ( ! defined( 'SP_NEXT_TEAM_PRESET_URL' ) ) {
				define( 'SP_NEXT_TEAM_PRESET_URL', plugin_dir_url( __FILE__ ) );
			}

			if ( ! defined( 'SP_NEXT_TEAM_PRESET_DIR' ) ) {
				define( 'SP_NEXT_TEAM_PRESET_DIR', plugin_dir_path( __FILE__ ) );
			}
		}

		/**
		 * Add additional options.
		 *
		 * @return array
		 */
		public function add_options( $options ) {
			  $options['Presets']['$nextteam'] = esc_attr__( 'Next Team', 'sportspress' );
			  return $options;
		}

		/**
		 * Add preset
		 *
		 * @return array
		 */
		public function presets( $presets ) {
			  $presets[] = '$nextteam';
			  return $presets;
		}

		/**
		 * Solve preset
		 *
		 * @return mixed
		 */
		public function solve( $input, $equation, $post_id ) {
			if ( strpos( $equation, '$nextteam' ) !== false ) {
				$args = array(
					'post_type'      => 'sp_event',
					'numberposts'    => 1,
					'posts_per_page' => 1,
					'post_status'    => 'future',
					'meta_query'     => array(
						array(
							'key'     => 'sp_team',
							'value'   => $post_id,
							'compare' => 'IN',
						),
					),
					'order'          => 'ASC',
				);

				if ( get_option( 'sportspress_table_next_team_filter_league', 'no' ) === 'yes' ) {
					$leagues = get_the_terms( $post_id, 'sp_league' );
					if ( ! isset( $league_ids ) ) {
						$league_ids = array();
					}
					if ( empty( $league_ids ) && $leagues ) :
						foreach ( $leagues as $league ) :
							  $league_ids[] = $league->term_id;
					endforeach;
						  endif;
					$league_ids = sp_add_auto_term( $league_ids, $post_id, 'sp_league' );

					if ( isset( $league_ids ) ) {
						$args['tax_query'][] = array(
							'taxonomy' => 'sp_league',
							'field'    => 'term_id',
							'terms'    => $league_ids,
						);
					}
				}

				$events = get_posts( $args );

				if ( $events ) {
					$event = reset( $events );
					$teams = array_filter( (array) get_post_meta( $event->ID, 'sp_team', false ) );
					if ( ( $key = array_search( $post_id, $teams ) ) !== false ) {
						unset( $teams[ $key ] );
					} else {
						return '-';
					}

					$team_id = reset( $teams );

					if ( ! $team_id ) {
						return '-';
					}

					if ( has_post_thumbnail( $team_id ) ) {
						$logo = get_the_post_thumbnail( $team_id, 'sportspress-fit-icon' );
						$icon = '<span class="team-logo">' . $logo . '</span>';
					} else {
						$icon = sp_team_abbreviation( $team_id, true );
					}

					if ( $this->link_events ) {
						return '<a title="' . $event->post_title . '" href="' . get_post_permalink( $event->ID, false, true ) . '">' . $icon . '</a>';
					} else {
						return '<span title="' . $event->post_title . '">' . $icon . '</a>';
					}
				} else {
					return '-';
				}
			} else {
				return $input;
			}
		}

		/**
		 * Add settings.
		 *
		 * @return array
		 */
		public function add_settings( $settings ) {
			  $settings[] = array(
				  'title'   => esc_attr__( 'Next Team', 'sportspress' ),
				  'desc'    => esc_attr__( 'Filter by League', 'sportspress' ),
				  'id'      => 'sportspress_table_next_team_filter_league',
				  'default' => 'no',
				  'type'    => 'checkbox',
			  );
			  return $settings;
		}
	}

endif;

new SportsPress_Next_Team_Preset();
