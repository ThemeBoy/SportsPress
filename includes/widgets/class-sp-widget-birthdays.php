<?php
class SP_Widget_Birthdays extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sportspress widget_birthdays widget_sp_birthdays', 'description' => __( 'Display players and staff on their birthday.', 'sportspress' ) );
		parent::__construct('sportspress-birthdays', __( 'Birthdays', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$date = empty($instance['date']) ? 'day' : strip_tags($instance['date']);

		do_action( 'sportspress_before_widget', $args, $instance, 'birthdays' );
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'birthdays' );

		sp_get_template( 'birthdays.php', array( 'date' => $date ) );

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'birthdays' );

		echo $after_widget;
		do_action( 'sportspress_after_widget', $args, $instance, 'birthdays' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['date'] = strip_tags($new_instance['date']);

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'birthdays' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'date' => 'day' ) );
		$title = strip_tags($instance['title']);
		$date = strip_tags($instance['date']);
		$options = array(
			'day' => __( 'Today', 'sportspress' ),
			'week' => __( 'This week', 'sportspress' ),
			'month' => __( 'This month', 'sportspress' ),
		);

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'birthdays' );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p>
			<label for="<?php echo $this->get_field_id('date'); ?>"><?php _e( 'Birthday:', 'sportspress' ); ?></label>
			<select name="<?php echo $this->get_field_name('date'); ?>" id="<?php echo $this->get_field_id('date'); ?>" class="postform widefat">
				<?php foreach ( $options as $value => $label ) { ?>
					<option value="<?php echo $value; ?>" <?php selected( $value, $date ); ?>><?php echo $label; ?></option>
				<?php } ?>
			</select>
		</p>
		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'birthdays' );
	}
}

register_widget( 'SP_Widget_Birthdays' );
