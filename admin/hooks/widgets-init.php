<?php
/**
 * Register all of the default WordPress widgets on startup.
 *
 * Calls 'widgets_init' action after all of the WordPress widgets have been
 * registered.
 *
 * @since 2.2.0
 */
function sportspress_widgets_init() {

	register_widget('SportsPress_Widget_Calendar');
}

add_action('widgets_init', 'sportspress_widgets_init', 1);
