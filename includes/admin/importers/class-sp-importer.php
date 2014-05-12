<?php
/**
 * SportsPress Importer
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Importers
 * @version     0.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( class_exists( 'WP_Importer' ) ) {
	class SP_Importer extends WP_Importer {

		var $id;
		var $file_url;
		var $import_page;
		var $delimiter;
		var $posts = array();
		var $imported;
		var $skipped;

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
			echo '<div class="wrap"><h2>' . __( 'Import', 'sportspress' ) . '</h2>';
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
		 * Added to http_request_timeout filter to force timeout at 60 seconds during import
		 * @param  int $val
		 * @return int 60
		 */
		function bump_request_timeout( $val ) {
			return 60;
		}
	}
}
