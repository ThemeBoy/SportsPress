<?php
/**
 * SportsPress modules
 *
 * The SportsPress modules class stores available modules.
 *
 * @class 		SP_Modules
 * @version     1.9
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Modules {

	/** @var array Array of modules */
	public $data;

	/**
	 * Constructor for the modules class - defines all default modules.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->data = apply_filters( 'sportspress_modules', array(
			'event' => array(
				'calendars' => array(
					'label' => __( 'Calendars', 'sportspress' ),
					'icon' => 'sp-icon-calendar',
					'desc' => __( 'Organize and publish calendars using different layouts.', 'sportspress' ),
				),
				'match_stats' => array(
					'label' => __( 'Match Stats', 'sportspress' ),
					'class' => 'SportsPress_Match_Stats',
					'icon' => 'sp-icon-statistics',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Display head-to-head team comparison charts in events.', 'sportspress' ),
				),
				'tournaments' => array(
					'label' => __( 'Tournaments', 'sportspress' ),
					'class' => 'SportsPress_Tournaments',
					'icon' => 'sp-icon-tournament',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Schedule tournaments and create interactive playoff brackets.', 'sportspress' ),
				),
			),
			'team' => array(
				'league_tables' => array(
					'label' => __( 'League Tables', 'sportspress' ),
					'icon' => 'sp-icon-chart',
					'desc' => __( 'Create automated league tables to keep track of team standings.', 'sportspress' ),
				),
				'league_menu' => array(
					'label' => __( 'League Menu', 'sportspress' ),
					'class' => 'SportsPress_League_Menu',
					'icon' => 'sp-icon-menu',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Add a global navigation bar to display logos that link to each team.', 'sportspress' ),
				),
				'team_colors' => array(
					'label' => __( 'Team Colors', 'sportspress' ),
					'class' => 'SportsPress_Team_Colors',
					'icon' => 'sp-icon-color',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Create a custom color palette for each team.', 'sportspress' ),
				),
				'team_access' => array(
					'label' => __( 'Team Access', 'sportspress' ),
					'class' => 'SportsPress_Team_Access',
					'icon' => 'sp-icon-key',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Limit user access to data that is related to their team.', 'sportspress' ),
				),
			),
			'player_staff' => array(
				'player_lists' => array(
					'label' => __( 'Player Lists', 'sportspress' ),
					'icon' => 'sp-icon-list',
					'desc' => __( 'Create team rosters, player galleries, and ranking charts.', 'sportspress' ),
				),
				'birthdays' => array(
					'label' => __( 'Birthdays', 'sportspress' ),
					'class' => 'SportsPress_Birthdays',
					'icon' => 'sp-icon-cake',
					'link' => 'http://tboy.co/pro',
					'desc' => __( "Display each player's birthday and their current age.", 'sportspress' ),
				),
				'staff_directories' => array(
					'label' => __( 'Directories', 'sportspress' ),
					'class' => 'SportsPress_Staff_Directories',
					'icon' => 'sp-icon-archive',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Organize and display staff in list and gallery layouts.', 'sportspress' ),
				),
			),
			'admin' => array(
				'tutorials' => array(
					'label' => __( 'Tutorials', 'sportspress' ),
					'icon' => 'dashicons dashicons-video-alt3',
					'desc' => __( 'Display a dashboard page with SportsPress video tutorials.', 'sportspress' ),
				),
				'branding' => array(
					'label' => __( 'Branding', 'sportspress' ),
					'class' => 'SportsPress_Branding',
					'icon' => 'sp-icon-sportspress',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Instantly rebrand the dashboard with your own logo and colors.', 'sportspress' ),
				),
				'duplicator' => array(
					'label' => __( 'Duplicator', 'sportspress' ),
					'class' => 'SportsPress_Duplicator',
					'icon' => 'sp-icon-copy',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Clone anything with just one click. Great for creating multiple events.', 'sportspress' ),
				),
				'lazy_loading' => array(
					'label' => __( 'Lazy Loading', 'sportspress' ),
					'class' => 'SportsPress_Lazy_Loading',
					'icon' => 'sp-icon-moon',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Load players using Ajax to speed up the event edit screen.', 'sportspress' ),
				),
			),
			'other' => array(
				'twitter' => array(
					'label' => __( 'Twitter', 'sportspress' ),
					'class' => 'SportsPress_Twitter',
					'action' => __( 'Tweet #SportsPress', 'sportspress' ),
					'link' => 'http://tboy.co/pro',
					'tip' => __( 'Help spread the word by tweeting with #SportsPress and get the Twitter module for free.', 'sportspress' ),
					'icon' => 'dashicons dashicons-twitter',
					'desc' => __( 'Add a Twitter feed to team, player, and staff pages.', 'sportspress' ),
				),
				'sponsors' => array(
					'label' => __( 'Sponsors', 'sportspress' ),
					'class' => 'SportsPress_Sponsors',
					'icon' => 'sp-icon-megaphone',
					'link' => 'http://tboy.co/pro',
					'desc' => __( 'Attract sponsors by offering them advertising space on your website.', 'sportspress' ),
				),
			),
		));

		if ( is_multisite() ) {
			$this->data['other']['multisite'] = array(
				'label' => __( 'Multisite', 'sportspress' ),
				'class' => 'SportsPress_Multisite',
				'icon' => 'sp-icon-globe',
				'link' => 'http://tboy.co/pro',
				'desc' => __( 'Manage multiple sports and display different widgets all on one site.', 'sportspress' ),
			);
		}
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}
}
