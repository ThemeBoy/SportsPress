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
	
	/** @var array The export pages. */
	public $export_pages;

	public function __construct() {
		add_action( 'admin_print_footer_scripts', array( $this, 'action_links' ) );
		add_action( 'admin_menu', array( $this, 'register_sub_menu' ) );
		add_action( 'admin_init', array( $this, 'download_exported_file' ) );
		
		$this->export_pages = array ( 'sp_event_exporter', 'sp_fixture_exporter', 'sp_team_exporter', 'sp_player_exporter', 'sp_staff_exporter' );
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
			'export.php', 
			'Export SportsPress Events', 
			'Export SportsPress Events', 
			'manage_options', 
			'sp_event_exporter', 
			array( $this, 'events_exporter' )
		);
		add_submenu_page( 
			'export.php', 
			'Export SportsPress Fixtures', 
			'Export SportsPress Fixtures', 
			'manage_options', 
			'sp_fixture_exporter', 
			array( $this, 'fixtures_exporter' )
		);
		add_submenu_page( 
			'export.php', 
			'Export SportsPress Teams', 
			'Export SportsPress Teams', 
			'manage_options', 
			'sp_team_exporter', 
			array( $this, 'teams_exporter' )
		);
		add_submenu_page( 
			'export.php', 
			'Export SportsPress Players', 
			'Export SportsPress Players', 
			'manage_options', 
			'sp_player_exporter', 
			array( $this, 'players_exporter' )
		);
		add_submenu_page( 
			'export.php', 
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
	
	/**
	 * Download Exported file 
	 */
	public function download_exported_file() {
		global $plugin_page;
		if ( in_array( $plugin_page, $this->export_pages ) && isset( $_POST['submit'] ) ) {
			
			function outputCsv( $fileName, $assocDataArray ) {
				ob_clean();
				header( 'Pragma: public' );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
				header( 'Cache-Control: private', false );
				header( 'Content-Type: text/csv' );
				header( 'Content-Disposition: attachment;filename=' . $fileName );
				if ( isset( $assocDataArray[0] ) ) {
					$fp = fopen( 'php://output', 'w' );
					fputcsv( $fp, array_keys( $assocDataArray[0] ) );
					foreach ( $assocDataArray AS $values ) {
						fputcsv( $fp, $values );
					}
					fclose( $fp );
				}
				ob_flush();
			}
			
			switch ( $plugin_page ) {
			  case 'sp_event_exporter':
				echo 'sp_event_exporter';
				break;
			  case 'sp_fixture_exporter':
				$fixtures = $this->sp_fixtures_data();
				outputCsv( 'sp_fixtures_' . time() . '.csv', $fixtures );
				break;
			  case 'sp_team_exporter':
				echo 'sp_team_exporter';
				break;
			  case 'sp_player_exporter':
				echo 'sp_player_exporter';
				break;
			  case 'sp_staff_exporter':
				echo 'sp_staff_exporter';
				break;
			  default:
				echo 'error';
			}
			
			exit;
		}
	}
	
	
	/**
	* Generate fixtures data
	*/
	public function sp_fixtures_data() {
		$args = array(
			'post_type' => 'sp_event',
			'posts_per_page' => -1,
			'meta_query' => array(
				'relation' => 'AND'
			),
			'tax_query' => array(
				'relation' => 'AND'
			),
		);
		
		if ( $_POST['sp_league'] != "-1" ) {
			$args['tax_query'][] = array(
						'taxonomy' => 'sp_league',
						'field' => 'slug',
						'terms' => $_POST['sp_league']
					);
		}
		if ( $_POST['sp_season'] != "-1" ) {
			$args['tax_query'][] = array(
						'taxonomy' => 'sp_season',
						'field' => 'slug',
						'terms' => $_POST['sp_season']
					);
		}
		$events = get_posts( $args );
		$events_array = array();
		$i = 0;
		if ( $events ) {
			foreach ( $events as $event ) {
				$events_array[$i]['event_id'] = $event->ID; //team_id
				$events_array[$i]['date'] = get_the_date( 'Y/m/d', $event ); //date
				$events_array[$i]['time'] = get_the_date( 'H:i:s', $event ); //time
				$teams = get_post_meta ( $event->ID, 'sp_team' );
				$events_array[$i]['home'] = get_the_title( $teams[0] ); //home
				$events_array[$i]['away'] = get_the_title( $teams[1] ); //away
				$venues = get_the_terms( $event->ID, 'sp_venue' );
				if ( $venues ) {
					$venue = $venues[0]->name;
				}else{
					$venue = '';
				}
				$events_array[$i]['venue'] = $venue; //venue
				$events_array[$i]['day'] = get_post_meta ( $event->ID, 'sp_day', true ); //day
				$i++;
			}
		}
		return $events_array;
	}
}

endif;

return new SP_Admin_Exporters();