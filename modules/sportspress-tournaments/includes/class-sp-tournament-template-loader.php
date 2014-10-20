<?php
/**
 * Tournament Bracket Template Loader
 *
 * @class 		SP_Tournament_Template_Loader
 * @version		1.4
 * @package		SportsPress_Tournaments
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Tournament_Template_Loader {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'template_loader' ) );
		add_filter( 'the_content', array( $this, 'tournament_content' ) );
	}

	public function add_content( $content, $template, $append = false ) {
		ob_start();
		$template = sp_get_template( 'content-single-tournament.php', array(), 'content-single-tournament', SP_TOURNAMENTS_DIR . 'templates/' );
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

	/**
	 * Load a template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. sportspress looks for theme
	 * overrides in /theme/sportspress/ by default
	 *
	 * For beginners, it also looks for a sportspress.php template first. If the user adds
	 * this to the theme (containing a sportspress() inside) this will be used for all
	 * sportspress templates.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public function template_loader( $template ) {
		$find = array();
		$file = '';

		if ( is_singular( 'sp_tournament' ) ):
			$file 	= 'single-tournament.php';
			$find[] = $file;
			$find[] = SP_TEMPLATE_PATH . $file;
		endif;

		if ( $file ):
			$located = locate_template( $find );
			if ( $located ):
				$template = $located;
			endif;
		endif;

		return $template;
	}
}

new SP_Tournament_Template_Loader();
			