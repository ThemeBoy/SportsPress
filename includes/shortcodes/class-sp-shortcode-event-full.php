<?php
/**
 * Event Full info Shortcode
 *
 * @author 		ThemeBoy
 * @category 	Shortcodes
 * @package 	SportsPress/Shortcodes/Event_Full
 * @version     2.6.9
 */
class SP_Shortcode_Event_Full {

	/**
	 * Output the event full shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];
		
		$type = 'event';
		
		$content = apply_filters( 'the_content', get_post_field( 'post_content', $atts['id'] ) );
		
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
				} elseif ( 'excerpt' === $key ) {
					sp_get_template( 'post-excerpt.php', $atts );
				} else {
					//call_user_func( $template['action'] );
					sp_get_template( 'event-' . $key . '.php', $atts );
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
					// Template content hook
					do_action( 'sportspress_single_' . $type . '_content' );
				} elseif ( 'excerpt' === $key ) {
					sp_get_template( 'post-excerpt.php', $atts );
				} else {
					//call_user_func( $template['action'] );
					sp_get_template( 'event-' . $key . '.php', $atts );
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
		
		echo $ob;

	}
}
