<?php
class SP_Widget_Event_List extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sportspress widget_sp_event_list', 'description' => __( 'A list of events.', 'sportspress' ) );
		parent::__construct('sportspress-event-list', __( 'Event List', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$id = empty($instance['id']) ? null : $instance['id'];
		$caption = empty($instance['caption']) ? null : $instance['caption'];
		$status = empty($instance['status']) ? 'default' : $instance['status'];
		$date = empty($instance['date']) ? 'default' : $instance['date'];
		$date_from = empty($instance['date_from']) ? 'default' : $instance['date_from'];
		$date_to = empty($instance['date_to']) ? 'default' : $instance['date_to'];
		$number = empty($instance['number']) ? null : $instance['number'];
		$columns = empty($instance['columns']) ? null : $instance['columns'];
		$order = empty($instance['order']) ? 'default' : $instance['order'];
		$show_all_events_link = empty($instance['show_all_events_link']) ? false : $instance['show_all_events_link'];

		do_action( 'sportspress_before_widget', $args, $instance, 'event-list' );
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'event-list' );

		sp_get_template( 'event-list.php', array( 'id' => $id, 'title' => $caption, 'status' => $status, 'date' => $date, 'date_from' => $date_from, 'date_to' => $date_to, 'number' => $number, 'columns' => $columns, 'order' => $order, 'show_all_events_link' => $show_all_events_link ) );

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'event-list' );

		echo $after_widget;
		do_action( 'sportspress_after_widget', $args, $instance, 'event-list' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);
		$instance['caption'] = strip_tags($new_instance['caption']);
		$instance['status'] = $new_instance['status'];
		$instance['date'] = $new_instance['date'];
		$instance['date_from'] = $new_instance['date_from'];
		$instance['date_to'] = $new_instance['date_to'];
		$instance['number'] = intval($new_instance['number']);
		$instance['columns'] = (array)$new_instance['columns'];
		$instance['order'] = strip_tags($new_instance['order']);
		$instance['show_all_events_link'] = $new_instance['show_all_events_link'];

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'event-list' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => null, 'caption' => '', 'status' => 'default', 'date' => 'default', 'date_from' => date_i18n( 'Y-m-d' ), 'date_to' => date_i18n( 'Y-m-d' ), 'number' => 5, 'columns' => null, 'order' => 'default', 'show_all_events_link' => true ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
		$caption = strip_tags($instance['caption']);
		$status = $instance['status'];
		$date = $instance['date'];
		$date_from = $instance['date_from'];
		$date_to = $instance['date_to'];
		$number = intval($instance['number']);
		$columns = $instance['columns'];
		$order = strip_tags($instance['order']);
		$show_all_events_link = $instance['show_all_events_link'];

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'event-list' );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('caption'); ?>"><?php _e( 'Heading:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="text" value="<?php echo esc_attr($caption); ?>" /></p>

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

		<div class="sp-date-selector">
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
			<p class="sp-date-range<?php if ( 'range' !== $date ): ?> hidden<?php endif; ?>">
				<input type="text" name="<?php echo $this->get_field_name( 'date_from' ); ?>" value="<?php echo $date_from; ?>" placeholder="yyyy-mm-dd" size="10">
				:
				<input type="text" name="<?php echo $this->get_field_name( 'date_to' ); ?>" value="<?php echo $date_to; ?>" placeholder="yyyy-mm-dd" size="10">
			</p>
		</div>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of events to show:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3"></p>

		<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e( 'Sort Order:', 'sportspress' ); ?></label>
		<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="sp-select-order widefat">
			<option value="default" <?php selected( 'default', $order ); ?>><?php _e( 'Default', 'sportspress' ); ?></option>
			<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
			<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
		</select></p>

		<p class="sp-prefs">
			<?php _e( 'Columns:', 'sportspress' ); ?><br>
			<?php 
			$the_columns = array(
				'event' => __( 'Event', 'sportspress' ),
				'time' => __( 'Time/Results', 'sportspress' ),
				'venue' => __( 'Venue', 'sportspress' ),
				'article' => __( 'Article', 'sportspress' ),
			);
			$field_name = $this->get_field_name('columns') . '[]';
			$field_id = $this->get_field_id('columns');
			?>
			<?php foreach ( $the_columns as $key => $label ): ?>
				<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . $key; ?>" value="<?php echo $key; ?>" <?php if ( $columns === null || in_array( $key, $columns ) ): ?>checked="checked"<?php endif; ?>><?php echo $label; ?></label>
			<?php endforeach; ?>
		</p>

		<p class="sp-event-calendar-show-all-toggle<?php if ( ! $id ): ?> hidden<?php endif; ?>"><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_all_events_link'); ?>" name="<?php echo $this->get_field_name('show_all_events_link'); ?>" value="1" <?php checked( $show_all_events_link, 1 ); ?>>
		<label for="<?php echo $this->get_field_id('show_all_events_link'); ?>"><?php _e( 'Display link to view all events', 'sportspress' ); ?></label></p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'event-list' );
	}
}

register_widget( 'SP_Widget_Event_List' );
