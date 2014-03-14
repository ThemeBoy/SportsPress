<?php
/**
 * Event importer - import events into SportsPress.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Importers
 * @version     0.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Event_Importer extends WP_Importer {

		var $id;
		var $file_url;
		var $import_page;
		var $delimiter;
		var $posts = array();
		var $imported;
		var $skipped;

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->import_page = 'sportspress_event_csv';
		}

		/**
		 * Registered callback function for the WordPress Importer
		 *
		 * Manages the three separate stages of the CSV import process
		 */
		function dispatch() {
			$this->header();

			if ( ! empty( $_POST['delimiter'] ) )
				$this->delimiter = stripslashes( trim( $_POST['delimiter'] ) );

			if ( ! $this->delimiter )
				$this->delimiter = ',';

			$step = empty( $_GET['step'] ) ? 0 : (int) $_GET['step'];
			switch ( $step ):
				case 0:
					$this->greet();
					break;
				case 1:
					check_admin_referer( 'import-upload' );
					if ( $this->handle_upload() ):

						if ( $this->id )
							$file = get_attached_file( $this->id );
						else
							$file = ABSPATH . $this->file_url;

						add_filter( 'http_request_timeout', array( $this, 'bump_request_timeout' ) );

						if ( function_exists( 'gc_enable' ) )
							gc_enable();

						@set_time_limit(0);
						@ob_flush();
						@flush();

						$this->import( $file );
					endif;
					break;
			endswitch;
			$this->footer();
		}

		/**
		 * format_data_from_csv function.
		 *
		 * @access public
		 * @param mixed $data
		 * @param string $enc
		 * @return string
		 */
		function format_data_from_csv( $data, $enc ) {
			return ( $enc == 'UTF-8' ) ? $data : utf8_encode( $data );
		}

		/**
		 * import function.
		 *
		 * @access public
		 * @param mixed $file
		 * @return void
		 */
		function import( $file ) {
			global $wpdb;

			$this->imported = $this->skipped = 0;

			if ( ! is_file($file) ):
				$this->footer();
				die();
			endif;

			ini_set( 'auto_detect_line_endings', '1' );

			if ( ( $handle = fopen( $file, "r" ) ) !== FALSE ):

				$header = fgetcsv( $handle, 0, $this->delimiter );

				if ( sizeof( $header ) >= 4 ):

					$loop = 0;

					// Get league
					$league = ( empty( $_POST['sp_league'] ) ? false : $_POST['sp_league'] );

					// Get season
					$season = ( empty( $_POST['sp_season'] ) ? false : $_POST['sp_season'] );

					// Get labels from result variables
					$result_labels = sportspress_get_var_labels( 'sp_result' );

					// Get labels from statistic variables
					$statistic_labels = sportspress_get_var_labels( 'sp_statistic' );

					while ( ( $row = fgetcsv( $handle, 0, $this->delimiter ) ) !== FALSE ):

						$date = str_replace( '/', '-', $row[0] );
						unset( $row[0] );

						if ( ! empty( $date ) ):

							// Add players to previous event
							if ( isset( $id ) && isset( $players ) && sizeof( $players ) > 0 ):
								foreach ( $players as $team => $team_players ):
									add_post_meta( $id, 'sp_player', '0', false );
									foreach ( $team_players as $player_id => $player_statistics ):
										add_post_meta( $id, 'sp_player', $player_id, false );
									endforeach;
								endforeach;
								update_post_meta( $id, 'sp_players', $players );
							endif;

							// Add time to date
							$date .= ' ' . $row[1];
							unset( $row[1] );

							$venue = $row[2];
							unset( $row[2] );

							// Initialize arrays
							$teams = array();
							$team_names = array();
							$players = array();
							$results = array();

							foreach ( $row as $team ):

								$teamdata = explode( '|', $team );

								$name = $teamdata[0];
								unset( $teamdata[0] );

								$team_results = array();

								if ( sizeof( $result_labels ) > 0 ):
									foreach( $result_labels as $key => $label ):
										$team_results[ $key ] = array_shift( $teamdata );
									endforeach;
								endif;

								$outcomes = array();

								foreach ( $teamdata as $outcome ):

									// Get or insert outcome
									$outcome_object = get_page_by_path( $outcome, OBJECT, 'sp_outcome' );
									if ( $outcome_object ):
										if ( $outcome_object->post_status != 'publish' ):
											wp_update_post( array( 'ID' => $outcome_object->ID, 'post_status' => 'publish' ) );
										endif;
										$outcome_slug = $outcome_object->post_name;
									else:
										$outcome_id = wp_insert_post( array( 'post_type' => 'sp_outcome', 'post_status' => 'publish', 'post_title' => $outcome ) );
									    $post_data = get_post( $outcome_id, ARRAY_A );
									    $outcome_slug = $post_data['post_name'];
										// Flag as import
										update_post_meta( $outcome_id, '_sp_import', 1 );
									endif;
									$outcomes[] = $outcome_slug;
								endforeach;

								$team_names[] = $name;

								$teams[] = array( 'name' => $name, 'results' => $team_results, 'outcomes' => $outcomes );

							endforeach;

							$title = implode( ' ' . __( 'vs', 'sportspress' ) . ' ', $team_names );

							$args = array( 'post_type' => 'sp_event', 'post_status' => 'publish', 'post_title' => $title, 'post_date' => $date );

							$id = wp_insert_post( $args );

							// Flag as import
							update_post_meta( $id, '_sp_import', 1 );

							// Update league
							if ( $league ):
								wp_set_object_terms( $id, $league, 'sp_league', false );
							endif;

							// Update season
							if ( $season ):
								wp_set_object_terms( $id, $season, 'sp_season', false );
							endif;

							// Update venue
							wp_set_object_terms( $id, $venue, 'sp_venue', false );

							$team_ids = array();

							foreach ( $teams as $team ):
								// Get or insert team
								$team_object = get_page_by_path( $team['name'], OBJECT, 'sp_team' );
								if ( $team_object ):
									if ( $team_object->post_status != 'publish' ):
										wp_update_post( array( 'ID' => $team_object->ID, 'post_status' => 'publish' ) );
									endif;
									$team_id = $team_object->ID;
								else:
									$team_id = wp_insert_post( array( 'post_type' => 'sp_team', 'post_status' => 'publish', 'post_title' => $team['name'] ) );
									// Flag as import
									update_post_meta( $team_id, '_sp_import', 1 );
								endif;

								if ( $league ):
									wp_set_object_terms( $team_id, $league, 'sp_league', true );
								endif;

								if ( $season ):
									wp_set_object_terms( $team_id, $season, 'sp_season', true );
								endif;

								$team_ids[ $team['name'] ] = $team_id;
								$players[ $team_id ] = array();

								$results[ $team_id ] = $team['results'];
								$results[ $team_id ]['outcome'] = $team['outcomes'];

								// Add team to event
								add_post_meta( $id, 'sp_team', $team_id );
							endforeach;

							// Update results
							update_post_meta( $id, 'sp_results', $results );

							$loop ++;
							$this->imported++;

						elseif ( isset( $id ) ):

							unset( $row[0], $row[1], $row[2] );
							$ti = 0;
							foreach ( $row as $player ):

								if ( ! empty( $player ) ):
									$team_name = $team_names[ $ti ];
									$statistics = explode( '|', $player );

									$name = $statistics[0];
									unset( $statistics[0] );

									$player_statistics = array();

									$s = 0;
									foreach ( $statistic_labels as $key => $label ):
										$player_statistics[ $key ] = sportspress_array_value( $statistics, $s, 0 );
										$s ++;
									endforeach;

									// Get or insert player
									$player_object = get_page_by_path( $name, OBJECT, 'sp_player' );
									if ( $player_object ):
										if ( $player_object->post_status != 'publish' ):
											wp_update_post( array( 'ID' => $player_object->ID, 'post_status' => 'publish' ) );
										endif;
										$player_id = $player_object->ID;
									else:
										$player_id = wp_insert_post( array( 'post_type' => 'sp_player', 'post_status' => 'publish', 'post_title' => $name ) );
										// Flag as import
										update_post_meta( $player_id, '_sp_import', 1 );
										update_post_meta( $player_id, 'sp_number', '' );
									endif;

									if ( $league ):
										wp_set_object_terms( $player_id, $league, 'sp_league', true );
									endif;

									if ( $season ):
										wp_set_object_terms( $player_id, $season, 'sp_season', true );
									endif;

									$team_id = $team_ids[ $team_name ];

									$player_teams = get_post_meta( $player_id, 'sp_team', false );
									$current_team = get_post_meta( $player_id, 'sp_current_team', true );
									$past_teams = get_post_meta( $player_id, 'sp_past_team', false );

									if ( ! in_array( $team_id, $player_teams ) ):
										// Add team
										add_post_meta( $player_id, 'sp_team', $team_id );
									endif;
									if ( ! $current_team ):
										// Set team as current team
										update_post_meta( $player_id, 'sp_current_team', $team_id );
									elseif ( $current_team != $team_id && ! in_array( $team_id, $past_teams ) ):
										// Add team as past team
										add_post_meta( $player_id, 'sp_past_team', $team_id );
									endif;

									// Add player to players array
									$players[ $team_id ][ $player_id ] = $player_statistics;
								endif;

								$ti++;

							endforeach;

						endif;

				    endwhile;

					// Add players to last event
					if ( isset( $id ) && isset( $players ) && sizeof( $players ) > 0 ):
						foreach ( $players as $team => $team_players ):
							add_post_meta( $id, 'sp_player', '0', false );
							foreach ( $team_players as $player ):
								add_post_meta( $id, 'sp_player', $player, false );
							endforeach;
						endforeach;
					endif;

				else:

					echo '<p><strong>' . __( 'Sorry, there has been an error.', 'sportspress' ) . '</strong><br />';
					echo __( 'The CSV is invalid.', 'sportspress' ) . '</p>';
					$this->footer();
					die();

				endif;

			    fclose( $handle );
			endif;

			// Show Result
			echo '<div class="updated settings-error below-h2"><p>
				'.sprintf( __( 'Import complete - imported <strong>%s</strong> events and skipped <strong>%s</strong>.', 'sportspress' ), $this->imported, $this->skipped ).'
			</p></div>';

			$this->import_end();
		}

		/**
		 * Performs post-import cleanup of files and the cache
		 */
		function import_end() {
			echo '<p>' . __( 'All done!', 'sportspress' ) . ' <a href="' . admin_url('edit.php?post_type=sp_event') . '">' . __( 'View Events', 'sportspress' ) . '</a>' . '</p>';

			do_action( 'import_end' );
		}

		/**
		 * Handles the CSV upload and initial parsing of the file to prepare for
		 * displaying author import options
		 *
		 * @return bool False if error uploading or invalid file, true otherwise
		 */
		function handle_upload() {

			if ( empty( $_POST['file_url'] ) ) {

				$file = wp_import_handle_upload();

				if ( isset( $file['error'] ) ) {
					echo '<p><strong>' . __( 'Sorry, there has been an error.', 'sportspress' ) . '</strong><br />';
					echo esc_html( $file['error'] ) . '</p>';
					return false;
				}

				$this->id = (int) $file['id'];

			} else {

				if ( file_exists( ABSPATH . $_POST['file_url'] ) ) {

					$this->file_url = esc_attr( $_POST['file_url'] );

				} else {

					echo '<p><strong>' . __( 'Sorry, there has been an error.', 'sportspress' ) . '</strong></p>';
					return false;

				}

			}

			return true;
		}

		/**
		 * header function.
		 *
		 * @access public
		 * @return void
		 */
		function header() {
			echo '<div class="wrap"><h2>' . __( 'Import Events', 'sportspress' ) . '</h2>';
		}

		/**
		 * footer function.
		 *
		 * @access public
		 * @return void
		 */
		function footer() {
			echo '</div>';
		}

		/**
		 * greet function.
		 *
		 * @access public
		 * @return void
		 */
		function greet() {
	
			echo '<div class="narrow">';
			echo '<p>' . __( 'Hi there! Choose a .csv file to upload, then click "Upload file and import".', 'sportspress' ).'</p>';

			echo '<p>' . sprintf( __( 'Events need to be defined with columns in a specific order (4+ columns). <a href="%s">Click here to download a sample</a>.', 'sportspress' ), SPORTSPRESS_PLUGIN_URL . 'dummy-data/events-sample.csv' ) . '</p>';

			$action = 'admin.php?import=sportspress_event_csv&step=1';

			$bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
			$size = size_format( $bytes );
			$upload_dir = wp_upload_dir();
			if ( ! empty( $upload_dir['error'] ) ) :
				?><div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:'); ?></p>
				<p><strong><?php echo $upload_dir['error']; ?></strong></p></div><?php
			else :
				?>
				<form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo esc_attr(wp_nonce_url($action, 'import-upload')); ?>">
					<table class="form-table">
						<tbody>
							<tr>
								<th>
									<label for="upload"><?php _e( 'Choose a file from your computer:' ); ?></label>
								</th>
								<td>
									<input type="file" id="upload" name="import" size="25" />
									<input type="hidden" name="action" value="save" />
									<input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
									<small><?php printf( __('Maximum size: %s' ), $size ); ?></small>
								</td>
							</tr>
							<tr>
								<th>
									<label for="file_url"><?php _e( 'OR enter path to file:', 'sportspress' ); ?></label>
								</th>
								<td>
									<?php echo ' ' . ABSPATH . ' '; ?><input type="text" id="file_url" name="file_url" size="25" />
								</td>
							</tr>
							<tr>
								<th><label><?php _e( 'Delimiter', 'sportspress' ); ?></label><br/></th>
								<td><input type="text" name="delimiter" placeholder="," size="2" /></td>
							</tr>
							<tr>
								<th><label><?php _e( 'League', 'sportspress' ); ?></label><br/></th>
								<td><?php
								$args = array(
									'taxonomy' => 'sp_league',
									'name' => 'sp_league',
									'values' => 'slug',
									'show_option_none' => __( '-- Not set --', 'sportspress' ),
								);
								sportspress_dropdown_taxonomies( $args );
								?></td>
							</tr>
							<tr>
								<th><label><?php _e( 'Season', 'sportspress' ); ?></label><br/></th>
								<td><?php
								$args = array(
									'taxonomy' => 'sp_season',
									'name' => 'sp_season',
									'values' => 'slug',
									'show_option_none' => __( '-- Not set --', 'sportspress' ),
								);
								sportspress_dropdown_taxonomies( $args );
								?></td>
							</tr>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" class="button" value="<?php esc_attr_e( 'Upload file and import' ); ?>" />
					</p>
				</form>
				<?php
			endif;

			echo '</div>';
		}

		/**
		 * Added to http_request_timeout filter to force timeout at 60 seconds during import
		 * @param  int $val
		 * @return int 60
		 */
		function bump_request_timeout( $val ) {
			return 60;
		}
	}
}
