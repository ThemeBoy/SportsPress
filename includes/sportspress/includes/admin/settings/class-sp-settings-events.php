<?php
/**
 * SportsPress Event Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version   2.5.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Events' ) ) :

/**
 * SP_Settings_Events
 */
class SP_Settings_Events extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'events';
		$this->label = __( 'Events', 'sportspress' );
		$this->template  = 'event';

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
				array( 'title' => __( 'Event Options', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'event_options' ),
			),

			apply_filters( 'sportspress_event_options', array_merge(
				array(
					array(
						'title'     => __( 'Link', 'sportspress' ),
						'desc' 		=> __( 'Link events', 'sportspress' ),
						'id' 		=> 'sportspress_link_events',
						'default'	=> 'yes',
						'type' 		=> 'checkbox',
					),
				),

				apply_filters( 'sportspress_event_template_options', array(
					array( 'type' 	=> 'event_layout' ),

					array( 'type' 	=> 'event_tabs' ),

					array(
						'title'     => __( 'Details', 'sportspress' ),
						'desc' 		=> __( 'Date', 'sportspress' ),
						'id' 		=> 'sportspress_event_show_date',
						'default'	=> 'yes',
						'type' 		=> 'checkbox',
						'checkboxgroup'		=> 'start',
					),

					array(
						'desc' 		=> __( 'Time', 'sportspress' ),
						'id' 		=> 'sportspress_event_show_time',
						'default'	=> 'yes',
						'type' 		=> 'checkbox',
						'checkboxgroup'		=> '',
					),

					array(
						'desc' 		=> __( 'Match Day', 'sportspress' ),
						'id' 		=> 'sportspress_event_show_day',
						'default'	=> 'no',
						'type' 		=> 'checkbox',
						'checkboxgroup'		=> '',
					),

					array(
						'desc' 		=> __( 'Full Time', 'sportspress' ),
						'id' 		=> 'sportspress_event_show_full_time',
						'default'	=> 'no',
						'type' 		=> 'checkbox',
						'checkboxgroup'		=> 'end',
					),
				) ),

				array(
					array(
						'title'     => __( 'Default mode', 'sportspress' ),
						'id'        => 'sportspress_mode',
						'default'   => 'team',
						'type'      => 'radio',
						'options'   => array(
							'team' => __( 'Team vs team', 'sportspress' ),
							'player' => __( 'Player vs player', 'sportspress' ),
						),
						'desc_tip' 		=> _x( 'Who competes in events?', 'mode setting description', 'sportspress' ),
					),
					
					array(
						'title' 	=> __( 'Limit', 'sportspress' ),
						'id' 		=> 'sportspress_event_teams',
						'class' 	=> 'small-text',
						'default'	=> '2',
						'desc' 		=> __( 'teams', 'sportspress' ),
						'type' 		=> 'number',
						'custom_attributes' => array(
							'min' 	=> 0,
							'step' 	=> 1
						),
					),
					
					array( 'type' => 'delimiter' ),

					array(
						'title'     => __( 'Teams', 'sportspress' ),
						'desc' 		=> __( 'Filter by league', 'sportspress' ),
						'id' 		=> 'sportspress_event_filter_teams_by_league',
						'default'	=> 'no',
						'type' 		=> 'checkbox',
						'checkboxgroup'	=> 'start',
					),

					array(
						'desc' 		=> __( 'Filter by season', 'sportspress' ),
						'id' 		=> 'sportspress_event_filter_teams_by_season',
						'default'	=> 'no',
						'type' 		=> 'checkbox',
						'checkboxgroup'	=> 'end',
					),
					
					array(
						'title' 	=> __( 'Full Time', 'sportspress' ),
						'id' 		=> 'sportspress_event_minutes',
						'class' 	=> 'small-text',
						'default'	=> '90',
						'desc' 		=> __( 'mins', 'sportspress' ),
						'type' 		=> 'number',
						'custom_attributes' => array(
							'min' 	=> 0,
							'step' 	=> 1
						),
					),

					array(
						'title'     => __( 'Comments', 'sportspress' ),
						'desc' 		=> __( 'Allow people to post comments on new articles', 'sportspress' ),
						'id' 		=> 'sportspress_event_comment_status',
						'default'	=> 'no',
						'type' 		=> 'checkbox',
					),
				)
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'event_options' ),
			),

			array(
				array( 'title' => __( 'Venues', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'venue_options' ),
			),

			apply_filters( 'sportspress_venue_options', array(
				array(
					'title'     => __( 'Link', 'sportspress' ),
					'desc' 		=> __( 'Link venues', 'sportspress' ),
					'id' 		=> 'sportspress_link_venues',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Google Maps', 'sportspress' ),
					'desc' 		=> __( 'Display maps', 'sportspress' ),
					'id' 		=> 'sportspress_event_show_maps',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Type', 'sportspress' ),
					'id'        => 'sportspress_map_type',
					'default'   => 'ROADMAP',
					'type'      => 'radio',
					'options'   => array(
						'ROADMAP' => __( 'Default', 'sportspress' ),
						'SATELLITE' => __( 'Satellite', 'sportspress' ),
					),
				),

				array(
					'title' 	=> __( 'Zoom', 'sportspress' ),
					'id' 		=> 'sportspress_map_zoom',
					'class' 	=> 'small-text',
					'default'	=> '15',
					'desc' 		=> '0 - 21',
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 0,
						'max' 	=> 21,
						'step' 	=> 1
					),
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'venue_options' ),
			),

			array(
				array( 'title' => __( 'Teams', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'event_logo_options' ),
			),

			apply_filters( 'sportspress_event_logo_options', array(
				array(
					'title'     => __( 'Order', 'sportspress' ),
					'desc' 		=> __( 'Reverse order', 'sportspress' ),
					'id' 		=> 'sportspress_event_reverse_teams',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Layout', 'sportspress' ),
					'id'        => 'sportspress_event_logos_format',
					'default'   => 'inline',
					'type'      => 'radio',
					'options'   => array(
						'inline' => __( 'Inline', 'sportspress' ),
						'block' => __( 'Block', 'sportspress' ),
					),
				),

				array(
					'title'     => __( 'Display', 'sportspress' ),
					'desc' 		=> __( 'Name', 'sportspress' ),
					'id' 		=> 'sportspress_event_logos_show_team_names',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> 'start',
				),

				array(
					'desc' 		=> __( 'Time', 'sportspress' ),
					'id' 		=> 'sportspress_event_logos_show_time',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> '',
				),

				array(
					'desc' 		=> __( 'Results', 'sportspress' ),
					'id' 		=> 'sportspress_event_logos_show_results',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> 'end',
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'event_logo_options' ),
			),

			array(
				array( 'title' => __( 'Players', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'eventplayer_options' ),
			),

			apply_filters( 'sportspress_eventplayer_options', array(
				array(
					'title' 	=> __( 'Player Sorting', 'sportspress' ),
					'id' 		=> 'sportspress_event_player_sort',
					'default'	=> 'jersey',
					'type' 		=> 'radio',
					'options' => array(
						'jersey'=> __( 'Jersey (e.g. "33. John Doe")', 'sportspress' ),
						'name'	=> __( 'Name (e.g. "John Doe (33)")', 'sportspress' ),
					),
					'desc_tip' 		=> _x( 'When editing an event, this determines how the checklist of players are sorted in the Teams metabox.  This does not affect the Box Score section.', 'event player sort setting description', 'sportspress' ),

				),

			) ),


			array(
				array( 'type' => 'sectionend', 'id' => 'eventplayer_options' ),
			),

			array(
				array( 'title' => __( 'Event Results', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'result_options' ),
			),

			apply_filters( 'sportspress_result_options', array(
				array(
					'title' 	=> __( 'Columns', 'sportspress' ),
					'id' 		=> 'sportspress_event_result_columns',
					'default'	=> 'auto',
					'type' 		=> 'radio',
					'options' => array(
						'auto'		=> __( 'Auto', 'sportspress' ),
						'manual'	=> __( 'Manual', 'sportspress' ),
					),
				),
				
				array(
					'title'     => __( 'Outcome', 'sportspress' ),
					'desc' 		=> __( 'Display outcome', 'sportspress' ),
					'id' 		=> 'sportspress_event_show_outcome',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'result_options' ),
			),

			array(
				array( 'title' => __( 'Box Score', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'performance_options' ),
			),

			apply_filters( 'sportspress_performance_options', array_merge(
				array(
					array(
						'title'     => __( 'Rows', 'sportspress' ),
						'desc' 		=> __( 'Staff', 'sportspress' ),
						'id' 		=> 'sportspress_event_show_staff',
						'default'	=> 'yes',
						'type' 		=> 'checkbox',
						'checkboxgroup'		=> 'start',
					),

					array(
						'desc' 		=> __( 'Players', 'sportspress' ),
						'id' 		=> 'sportspress_event_show_players',
						'default'	=> 'yes',
						'type' 		=> 'checkbox',
						'checkboxgroup'	=> '',
					),

					array(
						'desc' 		=> __( 'Total', 'sportspress' ),
						'id' 		=> 'sportspress_event_show_total',
						'default'	=> 'yes',
						'type' 		=> 'checkbox',
						'checkboxgroup'		=> 'end',
					),

					array(
						'title' 	=> __( 'Columns', 'sportspress' ),
						'id' 		=> 'sportspress_event_performance_columns',
						'default'	=> 'auto',
						'type' 		=> 'radio',
						'options' => array(
							'auto'		=> __( 'Auto', 'sportspress' ),
							'manual'	=> __( 'Manual', 'sportspress' ),
						),
					),

					array(
						'title' 	=> __( 'Mode', 'sportspress' ),
						'id' 		=> 'sportspress_event_performance_mode',
						'default'	=> 'values',
						'type' 		=> 'radio',
						'options' => array(
							'values'	=> __( 'Values', 'sportspress' ),
							'icons'		=> __( 'Icons', 'sportspress' ),
						),
					),

					array(
						'title' 	=> __( 'Awards', 'sportspress' ),
						'id' 		=> 'sportspress_event_performance_stars_type',
						'default'	=> 0,
						'type' 		=> 'radio',
						'options' => array(
							__( 'None', 'sportspress' ),
							__( 'Player of the Match', 'sportspress' ),
							__( 'Stars', 'sportspress' ),
							__( 'Star Number', 'sportspress' ),
						),
					),

					array(
						'title'     => __( 'Positions', 'sportspress' ),
						'desc' 		=> __( 'Top-level only', 'sportspress' ),
						'id' 		=> 'sportspress_event_hide_child_positions',
						'default'	=> 'no',
						'type' 		=> 'checkbox',
					),
				),

				apply_filters( 'sportspress_event_performance_display_options', array(
					array(
						'title'     => __( 'Display', 'sportspress' ),
						'desc' 		=> __( 'Squad Number', 'sportspress' ),
						'id' 		=> 'sportspress_event_show_player_numbers',
						'default'	=> 'yes',
						'type' 		=> 'checkbox',
						'checkboxgroup' 	=> 'start',
					),
					
					array(
						'desc' 		=> __( 'Position', 'sportspress' ),
						'id' 		=> 'sportspress_event_show_position',
						'default'	=> 'yes',
						'type' 		=> 'checkbox',
						'checkboxgroup'		=> '',
					),
					
					array(
						'desc' 		=> __( 'Minutes', 'sportspress' ),
						'id' 		=> 'sportspress_event_performance_show_minutes',
						'default'	=> 'no',
						'type' 		=> 'checkbox',
						'checkboxgroup'		=> 'end',
					),
				) ),

				array(
					array(
						'title' 	=> __( 'Performance', 'sportspress' ),
						'id' 		=> 'sportspress_event_performance_sections',
						'default'	=> -1,
						'type' 		=> 'radio',
						'options' => array(
							-1	  => __( 'Combined', 'sportspress' ),
							0	  => __( 'Offense', 'sportspress' ) . ' &rarr; ' . __( 'Defense', 'sportspress' ),
							1	  => __( 'Defense', 'sportspress' ) . ' &rarr; ' . __( 'Offense', 'sportspress' ),
						),
					),

					array(
						'title' 	=> __( 'Total', 'sportspress' ),
						'id' 		=> 'sportspress_event_total_performance',
						'default'	=> 'all',
						'type' 		=> 'radio',
						'options' => array(
							'all'		=> __( 'All', 'sportspress' ),
							'primary'	=> __( 'Primary', 'sportspress' ),
						),
					),
				)
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'performance_options' ),
			)

		);

		return apply_filters( 'sportspress_event_settings', $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		parent::save();
		
		if ( isset( $_POST['sportspress_event_teams_delimiter'] ) )
	    	update_option( 'sportspress_event_teams_delimiter', $_POST['sportspress_event_teams_delimiter'] );
	}

	/**
	 * Delimiter settings
	 *
	 * @access public
	 * @return void
	 */
	public function delimiter_setting() {
		$selection = get_option( 'sportspress_event_teams_delimiter', 'vs' );
		$limit = get_option( 'sportspress_event_teams', 2 );
		if ( 0 == $limit ) {
			$limit = 2;
		}
		if ( 3 >= $limit ) {
			$example = str_repeat( __( 'Team', 'sportspress' ) . ' %1$s ', $limit );
		} else {
			$example = str_repeat( __( 'Team', 'sportspress' ) . ' %1$s ', 3 ) . '&hellip;';
		}
		$example = rtrim( $example, ' %1$s ' );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<?php _e( 'Delimiter', 'sportspress' ); ?>
			</th>
		    <td class="forminp">
				<fieldset class="sp-custom-input-wrapper">
					<legend class="screen-reader-text"><span><?php _e( 'Delimiter', 'sportspress' ); ?></span></legend>
					<?php $delimiters = array( 'vs', 'v', '&mdash;', '/' ); ?>
					<?php foreach ( $delimiters as $delimiter ): ?>
						<label title="<?php echo $delimiter; ?>"><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="<?php echo $delimiter; ?>" data-example="<?php printf( $example, $delimiter ); ?>" <?php checked( $delimiter, $selection ); ?>> <span><?php printf( $example, $delimiter ); ?></span></label><br>
					<?php endforeach; ?>
					<label><input type="radio" class="preset" name="sportspress_event_teams_delimiter_preset" value="\c\u\s\t\o\m" <?php checked( false, in_array( $selection, $delimiters ) ); ?>> <?php _e( 'Custom:', 'sportspress' ); ?> </label><input type="text" class="small-text value" name="sportspress_event_teams_delimiter" value="<?php echo $selection; ?>" data-example-format="<?php printf( $example, '__val__' ); ?>">
					<span class="example"><?php printf( $example, $selection ); ?></span>
				</fieldset>
			</td>
		</tr>
		<?php
	}
}

endif;

return new SP_Settings_Events();
