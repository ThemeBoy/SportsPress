<?php
/**
 * SportsPress modules
 *
 * The SportsPress modules class stores available modules.
 *
 * @class 		SP_Modules
 * @version		2.6.15
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
				'results_matrix' => array(
					'label' => __( 'Results Matrix', 'sportspress' ),
					'class' => 'SportsPress_Results_Matrix',
					'icon' => 'sp-icon-matrix',
					'link' => 'https://www.themeboy.com/sportspress-extensions/results-matrix/',
					'desc' => __( 'Display fixtures and results between teams in a grid layout.', 'sportspress' ),
				),
				'scoreboard' => array(
					'label' => __( 'Scoreboard', 'sportspress' ),
					'class' => 'SportsPress_Scoreboard',
					'icon' => 'sp-icon-scoreboard',
					'link' => 'https://www.themeboy.com/sportspress-extensions/scoreboard/',
					'desc' => __( 'Display multiple event results in a horizontal scoreboard.', 'sportspress' ),
				),
				'google_maps' => array(
					'label' => __( 'Google Maps', 'sportspress' ),
					'class' => 'SportsPress_Google_Maps',
					'icon' => 'sp-icon-location',
					'link' => 'https://www.themeboy.com/sportspress-extensions/google-maps/',
					'desc' => __( 'Use Google Maps instead of OpenStreetMap for venues.', 'sportspress' ),
				),
				'user_scores' => array(
					'label' => __( 'User Scores', 'sportspress' ),
					'class' => 'SportsPress_User_Scores',
					'icon' => 'sp-icon-user-scores',
					'link' => 'https://www.themeboy.com/sportspress-extensions/user-scores/',
					'desc' => __( 'Let players, staff, and visitors submit event scores for review.', 'sportspress' ),
				),
				'match_stats' => array(
					'label' => __( 'Match Stats', 'sportspress' ),
					'class' => 'SportsPress_Match_Stats',
					'icon' => 'sp-icon-statistics',
					'link' => 'https://www.themeboy.com/sportspress-extensions/match-stats/',
					'desc' => __( 'Display head-to-head team comparison charts in events.', 'sportspress' ),
				),
				'past_meetings' => array(
					'label' => __( 'Past Meetings', 'sportspress' ),
					'class' => 'SportsPress_Past_Meetings',
					'icon' => 'sp-icon-history',
					'link' => 'https://www.themeboy.com/sportspress-extensions/past-meetings/',
					'desc' => __( 'Display previous events between teams in list or blocks layout.', 'sportspress' ),
				),
				'timelines' => array(
					'label' => __( 'Timelines', 'sportspress' ),
					'class' => 'SportsPress_Timelines',
					'icon' => 'sp-icon-timeline',
					'link' => 'https://www.themeboy.com/sportspress-extensions/timelines/',
					'desc' => __( 'Display a visual timeline of player performance in events.', 'sportspress' ),
				),
				'tournaments' => array(
					'label' => __( 'Tournaments', 'sportspress' ),
					'class' => 'SportsPress_Tournaments',
					'icon' => 'sp-icon-tournament',
					'link' => 'https://www.themeboy.com/sportspress-extensions/tournaments/',
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
					'link' => 'https://www.themeboy.com/sportspress-extensions/league-menu/',
					'desc' => __( 'Add a global navigation bar to display logos that link to each team.', 'sportspress' ),
				),
				'team_colors' => array(
					'label' => __( 'Team Colors', 'sportspress' ),
					'class' => 'SportsPress_Team_Colors',
					'icon' => 'sp-icon-color',
					'link' => 'https://www.themeboy.com/sportspress-extensions/team-colors/',
					'desc' => __( 'Create a custom color palette for each team.', 'sportspress' ),
				),
				'team_access' => array(
					'label' => __( 'Team Access', 'sportspress' ),
					'class' => 'SportsPress_Team_Access',
					'icon' => 'sp-icon-key',
					'link' => 'https://www.themeboy.com/sportspress-extensions/team-access/',
					'desc' => __( 'Limit user access to data that is related to their team.', 'sportspress' ),
				),
			),
			'player_staff' => array(
				'player_lists' => array(
					'label' => __( 'Player Lists', 'sportspress' ),
					'icon' => 'sp-icon-list',
					'desc' => __( 'Create team rosters, player galleries, and ranking charts.', 'sportspress' ),
				),
				'midseason_transfers' => array(
					'label' => __( 'Midseason Transfers', 'sportspress' ),
					'class' => 'SportsPress_Midseason_Transfers',
					'icon' => 'sp-icon-sub',
					'link' => 'https://www.themeboy.com/sportspress-extensions/midseason-transfers/',
					'desc' => __( 'Statistics for players who transferred between teams during a season.', 'sportspress' ),
					'default' => 'yes',
				),
				'staff_directories' => array(
					'label' => __( 'Directories', 'sportspress' ),
					'class' => 'SportsPress_Staff_Directories',
					'icon' => 'sp-icon-archive',
					'link' => 'https://www.themeboy.com/sportspress-extensions/directories/',
					'desc' => __( 'Organize and display staff in list and gallery layouts.', 'sportspress' ),
				),
				'officials' => array(
					'label' => __( 'Officials', 'sportspress' ),
					'icon' => 'sp-icon-whistle',
					'desc' => __( 'Manage referees, umpires, judges, timekeepers, and other officials.', 'sportspress' ),
					'default' => 'no',
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
					'link' => 'https://www.themeboy.com/sportspress-extensions/branding/',
					'desc' => __( 'Instantly rebrand the dashboard with your own logo and colors.', 'sportspress' ),
				),
				'duplicator' => array(
					'label' => __( 'Duplicator', 'sportspress' ),
					'class' => 'SportsPress_Duplicator',
					'icon' => 'sp-icon-copy',
					'link' => 'https://www.themeboy.com/sportspress-extensions/duplicator/',
					'desc' => __( 'Clone anything with just one click. Great for creating multiple events.', 'sportspress' ),
				),
			),
			'other' => array(
				'twitter' => array(
					'label' => __( 'Twitter', 'sportspress' ),
					'class' => 'SportsPress_Twitter',
					'icon' => 'dashicons dashicons-twitter',
					'link' => 'https://www.themeboy.com/sportspress-extensions/twitter/',
					'desc' => __( 'Add a Twitter feed to team, player, and staff pages.', 'sportspress' ),
					'tip' => __( 'Free', 'sportspress' ),
				),
				'facebook' => array(
					'label' => __( 'Facebook', 'sportspress' ),
					'class' => 'SportsPress_Facebook',
					'icon' => 'dashicons dashicons-facebook',
					'link' => 'https://www.themeboy.com/sportspress-extensions/facebook/',
					'desc' => __( 'Add a Facebook Page widget to embed and promote each team.', 'sportspress' ),
					'tip' => __( 'Free', 'sportspress' ),
				),
				'sponsors' => array(
					'label' => __( 'Sponsors', 'sportspress' ),
					'class' => 'SportsPress_Sponsors',
					'icon' => 'sp-icon-megaphone',
					'link' => 'https://www.themeboy.com/sportspress-extensions/sponsors/',
					'desc' => __( 'Attract sponsors by offering them advertising space on your website.', 'sportspress' ),
				),
			),
		));

		if ( class_exists( 'BuddyPress' ) ) {
			$this->data['other']['buddypress'] = array(
				'label' => __( 'BuddyPress', 'sportspress' ),
				'class' => 'BP_SportsPress_Component',
				'icon' => 'sp-icon-buddypress',
				'link' => 'https://www.themeboy.com/sportspress-extensions/buddypress/',
				'desc' => __( 'Easily display SportsPress player information in BuddyPress profiles.', 'sportspress' ),
				'tip' => __( 'Premium', 'sportspress' ),
			);
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$this->data['other']['woocommerce'] = array(
				'label' => __( 'WooCommerce', 'sportspress' ),
				'class' => 'WooCommerce_SportsPress',
				'icon' => 'sp-icon-woo',
				'link' => 'https://www.themeboy.com/sportspress-extensions/woocommerce/',
				'desc' => __( 'Sell team merchandise by integrating WooCommerce with SportsPress.', 'sportspress' ),
				'tip' => __( 'Premium', 'sportspress' ),
			);
		}

		if ( defined( 'WPSEO_FILE' ) ) {
			$this->data['other']['yoast_seo'] = array(
				'label' => __( 'Yoast SEO', 'sportspress' ),
				'class' => 'Yoast_SEO_SportsPress',
				'icon' => 'sp-icon-yoast',
				'link' => 'https://www.themeboy.com/sportspress-extensions/yoast-seo/',
				'desc' => __( 'Generate custom titles for SportsPress pages using Yoast SEO.', 'sportspress' ),
				'tip' => __( 'Free', 'sportspress' ),
			);
		}

		if ( is_multisite() ) {
			$this->data['other']['multisite'] = array(
				'label' => __( 'Multisite', 'sportspress' ),
				'class' => 'SportsPress_Multisite',
				'icon' => 'sp-icon-globe',
				'link' => 'https://www.themeboy.com/sportspress-extensions/multisite/',
				'desc' => __( 'Manage multiple sports and display different widgets all on one site.', 'sportspress' ),
			);
		}
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}
}
