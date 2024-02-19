<?php
class SP_Widget_Event_Calendar extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_sportspress widget_calendar widget_sp_event_calendar',
			'description' => esc_attr__( 'A calendar of events.', 'sportspress' ),
		);
		parent::__construct( 'sportspress-event-calendar', esc_attr__( 'Event Calendar', 'sportspress' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$id = empty( $instance['id'] ) ? null : $instance['id'];
		if ( $id && 'yes' == get_option( 'sportspress_widget_unique', 'no' ) && get_the_ID() === $id ) {
			$format = get_post_meta( $id, 'sp_format', true );
			if ( 'calendar' == $format ) {
				return;
			}
		}

		$title                = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$status               = empty( $instance['status'] ) ? 'default' : $instance['status'];
		$date                 = empty( $instance['date'] ) ? 'default' : $instance['date'];
		$date_from            = empty( $instance['date_from'] ) ? 'default' : $instance['date_from'];
		$date_to              = empty( $instance['date_to'] ) ? 'default' : $instance['date_to'];
		$date_past            = empty( $instance['date_past'] ) ? 'default' : $instance['date_past'];
		$date_future          = empty( $instance['date_future'] ) ? 'default' : $instance['date_future'];
		$date_relative        = empty( $instance['date_relative'] ) ? 'default' : $instance['date_relative'];
		$day                  = empty( $instance['day'] ) ? 'default' : $instance['day'];
		$show_all_events_link = empty( $instance['show_all_events_link'] ) ? false : $instance['show_all_events_link'];

		do_action( 'sportspress_before_widget', $args, $instance, 'event-calendar' );
		echo wp_kses_post( $before_widget );

		if ( $title ) {
			echo wp_kses_post( $before_title . $title . $after_title );
		}

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'event-calendar' );

		sp_get_template(
			'event-calendar.php',
			array(
				'id'                   => $id,
				'status'               => $status,
				'date'                 => $date,
				'date_from'            => $date_from,
				'date_to'              => $date_to,
				'date_past'            => $date_past,
				'date_future'          => $date_future,
				'date_relative'        => $date_relative,
				'caption_tag'          => 'caption',
				'day'                  => $day,
				'show_all_events_link' => $show_all_events_link,
			)
		);

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'event-calendar' );

		echo wp_kses_post( $after_widget );
		do_action( 'sportspress_after_widget', $args, $instance, 'event-calendar' );
	}

	function update( $new_instance, $old_instance ) {
		$instance                         = $old_instance;
		$instance['title']                = strip_tags( $new_instance['title'] );
		$instance['id']                   = intval( $new_instance['id'] );
		$instance['status']               = $new_instance['status'];
		$instance['date']                 = $new_instance['date'];
		$instance['date_from']            = $new_instance['date_from'];
		$instance['date_to']              = $new_instance['date_to'];
		$instance['date_past']            = $new_instance['date_past'];
		$instance['date_future']          = $new_instance['date_future'];
		$instance['date_relative']   	  = isset( $new_instance['date_relative'] ) ? $new_instance['date_relative'] : false;
		$instance['day']                  = $new_instance['day'];
		$instance['show_all_events_link'] = isset( $new_instance['show_all_events_link'] ) ? $new_instance['show_all_events_link'] : false;

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'event-calendar' );

		return $instance;
	}

	function form( $instance ) {
		$instance             = wp_parse_args(
			(array) $instance,
			array(
				'title'                => '',
				'id'                   => null,
				'status'               => 'default',
				'date'                 => 'default',
				'date_from'            => date_i18n( 'Y-m-d' ),
				'date_to'              => date_i18n( 'Y-m-d' ),
				'date_past'            => 7,
				'date_future'          => 7,
				'date_relative'        => false,
				'day'                  => '',
				'show_all_events_link' => false,
			)
		);
		$title                = strip_tags( $instance['title'] );
		$id                   = intval( $instance['id'] );
		$status               = $instance['status'];
		$date                 = $instance['date'];
		$date_from            = $instance['date_from'];
		$date_to              = $instance['date_to'];
		$date_past            = $instance['date_past'];
		$date_future          = $instance['date_future'];
		$date_relative        = $instance['date_relative'];
		$day                  = $instance['day'];
		$show_all_events_link = $instance['show_all_events_link'];

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'event-calendar' );
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php printf( esc_attr__( 'Select %s:', 'sportspress' ), esc_attr__( 'Calendar', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type'       => 'sp_calendar',
			'show_option_all' => esc_attr__( 'All', 'sportspress' ),
			'name'            => $this->get_field_name( 'id' ),
			'id'              => $this->get_field_id( 'id' ),
			'selected'        => $id,
			'values'          => 'ID',
			'class'           => 'sp-event-calendar-select widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ) :
			sp_post_adder( 'sp_calendar', esc_attr__( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'status' ) ); ?>"><?php esc_attr_e( 'Status:', 'sportspress' ); ?></label>
			<?php
			$args = array(
				'show_option_default' => esc_attr__( 'Default', 'sportspress' ),
				'name'                => $this->get_field_name( 'status' ),
				'id'                  => $this->get_field_id( 'status' ),
				'selected'            => $status,
				'class'               => 'sp-event-status-select widefat',
			);
			sp_dropdown_statuses( $args );
			?>
		</p>

		<div class="sp-date-selector">
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php esc_attr_e( 'Date:', 'sportspress' ); ?></label>
				<?php
				$args = array(
					'show_option_default' => esc_attr__( 'Default', 'sportspress' ),
					'name'                => $this->get_field_name( 'date' ),
					'id'                  => $this->get_field_id( 'date' ),
					'selected'            => $date,
					'class'               => 'sp-event-date-select widefat',
				);
				sp_dropdown_dates( $args );
				?>
			</p>
			<div class="sp-date-range
			<?php
			if ( 'range' !== $date ) :
				?>
				 hidden<?php endif; ?>">
				<p class="sp-date-range-absolute
				<?php
				if ( $date_relative ) :
					?>
					 hidden<?php endif; ?>">
					<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'date_from' ) ); ?>" value="<?php echo esc_attr( $date_from ); ?>" class="sp-datepicker-from" placeholder="yyyy-mm-dd" size="10">
					:
					<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'date_to' ) ); ?>" value="<?php echo esc_attr( $date_to ); ?>" class="sp-datepicker-to" placeholder="yyyy-mm-dd" size="10">
				</p>

				<p class="sp-date-range-relative
				<?php
				if ( ! $date_relative ) :
					?>
					 hidden<?php endif; ?>">
					<?php esc_attr_e( 'Past', 'sportspress' ); ?>
					<input type="number" min="0" step="1" class="tiny-text" name="<?php echo esc_attr( $this->get_field_name( 'date_past' ) ); ?>" value="<?php echo esc_attr( $date_past ); ?>">
					&rarr;
					<?php esc_attr_e( 'Next', 'sportspress' ); ?>
					<input type="number" min="0" step="1" class="tiny-text" name="<?php echo esc_attr( $this->get_field_name( 'date_future' ) ); ?>" value="<?php echo esc_attr( $date_future ); ?>">
					<?php esc_attr_e( 'days', 'sportspress' ); ?>
				</p>

				<p class="sp-date-relative">
					<label>
						<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'date_relative' ) ); ?>" value="1" id="<?php echo esc_attr( $this->get_field_id( 'date_relative' ) ); ?>" <?php checked( $date_relative ); ?>>
						<?php esc_attr_e( 'Relative', 'sportspress' ); ?>
					</label>
				</p>
			</div>
		</div>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'day' ) ); ?>"><?php esc_attr_e( 'Match Day:', 'sportspress' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'day' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'day' ) ); ?>" type="text" placeholder="<?php esc_attr_e( 'All', 'sportspress' ); ?>" value="<?php echo esc_attr( $day ); ?>" size="10"></p>

		<p class="sp-event-calendar-show-all-toggle
		<?php
		if ( ! $id ) :
			?>
			 hidden<?php endif; ?>"><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_all_events_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_all_events_link' ) ); ?>" value="1" <?php checked( $show_all_events_link, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_all_events_link' ) ); ?>"><?php esc_attr_e( 'Display link to view all events', 'sportspress' ); ?></label></p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'event-calendar' );
	}
}

register_widget( 'SP_Widget_Event_Calendar' );
