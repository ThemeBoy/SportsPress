<?php
/**
 * Setup exporters for SP data.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version		2.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Exporters' ) ) :

/**
 * SP_Admin_Exporters Class
 */
class SP_Admin_Exporters {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_print_footer_scripts', array( $this, 'action_links' ) );
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
	}

	/**
	 * Add action link after post list title
	 */
	public function action_links() {
		global $pagenow, $typenow;
		if ( in_array( $typenow, sp_importable_post_types() ) ) {
			if ( 'sp_event' === $typenow ) {
				if ( 'edit.php' === $pagenow ) {
					?>
					<script type="text/javascript">
					(function($) {
						$(".wrap .page-title-action").first().after(
							$("<a class=\"add-new-h2\" href=\"<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp_fixture_exporter' ), 'admin.php' ) ) ); ?>\"><?php _e( 'Export Fixtures', 'sportspress' ); ?></a>")
						).after(
							$("<a class=\"add-new-h2\" href=\"<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'sp_event_exporter' ), 'admin.php' ) ) ); ?>\"><?php _e( 'Export Events', 'sportspress' ); ?></a>")
						);
					})(jQuery);
					</script>
					<?php
				}
			} else {
				if ( 'edit.php' === $pagenow ) {
					?>
					<script type="text/javascript">
					(function($) {
						$(".wrap .page-title-action").first().after(
							$("<a class=\"add-new-h2\" href=\"<?php echo esc_url( admin_url( add_query_arg( array( 'page' => $typenow . '_exporter' ), 'admin.php' ) ) ); ?>\"><?php _e( 'Export', 'sportspress' ); ?></a>")
						);
					})(jQuery);
					</script>
					<?php
				}
			}
		}
	}
	
	/**
	 * Register submenu
	 * @return void
	 */
	public function register_sub_menu() {
		add_submenu_page( 
			'edit.php?post_type=sp_event', 
			'Export SportsPress Events', 
			'Export SportsPress Events', 
			'manage_options', 
			'sp_event_exporter', 
			array( $this, 'events_exporter' )
		);
		add_submenu_page( 
			'edit.php?post_type=sp_event', 
			'Export SportsPress Fixtures', 
			'Export SportsPress Fixtures', 
			'manage_options', 
			'sp_fixture_exporter', 
			array( $this, 'fixtures_exporter' )
		);
		add_submenu_page( 
			'edit.php?post_type=sp_team', 
			'Export SportsPress Teams', 
			'Export SportsPress Teams', 
			'manage_options', 
			'sp_team_exporter', 
			array( $this, 'teams_exporter' )
		);
		add_submenu_page( 
			'edit.php?post_type=sp_player', 
			'Export SportsPress Players', 
			'Export SportsPress Players', 
			'manage_options', 
			'sp_player_exporter', 
			array( $this, 'players_exporter' )
		);
		add_submenu_page( 
			'edit.php?post_type=sp_staff', 
			'Export SportsPress Staff', 
			'Export SportsPress Staff', 
			'manage_options', 
			'sp_staff_exporter', 
			array( $this, 'staff_exporter' )
		);
	}

	/**
	 * Add menu item
	 */
	public function events_exporter() {
		require 'exporters/class-sp-event-exporter.php';
	}

	/**
	 * Add menu item
	 */
	public function fixtures_exporter() {
		require 'exporters/class-sp-fixture-exporter.php';
	}

	/**
	 * Add menu item
	 */
	public function teams_exporter() {
		require 'exporters/class-sp-team-exporter.php';
	}

	/**
	 * Add menu item
	 */
	public function players_exporter() {
		require 'exporters/class-sp-player-exporter.php';
	}

	/**
	 * Add menu item
	 */
	public function staff_exporter() {
		require 'exporters/class-sp-staff-exporter.php';
	}
	
}

endif;

return new SP_Admin_Exporters();