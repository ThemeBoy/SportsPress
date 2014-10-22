<?php
/**
 * Debug/Status page
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Status' ) ) :

/**
 * SP_Admin_Status Class
 */
class SP_Admin_Status {

	/**
	 * Retrieve metadata from a file. Based on WP Core's get_file_data function
	 *
	 * @since 0.8
	 * @param string $file Path to the file
	 * @param array $all_headers List of headers, in the format array('HeaderKey' => 'Header Name')
	 */
	public function get_file_version( $file ) {
		// We don't need to write to the file, so just open for reading.
		$fp = fopen( $file, 'r' );

		// Pull only the first 8kiB of the file in.
		$file_data = fread( $fp, 8192 );

		// PHP will close file handle, but we are good citizens.
		fclose( $fp );

		// Make sure we catch CR-only line endings.
		$file_data = str_replace( "\r", "\n", $file_data );
		$version   = '';

		if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( '@version', '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] )
			$version = _cleanup_header_comment( $match[1] );

		return $version ;
	}
	
	/**
	 * Scan the template files
	 *
	 * @access public
 	 * @param string $template_path
 	 * @return array
	 */
	public function scan_template_files( $template_path ) {
		$files         = scandir( $template_path );
		$result        = array();
		if ( $files ) {
			foreach ( $files as $key => $value ) {
				if ( ! in_array( $value, array( ".",".." ) ) ) {
					if ( is_dir( $template_path . DIRECTORY_SEPARATOR . $value ) ) {
						$sub_files = $this->scan_template_files( $template_path . DIRECTORY_SEPARATOR . $value );
						foreach ( $sub_files as $sub_file ) {
							$result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
						}
					} else {
						$result[] = $value;
					}
				}
			}
		}
		return $result;
	}
}

endif;

return new SP_Admin_Status();
