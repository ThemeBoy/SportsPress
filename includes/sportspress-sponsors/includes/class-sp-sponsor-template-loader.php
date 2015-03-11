<?php
/**
 * Sponsor Template Loader
 *
 * @class 		SP_Sponsor_Template_Loader
 * @version		1.7
 * @package		SportsPress Sponsors
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Sponsor_Template_Loader {

	/**
	 * Constructor
	 */
	public function __construct() {
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
}

new SP_Sponsor_Template_Loader();
			