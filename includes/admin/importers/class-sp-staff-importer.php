<?php
/**
 * Staff importer - import staff into SportsPress.
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Importers
 * @version     2.7.13
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Staff_Importer extends SP_Importer {

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->import_page  = 'sp_staff_csv';
			$this->import_label = esc_attr__( 'Import Staff', 'sportspress' );
			$this->columns      = array(
				'post_title'     => esc_attr__( 'Name', 'sportspress' ),
				'sp_role'        => esc_attr__( 'Jobs', 'sportspress' ),
				'sp_team'        => esc_attr__( 'Teams', 'sportspress' ),
				'sp_league'      => esc_attr__( 'Leagues', 'sportspress' ),
				'sp_season'      => esc_attr__( 'Seasons', 'sportspress' ),
				'sp_nationality' => esc_attr__( 'Nationality', 'sportspress' ),
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

			if ( ! is_array( $array ) || ! sizeof( $array ) ) :
				$this->footer();
				die();
			endif;

			$rows = array_chunk( $array, sizeof( $columns ) );

			foreach ( $rows as $row ) :

				$row = array_filter( $row );

				if ( empty( $row ) ) {
					continue;
				}

				$meta = array();

				foreach ( $columns as $index => $key ) :
					$meta[ $key ] = sp_array_value( $row, $index );
				endforeach;

				$name = sp_array_value( $meta, 'post_title' );

				if ( ! $name ) :
					$this->skipped++;
					continue;
				endif;

				// Get or insert staff
				$staff_object = sp_array_value( $_POST, 'merge', 0 ) ? sp_get_post_by_title( stripslashes( $name ), 'sp_staff' ) : false;
				if ( $staff_object ) :
					if ( $staff_object->post_status != 'publish' ) :
						wp_update_post(
							array(
								'ID'          => $staff_object->ID,
								'post_status' => 'publish',
							)
						);
					endif;
					$id = $staff_object->ID;
				else :
					$args = array(
						'post_type'   => 'sp_staff',
						'post_status' => 'publish',
						'post_title'  => wp_strip_all_tags( $name ),
					);
					$id   = wp_insert_post( $args );

					// Flag as import
					update_post_meta( $id, '_sp_import', 1 );
				endif;

				// Update roles
				$roles = explode( '|', sp_array_value( $meta, 'sp_role' ) );
				wp_set_object_terms( $id, $roles, 'sp_role', false );

				// Update leagues
				$leagues = explode( '|', sp_array_value( $meta, 'sp_league' ) );
				wp_set_object_terms( $id, $leagues, 'sp_league', false );

				// Update seasons
				$seasons = explode( '|', sp_array_value( $meta, 'sp_season' ) );
				wp_set_object_terms( $id, $seasons, 'sp_season', false );

				// Update teams
				$teams = (array) explode( '|', sp_array_value( $meta, 'sp_team' ) );
				$i     = 0;
				foreach ( $teams as $team ) :
					// Get or insert team
					$team_object = sp_get_post_by_title( stripslashes( $team ), 'sp_team' );
					if ( $team_object ) :
						if ( $team_object->post_status != 'publish' ) :
							wp_update_post(
								array(
									'ID'          => $team_object->ID,
									'post_status' => 'publish',
								)
							);
						endif;
						$team_id = $team_object->ID;
					else :
						$team_id = wp_insert_post(
							array(
								'post_type'   => 'sp_team',
								'post_status' => 'publish',
								'post_title'  => wp_strip_all_tags( $team ),
							)
						);
						// Flag as import
						update_post_meta( $team_id, '_sp_import', 1 );
						wp_set_object_terms( $team_id, $leagues, 'sp_league', false );
						wp_set_object_terms( $team_id, $seasons, 'sp_season', false );
					endif;

					// Add team to staff
					add_post_meta( $id, 'sp_team', $team_id );

					// Update current team if first in array
					if ( $i == 0 ) :
						update_post_meta( $id, 'sp_current_team', $team_id );
					endif;

					$i++;
				endforeach;

				// Update nationality
				$nationality = trim( strtolower( sp_array_value( $meta, 'sp_nationality' ) ) );
				if ( $nationality == '*' ) {
					$nationality = '';
				}
				update_post_meta( $id, 'sp_nationality', $nationality );

				$this->imported++;

			endforeach;

			// Show Result
			echo '<div class="updated settings-error below-h2"><p>
				' . wp_kses_post( sprintf( __( 'Import complete - imported <strong>%1$s</strong> staff and skipped <strong>%2$s</strong>.', 'sportspress' ), esc_html( $this->imported ), esc_html( $this->skipped ) ) ) . '
			</p></div>';

			$this->import_end();
		}

		/**
		 * Performs post-import cleanup of files and the cache
		 */
		function import_end() {
			echo '<p>' . esc_html__( 'All done!', 'sportspress' ) . ' <a href="' . esc_url( admin_url( 'edit.php?post_type=sp_staff' ) ) . '">' . esc_html__( 'View Staff', 'sportspress' ) . '</a>' . '</p>';

			do_action( 'import_end' );
		}

		/**
		 * header function.
		 *
		 * @access public
		 * @return void
		 */
		function header() {
			echo '<div class="wrap"><h2>' . esc_html__( 'Import Staff', 'sportspress' ) . '</h2>';
		}

		/**
		 * greet function.
		 *
		 * @access public
		 * @return void
		 */
		function greet() {
			echo '<div class="narrow">';
			echo '<p>' . esc_html__( 'Hi there! Choose a .csv file to upload, then click "Upload file and import".', 'sportspress' ) . '</p>';
			echo '<p>' . wp_kses_post( sprintf( __( 'Staff need to be defined with columns in a specific order (6 columns). <a href="%s">Click here to download a sample</a>.', 'sportspress' ), esc_url( plugin_dir_url( SP_PLUGIN_FILE ) ) . 'dummy-data/staff-sample.csv' ) ) . '</p>';
			wp_import_upload_form( 'admin.php?import=sp_staff_csv&step=1' );
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
								<?php esc_html_e( 'Merge duplicates', 'sportspress' ); ?>
							</label>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
	}
}
