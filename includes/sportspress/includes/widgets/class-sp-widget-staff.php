<?php
class SP_Widget_Staff extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_sportspress widget_staff widget_sp_staff',
			'description' => esc_attr__( 'Display a single staff member.', 'sportspress' ),
		);
		parent::__construct( 'sportspress-staff', esc_attr__( 'Staff', 'sportspress' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? null : $instance['title'], $instance, $this->id_base );
		$id    = empty( $instance['id'] ) ? null : $instance['id'];

		do_action( 'sportspress_before_widget', $args, $instance, 'staff' );
		echo wp_kses_post( $before_widget );

		if ( $title ) {
			echo wp_kses_post( $before_title . $title . $after_title );
		}

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'staff' );

		echo '<h4 class="sp-table-caption">' . wp_kses_post( get_the_title( $id ) ) . '</h4>';

		sp_get_template( 'staff-photo.php', array( 'id' => $id ) );
		sp_get_template( 'staff-details.php', array( 'id' => $id ) );

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'staff' );

		echo wp_kses_post( $after_widget );
		do_action( 'sportspress_after_widget', $args, $instance, 'staff' );
	}

	function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['id']    = intval( $new_instance['id'] );

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'staff' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => '',
				'id'    => '',
			)
		);
		$title    = strip_tags( $instance['title'] );
		$id       = intval( $instance['id'] );

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'staff' );
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php printf( esc_attr__( 'Select %s:', 'sportspress' ), esc_attr__( 'Staff', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_staff',
			'name'      => $this->get_field_name( 'id' ),
			'id'        => $this->get_field_id( 'id' ),
			'selected'  => $id,
			'values'    => 'ID',
			'class'     => 'widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ) :
			sp_post_adder( 'sp_staff', esc_attr__( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'staff' );
	}
}

register_widget( 'SP_Widget_Staff' );
