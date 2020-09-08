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

		if ( in_array( $plugin_page, $this->export_pages ) && isset( $_POST['submit'] ) && isset( $_POST['sp_exporter_nonce'] ) && wp_verify_nonce( $_POST['sp_exporter_nonce'], 'sp-admin-exporters' ) ) {

			function outputData( $fileName, $assocDataArray, $format = 'csv' ) {
				$content_type = ( $format == 'json' ) ? 'application/json' : 'text/csv';
				ob_clean();
				header( 'Pragma: public' );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
				header( 'Cache-Control: private', false );
				header( 'Content-Type: ' . $content_type );
				header( 'Content-Disposition: attachment;filename=' . $fileName );
				if ( isset( $assocDataArray[0] ) ) {
					$fp = fopen( 'php://output', 'w' );
					if ( $format == 'json' ) {
						fwrite( $fp, json_encode( $assocDataArray ) );
					}else{
						fputcsv( $fp, array_keys( $assocDataArray[0] ) );
						foreach ( $assocDataArray AS $values ) {
							fputcsv( $fp, $values );
						}
					}
					fclose( $fp );
				}
				ob_flush();
			}
			
			// Get file format ( csv as default )
			$format = ( isset( $_POST['format'] ) ) ? $_POST['format'] : 'csv';
			
			switch ( $plugin_page ) {
			  case 'sp_event_exporter':
				echo 'sp_event_exporter';
				break;
			  case 'sp_fixture_exporter':
				$fixtures = $this->sp_fixtures_data();
				outputData( 'sp_fixtures_' . time() . '.' . $format, $fixtures, $format );
				break;
			  case 'sp_team_exporter':
				$teams = $this->sp_teams_data();
				outputData( 'sp_teams_' . time() . '.' . $format, $teams, $format );
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
	* Generate events data
	*/
	public function sp_events_data() {
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
		
		$event_format = ( empty( $_POST['sp_format'] ) ? false : $_POST['sp_format'] );
		if ( $event_format ) {
			$args['meta_query'][] = array(
						'key' => 'sp_format',
						'value' => $event_format
					);
		}
		
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
		$args = apply_filters( 'sportspress_events_data_export_args', $args );
		$events = get_posts( $args );
		$events_array = array();
		$i = 0;
		if ( $events ) {
			foreach ( $events as $event ) {
				$events_array[$i]['event_id'] = $event->ID; //team_id
				$events_array[$i]['date'] = get_the_date( 'Y/m/d', $event ); //date
				$events_array[$i]['time'] = get_the_date( 'H:i:s', $event ); //time
				//$teams = get_post_meta ( $event->ID, 'sp_team' );
				//$events_array[$i]['home'] = get_the_title( $teams[0] ); //home
				//$events_array[$i]['away'] = get_the_title( $teams[1] ); //away
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
		
		$event_format = ( empty( $_POST['sp_format'] ) ? false : $_POST['sp_format'] );
		if ( $event_format ) {
			$args['meta_query'][] = array(
						'key' => 'sp_format',
						'value' => $event_format
					);
		}
		
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
		$args = apply_filters( 'sportspress_fixtures_data_export_args', $args );
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
	
	/**
	* Generate teams data
	*/
	public function sp_teams_data() {
		$args = array(
			'post_type' => 'sp_team',
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
		$args = apply_filters( 'sportspress_teams_data_export_args', $args );
		$teams = get_posts( $args );
		$teams_array = array();
		$i = 0;
		if ( $teams ) {
			foreach ( $teams as $team ) {
				//team_id
				$teams_array[$i]['team_id'] = $team->ID;
				//Name
				$teams_array[$i]['Name'] = $team->post_title; 
				//Leagues
				$leagues = get_the_terms( $team->ID, 'sp_league' );
				$leagues_names = array();
				foreach ( $leagues as $league ) {
					$leagues_names[] = $league->name;
				}
				$teams_array[$i]['Leagues'] = implode( '|', $leagues_names );
				//Seasons
				$seasons = get_the_terms( $team->ID, 'sp_season' );
				$seasons_names = array();
				foreach ( $seasons as $season ) {
					$seasons_names[] = $season->name;
				}
				$teams_array[$i]['Seasons'] = implode( '|', $seasons_names );
				//Site Url
				$url = get_post_meta ( $team->ID, 'sp_url', true );
				$teams_array[$i]['Site Url'] = $url;
				//Abbreviation
				$abbreviation = get_post_meta ( $team->ID, 'sp_abbreviation', true );
				$teams_array[$i]['Abbreviation'] = $abbreviation;
				//Home
				$venues = get_the_terms( $team->ID, 'sp_venue' );
				$venues_names = array();
				foreach ( $venues as $venue ) {
					$venues_names[] = $venue->name;
				}
				$teams_array[$i]['Home'] = implode( '|', $venues_names );
				$i++;
			}
		}
		return $teams_array;
	}
	
	/**
	* Generate players data
	*/
	public function sp_players_data() {
		$args = array(
			'post_type' => 'sp_player',
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
		$args = apply_filters( 'sportspress_players_data_export_args', $args );
		$players = get_posts( $args );
		$players_array = array();
		$i = 0;
		if ( $players ) {
			foreach ( $players as $player ) {
				//player_id
				$players_array[$i]['player_id'] = $player->ID;
				//Number
				$number = get_post_meta ( $player->ID, 'sp_number', true );
				$players_array[$i]['Number'] = $number;
				//Name
				$players_array[$i]['Name'] = $player->post_title;
				//Positions
				$positions = get_the_terms( $player->ID, 'sp_position' );
				$positions_names = array();
				foreach ( $positions as $position ) {
					$positions_names[] = $position->name;
				}
				$players_array[$i]['Positions'] = implode( '|', $positions_names );
				//Teams
				$teams = get_post_meta ( $player->ID, 'sp_team' );
				$teams_names = array();
				foreach ( $teams as $team_id ) {
					$teams_names[] = get_the_title( $team_id );
				}
				$players_array[$i]['Teams'] = implode( '|', $teams_names );
				//Leagues
				$leagues = get_the_terms( $player->ID, 'sp_league' );
				$leagues_names = array();
				foreach ( $leagues as $league ) {
					$leagues_names[] = $league->name;
				}
				$players_array[$i]['Leagues'] = implode( '|', $leagues_names );
				//Seasons
				$seasons = get_the_terms( $player->ID, 'sp_season' );
				$seasons_names = array();
				foreach ( $seasons as $season ) {
					$seasons_names[] = $season->name;
				}
				$players_array[$i]['Seasons'] = implode( '|', $seasons_names );
				//Nationality
				$nationality = get_post_meta ( $player->ID, 'sp_nationality', true );
				$players_array[$i]['Nationality'] = $nationality;
				//DoB
				$players_array[$i]['DoB'] = get_the_date( 'Y/m/d', $player );
				
				$i++;
			}
		}
		return $players_array;
	}
}

endif;

return new SP_Admin_Exporters();