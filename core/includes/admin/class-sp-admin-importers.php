<?php
/**
 * Setup importers for SP data.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
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
		register_importer( 'sportspress_event_csv', __( 'SportsPress Events (CSV)', 'sportspress' ), __( 'Import <strong>events</strong> from a csv file.', 'sportspress'), array( $this, 'events_importer' ) );
		register_importer( 'sportspress_team_csv', __( 'SportsPress Teams (CSV)', 'sportspress' ), __( 'Import <strong>teams</strong> from a csv file.', 'sportspress'), array( $this, 'teams_importer' ) );
		register_importer( 'sportspress_player_csv', __( 'SportsPress Players (CSV)', 'sportspress' ), __( 'Import <strong>players</strong> from a csv file.', 'sportspress'), array( $this, 'players_importer' ) );
		register_importer( 'sportspress_staff_csv', __( 'SportsPress Staff (CSV)', 'sportspress' ), __( 'Import <strong>staff</strong> from a csv file.', 'sportspress'), array( $this, 'staff_importer' ) );
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