<?php
/**
 * SportsPress modules
 *
 * The SportsPress modules class stores available modules.
 *
 * @class 		SP_Modules
 * @version     1.6
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
				),
				'event_videos' => array(
					'label' => __( 'Videos', 'sportspress' ),
					'icon' => 'dashicons dashicons-video-alt',
				),
				'tournaments' => array(
					'label' => __( 'Tournaments', 'sportspress' ),
					'class' => 'SportsPress_Tournaments',
					'tip' => __( 'Upgrade to Pro', 'sportspress' ),
					'icon' => 'sp-icon-tournament',
					'link' => 'http://tboy.co/tournaments',
				),
			),
			'team' => array(
				'league_tables' => array(
					'label' => __( 'League Tables', 'sportspress' ),
					'icon' => 'sp-icon-chart',
				),
				'team_colors' => array(
					'label' => __( 'Team Colors', 'sportspress' ),
					'class' => 'SportsPress_Team_Colors',
					'tip' => __( 'Upgrade to Pro', 'sportspress' ),
					'icon' => 'sp-icon-color',
					'link' => 'http://tboy.co/colors',
				),
			),
			'player' => array(
				'player_lists' => array(
					'label' => __( 'Player Lists', 'sportspress' ),
					'icon' => 'sp-icon-list',
				),
				'birthdays' => array(
					'label' => __( 'Birthdays', 'sportspress' ),
					'class' => 'SportsPress_Birthdays',
					'action' => __( 'Review on WP.org', 'sportspress' ),
					'link' => 'http://tboy.co/review',
					'tip' => str_replace(
						array( '[stars]', '[link]', '[/link]' ),
						array( '&#9733;&#9733;&#9733;&#9733;&#9733;', '', '' ),
						__( 'Add your [stars] on [link]wordpress.org[/link] and get the Birthdays module for free.','sportspress' )
					),
					'icon' => 'sp-icon-cake',
				),
			),
			'staff' => array(
				'staff_directories' => array(
					'label' => __( 'Directories', 'sportspress' ),
					'class' => 'SportsPress_Staff_Directories',
					'tip' => __( 'Upgrade to Pro', 'sportspress' ),
					'icon' => 'sp-icon-archive',
					'link' => 'http://tboy.co/directories',
				),
			),
			'other' => array(
				'twitter' => array(
					'label' => __( 'Twitter', 'sportspress' ),
					'class' => 'SportsPress_Twitter',
					'action' => __( 'Tweet #SportsPress', 'sportspress' ),
					'link' => 'http://ctt.ec/d0sCF',
					'tip' => __( 'Help spread the word by tweeting with #SportsPress and get the Twitter module for free.', 'sportspress' ),
					'icon' => 'dashicons dashicons-twitter',
				),
				'branding' => array(
					'label' => __( 'Branding', 'sportspress' ),
					'class' => 'SportsPress_Branding',
					'tip' => __( 'Upgrade to Pro', 'sportspress' ),
					'icon' => 'sp-icon-sportspress',
					'link' => 'http://tboy.co/branding',
				),
				'league_menu' => array(
					'label' => __( 'League Menu', 'sportspress' ),
					'class' => 'SportsPress_League_Menu',
					'tip' => __( 'Upgrade to Pro', 'sportspress' ),
					'icon' => 'sp-icon-menu',
					'link' => 'http://tboy.co/menu',
				),
				'sponsors' => array(
					'label' => __( 'Sponsors', 'sportspress' ),
					'class' => 'SportsPress_Sponsors',
					'tip' => __( 'Upgrade to Pro', 'sportspress' ),
					'icon' => 'sp-icon-megaphone',
					'link' => 'http://tboy.co/sponsors',
				),
				'multisite' => array(
					'label' => __( 'Multisite', 'sportspress' ),
					'class' => 'SportsPress_Multisite',
					'tip' => __( 'Upgrade to Pro', 'sportspress' ),
					'icon' => 'sp-icon-globe',
					'link' => 'http://tboy.co/multisite',
				),
			),
		));
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}
}
