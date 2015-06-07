<?php
/**
 * SportsPress modules
 *
 * The SportsPress modules class stores available modules.
 *
 * @class 		SP_Modules
 * @version     1.8.6
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
				'tournaments' => array(
					'label' => __( 'Tournaments', 'sportspress' ),
					'class' => 'SportsPress_Tournaments',
					'icon' => 'sp-icon-tournament',
					'link' => 'http://tboy.co/tournaments',
					'desc' => __( 'Schedule tournaments and create interactive playoff brackets.', 'sportspress' ),
				),
			),
			'team' => array(
				'league_tables' => array(
					'label' => __( 'League Tables', 'sportspress' ),
					'icon' => 'sp-icon-chart',
					'desc' => __( 'Create automated league tables to keep track of team standings.', 'sportspress' ),
				),
				'team_colors' => array(
					'label' => __( 'Team Colors', 'sportspress' ),
					'class' => 'SportsPress_Team_Colors',
					'icon' => 'sp-icon-color',
					'link' => 'http://tboy.co/colors',
					'desc' => __( 'Create a custom color palette for each team.', 'sportspress' ),
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
					'link' => 'http://tboy.co/features',
					'desc' => __( "Display each player's birthday and their current age.", 'sportspress' ),
				),
				'staff_directories' => array(
					'label' => __( 'Directories', 'sportspress' ),
					'class' => 'SportsPress_Staff_Directories',
					'icon' => 'sp-icon-archive',
					'link' => 'http://tboy.co/directories',
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
					'link' => 'http://tboy.co/branding',
					'desc' => __( 'Instantly rebrand the dashboard with your own logo and colors.', 'sportspress' ),
				),
			),
			'other' => array(
				'twitter' => array(
					'label' => __( 'Twitter', 'sportspress' ),
					'class' => 'SportsPress_Twitter',
					'action' => __( 'Tweet #SportsPress', 'sportspress' ),
					'link' => 'http://tboy.co/tweet',
					'tip' => __( 'Help spread the word by tweeting with #SportsPress and get the Twitter module for free.', 'sportspress' ),
					'icon' => 'dashicons dashicons-twitter',
					'desc' => __( 'Add a Twitter feed to team, player, and staff pages.', 'sportspress' ),
				),
				'league_menu' => array(
					'label' => __( 'League Menu', 'sportspress' ),
					'class' => 'SportsPress_League_Menu',
					'icon' => 'sp-icon-menu',
					'link' => 'http://tboy.co/menu',
					'desc' => __( 'Add a global navigation bar to display logos that link to each team.', 'sportspress' ),
				),
				'sponsors' => array(
					'label' => __( 'Sponsors', 'sportspress' ),
					'class' => 'SportsPress_Sponsors',
					'icon' => 'sp-icon-megaphone',
					'link' => 'http://tboy.co/sponsors',
					'desc' => __( 'Attract sponsors by offering them advertising space on your website.', 'sportspress' ),
				),
			),
		));
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}
}
