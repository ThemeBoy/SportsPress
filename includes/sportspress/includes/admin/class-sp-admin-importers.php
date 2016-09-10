<?php
/**
 * Setup importers for SP data.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Importers' ) ) :

/**
 * SP_Admin_Importers Class
 */
class SP_Admin_Importers {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_importers' ) );
	}

	/**
	 * Add menu items
	 */
	public function register_importers() {
		$importers = apply_filters( 'sportspress_importers', array(
			'sp_event_csv' => array(
				'name' => __( 'SportsPress Events (CSV)', 'sportspress' ),
				'description' => __( 'Import <strong>events</strong> from a csv file.', 'sportspress'),
				'callback' => array( $this, 'events_importer' ),
			),
			'sp_fixture_csv' => array(
				'name' => __( 'SportsPress Fixtures (CSV)', 'sportspress' ),
				'description' => __( 'Import <strong>fixtures</strong> from a csv file.', 'sportspress'),
				'callback' => array( $this, 'fixtures_importer' ),
			),
			'sp_team_csv' => array(
				'name' => __( 'SportsPress Teams (CSV)', 'sportspress' ),
				'description' => __( 'Import <strong>teams</strong> from a csv file.', 'sportspress'),
				'callback' => array( $this, 'teams_importer' ),
			),
			'sp_player_csv' => array(
				'name' => __( 'SportsPress Players (CSV)', 'sportspress' ),
				'description' => __( 'Import <strong>players</strong> from a csv file.', 'sportspress'),
				'callback' => array( $this, 'players_importer' ),
			),
			'sp_staff_csv' => array(
				'name' => __( 'SportsPress Staff (CSV)', 'sportspress' ),
				'description' => __( 'Import <strong>staff</strong> from a csv file.', 'sportspress'),
				'callback' => array( $this, 'staff_importer' ),
			),
		) );

		foreach ( $importers as $id => $importer ) {
			register_importer( $id, $importer['name'], $importer['description'], $importer['callback'] );
		}
	}

	/**
	 * Add menu item
	 */
	public function events_importer() {
		$this->includes();
		
	    require 'importers/class-sp-event-importer.php';

	    // Dispatch
	    $importer = new SP_Event_Importer();
	    $importer->dispatch();
	}

	/**
	 * Add menu item
	 */
	public function fixtures_importer() {
		$this->includes();
		
	    require 'importers/class-sp-fixture-importer.php';

	    // Dispatch
	    $importer = new SP_Fixture_Importer();
	    $importer->dispatch();
	}

	/**
	 * Add menu item
	 */
	public function teams_importer() {
		$this->includes();
		
	    require 'importers/class-sp-team-importer.php';

	    // Dispatch
	    $importer = new SP_Team_Importer();
	    $importer->dispatch();
	}

	/**
	 * Add menu item
	 */
	public function players_importer() {
		$this->includes();
		
	    require 'importers/class-sp-player-importer.php';

	    // Dispatch
	    $importer = new SP_Player_Importer();
	    $importer->dispatch();
	}

	/**
	 * Add menu item
	 */
	public function staff_importer() {
		$this->includes();

	    require 'importers/class-sp-staff-importer.php';

	    // Dispatch
	    $importer = new SP_Staff_Importer();
	    $importer->dispatch();
	}

	public static function includes() {
		// Load Importer API
	    require_once ABSPATH . 'wp-admin/includes/import.php';

	    if ( ! class_exists( 'WP_Importer' ) ) {
	        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	        if ( file_exists( $class_wp_importer ) )
	            require $class_wp_importer;
	    }

	    require 'importers/class-sp-importer.php';
	}
}

endif;

return new SP_Admin_Importers();