<?php
/**
 * SportsPress Event Settings
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Settings_Events' ) ) :

	/**
	 * SP_Settings_Events
	 */
	class SP_Settings_Events extends SP_Settings_Page {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->id       = 'events';
			$this->label    = esc_attr__( 'Events', 'sportspress' );
			$this->template = 'event';

			add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'sportspress_admin_field_delimiter', array( $this, 'delimiter_setting' ) );
			add_action( 'sportspress_admin_field_event_layout', array( $this, 'layout_setting' ) );
			add_action( 'sportspress_admin_field_event_tabs', array( $this, 'tabs_setting' ) );
			add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
		}

		/**
		 * Get settings array
		 *
		 * @return array
		 */
		public function get_settings() {

			$settings = array_merge(
				array(
					array(
						'title' => esc_attr__( 'Event Options', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'event_options',
					),
				),
				apply_filters(
					'sportspress_event_options',
					array_merge(
						array(
							array(
								'title'   => esc_attr__( 'Link', 'sportspress' ),
								'desc'    => esc_attr__( 'Link events', 'sportspress' ),
								'id'      => 'sportspress_link_events',
								'default' => 'yes',
								'type'    => 'checkbox',
							),
						),
						apply_filters(
							'sportspress_event_template_options',
							array(
								array( 'type' => 'event_layout' ),

								array( 'type' => 'event_tabs' ),

								array(
									'title'         => esc_attr__( 'Details', 'sportspress' ),
									'desc'          => esc_attr__( 'Date', 'sportspress' ),
									'id'            => 'sportspress_event_show_date',
									'default'       => 'yes',
									'type'          => 'checkbox',
									'checkboxgroup' => 'start',
								),

								array(
									'desc'          => esc_attr__( 'Time', 'sportspress' ),
									'id'            => 'sportspress_event_show_time',
									'default'       => 'yes',
									'type'          => 'checkbox',
									'checkboxgroup' => '',
								),

								array(
									'desc'          => esc_attr__( 'Match Day', 'sportspress' ),
									'id'            => 'sportspress_event_show_day',
									'default'       => 'no',
									'type'          => 'checkbox',
									'checkboxgroup' => '',
								),

								array(
									'desc'          => esc_attr__( 'Full Time', 'sportspress' ),
									'id'            => 'sportspress_event_show_full_time',
									'default'       => 'no',
									'type'          => 'checkbox',
									'checkboxgroup' => 'end',
								),
							)
						),
						array(
							array(
								'title'    => esc_attr__( 'Default mode', 'sportspress' ),
								'id'       => 'sportspress_mode',
								'default'  => 'team',
								'type'     => 'radio',
								'options'  => array(
									'team'   => esc_attr__( 'Team vs team', 'sportspress' ),
									'player' => esc_attr__( 'Player vs player', 'sportspress' ),
								),
								'desc_tip' => _x( 'Who competes in events?', 'mode setting description', 'sportspress' ),
							),

							array(
								'title'             => esc_attr__( 'Limit', 'sportspress' ),
								'id'                => 'sportspress_event_teams',
								'class'             => 'small-text',
								'default'           => '2',
								'desc'              => esc_attr__( 'teams', 'sportspress' ),
								'type'              => 'number',
								'custom_attributes' => array(
									'min'  => 0,
									'step' => 1,
								),
							),

							array( 'type' => 'delimiter' ),

							array(
								'title'         => esc_attr__( 'Teams', 'sportspress' ),
								'desc'          => esc_attr__( 'Filter by league', 'sportspress' ),
								'id'            => 'sportspress_event_filter_teams_by_league',
								'default'       => 'no',
								'type'          => 'checkbox',
								'checkboxgroup' => 'start',
							),

							array(
								'desc'          => esc_attr__( 'Filter by season', 'sportspress' ),
								'id'            => 'sportspress_event_filter_teams_by_season',
								'default'       => 'no',
								'type'          => 'checkbox',
								'checkboxgroup' => 'end',
							),

							array(
								'title'             => esc_attr__( 'Full Time', 'sportspress' ),
								'id'                => 'sportspress_event_minutes',
								'class'             => 'small-text',
								'default'           => '90',
								'desc'              => esc_attr__( 'mins', 'sportspress' ),
								'type'              => 'number',
								'custom_attributes' => array(
									'min'  => 0,
									'step' => 1,
								),
							),

							array(
								'title'   => esc_attr__( 'Comments', 'sportspress' ),
								'desc'    => esc_attr__( 'Allow people to post comments on new articles', 'sportspress' ),
								'id'      => 'sportspress_event_comment_status',
								'default' => 'no',
								'type'    => 'checkbox',
							),
						)
					)
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'event_options',
					),
				),
				array(
					array(
						'title' => esc_attr__( 'Venues', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'venue_options',
					),
				),
				apply_filters(
					'sportspress_venue_options',
					array(
						array(
							'title'   => esc_attr__( 'Link', 'sportspress' ),
							'desc'    => esc_attr__( 'Link venues', 'sportspress' ),
							'id'      => 'sportspress_link_venues',
							'default' => 'no',
							'type'    => 'checkbox',
						),

						array(
							'title'   => esc_attr__( 'Venue Map', 'sportspress' ),
							'desc'    => esc_attr__( 'Display venue map', 'sportspress' ),
							'id'      => 'sportspress_event_show_maps',
							'default' => 'yes',
							'type'    => 'checkbox',
						),

						array(
							'title'   => esc_attr__( 'Type', 'sportspress' ),
							'id'      => 'sportspress_map_type',
							'default' => 'ROADMAP',
							'type'    => 'radio',
							'options' => array(
								'ROADMAP'   => esc_attr__( 'Default', 'sportspress' ),
								'SATELLITE' => esc_attr__( 'Satellite', 'sportspress' ),
							),
						),

						array(
							'title'             => esc_attr__( 'Zoom', 'sportspress' ),
							'id'                => 'sportspress_map_zoom',
							'class'             => 'small-text',
							'default'           => '15',
							'desc'              => '0 - 21',
							'type'              => 'number',
							'custom_attributes' => array(
								'min'  => 0,
								'max'  => 21,
								'step' => 1,
							),
						),
					)
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'venue_options',
					),
				),
				array(
					array(
						'title' => esc_attr__( 'Teams', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'event_logo_options',
					),
				),
				apply_filters(
					'sportspress_event_logo_options',
					array(
						array(
							'title'   => esc_attr__( 'Order', 'sportspress' ),
							'desc'    => esc_attr__( 'Reverse order', 'sportspress' ),
							'id'      => 'sportspress_event_reverse_teams',
							'default' => 'no',
							'type'    => 'checkbox',
						),

						array(
							'title'   => esc_attr__( 'Layout', 'sportspress' ),
							'id'      => 'sportspress_event_logos_format',
							'default' => 'inline',
							'type'    => 'radio',
							'options' => array(
								'inline' => esc_attr__( 'Inline', 'sportspress' ),
								'block'  => esc_attr__( 'Block', 'sportspress' ),
							),
						),

						array(
							'title'         => esc_attr__( 'Display', 'sportspress' ),
							'desc'          => esc_attr__( 'Name', 'sportspress' ),
							'id'            => 'sportspress_event_logos_show_team_names',
							'default'       => 'yes',
							'type'          => 'checkbox',
							'checkboxgroup' => 'start',
						),

						array(
							'desc'          => esc_attr__( 'Time', 'sportspress' ),
							'id'            => 'sportspress_event_logos_show_time',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => '',
						),

						array(
							'desc'          => esc_attr__( 'Results', 'sportspress' ),
							'id'            => 'sportspress_event_logos_show_results',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => 'end',
						),
					)
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'event_logo_options',
					),
				),
				array(
					array(
						'title' => esc_attr__( 'Players', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'eventplayer_options',
					),
				),
				apply_filters(
					'sportspress_eventplayer_options',
					array(
						array(
							'title'    => esc_attr__( 'Order', 'sportspress' ),
							'id'       => 'sportspress_event_player_sort',
							'default'  => 'jersey',
							'type'     => 'radio',
							'options'  => array(
								'jersey' => esc_attr__( 'Jersey (e.g. "33. John Doe")', 'sportspress' ),
								'name'   => esc_attr__( 'Name (e.g. "John Doe (33)")', 'sportspress' ),
							),
							'desc_tip' => 'When editing an event, this determines how the checklist of players are sorted in the Teams metabox.  This does not affect the Box Score section.',
						),

					)
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'eventplayer_options',
					),
				),
				array(
					array(
						'title' => esc_attr__( 'Event Results', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'result_options',
					),
				),
				apply_filters(
					'sportspress_result_options',
					array(
						array(
							'title'   => esc_attr__( 'Columns', 'sportspress' ),
							'id'      => 'sportspress_event_result_columns',
							'default' => 'auto',
							'type'    => 'radio',
							'options' => array(
								'auto'   => esc_attr__( 'Auto', 'sportspress' ),
								'manual' => esc_attr__( 'Manual', 'sportspress' ),
							),
						),

						array(
							'title'   => esc_attr__( 'Outcome', 'sportspress' ),
							'desc'    => esc_attr__( 'Display outcome', 'sportspress' ),
							'id'      => 'sportspress_event_show_outcome',
							'default' => 'no',
							'type'    => 'checkbox',
						),
					)
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'result_options',
					),
				),
				array(
					array(
						'title' => esc_attr__( 'Box Score', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'performance_options',
					),
				),
				apply_filters(
					'sportspress_performance_options',
					array_merge(
						array(
							array(
								'title'         => esc_attr__( 'Rows', 'sportspress' ),
								'desc'          => esc_attr__( 'Staff', 'sportspress' ),
								'id'            => 'sportspress_event_show_staff',
								'default'       => 'yes',
								'type'          => 'checkbox',
								'checkboxgroup' => 'start',
							),

							array(
								'desc'          => esc_attr__( 'Players', 'sportspress' ),
								'id'            => 'sportspress_event_show_players',
								'default'       => 'yes',
								'type'          => 'checkbox',
								'checkboxgroup' => '',
							),

							array(
								'desc'          => esc_attr__( 'Total', 'sportspress' ),
								'id'            => 'sportspress_event_show_total',
								'default'       => 'yes',
								'type'          => 'checkbox',
								'checkboxgroup' => 'end',
							),

							array(
								'title'   => esc_attr__( 'Columns', 'sportspress' ),
								'id'      => 'sportspress_event_performance_columns',
								'default' => 'auto',
								'type'    => 'radio',
								'options' => array(
									'auto'   => esc_attr__( 'Auto', 'sportspress' ),
									'manual' => esc_attr__( 'Manual', 'sportspress' ),
								),
							),

							array(
								'title'   => esc_attr__( 'Mode', 'sportspress' ),
								'id'      => 'sportspress_event_performance_mode',
								'default' => 'values',
								'type'    => 'radio',
								'options' => array(
									'values' => esc_attr__( 'Values', 'sportspress' ),
									'icons'  => esc_attr__( 'Icons', 'sportspress' ),
								),
							),

							array(
								'title'   => esc_attr__( 'Awards', 'sportspress' ),
								'id'      => 'sportspress_event_performance_stars_type',
								'default' => 0,
								'type'    => 'radio',
								'options' => array(
									__( 'None', 'sportspress' ),
									__( 'Player of the Match', 'sportspress' ),
									__( 'Stars', 'sportspress' ),
									__( 'Star Number', 'sportspress' ),
								),
							),

							array(
								'title'   => esc_attr__( 'Positions', 'sportspress' ),
								'desc'    => esc_attr__( 'Top-level only', 'sportspress' ),
								'id'      => 'sportspress_event_hide_child_positions',
								'default' => 'no',
								'type'    => 'checkbox',
							),
						),
						apply_filters(
							'sportspress_event_performance_display_options',
							array(
								array(
									'title'         => esc_attr__( 'Display', 'sportspress' ),
									'desc'          => esc_attr__( 'Squad Number', 'sportspress' ),
									'id'            => 'sportspress_event_show_player_numbers',
									'default'       => 'yes',
									'type'          => 'checkbox',
									'checkboxgroup' => 'start',
								),

								array(
									'desc'          => esc_attr__( 'Position', 'sportspress' ),
									'id'            => 'sportspress_event_show_position',
									'default'       => 'yes',
									'type'          => 'checkbox',
									'checkboxgroup' => '',
								),

								array(
									'desc'          => esc_attr__( 'Minutes', 'sportspress' ),
									'id'            => 'sportspress_event_performance_show_minutes',
									'default'       => 'no',
									'type'          => 'checkbox',
									'checkboxgroup' => 'end',
								),
							)
						),
						array(
							array(
								'title'   => esc_attr__( 'Performance', 'sportspress' ),
								'id'      => 'sportspress_event_performance_sections',
								'default' => -1,
								'type'    => 'radio',
								'options' => array(
									-1 => esc_attr__( 'Combined', 'sportspress' ),
									0  => esc_attr__( 'Offense', 'sportspress' ) . ' &rarr; ' . esc_attr__( 'Defense', 'sportspress' ),
									1  => esc_attr__( 'Defense', 'sportspress' ) . ' &rarr; ' . esc_attr__( 'Offense', 'sportspress' ),
								),
							),

							array(
								'title'   => esc_attr__( 'Total', 'sportspress' ),
								'id'      => 'sportspress_event_total_performance',
								'default' => 'all',
								'type'    => 'radio',
								'options' => array(
									'all'     => esc_attr__( 'All', 'sportspress' ),
									'primary' => esc_attr__( 'Primary', 'sportspress' ),
								),
							),
						)
					)
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'performance_options',
					),
				)
			);

			return apply_filters( 'sportspress_event_settings', $settings );
		}

		/**
		 * Save settings
		 */
		public function save() {
			parent::save();

			if ( isset( $_POST['sportspress_event_teams_delimiter'] ) ) {
				update_option( 'sportspress_event_teams_delimiter', sanitize_text_field( wp_unslash( $_POST['sportspress_event_teams_delimiter'] ) ) );
			}
		}

		/**
		 * Delimiter settings
		 *
		 * @access public
		 * @return void
		 */
		public function delimiter_setting() {
			$selection = get_option( 'sportspress_event_teams_delimiter', 'vs' );
			$limit     = get_option( 'sportspress_event_teams', 2 );
			if ( 0 == $limit ) {
				$limit = 2;
			}
			if ( 3 >= $limit ) {
				$example = str_repeat( esc_attr__( 'Team', 'sportspress' ) . ' %1$s ', $limit );
			} else {
				$example = str_repeat( esc_attr__( 'Team', 'sportspress' ) . ' %1$s ', 3 ) . '&hellip;';
			}
			$example = rtrim( $example, ' %1$s ' );
			?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php esc_html_e( 'Delimiter', 'sportspress' ); ?>
			</th>
			<td class="forminp">
				<fieldset class="sp-custom-input-wrapper">
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Delimiter', 'sportspress' ); ?></span></legend>
					<?php $delimiters = array( 'vs', 'v', '&mdash;', '/' ); ?>
					<?php foreach ( $delimiters as $delimiter ) : ?>
						<label title="<?php echo esc_attr( $delimiter ); ?>"><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="<?php echo esc_attr( $delimiter ); ?>" data-example="<?php printf( esc_attr( $example ), esc_attr( $delimiter ) ); ?>" <?php checked( $delimiter, $selection ); ?>> <span><?php printf( esc_attr( $example ), esc_attr( $delimiter ) ); ?></span></label><br>
					<?php endforeach; ?>
					<label><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="\c\u\s\t\o\m" <?php checked( false, in_array( $selection, $delimiters ) ); ?>> <?php esc_html_e( 'Custom:', 'sportspress' ); ?> </label><input type="text" class="small-text value" name="sportspress_event_teams_delimiter" value="<?php echo esc_attr( $selection ); ?>" data-example-format="<?php printf( esc_attr( $example ), '__val__' ); ?>">
					<span class="example"><?php printf( esc_html( $example ), esc_html( $selection ) ); ?></span>
				</fieldset>
			</td>
		</tr>
			<?php
		}
	}

endif;

return new SP_Settings_Events();
