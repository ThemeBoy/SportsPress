<?php
class SportsPress_Widget_Future_Events extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_recent_entries widget_sp_future_events', 'description' => __( 'A list of upcoming events.', 'sportspress' ) );
		parent::__construct('sp_future_events', __( 'SportsPress Future Events', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __( 'Future Events' ) : $instance['title'], $instance, $this->id_base);
		$league = empty($instance['league']) ? null : $instance['league'];
		$season = empty($instance['season']) ? null : $instance['season'];
		$venue = empty($instance['venue']) ? null : $instance['venue'];
		$team = empty($instance['team']) ? null : $instance['team'];
		$number = empty($instance['number']) ? get_option( 'posts_per_page' ) : $instance['number'];
		$args = array(
			'status' => 'future',
			'league' => $league,
			'season' => $season,
			'venue' => $venue,
			'team' => $team,
			'number' => $number,
		);
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div id="sp_future_events_wrap">';
		echo sportspress_events( $args );
		echo '</div>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['league'] = intval($new_instance['league']);
		$instance['season'] = intval($new_instance['season']);
		$instance['venue'] = intval($new_instance['venue']);
		$instance['team'] = intval($new_instance['team']);
		$instance['number'] = intval($new_instance['number']);

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'league' => '', 'season' => '', 'venue' => '', 'team' => '', 'number' => 3 ) );
		$title = strip_tags($instance['title']);
		$league = intval($instance['league']);
		$season = intval($instance['season']);
		$venue = intval($instance['venue']);
		$team = intval($instance['team']);
		$number = intval($instance['number']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('league'); ?>"><?php _e( 'League:', 'sportspress' ); ?></label>
		<?php
		$args = array(
			'taxonomy' => 'sp_league',
			'name' => $this->get_field_name('league'),
			'id' => $this->get_field_id('league'),
			'selected' => $league,
			'show_option_all' => __( 'All Leagues', 'sportspress' ),
			'hide_empty' => 0, 
			'values' => 'term_id',
			'class' => 'widefat',
		);
		wp_dropdown_categories( $args );
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('season'); ?>"><?php _e( 'Season:', 'sportspress' ); ?></label>
		<?php
		$args = array(
			'taxonomy' => 'sp_season',
			'name' => $this->get_field_name('season'),
			'id' => $this->get_field_id('season'),
			'selected' => $season,
			'show_option_all' => __( 'All Seasons', 'sportspress' ),
			'hide_empty' => 0, 
			'values' => 'term_id',
			'class' => 'widefat',
		);
		wp_dropdown_categories( $args );
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('venue'); ?>"><?php _e( 'Venue:', 'sportspress' ); ?></label>
		<?php
		$args = array(
			'taxonomy' => 'sp_venue',
			'name' => $this->get_field_name('venue'),
			'id' => $this->get_field_id('venue'),
			'selected' => $venue,
			'show_option_all' => __( 'All Venues', 'sportspress' ),
			'hide_empty' => 0, 
			'values' => 'term_id',
			'class' => 'widefat',
		);
		wp_dropdown_categories( $args );
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('team'); ?>"><?php _e( 'Team:', 'sportspress' ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_team',
			'name' => $this->get_field_name('team'),
			'id' => $this->get_field_id('team'),
			'selected' => $team,
			'show_option_all' => __( 'All Teams', 'sportspress' ),
			'values' => 'ID',
			'class' => 'widefat',
		);
		if ( ! sportspress_dropdown_pages( $args ) ):
			sportspress_post_adder( 'sp_table', __( 'Add New League Table', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of events to show:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3"></p>
<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "SportsPress_Widget_Future_Events" );' ) );