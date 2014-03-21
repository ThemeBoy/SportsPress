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
			global $wpdb, $sportspress_options;

			$this->imported = $this->skipped = 0;

			if ( ! is_file($file) ):
				$this->footer();
				die();
			endif;

			ini_set( 'auto_detect_line_endings', '1' );

			if ( ( $handle = fopen( $file, "r" ) ) !== FALSE ):

				$header = fgetcsv( $handle, 0, $this->delimiter );

				if ( sizeof( $header ) >= 3 ):

					$loop = 0;

					// Get event format, league, and season from post vars
					$event_format = ( empty( $_POST['sp_format'] ) ? false : $_POST['sp_format'] );
					$league = ( empty( $_POST['sp_league'] ) ? false : $_POST['sp_league'] );
					$season = ( empty( $_POST['sp_season'] ) ? false : $_POST['sp_season'] );

					// Get labels from result and statistic post types
					$result_labels = sportspress_get_var_labels( 'sp_result' );
					$statistic_labels = sportspress_get_var_labels( 'sp_statistic' );

					while ( ( $row = fgetcsv( $handle, 0, $this->delimiter ) ) !== FALSE ):

						// Slice array into event, team, and player
						$event = array_slice( $row, 0, 3 );
						$team = array_slice( $row, 3, 3 );
						$player = array_slice( $row, 6 );

						// Add new event if date is given
						if ( sizeof( $event ) > 0 && ! empty( $event[0] ) ):

							// Add player statistics to last event if available
							if ( isset( $id ) && isset( $players ) && sizeof( $players ) > 0 ):
								update_post_meta( $id, 'sp_players', $players );
							endif;

							// List event columns
							list( $date, $time, $venue ) = $event;

							// Format date by replacing slashes with dashes
							$date = str_replace( '/', '-', trim( $date ) );

							// Add time to date if given
							if ( ! empty( $time ) ):
								$date .= ' ' . trim( $time );
							endif;

							// Define post type args
							$args = array( 'post_type' => 'sp_event', 'post_status' => 'publish', 'post_date' => $date );

							// Insert event
							$id = wp_insert_post( $args );

							// Initialize statistics array
							$players = array();

							// Flag as import
							update_post_meta( $id, '_sp_import', 1 );

							// Update event format
							if ( $event_format ):
								update_post_meta( $id, 'sp_format', $event_format );
							endif;

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

							// Increment
							$loop ++;
							$this->imported ++;

						endif;

						// Add new team if team name is given
						if ( sizeof( $team ) > 0 && ! empty( $team[0] ) ):

							// List team columns
							list( $team_name, $result, $outcome ) = $team;

							// Find out if team exists
							$team_object = get_page_by_title( $team_name, OBJECT, 'sp_team' );

							// Get or insert team
							if ( $team_object ):

								// Make sure team is published
								if ( $team_object->post_status != 'publish' ):
									wp_update_post( array( 'ID' => $team_object->ID, 'post_status' => 'publish' ) );
								endif;

								// Get team ID
								$team_id = $team_object->ID;

							else:

								// Insert team
								$team_id = wp_insert_post( array( 'post_type' => 'sp_team', 'post_status' => 'publish', 'post_title' => $team_name ) );

								// Flag as import
								update_post_meta( $team_id, '_sp_import', 1 );

							endif;

							// Update league
							if ( $league ):
								wp_set_object_terms( $team_id, $league, 'sp_league', true );
							endif;

							// Update season
							if ( $season ):
								wp_set_object_terms( $team_id, $season, 'sp_season', true );
							endif;

							// Add to event if exists
							if ( isset( $id ) ):

								// Add team to event
								add_post_meta( $id, 'sp_team', $team_id );

								// Add empty player to event
								add_post_meta( $id, 'sp_player', 0 );

								// Explode results into array
								$results = explode( '|', $result );

								// Create team results array from result keys
								$team_results = array();
								if ( sizeof( $result_labels ) > 0 ):
									foreach( $result_labels as $key => $label ):
										$team_results[ $key ] = trim( array_shift( $results ) );
									endforeach;
									$team_results[ 'outcome' ] = array();
								endif;

								// Explode outcomes into array
								$outcomes = explode( '|', $outcome );

								// Add outcome slugs to team outcomes array
								foreach ( $outcomes as $outcome ):

									// Continue if outcome doesn't exist
									if ( $outcome == null ):
										continue;
									endif;

									// Remove whitespace
									$outcome = trim( $outcome );

									// Get or insert outcome
									$outcome_object = get_page_by_title( $outcome, OBJECT, 'sp_outcome' );

									if ( $outcome_object ):

										// Make sure outcome is published
										if ( $outcome_object->post_status != 'publish' ):
											wp_update_post( array( 'ID' => $outcome_object->ID, 'post_status' => 'publish' ) );
										endif;

										// Get outcome slug
										$outcome_slug = $outcome_object->post_name;

									else:

										// Insert outcome
										$outcome_id = wp_insert_post( array( 'post_type' => 'sp_outcome', 'post_status' => 'publish', 'post_title' => $outcome ) );

										// Get outcome slug
									    $post_data = get_post( $outcome_id, ARRAY_A );
									    $outcome_slug = $post_data['post_name'];

										// Flag as import
										update_post_meta( $outcome_id, '_sp_import', 1 );

									endif;

									// Add to team results array
									$team_results[ 'outcome' ][] = $outcome_slug;

								endforeach;

								// Get existing results
								$event_results = get_post_meta( $id, 'sp_results', true );

								// Create new array if results not exists
								if ( ! $event_results ):
									$event_results = array();
								endif;

								// Add team results to existing results
								$event_results[ $team_id ] = $team_results;

								// Update event results
								update_post_meta( $id, 'sp_results', $event_results );

								// Get event name
								$title = get_the_title( $id );

								// Add delimiter if event name is set
								if ( $title ):
									$title .= ' ' . sportspress_array_value( $sportspress_options, 'event_teams_delimiter', 'vs' ) . ' ';
								endif;

								// Append team name to event name
								$title .= $team_name;

								// Update event with new name
								$post = array(
									'ID' => $id,
									'post_title' => $title,
								);
								wp_update_post( $post );

							endif;

						endif;

						// Add new player if player name is given
						if ( sizeof( $player ) > 0 && ! empty( $player[0] ) ):

							// Get and unset player name leaving us with the statistics
							$player_name = $player[0];
							unset( $player[0] );

							// Find out if player exists
							$player_object = get_page_by_title( $player_name, OBJECT, 'sp_player' );

							// Get or insert player
							if ( $player_object ):

								// Make sure player is published
								if ( $player_object->post_status != 'publish' ):
									wp_update_post( array( 'ID' => $player_object->ID, 'post_status' => 'publish' ) );
								endif;

								// Get player ID
								$player_id = $player_object->ID;

							else:

								// Insert player
								$player_id = wp_insert_post( array( 'post_type' => 'sp_player', 'post_status' => 'publish', 'post_title' => $player_name ) );

								// Flag as import
								update_post_meta( $player_id, '_sp_import', 1 );

								// Update number
								update_post_meta( $player_id, 'sp_number', null );

							endif;

							// Update league
							if ( $league ):
								wp_set_object_terms( $player_id, $league, 'sp_league', true );
							endif;

							// Update season
							if ( $season ):
								wp_set_object_terms( $player_id, $season, 'sp_season', true );
							endif;

							// Add to event if exists
							if ( isset( $id ) ):

								// Add player to event
								add_post_meta( $id, 'sp_player', $player_id );

								// Add player statistics to array if team is available
								if ( isset( $team_id ) ):

									// Initialize statistics array
									$statistics = array();

									// Map keys to player statistics
									foreach ( $statistic_labels as $key => $label ):
										$statistics[ $key ] = array_shift( $player );
									endforeach;
									$players[ $team_id ][ $player_id ] = $statistics;

									// Get player teams
									$player_teams = get_post_meta( $player_id, 'sp_team', false );
									$current_team = get_post_meta( $player_id, 'sp_current_team', true );
									$past_teams = get_post_meta( $player_id, 'sp_past_team', false );

									// Add team if not exists in player
									if ( ! in_array( $team_id, $player_teams ) ):
										add_post_meta( $player_id, 'sp_team', $team_id );
									endif;

									// Add as past team or set current team if not set
									if ( ! $current_team ):
										update_post_meta( $player_id, 'sp_current_team', $team_id );
									elseif ( $current_team != $team_id && ! in_array( $team_id, $past_teams ) ):
										add_post_meta( $player_id, 'sp_past_team', $team_id );
									endif;

								endif;

							endif;

						endif;

					endwhile;

					// Add player statistics to last event if available
					if ( isset( $id ) && isset( $players ) && sizeof( $players ) > 0 ):
						update_post_meta( $id, 'sp_players', $players );
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

			echo '<p>' . sprintf( __( 'Events need to be defined with columns in a specific order (3+ columns). <a href="%s">Click here to download a sample</a>.', 'sportspress' ), SPORTSPRESS_PLUGIN_URL . 'dummy-data/events-sample.csv' ) . '</p>';

			$action = 'admin.php?import=sportspress_event_csv&step=1';

			$bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
			$size = size_format( $bytes );
			$upload_dir = wp_upload_dir();
			if ( ! empty( $upload_dir['error'] ) ) :
				?><div class="error"><p><?php _e('Before you can upload your import file, you will need to fix the following error:', 'sportspress'); ?></p>
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
									<small><?php printf( __( 'Maximum size: %s', 'sportspress' ), $size ); ?></small>
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
								<th><label><?php _e( 'Format', 'sportspress' ); ?></label><br/></th>
								<td id="sp_formatdiv">
									<div id="post-formats-select">
										<input type="radio" name="sp_format" class="post-format" id="post-format-league" value="league" checked="checked"> <label for="post-format-league" class="post-format-icon post-format-league">League</label>
										<br><input type="radio" name="sp_format" class="post-format" id="post-format-friendly" value="friendly"> <label for="post-format-friendly" class="post-format-icon post-format-friendly">Friendly</label>
										<br>
									</div>
								</td>
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
								if ( ! sportspress_dropdown_taxonomies( $args ) ):
									echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
									sportspress_taxonomy_adder( 'sp_league', 'sp_team', __( 'Add New', 'sportspress' ) );
								endif;
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
								if ( ! sportspress_dropdown_taxonomies( $args ) ):
									echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
									sportspress_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' ) );
								endif;
								?></td>
							</tr>
						</tbody>
					</table>
					<p class="submit">
						<input type="submit" class="button" value="<?php esc_attr_e( 'Upload file and import', 'sportspress' ); ?>" />
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
