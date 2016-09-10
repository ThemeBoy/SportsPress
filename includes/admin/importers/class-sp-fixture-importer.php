<?php
/**
 * Fixture importer - import fixtures into SportsPress.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Importers
 * @version     2.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Fixture_Importer extends SP_Importer {

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->import_page = 'sp_fixture_csv';
			$this->import_label = __( 'Import Fixtures', 'sportspress' );
			$this->columns = array(
				'post_date' => __( 'Date', 'sportspress' ),
				'post_time' => __( 'Time', 'sportspress' ),
				'sp_venue' => __( 'Venue', 'sportspress' ),
				'sp_home' => __( 'Home', 'sportspress' ),
				'sp_away' => __( 'Away', 'sportspress' ),
				'sp_day' => __( 'Match Day', 'sportspress' ),
			);
			$this->optionals = array( 'sp_day' );
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

			foreach ( $rows as $row ):

				$row = array_filter( $row );

				if ( empty( $row ) ) continue;

				$meta = array();

				foreach ( $columns as $index => $key ):
					$meta[ $key ] = sp_array_value( $row, $index );
				endforeach;

				// Get event details
				$event = array(
					sp_array_value( $meta, 'post_date' ),
					sp_array_value( $meta, 'post_time' ),
					sp_array_value( $meta, 'sp_venue' ),
					sp_array_value( $meta, 'sp_day' ),
				);

				$teams = array(
					sp_array_value( $meta, 'sp_home' ),
					sp_array_value( $meta, 'sp_away' ),
				);

				// Add new event if date is given
				if ( sizeof( $event ) > 0 && ! empty( $event[0] ) ):

					// List event columns
					list( $date, $time, $venue, $day ) = $event;

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
					$args = array( 'post_type' => 'sp_event', 'post_status' => 'publish', 'post_date' => $date, 'post_title' => __( 'Event', 'sportspress' ) );

					// Insert event
					$id = wp_insert_post( $args );

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

					// Update match day
					if ( '' !== $day ) {
						update_post_meta( $id, 'sp_day', $day );
					}

					// Increment
					$this->imported ++;

				endif;

				// Add teams to event
				if ( sizeof( $teams ) > 0 ):

					foreach ( $teams as $team_name ):

						if ( '' !== $team_name ):

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

								// Get event name
								$title = get_the_title( $id );

								// Initialize event name
								if ( __( 'Event', 'sportspress' ) === $title ) {
									$title = '';
								} else {
									$title .= ' ' . get_option( 'sportspress_event_teams_delimiter', 'vs' ) . ' ';
								}

								// Append team name to event name
								$title .= $team_name;

								// Update event with new name
								$post = array(
									'ID' => $id,
									'post_title' => $title,
									'post_name' => $id,
								);
								wp_update_post( $post );

							endif;

						else:

							// Add empty team to event
							add_post_meta( $id, 'sp_team', -1 );
						
						endif;

					endforeach;

				endif;

			endforeach;

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
			echo '<p>' . __( 'All done!', 'sportspress' ) . ' <a href="' . admin_url('edit.php?post_type=sp_event') . '">' . __( 'View Fixtures', 'sportspress' ) . '</a>' . '</p>';

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
			echo '<p>' . sprintf( __( 'Fixtures need to be defined with columns in a specific order (4+ columns). <a href="%s">Click here to download a sample</a>.', 'sportspress' ), plugin_dir_url( SP_PLUGIN_FILE ) . 'dummy-data/fixtures-sample.csv' ) . '</p>';
			echo '<p>' . sprintf( __( 'Supports CSV files generated by <a href="%s">LeagueLobster</a>.', 'sportspress' ), 'http://tboy.co/leaguelobster' ) . '</p>';
			wp_import_upload_form( 'admin.php?import=sp_fixture_csv&step=1' );
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
