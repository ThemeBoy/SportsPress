<?php
class SP_Widget_Event_Blocks extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sp_event_blocks', 'description' => __( 'A list of events.', 'sportspress' ) );
		parent::__construct('sp_event_blocks', __( 'Event Blocks', 'sportspress' ) . ' (' . __( 'SportsPress', 'sportspress' ) . ')', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$id = empty($instance['id']) ? null : $instance['id'];
		$status = empty($instance['status']) ? 'default' : $instance['status'];
		$date = empty($instance['date']) ? 'default' : $instance['date'];
		$number = empty($instance['number']) ? null : $instance['number'];
		$order = empty($instance['order']) ? 'default' : $instance['order'];
		$show_all_events_link = empty($instance['show_all_events_link']) ? false : $instance['show_all_events_link'];
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		sp_get_template( 'event-blocks.php', array( 'id' => $id, 'status' => $status, 'date' => $date, 'number' => $number, 'order' => $order, 'show_all_events_link' => $show_all_events_link ) );
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);
		$instance['status'] = $new_instance['status'];
		$instance['date'] = $new_instance['date'];
		$instance['number'] = intval($new_instance['number']);
		$instance['order'] = strip_tags($new_instance['order']);
		$instance['show_all_events_link'] = $new_instance['show_all_events_link'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => null, 'status' => 'default', 'date' => 'default', 'number' => 5, 'order' => 'default', 'show_all_events_link' => true ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
		$status = $instance['status'];
		$date = $instance['date'];
		$number = intval($instance['number']);
		$order = strip_tags($instance['order']);
		$show_all_events_link = $instance['show_all_events_link'];
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('id'); ?>"><?php printf( __( 'Select %s:', 'sportspress' ), __( 'Calendar', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_calendar',
			'show_option_all' => __( 'All', 'sportspress' ),
			'name' => $this->get_field_name('id'),
			'id' => $this->get_field_id('id'),
			'selected' => $id,
			'values' => 'ID',
			'class' => 'sp-event-calendar-select widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ):
			sp_post_adder( 'sp_calendar', __( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('status'); ?>"><?php _e( 'Status:', 'sportspress' ); ?></label>
			<?php
			$args = array(
				'show_option_default' => __( 'Default', 'sportspress' ),
				'name' => $this->get_field_name('status'),
				'id' => $this->get_field_id('status'),
				'selected' => $status,
				'class' => 'sp-event-status-select widefat',
			);
			sp_dropdown_statuses( $args );
			?>
		</p>

		<p><label for="<?php echo $this->get_field_id('date'); ?>"><?php _e( 'Date:', 'sportspress' ); ?></label>
			<?php
			$args = array(
				'show_option_default' => __( 'Default', 'sportspress' ),
				'name' => $this->get_field_name('date'),
				'id' => $this->get_field_id('date'),
				'selected' => $date,
				'class' => 'sp-event-date-select widefat',
			);
			sp_dropdown_dates( $args );
			?>
		</p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of events to show:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3"></p>

		<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e( 'Sort Order:', 'sportspress' ); ?></label>
		<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="sp-select-order widefat">
			<option value="default" <?php selected( 'default', $order ); ?>><?php _e( 'Default', 'sportspress' ); ?></option>
			<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
			<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
		</select></p>

		<p class="sp-event-calendar-show-all-toggle<?php if ( ! $id ): ?> hidden<?php endif; ?>"><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_all_events_link'); ?>" name="<?php echo $this->get_field_name('show_all_events_link'); ?>" value="1" <?php checked( $show_all_events_link, 1 ); ?>>
		<label for="<?php echo $this->get_field_id('show_all_events_link'); ?>"><?php _e( 'Display link to view all events', 'sportspress' ); ?></label></p>
<?php
	}
}

register_widget( 'SP_Widget_Event_Blocks' );
