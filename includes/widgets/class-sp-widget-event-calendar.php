<?php
class SP_Widget_Event_Calendar extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sportspress widget_calendar widget_sp_event_calendar', 'description' => __( 'A calendar of events.', 'sportspress' ) );
		parent::__construct('sportspress-event-calendar', __( 'Event Calendar', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);

		$id = empty($instance['id']) ? null : $instance['id'];
		if ( $id && 'yes' == get_option( 'sportspress_widget_unique', 'no' ) && get_the_ID() === $id ) {
			$format = get_post_meta( $id, 'sp_format', true );
			if ( 'calendar' == $format ) return;
		}

		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$status = empty($instance['status']) ? 'default' : $instance['status'];
		$date = empty($instance['date']) ? 'default' : $instance['date'];
		$date_from = empty($instance['date_from']) ? 'default' : $instance['date_from'];
		$date_to = empty($instance['date_to']) ? 'default' : $instance['date_to'];
		$day = empty($instance['day']) ? 'default' : $instance['day'];
		$show_all_events_link = empty($instance['show_all_events_link']) ? false : $instance['show_all_events_link'];

		do_action( 'sportspress_before_widget', $args, $instance, 'event-calendar' );
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'event-calendar' );

		sp_get_template( 'event-calendar.php', array( 'id' => $id, 'status' => $status, 'date' => $date, 'date_from' => $date_from, 'date_to' => $date_to, 'caption_tag' => 'caption', 'day' => $day, 'show_all_events_link' => $show_all_events_link )  );

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'event-calendar' );

		echo $after_widget;
		do_action( 'sportspress_after_widget', $args, $instance, 'event-calendar' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);
		$instance['status'] = $new_instance['status'];
		$instance['date'] = $new_instance['date'];
		$instance['date_from'] = $new_instance['date_from'];
		$instance['date_to'] = $new_instance['date_to'];
		$instance['day'] = $new_instance['day'];
		$instance['show_all_events_link'] = $new_instance['show_all_events_link'];

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'event-calendar' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => null, 'status' => 'default', 'date' => 'default', 'date_from' => date_i18n( 'Y-m-d' ), 'date_to' => date_i18n( 'Y-m-d' ), 'day' => '', 'show_all_events_link' => false ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
		$status = $instance['status'];
		$date = $instance['date'];
		$date_from = $instance['date_from'];
		$date_to = $instance['date_to'];
		$day = $instance['day'];
		$show_all_events_link = $instance['show_all_events_link'];

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'event-calendar' );
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

		<div class="sp-date-selector sp-event-calendar-show-all-toggle<?php if ( ! $id ): ?> hidden<?php endif; ?>">
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

		<p><label for="<?php echo $this->get_field_id('day'); ?>"><?php _e( 'Match Day:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('day'); ?>" name="<?php echo $this->get_field_name('day'); ?>" type="text" placeholder="<?php _e( 'All', 'sportspress' ); ?>" value="<?php echo esc_attr($day); ?>" size="10"></p>

		<p class="sp-event-calendar-show-all-toggle<?php if ( ! $id ): ?> hidden<?php endif; ?>"><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_all_events_link'); ?>" name="<?php echo $this->get_field_name('show_all_events_link'); ?>" value="1" <?php checked( $show_all_events_link, 1 ); ?>>
		<label for="<?php echo $this->get_field_id('show_all_events_link'); ?>"><?php _e( 'Display link to view all events', 'sportspress' ); ?></label></p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'event-calendar' );
	}
}

register_widget( 'SP_Widget_Event_Calendar' );
