<?php
/*
Plugin Name: SportsPress User Scores
Plugin URI: http://tboy.co/pro
Description: Allow players, staff, and visitors to submit event scores.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_User_Scores' ) ) :

/**
 * Main SportsPress User Scores Class
 *
 * @class SportsPress_User_Scores
 * @version	2.3
 *
 */
class SportsPress_User_Scores {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_filter( 'sportspress_event_templates', array( $this, 'templates' ) );
	  add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter( 'sportspress_event_settings', array( $this, 'add_settings' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handlers' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_USER_SCORES_VERSION' ) )
			define( 'SP_USER_SCORES_VERSION', '2.3' );

		if ( !defined( 'SP_USER_SCORES_URL' ) )
			define( 'SP_USER_SCORES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_USER_SCORES_DIR' ) )
			define( 'SP_USER_SCORES_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add templates to event layout.
	 *
	 * @return array
	 */
	public function templates( $templates = array() ) {
		$templates['user_results'] = array(
			'title' => __( 'User Results', 'sportspress' ),
			'label' => __( 'My Results', 'sportspress' ),
			'option' => 'sportspress_event_show_user_results',
			'action' => array( $this, 'results_form' ),
			'default' => 'no',
		);

		$templates['user_scores'] = array(
			'title' => __( 'User Scores', 'sportspress' ),
			'label' => __( 'My Scores', 'sportspress' ),
			'option' => 'sportspress_event_show_user_scores',
			'action' => array( $this, 'scores_form' ),
			'default' => 'no',
		);
		
		return $templates;
	}

	/**
	 * Output user results submission form.
	 *
	 * @access public
	 * @return void
	 */
	public function results_form() {
		sp_get_template( 'event-user-results.php', array(), '', SP_USER_SCORES_DIR . 'templates/' );
	}

	/**
	 * Output user scores submission form.
	 *
	 * @access public
	 * @return void
	 */
	public function scores_form() {
		sp_get_template( 'event-user-scores.php', array(), '', SP_USER_SCORES_DIR . 'templates/' );
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-user-scores'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_USER_SCORES_URL ) . 'css/sportspress-user-scores.css',
			'deps'    => 'sportspress-general',
			'version' => SP_USER_SCORES_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Add event settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		$settings = array_merge( $settings,
			array(
				array( 'title' => __( 'User Scores', 'sportspress' ), 'type' => 'title', 'id' => 'user_scores_options' ),
			),

			apply_filters( 'sportspress_user_scores_options', array(
				array(
					'title'     => __( 'Role', 'sportspress' ),
					'desc'     => __( 'League Manager', 'sportspress' ),
					'id' 		=> 'sportspress_user_scores_league_manager_status',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> 'start',
					'desc_tip' 		=> __( 'League managers can submit scores for any player.', 'sportspress' ),
				),

				array(
					'desc'     => __( 'Event Manager', 'sportspress' ),
					'id' 		=> 'sportspress_user_scores_event_manager_status',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> '',
					'desc_tip' 		=> __( 'Event managers can submit scores for any player.', 'sportspress' ),
				),

				array(
					'desc'     => __( 'Team Manager', 'sportspress' ),
					'id' 		=> 'sportspress_user_scores_team_manager_status',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> '',
					'desc_tip' 		=> __( 'Team managers can submit scores for their own team.', 'sportspress' ),
				),

				array(
					'desc'     => __( 'Staff', 'sportspress' ),
					'id' 		=> 'sportspress_user_scores_staff_status',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> '',
					'desc_tip' 		=> __( 'Staff can submit scores for their own team.', 'sportspress' ),
				),

				array(
					'desc'     => __( 'Players', 'sportspress' ),
					'id' 		=> 'sportspress_user_scores_player_status',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'end',
					'desc_tip' 		=> __( 'Players can submit individual scores.', 'sportspress' ),
				),
			)),

			array(
				array( 'type' => 'sectionend', 'id' => 'user_scores_options' ),
			)
		);
		return $settings;
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'My Results', 'sportspress' ),
			__( 'Submit Your Results', 'sportspress' ),
			__( 'Update Your Results', 'sportspress' ),
			__( 'My Scores', 'sportspress' ),
			__( 'Submit Your Scores', 'sportspress' ),
			__( 'Update Your Scores', 'sportspress' ),
			__( 'Submit', 'sportspress' ),
		) );
	}

	/**
	 * Conditonally load classes and functions only needed when viewing the post type.
	 */
	public function include_post_type_handlers() {
		include_once( 'includes/class-sp-user-scores-meta-boxes.php' );
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( in_array( $screen->id, array( 'sp_event', 'edit-sp_event' ) ) ) {
			wp_enqueue_style( 'sportspress-user-scores-admin', SP_USER_SCORES_URL . 'css/admin.css', array(), SP_USER_SCORES_VERSION );
			wp_enqueue_script( 'sportspress-user-scores-admin', SP_USER_SCORES_URL . 'js/admin.js', array( 'jquery' ), SP_USER_SCORES_VERSION );
		}
	}
}

endif;

if ( get_option( 'sportspress_load_user_scores_module', 'yes' ) == 'yes' ) {
	new SportsPress_User_Scores();
}
