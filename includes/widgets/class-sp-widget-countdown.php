<?php
class SP_Widget_Countdown extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_sportspress widget_countdown widget_sp_countdown',
			'description' => esc_attr__( 'A clock that counts down to an upcoming event.', 'sportspress' ),
		);
		parent::__construct( 'sportspress-countdown', esc_attr__( 'Countdown', 'sportspress' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title         = apply_filters( 'widget_title', empty( $instance['title'] ) ? null : $instance['title'], $instance, $this->id_base );
		$caption       = empty( $instance['caption'] ) ? null : $instance['caption'];
		$calendar      = empty( $instance['calendar'] ) ? null : $instance['calendar'];
		$team          = empty( $instance['team'] ) ? null : $instance['team'];
		$id            = empty( $instance['id'] ) ? null : $instance['id'];
		$show_venue    = empty( $instance['show_venue'] ) ? false : $instance['show_venue'];
		$show_league   = empty( $instance['show_league'] ) ? false : $instance['show_league'];
		$show_date     = empty( $instance['show_date'] ) ? false : $instance['show_date'];
		$show_excluded = empty( $instance['show_excluded'] ) ? false : $instance['show_excluded'];
		$order         = empty( $instance['order'] ) ? false : $instance['order'];
		$orderby       = empty( $instance['orderby'] ) ? false : $instance['orderby'];
		$show_status   = empty( $instance['show_status'] ) ? false : $instance['show_status'];

		do_action( 'sportspress_before_widget', $args, $instance, 'countdown' );
		echo wp_kses_post( $before_widget );

		if ( $title ) {
			echo wp_kses_post( $before_title . $title . $after_title );
		}

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'countdown' );

		sp_get_template(
			'countdown.php',
			array(
				'calendar'      => $calendar,
				'team'          => $team,
				'id'            => $id,
				'title'         => $caption,
				'show_venue'    => $show_venue,
				'show_league'   => $show_league,
				'show_date'     => $show_date,
				'show_excluded' => $show_excluded,
				'order'         => $order,
				'orderby'       => $orderby,
				'show_status'   => $show_status,
			)
		);

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'countdown' );

		echo wp_kses_post( $after_widget );
		do_action( 'sportspress_after_widget', $args, $instance, 'countdown' );
	}

	function update( $new_instance, $old_instance ) {
		$instance                  = $old_instance;
		$instance['title']         = strip_tags( $new_instance['title'] );
		$instance['calendar']      = intval( $new_instance['calendar'] );
		$instance['team']          = intval( $new_instance['team'] );
		$instance['caption']       = strip_tags( $new_instance['caption'] );
		$instance['id']            = intval( $new_instance['id'] );
		$instance['show_venue']    = isset( $new_instance['show_venue'] ) ? $new_instance['show_venue'] : false;
		$instance['show_league']   = isset( $new_instance['show_league'] ) ? $new_instance['show_league'] : false;
		$instance['show_date']     = isset( $new_instance['show_date'] ) ? $new_instance['show_date'] : false;
		$instance['show_excluded'] = isset( $new_instance['show_excluded'] ) ? $new_instance['show_excluded'] : false;
		$instance['order']         = strip_tags( $new_instance['order'] );
		$instance['orderby']       = strip_tags( $new_instance['orderby'] );
		$instance['show_status']   = isset( $new_instance['show_status'] ) ? $new_instance['show_status'] : false;

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'countdown' );

		return $instance;
	}

	function form( $instance ) {
		$instance      = wp_parse_args(
			(array) $instance,
			array(
				'title'         => '',
				'calendar'      => '',
				'team'          => '',
				'id'            => '',
				'caption'       => '',
				'show_venue'    => false,
				'show_league'   => false,
				'show_date'     => false,
				'show_excluded' => false,
				'order'         => '',
				'orderby'       => '',
				'show_status'   => true,
			)
		);
		$title         = strip_tags( $instance['title'] );
		$caption       = strip_tags( $instance['caption'] );
		$calendar      = intval( $instance['calendar'] );
		$team          = intval( $instance['team'] );
		$id            = intval( $instance['id'] );
		$show_venue    = intval( $instance['show_venue'] );
		$show_league   = intval( $instance['show_league'] );
		$show_date     = intval( $instance['show_date'] );
		$show_excluded = intval( $instance['show_excluded'] );
		$order         = strip_tags( $instance['order'] );
		$orderby       = strip_tags( $instance['orderby'] );
		$show_status   = intval( $instance['show_status'] );

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'countdown' );
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'caption' ) ); ?>"><?php esc_attr_e( 'Heading:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'caption' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'caption' ) ); ?>" type="text" value="<?php echo esc_attr( $caption ); ?>" /></p>
		
		<p class="sp-dropdown-filter"><label for="<?php echo esc_attr( $this->get_field_id( 'calendar' ) ); ?>"><?php printf( esc_attr__( 'Select %s:', 'sportspress' ), esc_attr__( 'Calendar', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type'       => 'sp_calendar',
			'name'            => $this->get_field_name( 'calendar' ),
			'id'              => $this->get_field_id( 'calendar' ),
			'selected'        => $calendar,
			'show_option_all' => esc_attr__( 'All', 'sportspress' ),
			'values'          => 'ID',
			'class'           => 'widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ) :
			sp_post_adder( 'sp_calendar', esc_attr__( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>
		
		<p class="sp-dropdown-filter"><label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php printf( esc_attr__( 'Sort by:', 'sportspress' ) ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" class="postform widefat">
				<option value="" <?php selected( 'default', $orderby ); ?>><?php esc_attr_e( 'Default', 'sportspress' ); ?></option>
				<option value="date" <?php selected( 'date', $orderby ); ?>><?php esc_attr_e( 'Date', 'sportspress' ); ?></option>
				<option value="day" <?php selected( 'day', $orderby ); ?>><?php esc_attr_e( 'Match Day', 'sportspress' ); ?></option>
			</select>
		</p>
		
		<p class="sp-dropdown-filter"><label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php printf( esc_attr__( 'Sort Order:', 'sportspress' ) ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" class="postform widefat">
				<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php esc_attr_e( 'Ascending', 'sportspress' ); ?></option>
				<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php esc_attr_e( 'Descending', 'sportspress' ); ?></option>
			</select>
		</p>

		<p class="sp-dropdown-filter"><label for="<?php echo esc_attr( $this->get_field_id( 'team' ) ); ?>"><?php printf( esc_attr__( 'Select %s:', 'sportspress' ), esc_attr__( 'Team', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type'       => 'sp_team',
			'name'            => $this->get_field_name( 'team' ),
			'id'              => $this->get_field_id( 'team' ),
			'selected'        => $team,
			'show_option_all' => esc_attr__( 'All', 'sportspress' ),
			'values'          => 'ID',
			'class'           => 'widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ) :
			sp_post_adder( 'sp_team', esc_attr__( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p class="sp-dropdown-target"><label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php printf( esc_attr__( 'Select %s:', 'sportspress' ), esc_attr__( 'Event', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type'       => 'sp_event',
			'name'            => $this->get_field_name( 'id' ),
			'id'              => $this->get_field_id( 'id' ),
			'selected'        => $id,
			'show_option_all' => esc_attr__( '(Auto)', 'sportspress' ),
			'values'          => 'ID',
			'class'           => 'widefat',
			'show_dates'      => true,
			'post_status'     => 'future',
			'filter'          => 'sp_team',
		);
		if ( ! sp_dropdown_pages( $args ) ) :
			sp_post_adder( 'sp_event', esc_attr__( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_venue' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_venue' ) ); ?>" value="1" <?php checked( $show_venue, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_venue' ) ); ?>"><?php esc_attr_e( 'Display venue', 'sportspress' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_league' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_league' ) ); ?>" value="1" <?php checked( $show_league, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_league' ) ); ?>"><?php esc_attr_e( 'Display league', 'sportspress' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" value="1" <?php checked( $show_date, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_attr_e( 'Display date', 'sportspress' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_excluded' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excluded' ) ); ?>" value="1" <?php checked( $show_excluded, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_excluded' ) ); ?>"><?php esc_attr_e( 'Display excluded events', 'sportspress' ); ?></label></p>
		
		<p><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_status' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_status' ) ); ?>" value="1" <?php checked( $show_status, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_status' ) ); ?>"><?php esc_attr_e( 'Display event status', 'sportspress' ); ?></label></p>
		
		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'countdown' );
	}
}

register_widget( 'SP_Widget_Countdown' );
