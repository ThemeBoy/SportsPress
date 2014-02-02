<?php
class SP_Widget_Countdown_Timer extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_countdown_timer widget_sp_countdown_timer', 'description' => __( 'SportsPress widget.', 'sportspress' ) );
		parent::__construct('sp_countdown_timer', __( 'Countdown Timer', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __( 'Countdown Timer', 'sportspress' ) : $instance['title'], $instance, $this->id_base);
		echo $before_widget;
		$id = empty($instance['id']) ? null : $instance['id'];
		if ( $title )
			echo $before_title . $title . $after_title;
		if ( $id )
			$post = get_post( $id );
		if ( isset( $post ) ):
			echo '<div id="sp_countdown_timer_wrap">';
			echo '<h3 class="event-name"><a href="' . get_permalink( $post->ID ) . '">' . $post->post_title . '</a></h3>';

			$leagues = get_the_terms( $post->ID, 'sp_league' );
			if ( $leagues ):
				foreach( $leagues as $league ):
					$term = get_term( $league->term_id, 'sp_league' );
					echo '<h5 class="event-league">' . $term->name . '</h5>';
				endforeach;
			endif;

			$venues = get_the_terms( $post->ID, 'sp_venue' );
			if ( $venues ):
				foreach( $venues as $venue ):
					$term = get_term( $venue->term_id, 'sp_venue' );
					echo '<h5 class="event-venue"><div class="dashicons dashicons-location"></div> ' . $term->name . '</h5>';
				endforeach;
			endif;

			$now = new DateTime( date("Y-m-d H:i:s") );
			$date = new DateTime( $post->post_date );
			$interval = $date->diff( $now );

			echo '<h3 class="countdown-timer sp-countdown-timer clearfix"><time datetime="' . $post->post_date . '">' .
				'<span class="d">' . $interval->d . ' <small>' . __( 'Days', 'sportspress' ) . '</small></span> ' .
				'<span class="h">' . $interval->h . ' <small>' . __( 'Hours', 'sportspress' ) . '</small></span> ' .
				'<span class="m">' . $interval->m . ' <small>' . __( 'Mins', 'sportspress' ) . '</small></span> ' .
				'<span class="s">' . $interval->s . ' <small>' . __( 'Secs', 'sportspress' ) . '</small></span>' .
			'</time></h3>';

			echo '</div>';
		endif;
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => '' ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('id'); ?>"><?php _e( 'Event:', 'sportspress' ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_event',
			'name' => $this->get_field_name('id'),
			'id' => $this->get_field_id('id'),
			'selected' => $id,
			'show_option_none' => '(' . __( 'Next Event', 'premier' ) . ')',
			'values' => 'ID',
			'class' => 'widefat',
			'show_dates' => true,
			'post_status' => 'future',
		);
		if ( ! sportspress_dropdown_pages( $args ) ):
			sportspress_post_adder( 'sp_event' );
		endif;
		?>
		</p>
<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "SP_Widget_Countdown_Timer" );' ) );
