<?php
/**
 * Sponsor importer - import sponsors into SportsPress.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress Sponsors
 * @version   2.5.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Sponsor_Importer extends SP_Importer {

		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->import_page = 'sp_sponsor_csv';
			$this->import_label = __( 'Import Sponsors', 'sportspress' );
			$this->columns = array(
				'post_title' => __( 'Name', 'sportspress' ),
				'sp_url' => __( 'URL', 'sportspress' ),
				'sp_level' => __( 'Level', 'sportspress' ),
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

				$args = array( 'post_type' => 'sp_sponsor', 'post_status' => 'publish', 'post_title' => wp_strip_all_tags( $name ) );

				$id = wp_insert_post( $args );

				// Update URL
				update_post_meta( $id, 'sp_url', sp_array_value( $meta, 'sp_url' ) );

				// Update levels
				$levels = explode( '|', sp_array_value( $meta, 'sp_level' ) );
				wp_set_object_terms( $id, $levels, 'sp_level', false );

				$this->imported++;

			endforeach;

			// Show Result
			echo '<div class="updated settings-error below-h2"><p>
				'.sprintf( __( 'Import complete - imported <strong>%s</strong> sponsors and skipped <strong>%s</strong>.', 'sportspress' ), $this->imported, $this->skipped ).'
			</p></div>';

			$this->import_end();
		}

		/**
		 * Performs post-import cleanup of files and the cache
		 */
		function import_end() {
			echo '<p>' . __( 'All done!', 'sportspress' ) . ' <a href="' . admin_url('edit.php?post_type=sp_sponsor') . '">' . __( 'View Sponsors', 'sportspress' ) . '</a>' . '</p>';

			do_action( 'import_end' );
		}

		/**
		 * header function.
		 *
		 * @access public
		 * @return void
		 */
		function header() {
			echo '<div class="wrap"><h2>' . __( 'Import Sponsors', 'sportspress' ) . '</h2>';
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
			echo '<p>' . sprintf( __( '%1$s need to be defined with columns in a specific order. <a href="%2$s">Click here to download a sample</a>.', 'sportspress' ), __( 'Sponsors', 'sportspress' ), SP_SPONSORS_URL . 'dummy-data/sponsors-sample.csv' ) . '</p>';
			wp_import_upload_form( 'admin.php?import=sp_sponsor_csv&step=1' );
			echo '</div>';
		}
	}
}
