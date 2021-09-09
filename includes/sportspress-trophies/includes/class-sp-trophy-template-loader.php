<?php
/**
 * Trophy Template Loader
 *
 * @class 		SP_Trophy_Template_Loader
 * @version		2.8
 * @package		SportsPress Trophies
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Trophy_Template_Loader {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'trophy_content' ) );
	}

	public function add_content( $content, $template, $append = false ) {
		ob_start();
		sp_get_template( 'content-single-' . $template . '.php', array(), '', SP_TROPHIES_DIR . 'templates/' );
		if ( $append )
			return $content . ob_get_clean();
		else
			return ob_get_clean() . $content;
	}

	public function trophy_content( $content ) {
		if ( is_singular( 'sp_trophy' ) )
			$content = self::add_content( $content, 'trophy', apply_filters( 'sportspress_append_trophy_content', false ) );
		return $content;
	}
}

new SP_Trophy_Template_Loader();
			