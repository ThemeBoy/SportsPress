<?php
/**
 * Template Loader
 *
 * @class 		SP_Template_Loader
 * @version		2.3.1
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

	public function add_content( $content, $type, $position = 10, $caption = null ) {
		if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
		if ( ! in_the_loop() ) return; // Return if not in main loop

		// Return password form if required
		if ( post_password_required() ) {
			echo get_the_password_form();
			return;
		}

		// Prepend caption to content if given
		if ( $content ) {
			if ( $caption ) {
				$content = '<h3 class="sp-post-caption">' . $caption . '</h3>' . $content;
			}

			$content = '<div class="sp-post-content">' . $content . '</div>';
		}
		
		// Get layout setting
		$layout = (array) get_option( 'sportspress_' . $type . '_template_order', array() );
		
		// Get templates
		$templates = SP()->templates->$type;
		
		// Combine layout setting with available templates
		$templates = array_merge( array_flip( $layout ), $templates );
		
		$templates = apply_filters( 'sportspress_' . $type . '_templates', $templates );

		// Split templates into sections and tabs
		$slice = array_search( 'tabs', array_keys( $templates ) );
		if ( $slice ) {
			$section_templates = array_slice( $templates, 0, $slice );
			$tab_templates = array_slice( $templates, $slice );
		} else {
			$section_templates = $templates;
			$tab_templates = array();
		}

		ob_start();

		// Before template hook
		do_action( 'sportspress_before_single_' . $type );
		
		// Loop through sections
		if ( ! empty( $section_templates ) ) {
			foreach ( $section_templates as $key => $template ) {
				// Ignore templates that are unavailable or that have been turned off
				if ( ! is_array( $template ) ) continue;
				if ( ! isset( $template['option'] ) ) continue;
				if ( 'yes' !== get_option( $template['option'], sp_array_value( $template, 'default', 'yes' ) ) ) continue;
				
				// Render the template
				echo '<div class="sp-section-content sp-section-content-' . $key . '">';
				if ( 'content' === $key ) {
					echo $content;
					// Template content hook
					do_action( 'sportspress_single_' . $type . '_content' );
				} else {
					call_user_func( $template['action'] );
				}
				echo '</div>';
			}
		}

		// After template hook
		do_action( 'sportspress_after_single_' . $type );
		
		$ob = ob_get_clean();
		
		$tabs = '';
		
		if ( ! empty( $tab_templates ) ) {
			$i = 0;
			$tab_content = '';

			foreach ( $tab_templates as $key => $template ) {
				// Ignore templates that are unavailable or that have been turned off
				if ( ! is_array( $template ) ) continue;
				if ( ! isset( $template['option'] ) ) continue;
				if ( 'yes' !== get_option( $template['option'], sp_array_value( $template, 'default', 'yes' ) ) ) continue;
				
				// Put tab content into buffer
				ob_start();
				if ( 'content' === $key ) {
					echo $content;
				} else {
					call_user_func( $template['action'] );
				}
				$buffer = ob_get_clean();

				// Trim whitespace from buffer
				$buffer = trim( $buffer );
				
				// Continue if tab content is empty
				if ( empty( $buffer ) ) continue;
				
				// Get template label
				$label = sp_array_value( $template, 'label', $template['title'] );
				
				// Add to tabs
				$tabs .= '<li class="sp-tab-menu-item' . ( 0 === $i ? ' sp-tab-menu-item-active' : '' ) . '"><a href="#sp-tab-content-' . $key . '" data-sp-tab="' . $key . '">' . apply_filters( 'gettext', $label, $label, 'sportspress' ) . '</a></li>';
				
				// Render the template
				$tab_content .= '<div class="sp-tab-content sp-tab-content-' . $key . '" id="sp-tab-content-' . $key . '"' . ( 0 === $i ? ' style="display: block;"' : '' ) . '>' . $buffer . '</div>';

				$i++;
			}
			
			$ob .= '<div class="sp-tab-group">';
		
			if ( ! empty( $tabs ) ) {
				$ob .= '<ul class="sp-tab-menu">' . $tabs . '</ul>';
			}

			$ob .= $tab_content;
			
			$ob .= '</div>';
		}
		
		return $ob;
	}

	public function event_content( $content ) {
		if ( is_singular( 'sp_event' ) ) {
			$status = sp_get_status( get_the_ID() );
			if ( 'results' == $status ) {
				$caption = __( 'Recap', 'sportspress' );
			} else {
				$caption = __( 'Preview', 'sportspress' );
			}
			$content = self::add_content( $content, 'event', apply_filters( 'sportspress_event_content_priority', 10 ), $caption );
		}
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
	 * For beginners, it also looks for a sportspress.php template last. If the user adds
	 * this to the theme (containing a sportspress() inside) this will be used as a
	 * fallback for all sportspress templates.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public function template_loader( $template ) {
		$find = array();
		$file = '';

		if ( is_single() ):

			$post_type = get_post_type();
		
			if ( is_sp_post_type( $post_type ) ):
				$file = 'single-' . str_replace( 'sp_', '', $post_type ) . '.php';
				$find[] = $file;
				$find[] = SP_TEMPLATE_PATH . $file;
			endif;

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

		$find[] = 'sportspress.php';

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
			