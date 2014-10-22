<?php
/**
 * Template Loader
 *
 * @class 		SP_Template_Loader
 * @version		1.1.4
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Template_Loader {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'template_include', array( $this, 'template_loader' ) );
		add_filter( 'the_content', array( $this, 'event_content' ) );
		add_filter( 'the_content', array( $this, 'calendar_content' ) );
		add_filter( 'the_content', array( $this, 'team_content' ) );
		add_filter( 'the_content', array( $this, 'table_content' ) );
		add_filter( 'the_content', array( $this, 'player_content' ) );
		add_filter( 'the_content', array( $this, 'list_content' ) );
		add_filter( 'the_content', array( $this, 'staff_content' ) );
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

	public function event_content( $content ) {
		if ( is_singular( 'sp_event' ) )
			$content = self::add_content( $content, 'event', apply_filters( 'sportspress_event_content_priority', 10 ) );
		return $content;
	}

	public function calendar_content( $content ) {
		if ( is_singular( 'sp_calendar' ) )
			$content = self::add_content( $content, 'calendar', apply_filters( 'sportspress_calendar_content_priority', 10 ) );
		return $content;
	}

	public function team_content( $content ) {
		if ( is_singular( 'sp_team' ) )
			$content = self::add_content( $content, 'team', apply_filters( 'sportspress_team_content_priority', 10 ) );
		return $content;
	}

	public function table_content( $content ) {
		if ( is_singular( 'sp_table' ) )
			$content = self::add_content( $content, 'table', apply_filters( 'sportspress_table_content_priority', 10 ) );
		return $content;
	}

	public function player_content( $content ) {
		if ( is_singular( 'sp_player' ) )
			$content = self::add_content( $content, 'player', apply_filters( 'sportspress_player_content_priority', 10 ) );
		return $content;
	}

	public function list_content( $content ) {
		if ( is_singular( 'sp_list' ) )
			$content = self::add_content( $content, 'list', apply_filters( 'sportspress_list_content_priority', 10 ) );
		return $content;
	}

	public function staff_content( $content ) {
		if ( is_singular( 'sp_staff' ) )
			$content = self::add_content( $content, 'staff', apply_filters( 'sportspress_staff_content_priority', 10 ) );
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

		if ( is_single() ):

			switch( get_post_type() ):
				case 'sp_event':
					$file 	= 'single-event.php';
					$find[] = $file;
					$find[] = SP_TEMPLATE_PATH . $file;
					break;
				case 'sp_calendar':
					$file 	= 'single-calendar.php';
					$find[] = $file;
					$find[] = SP_TEMPLATE_PATH . $file;
					break;
				case 'sp_team':
					$file 	= 'single-team.php';
					$find[] = $file;
					$find[] = SP_TEMPLATE_PATH . $file;
					break;
				case 'sp_table':
					$file 	= 'single-table.php';
					$find[] = $file;
					$find[] = SP_TEMPLATE_PATH . $file;
					break;
				case 'sp_player':
					$file 	= 'single-player.php';
					$find[] = $file;
					$find[] = SP_TEMPLATE_PATH . $file;
					break;
				case 'sp_list':
					$file 	= 'single-list.php';
					$find[] = $file;
					$find[] = SP_TEMPLATE_PATH . $file;
					break;
				case 'sp_staff':
					$file 	= 'single-staff.php';
					$find[] = $file;
					$find[] = SP_TEMPLATE_PATH . $file;
					break;
			endswitch;

		elseif ( is_tax() ):

			$term = get_queried_object();

			switch( $term->taxonomy ):
				case 'sp_venue':
				$file = 'taxonomy-venue.php';
				$find[] 	= 'taxonomy-venue-' . $term->slug . '.php';
				$find[] 	= SP_TEMPLATE_PATH . 'taxonomy-venue-' . $term->slug . '.php';
				$find[] 	= $file;
				$find[] 	= SP_TEMPLATE_PATH . $file;
			endswitch;

		endif;

		if ( $file ):
			$located       = locate_template( $find );
			if ( $located ):
				$template = $located;
			endif;
		endif;

		return $template;
	}
}

new SP_Template_Loader();
			