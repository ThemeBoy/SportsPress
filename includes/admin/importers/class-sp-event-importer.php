<?php
/**
 * Event importer - import events into SportsPress.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Importers
 * @version     1.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Event_Importer extends SP_Importer {

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->import_page = 'sp_event_csv';
			$this->import_label = __( 'Import Events', 'sportspress' );
			$this->columns = array(
				'post_date' => __( 'Date', 'sportspress' ),
				'post_time' => __( 'Time', 'sportspress' ),
				'sp_venue' => __( 'Venue', 'sportspress' ),
				'sp_team' => __( 'Teams', 'sportspress' ),
				'sp_results' => __( 'Results', 'sportspress' ),
				'sp_outcome' => __( 'Outcome', 'sportspress' ),
				'sp_player' => __( 'Players', 'sportspress' ),
			);
			$performance_labels = sp_get_var_labels( 'sp_performance' );
			if ( $performance_labels && is_array( $performance_labels ) && sizeof( $performance_labels ) )
				$this->columns = array_merge( $this->columns, $performance_labels );
		}

		/**
		 * import function.
		 *
		 * @access public
		 * @param array $array
		 * @param array $columns
		 * @return void
		 */
		function import( $array = array(), $columns = array( 'post_title' ) ) {
			$this->imported = $this->skipped = 0;

			if ( ! is_array( $array ) || ! sizeof( $array ) ):
				$this->footer();
				die();
			endif;

			$rows = array_chunk( $array, sizeof( $columns ) );

			// Get event format, league, and season from post vars
			$event_format = ( empty( $_POST['sp_format'] ) ? false : $_POST['sp_format'] );
			$league = ( sp_array_value( $_POST, 'sp_league', '-1' ) == '-1' ? false : $_POST['sp_league'] );
			$season = ( sp_array_value( $_POST, 'sp_season', '-1' ) == '-1' ? false : $_POST['sp_season'] );
			$date_format = ( empty( $_POST['sp_date_format'] ) ? 'yyyy/mm/dd' : $_POST['sp_date_format'] );

			// Get labels from result and performance post types
			$result_labels = sp_get_var_labels( 'sp_result' );
			$performance_labels = sp_get_var_labels( 'sp_performance' );

			foreach ( $rows as $row ):

				$row = array_filter( $row );

				if ( empty( $row ) ) continue;

				$meta = array();

				foreach ( $columns as $index => $key ):
					$meta[ $key ] = sp_array_value( $row, $index );
				endforeach;

				// Slice array into event, team, and player
				$event = array_slice( $row, 0, 3 );
				$team = array_slice( $row, 3, 3 );
				$player = array_slice( $row, 6 );

				// Get event details
				$event = array(
					sp_array_value( $meta, 'post_date' ),
					sp_array_value( $meta, 'post_time' ),
					sp_array_value( $meta, 'sp_venue' ),
				);
				unset( $meta['post_date'] );
				unset( $meta['post_time'] );
				unset( $meta['sp_venue'] );

				// Get team results
				$team = array(
					sp_array_value( $meta, 'sp_team' ),
					sp_array_value( $meta, 'sp_results' ),
					sp_array_value( $meta, 'sp_outcome' ),
				);
				unset( $meta['sp_team'] );
				unset( $meta['sp_results'] );
				unset( $meta['sp_outcome'] );

				// Get player performance
				$player = array(
					sp_array_value( $meta, 'sp_player' ),
				);
				unset( $meta['sp_player' ] );
				foreach ( $performance_labels as $key => $label ):
					$player[] = sp_array_value( $meta, $key, '' );
				endforeach;

				// Add new event if date is given
				if ( sizeof( $event ) > 0 && ! empty( $event[0] ) ):

					// Add player performance to last event if available
					if ( isset( $id ) && isset( $players ) && sizeof( $players ) > 0 ):
						update_post_meta( $id, 'sp_players', $players );
					endif;

					// List event columns
					list( $date, $time, $venue ) = $event;

					// Format date
					$date = str_replace( '/', '-', trim( $date ) );
					$date_array = explode( '-', $date );
					switch ( $date_format ):
						case 'dd/mm/yyyy':
							$date = substr( str_pad( sp_array_value( $date_array, 2, '0000' ), 4, '0', STR_PAD_LEFT ), 0, 4 ) . '-' .
								substr( str_pad( sp_array_value( $date_array, 1, '00' ), 2, '0', STR_PAD_LEFT ), 0, 2 ) . '-' .
								substr( str_pad( sp_array_value( $date_array, 0, '00' ), 2, '0', STR_PAD_LEFT ), 0, 2 );
							break;
						case 'mm/dd/yyyy':
							$date = substr( str_pad( sp_array_value( $date_array, 2, '0000' ), 4, '0', STR_PAD_LEFT ), 0, 4 ) . '-' .
								substr( str_pad( sp_array_value( $date_array, 0, '00' ), 2, '0', STR_PAD_LEFT ), 0, 2 ) . '-' .
								substr( str_pad( sp_array_value( $date_array, 1, '00' ), 2, '0', STR_PAD_LEFT ), 0, 2 );
							break;
						default:
							$date = substr( str_pad( sp_array_value( $date_array, 0, '0000' ), 4, '0', STR_PAD_LEFT ), 0, 4 ) . '-' .
								substr( str_pad( sp_array_value( $date_array, 1, '00' ), 2, '0', STR_PAD_LEFT ), 0, 2 ) . '-' .
								substr( str_pad( sp_array_value( $date_array, 2, '00' ), 2, '0', STR_PAD_LEFT ), 0, 2 );
					endswitch;

					// Add time to date if given
					if ( ! empty( $time ) ):
						$date .= ' ' . trim( $time );
					endif;

					// Define post type args
					$args = array( 'post_type' => 'sp_event', 'post_status' => 'publish', 'post_date' => $date );

					// Insert event
					$id = wp_insert_post( $args );

					// Initialize performance array
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
					$this->imported ++;

				endif;

				// Add new team if team name is given
				if ( sizeof( $team ) > 0 && ! empty( $team[0] ) ):

					// List team columns
					list( $team_name, $result, $outcome ) = $team;

					// Find out if team exists
					$team_object = get_page_by_title( stripslashes( $team_name ), OBJECT, 'sp_team' );

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
						$team_id = wp_insert_post( array( 'post_type' => 'sp_team', 'post_status' => 'publish', 'post_title' => wp_strip_all_tags( $team_name ) ) );

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
							$outcome_object = get_page_by_title( stripslashes( $outcome ), OBJECT, 'sp_outcome' );

							if ( $outcome_object ):

								// Make sure outcome is published
								if ( $outcome_object->post_status != 'publish' ):
									wp_update_post( array( 'ID' => $outcome_object->ID, 'post_status' => 'publish' ) );
								endif;

								// Get outcome slug
								$outcome_slug = $outcome_object->post_name;

							else:

								// Insert outcome
								$outcome_id = wp_insert_post( array( 'post_type' => 'sp_outcome', 'post_status' => 'publish', 'post_title' => wp_strip_all_tags( $outcome ) ) );

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
							$title .= ' ' . get_option( 'sportspress_event_teams_delimiter', 'vs' ) . ' ';
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

					// Get and unset player name leaving us with the performance
					$player_name = $player[0];
					unset( $player[0] );

					// Find out if player exists
					$player_object = get_page_by_title( stripslashes( $player_name ), OBJECT, 'sp_player' );

					// Get or insert player
					if ( $player_object ):

						// Make sure player is published
						if ( $player_object->post_status != 'publish' ):
							wp_update_post( array( 'ID' => $player_object->ID, 'post_status' => 'publish' ) );
						endif;

						// Get player ID
						$player_id = $player_object->ID;

						// Get player number
						$player_number = get_post_meta( $player_id, 'sp_number', true );

					else:

						// Insert player
						$player_id = wp_insert_post( array( 'post_type' => 'sp_player', 'post_status' => 'publish', 'post_title' => wp_strip_all_tags( $player_name ) ) );

						// Flag as import
						update_post_meta( $player_id, '_sp_import', 1 );

						// Update number
						update_post_meta( $player_id, 'sp_number', null );

						// Get player number
						$player_number = null;

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

						// Add player performance to array if team is available
						if ( isset( $team_id ) ):

							// Initialize performance array
							$performance = array( 'number' => $player_number );

							// Map keys to player performance
							foreach ( $performance_labels as $key => $label ):
								$performance[ $key ] = array_shift( $player );
							endforeach;
							$players[ $team_id ][ $player_id ] = $performance;

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

			endforeach;

			// Add player performance to last event if available
			if ( isset( $id ) && isset( $players ) && sizeof( $players ) > 0 ):
				update_post_meta( $id, 'sp_players', $players );
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
		 * greet function.
		 *
		 * @access public
		 * @return void
		 */
		function greet() {
			echo '<div class="narrow">';
			echo '<p>' . __( 'Hi there! Choose a .csv file to upload, then click "Upload file and import".', 'sportspress' ).'</p>';
			echo '<p>' . sprintf( __( 'Events need to be defined with columns in a specific order (3+ columns). <a href="%s">Click here to download a sample</a>.', 'sportspress' ), plugin_dir_url( SP_PLUGIN_FILE ) . 'dummy-data/events-sample.csv' ) . '</p>';
			wp_import_upload_form( 'admin.php?import=sp_event_csv&step=1' );
			echo '</div>';
		}

		/**
		 * options function.
		 *
		 * @access public
		 * @return void
		 */
		function options() {
			?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label><?php _e( 'Format', 'sportspress' ); ?></label><br/></th>
						<td class="forminp forminp-radio" id="sp_formatdiv">
							<fieldset id="post-formats-select">
								<ul>
									<li><input type="radio" name="sp_format" class="post-format" id="post-format-league" value="league" checked="checked"> <label for="post-format-league" class="post-format-icon post-format-league"><?php _e( 'Competitive', 'sportspress' ); ?></label></li>
									<li><input type="radio" name="sp_format" class="post-format" id="post-format-friendly" value="friendly"> <label for="post-format-friendly" class="post-format-icon post-format-friendly"><?php _e( 'Friendly', 'sportspress' ); ?></label></li>
								<br>
							</fieldset>
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Competition', 'sportspress' ); ?></label><br/></th>
						<td><?php
						$args = array(
							'taxonomy' => 'sp_league',
							'name' => 'sp_league',
							'values' => 'slug',
							'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
						);
						if ( ! sp_dropdown_taxonomies( $args ) ):
							echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
							sp_taxonomy_adder( 'sp_league', 'sp_team', __( 'Add New', 'sportspress' ) );
						endif;
						?></td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Season', 'sportspress' ); ?></label><br/></th>
						<td><?php
						$args = array(
							'taxonomy' => 'sp_season',
							'name' => 'sp_season',
							'values' => 'slug',
							'show_option_none' => __( '&mdash; Not set &mdash;', 'sportspress' ),
						);
						if ( ! sp_dropdown_taxonomies( $args ) ):
							echo '<p>' . __( 'None', 'sportspress' ) . '</p>';
							sp_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' ) );
						endif;
						?></td>
					</tr>
					<tr>
						<th scope="row" class="titledesc">
							<?php _e( 'Date Format', 'sportspress' ); ?>
						</th>
                		<td class="forminp forminp-radio">
                			<fieldset>
                				<ul>
									<li>
		                        		<label><input name="sp_date_format" value="yyyy/mm/dd" type="radio" checked> yyyy/mm/dd</label>
		                        	</li>
									<li>
		                        		<label><input name="sp_date_format" value="dd/mm/yyyy" type="radio"> dd/mm/yyyy</label>
		                        	</li>
									<li>
		                        		<label><input name="sp_date_format" value="mm/dd/yyyy" type="radio"> mm/dd/yyyy</label>
		                        	</li>
								</ul>
	                    	</fieldset>
	                    </td>
	                </tr>
	            </tbody>
	        </table>
			<?php
		}
	}
}
