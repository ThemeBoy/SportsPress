<?php
/**
 * Event Performance importer - import box scores into SportsPress.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Importers
 * @version		2.5.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Event_Performance_Importer extends SP_Importer {

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->import_page = 'sp_event_performance_csv';
			$this->import_label = __( 'Import Box Score', 'sportspress' );
			$this->columns = array(
				'sp_player' => __( 'Player', 'sportspress' ),
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
		function import( $array = array(), $columns = array( 'sp_player' ) ) {
			$this->imported = $this->skipped = 0;

			if ( ! is_array( $array ) || ! sizeof( $array ) ):
				$this->footer();
				die();
			endif;

			$rows = array_chunk( $array, sizeof( $columns ) );

			// Get event ID and team ID from post vars
			$event = ( empty( $_POST['sp_event'] ) ? false : $_POST['sp_event'] );
			$teams = ( empty( $_POST['sp_teams'] ) ? false : $_POST['sp_teams'] );
			$index = ( empty( $_POST['sp_index'] ) ? false : $_POST['sp_index'] );
			$team = ( empty( $_POST['sp_team'] ) ? false : $_POST['sp_team'] );

			$team_players = array( 0 );
			$team_performance = array();
			$name_index = (int) array_search( 'sp_player', $columns );

			foreach ( $rows as $row ):

				$row = array_filter( $row );

				if ( empty( $row ) ) continue;

				$player_name = sp_array_value( $row, $name_index );
				
				if ( ! $player_name ):
					$this->skipped ++;
					continue;
				endif;

				$player_object = get_page_by_title( stripslashes( $player_name ), OBJECT, 'sp_player' );

				if ( $player_object ):

					// Get player ID
					$player_id = $player_object->ID;

				else:

					// Insert player
					$player_id = wp_insert_post( array( 'post_type' => 'sp_player', 'post_status' => 'publish', 'post_title' => wp_strip_all_tags( $player_name ) ) );

					// Flag as import
					update_post_meta( $player_id, '_sp_import', 1 );

				endif;

				$team_players[] = $player_id;
				$player = array();

				foreach ( $columns as $i => $key ):
					if ( 'sp_player' === $key ) continue;
					$player[ $key ] = sp_array_value( $row, $i, '' );
				endforeach;

				$team_performance[ $player_id ] = $player;

			endforeach;

			if ( $event && $team ):
				$the_players = get_post_meta( $event, 'sp_player', false );
				$players = array();
				for ( $i = 0; $i < $teams; $i++ ):
					if ( $index == $i ):
						array_push( $players, $team_players );
					else:
						array_push( $players, sp_array_between( $the_players, 0, $i ) );
					endif;
				endfor;
				sp_update_post_meta_recursive( $event, 'sp_player', $players );

				$this->imported = sizeof( $team_players ) - 1;

				$performance = (array) get_post_meta( $event, 'sp_players', true );
				$performance = array_filter( $performance );
				$performance[ $team ] = $team_performance;
				update_post_meta( $event, 'sp_players', $performance );
			endif;

			// Show Result
			echo '<div class="updated settings-error below-h2"><p>
				'.sprintf( __( 'Import complete - imported <strong>%s</strong> rows and skipped <strong>%s</strong>.', 'sportspress' ), $this->imported, $this->skipped ).'
			</p></div>';

			$this->import_end( $event );
		}

		/**
		 * Performs post-import cleanup of files and the cache
		 */
		function import_end( $event = 0 ) {
			echo '<p>' . __( 'All done!', 'sportspress' ) . ' <a href="' . admin_url( add_query_arg( array( 'post' => $event, 'action' => 'edit' ), 'post.php' ) ) . '">' . __( 'View Event', 'sportspress' ) . '</a>' . '</p>';

			do_action( 'import_end' );
		}

		/**
		 * greet function.
		 *
		 * @access public
		 * @return void
		 */
		function greet() {
			$event = sp_array_value( $_REQUEST, 'event', 0 );

			echo '<div class="narrow">';

			if ( $event ) {
				$args = array_merge( $_REQUEST, array( 'import' => 'sp_event_performance_csv', 'step' => '1' ) );
				echo '<p>' . __( 'Hi there! Choose a .csv file to upload, then click "Upload file and import".', 'sportspress' ).'</p>';
				echo '<p>' . sprintf( __( 'Box scores need to be defined with columns in a specific order. <a href="%s">Click here to download a sample</a>.', 'sportspress' ), plugin_dir_url( SP_PLUGIN_FILE ) . 'dummy-data/event-performance-sample.csv' ) . '</p>';
				wp_import_upload_form( add_query_arg( $args, 'admin.php' ) );
			} else {
				echo '<p><a href="' . admin_url( add_query_arg( array( 'post_type' => 'sp_event' ), 'edit.php' ) ) . '">' . sprintf( __( 'Select %s', 'sportspress' ), __( 'Event', 'sportspress' ) ) . '</a></p>';
			}

			echo '</div>';
		}

		/**
		 * options function.
		 *
		 * @access public
		 * @return void
		 */
		function options() {
			$event = sp_array_value( $_REQUEST, 'event', 0 );
			$teams = sp_array_value( $_REQUEST, 'teams', 0 );
			$index = sp_array_value( $_REQUEST, 'index', 0 );
			$team = sp_array_value( $_REQUEST, 'team', 0 );
			$include = get_post_meta( $event, 'sp_team', false );
			?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><label><?php _e( 'Event', 'sportspress' ); ?></label><br/></th>
						<td>
							<a href="<?php echo get_post_permalink( $event ); ?>" target="_blank">
								<?php echo get_the_title( $event ); ?>
							</a>
							<input type="hidden" name="sp_event" value="<?php echo $event; ?>">
							<input type="hidden" name="sp_teams" value="<?php echo $teams; ?>">
							<input type="hidden" name="sp_index" value="<?php echo $index; ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label><?php _e( 'Team', 'sportspress' ); ?></label><br/></th>
						<td>
							<?php
							$args = array(
								'post_type' => 'sp_team',
								'name' => 'sp_team',
								'values' => 'ID',
								'selected' => $team,
								'include' => $include,
							);
							sp_dropdown_pages( $args );
							?>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
	}
}
