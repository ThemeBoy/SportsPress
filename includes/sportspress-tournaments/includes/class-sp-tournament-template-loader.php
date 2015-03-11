<?php
/**
 * Tournament Bracket Template Loader
 *
 * @class 		SP_Tournament_Template_Loader
 * @version		1.7
 * @package		SportsPress_Tournaments
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Tournament_Template_Loader {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'tournament_content' ) );
	}

	public function add_content( $content, $template, $append = false ) {
		ob_start();
		$template = sp_get_template( 'content-single-tournament.php', array(), '', SP_TOURNAMENTS_DIR . 'templates/' );
		if ( $append )
			return $content . ob_get_clean();
		else
			return ob_get_clean() . $content;
	}

	public function tournament_content( $content ) {
		if ( is_singular( 'sp_tournament' ) )
			$content = self::add_content( $content, 'tournament' );
		return $content;
	}
}

new SP_Tournament_Template_Loader();
			