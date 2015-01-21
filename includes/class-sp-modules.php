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
				'tournaments' => array(
					'label' => __( 'Tournaments', 'sportspress' ),
					'class' => 'SportsPress_Tournaments',
					'icon' => 'sp-icon-trophy',
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
					'icon' => 'sp-icon-color',
				),
			),
			'player' => array(
				'player_lists' => array(
					'label' => __( 'Player Lists', 'sportspress' ),
					'icon' => 'sp-icon-list',
				),
				'player_birthdays' => array(
					'label' => __( 'Birthdays', 'sportspress' ),
					'class' => 'SportsPress_Birthdays',
					'action' => __( 'Review on WP.org', 'sportspress' ),
					'link' => 'http://wordpress.org/support/view/plugin-reviews/sportspress#postform',
					'icon' => 'sp-icon-cake',
				),
			),
			'staff' => array(
				'staff_directories' => array(
					'label' => __( 'Directories', 'sportspress' ),
					'class' => 'SportsPress_Staff_Directories',
					'icon' => 'sp-icon-archive',
				),
			),
			'other' => array(
				'twitter' => array(
					'label' => __( 'Twitter', 'sportspress' ),
					'class' => 'SportsPress_Twitter',
					'action' => __( 'Tweet #SportsPress', 'sportspress' ),
					'link' => 'http://ctt.ec/d0sCF',
					'icon' => 'dashicons dashicons-twitter',
				),
				'branding' => array(
					'label' => __( 'Branding', 'sportspress' ),
					'class' => 'SportsPress_Branding',
					'icon' => 'sp-icon-sportspress',
				),
				'league_menu' => array(
					'label' => __( 'League Menu', 'sportspress' ),
					'class' => 'SportsPress_League_Menu',
					'icon' => 'sp-icon-menu',
				),
				'sponsors' => array(
					'label' => __( 'Sponsors', 'sportspress' ),
					'class' => 'SportsPress_Sponsors',
					'icon' => 'sp-icon-megaphone',
				),
				'multisite' => array(
					'label' => __( 'Multisite', 'sportspress' ),
					'class' => 'SportsPress_Multisite',
					'icon' => 'sp-icon-globe',
				),
			),
		));
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}
}
