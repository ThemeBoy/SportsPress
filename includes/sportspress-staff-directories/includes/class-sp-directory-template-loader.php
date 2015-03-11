<?php
/**
 * Staff Directory Template Loader
 *
 * @class 		SP_Staff_Directory_Template_Loader
 * @version		1.7
 * @package		SportsPress_Staff_Directories
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Staff_Directory_Template_Loader {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'directory_content' ) );
	}

	public function add_content( $content, $template, $append = false ) {
		ob_start();
		$template = sp_get_template( 'content-single-directory.php', array(), '', SP_STAFF_DIRECTORIES_DIR . 'templates/' );
		if ( $append )
			return $content . ob_get_clean();
		else
			return ob_get_clean() . $content;
	}

	public function directory_content( $content ) {
		if ( is_singular( 'sp_directory' ) )
			$content = self::add_content( $content, 'directory' );
		return $content;
	}
}

new SP_Staff_Directory_Template_Loader();
			