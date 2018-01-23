<?php
/**
 * Team importer - import teams into SportsPress.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Importers
 * @version		2.5.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Team_Importer extends SP_Importer {

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->import_page = 'sp_team_csv';
			$this->import_label = __( 'Import Teams', 'sportspress' );
			$this->columns = array(
				'post_title' => __( 'Name', 'sportspress' ),
				'sp_league' => __( 'Leagues', 'sportspress' ),
				'sp_season' => __( 'Seasons', 'sportspress' ),
				'sp_url' => __( 'Site URL', 'sportspress' ),
				'sp_abbreviation' => __( 'Abbreviation', 'sportspress' ),
				'sp_venue' => __( 'Home', 'sportspress' ),
			);
			parent::__construct();
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

			foreach ( $rows as $row ):

				$row = array_filter( $row );

				if ( empty( $row ) ) continue;

				$meta = array();

				foreach ( $columns as $index => $key ):
					$meta[ $key ] = sp_array_value( $row, $index );
				endforeach;

				$name = sp_array_value( $meta, 'post_title' );

				if ( ! $name ):
					$this->skipped++;
					continue;
				endif;

				// Get or insert team
				$team_object = sp_array_value( $_POST, 'merge', 0 ) ? get_page_by_title( stripslashes( $name ), OBJECT, 'sp_team' ) : false;
				if ( $team_object ):
					if ( $team_object->post_status != 'publish' ):
						wp_update_post( array( 'ID' => $team_object->ID, 'post_status' => 'publish' ) );
					endif;
					$id = $team_object->ID;
				else:
					$args = array( 'post_type' => 'sp_team', 'post_status' => 'publish', 'post_title' => wp_strip_all_tags( $name ) );
					$id = wp_insert_post( $args );

					// Flag as import
					update_post_meta( $id, '_sp_import', 1 );
				endif;

				// Update leagues
				$leagues = explode( '|', sp_array_value( $meta, 'sp_league' ) );
				wp_set_object_terms( $id, $leagues, 'sp_league', false );

				// Update seasons
				$seasons = explode( '|', sp_array_value( $meta, 'sp_season' ) );
				wp_set_object_terms( $id, $seasons, 'sp_season', false );

				// Update venues
				$venues = explode( '|', sp_array_value( $meta, 'sp_venue' ) );
				wp_set_object_terms( $id, $venues, 'sp_venue', false );

				// Update meta
				update_post_meta( $id, 'sp_url', sp_array_value( $meta, 'sp_url' ) );
				update_post_meta( $id, 'sp_abbreviation', sp_array_value( $meta, 'sp_abbreviation' ) );

				$this->imported++;

			endforeach;

			// Show Result
			echo '<div class="updated settings-error below-h2"><p>
				'.sprintf( __( 'Import complete - imported <strong>%s</strong> teams and skipped <strong>%s</strong>.', 'sportspress' ), $this->imported, $this->skipped ).'
			</p></div>';

			$this->import_end();
		}

		/**
		 * Performs post-import cleanup of files and the cache
		 */
		function import_end() {
			echo '<p>' . __( 'All done!', 'sportspress' ) . ' <a href="' . admin_url('edit.php?post_type=sp_team') . '">' . __( 'View Teams', 'sportspress' ) . '</a>' . '</p>';

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
			echo '<p>' . sprintf( __( 'Teams need to be defined with columns in a specific order (3 columns). <a href="%s">Click here to download a sample</a>.', 'sportspress' ), plugin_dir_url( SP_PLUGIN_FILE ) . 'dummy-data/teams-sample.csv' ) . '</p>';
			wp_import_upload_form( 'admin.php?import=sp_team_csv&step=1' );
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
						<td>
							<label>
								<input type="hidden" name="merge" value="0">
								<input type="checkbox" name="merge" value="1" checked="checked">
								<?php _e( 'Merge duplicates', 'sportspress' ); ?>
							</label>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
	}
}
