<?php
/**
 * Sponsor Template Loader
 *
 * @class 		SP_Sponsor_Template_Loader
 * @version		1.0
 * @package		SportsPress Sponsors
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Sponsor_Template_Loader {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'template_loader' ) );
		add_filter( 'the_content', array( $this, 'sponsor_content' ) );
	}

	public function add_content( $content, $template, $position = 10 ) {
		if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
		if ( ! in_the_loop() ) return; // Return if not in main loop

		$content = '<div class="sp-post-content">' . $content . '</div>';

		ob_start();

		if ( $position <= 0 )
			echo $content;

		do_action( 'sportspress_before_single_' . $template );

		if ( post_password_required() ) {
			echo get_the_password_form();
			return;
		}

		if ( $position > 0 && $position <= 5 )
			echo $content;

		do_action( 'sportspress_single_' . $template . '_content' );

		if ( $position > 5 && $position <= 10 )
			echo $content;

		do_action( 'sportspress_after_single_' . $template );

		if ( $position > 10 )
			echo $content;

		return ob_get_clean();
	}

	public function sponsor_content( $content ) {
		if ( is_singular( 'sp_sponsor' ) )
			$content = self::add_content( $content, 'sponsor', apply_filters( 'sportspress_sponsor_content_priority', 10 ) );
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
		$find = array( 'sportspress.php' );
		$file = '';

		if ( is_singular( 'sp_sponsor' ) ):
			$file 	= 'single-sponsor.php';
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

new SP_Sponsor_Template_Loader();
			