<?php
/**
 * SportsPress templates
 *
 * The SportsPress templates class stores template layout data.
 *
 * @class       SP_Templates
 * @version     2.2
 * @package     SportsPress/Classes
 * @category    Class
 * @author      ThemeBoy
 */
class SP_Templates {

	/** @var array Array of templates */
	private $data = array();

	/**
	 * Constructor for the templates class - defines all templates.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->data = array(
			'event'    => array_merge(
				apply_filters(
					'sportspress_before_event_template',
					array(
						'logos'   => array(
							'title'   => esc_attr__( 'Teams', 'sportspress' ),
							'option'  => 'sportspress_event_show_logos',
							'action'  => 'sportspress_output_event_logos',
							'default' => 'yes',
						),
						'excerpt' => array(
							'title'   => esc_attr__( 'Excerpt', 'sportspress' ),
							'option'  => 'sportspress_event_show_excerpt',
							'action'  => 'sportspress_output_post_excerpt',
							'default' => 'yes',
						),
					)
				),
				array(
					'content' => array(
						'title'   => esc_attr__( 'Article', 'sportspress' ),
						'option'  => 'sportspress_event_show_content',
						'action'  => 'sportspress_output_event_content',
						'default' => 'yes',
					),
				),
				apply_filters(
					'sportspress_after_event_template',
					array(
						'video'       => array(
							'title'   => esc_attr__( 'Video', 'sportspress' ),
							'option'  => 'sportspress_event_show_video',
							'action'  => 'sportspress_output_event_video',
							'default' => 'yes',
						),
						'details'     => array(
							'title'   => esc_attr__( 'Details', 'sportspress' ),
							'option'  => 'sportspress_event_show_details',
							'action'  => 'sportspress_output_event_details',
							'default' => 'yes',
						),
						'venue'       => array(
							'title'   => esc_attr__( 'Venue', 'sportspress' ),
							'option'  => 'sportspress_event_show_venue',
							'action'  => 'sportspress_output_event_venue',
							'default' => 'yes',
						),
						'results'     => array(
							'title'   => esc_attr__( 'Results', 'sportspress' ),
							'option'  => 'sportspress_event_show_results',
							'action'  => 'sportspress_output_event_results',
							'default' => 'yes',
						),
						'performance' => array(
							'title'   => esc_attr__( 'Box Score', 'sportspress' ),
							'option'  => 'sportspress_event_show_performance',
							'action'  => 'sportspress_output_event_performance',
							'default' => 'yes',
						),
					)
				)
			),
			'calendar' => array_merge(
				apply_filters( 'sportspress_before_calendar_template', array() ),
				array(
					'content' => array(
						'title'   => esc_attr__( 'Description', 'sportspress' ),
						'option'  => 'sportspress_calendar_show_content',
						'action'  => 'sportspress_output_calendar_content',
						'default' => 'yes',
					),
				),
				apply_filters(
					'sportspress_after_calendar_template',
					array(
						'data' => array(
							'title'   => esc_attr__( 'Calendar', 'sportspress' ),
							'option'  => 'sportspress_calendar_show_data',
							'action'  => 'sportspress_output_calendar',
							'default' => 'yes',
						),
					)
				)
			),
			'team'     => array_merge(
				apply_filters(
					'sportspress_before_team_template',
					array(
						'logo'    => array(
							'title'   => esc_attr__( 'Logo', 'sportspress' ),
							'option'  => 'sportspress_team_show_logo',
							'action'  => 'sportspress_output_team_logo',
							'default' => 'yes',
						),
						'excerpt' => array(
							'title'   => esc_attr__( 'Excerpt', 'sportspress' ),
							'option'  => 'sportspress_team_show_excerpt',
							'action'  => 'sportspress_output_post_excerpt',
							'default' => 'yes',
						),
					)
				),
				array(
					'content' => array(
						'title'   => esc_attr__( 'Profile', 'sportspress' ),
						'option'  => 'sportspress_team_show_content',
						'action'  => 'sportspress_output_team_content',
						'default' => 'yes',
					),
				),
				apply_filters(
					'sportspress_after_team_template',
					array(
						'link'    => array(
							'title'   => esc_attr__( 'Visit Site', 'sportspress' ),
							'label'   => esc_attr__( 'Link', 'sportspress' ),
							'option'  => 'sportspress_team_show_link',
							'action'  => 'sportspress_output_team_link',
							'default' => 'no',
						),
						'details' => array(
							'title'   => esc_attr__( 'Details', 'sportspress' ),
							'option'  => 'sportspress_team_show_details',
							'action'  => 'sportspress_output_team_details',
							'default' => 'no',
						),
						'staff'   => array(
							'title'   => esc_attr__( 'Staff', 'sportspress' ),
							'option'  => 'sportspress_team_show_staff',
							'action'  => 'sportspress_output_team_staff',
							'default' => 'yes',
						),
					)
				)
			),
			'table'    => array_merge(
				apply_filters( 'sportspress_before_table_template', array() ),
				array(
					'content' => array(
						'title'   => esc_attr__( 'Description', 'sportspress' ),
						'option'  => 'sportspress_table_show_content',
						'action'  => 'sportspress_output_table_content',
						'default' => 'yes',
					),
				),
				apply_filters(
					'sportspress_after_table_template',
					array(
						'data' => array(
							'title'   => esc_attr__( 'League Table', 'sportspress' ),
							'option'  => 'sportspress_table_show_data',
							'action'  => 'sportspress_output_league_table',
							'default' => 'yes',
						),
					)
				)
			),
			'player'   => array_merge(
				apply_filters(
					'sportspress_before_player_template',
					array(
						'selector' => array(
							'title'   => esc_attr__( 'Dropdown', 'sportspress' ),
							'label'   => esc_attr__( 'Players', 'sportspress' ),
							'option'  => 'sportspress_player_show_selector',
							'action'  => 'sportspress_output_player_selector',
							'default' => 'yes',
						),
						'photo'    => array(
							'title'   => esc_attr__( 'Photo', 'sportspress' ),
							'option'  => 'sportspress_player_show_photo',
							'action'  => 'sportspress_output_player_photo',
							'default' => 'yes',
						),
						'details'  => array(
							'title'   => esc_attr__( 'Details', 'sportspress' ),
							'option'  => 'sportspress_player_show_details',
							'action'  => 'sportspress_output_player_details',
							'default' => 'yes',
						),
						'excerpt'  => array(
							'title'   => esc_attr__( 'Excerpt', 'sportspress' ),
							'option'  => 'sportspress_player_show_excerpt',
							'action'  => 'sportspress_output_post_excerpt',
							'default' => 'yes',
						),
					)
				),
				array(
					'content' => array(
						'title'   => esc_attr__( 'Profile', 'sportspress' ),
						'option'  => 'sportspress_player_show_content',
						'action'  => 'sportspress_output_player_content',
						'default' => 'yes',
					),
				),
				apply_filters(
					'sportspress_after_player_template',
					array(
						'statistics' => array(
							'title'   => esc_attr__( 'Statistics', 'sportspress' ),
							'option'  => 'sportspress_player_show_statistics',
							'action'  => 'sportspress_output_player_statistics',
							'default' => 'yes',
						),
					)
				)
			),
			'list'     => array_merge(
				apply_filters( 'sportspress_before_list_template', array() ),
				array(
					'content' => array(
						'title'   => esc_attr__( 'Description', 'sportspress' ),
						'option'  => 'sportspress_list_show_content',
						'action'  => 'sportspress_output_list_content',
						'default' => 'yes',
					),
				),
				apply_filters(
					'sportspress_after_list_template',
					array(
						'data' => array(
							'title'   => esc_attr__( 'Player List', 'sportspress' ),
							'option'  => 'sportspress_list_show_data',
							'action'  => 'sportspress_output_player_list',
							'default' => 'yes',
						),
					)
				)
			),
			'staff'    => array_merge(
				apply_filters(
					'sportspress_before_staff_template',
					array(
						'selector' => array(
							'title'   => esc_attr__( 'Dropdown', 'sportspress' ),
							'label'   => esc_attr__( 'Staff', 'sportspress' ),
							'option'  => 'sportspress_staff_show_selector',
							'action'  => 'sportspress_output_staff_selector',
							'default' => 'yes',
						),
						'photo'    => array(
							'title'   => esc_attr__( 'Photo', 'sportspress' ),
							'option'  => 'sportspress_staff_show_photo',
							'action'  => 'sportspress_output_staff_photo',
							'default' => 'yes',
						),
						'details'  => array(
							'title'   => esc_attr__( 'Details', 'sportspress' ),
							'option'  => 'sportspress_staff_show_details',
							'action'  => 'sportspress_output_staff_details',
							'default' => 'yes',
						),
						'excerpt'  => array(
							'title'   => esc_attr__( 'Excerpt', 'sportspress' ),
							'option'  => 'sportspress_staff_show_excerpt',
							'action'  => 'sportspress_output_post_excerpt',
							'default' => 'yes',
						),
					)
				),
				array(
					'content' => array(
						'title'   => esc_attr__( 'Profile', 'sportspress' ),
						'option'  => 'sportspress_staff_show_content',
						'action'  => 'sportspress_output_staff_content',
						'default' => 'yes',
					),
				),
				apply_filters( 'sportspress_after_staff_template', array() )
			),
		);
	}

	public function __get( $key ) {
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : array() );
	}

	public function __set( $key, $value ) {
		$this->data[ $key ] = $value;
	}
}
